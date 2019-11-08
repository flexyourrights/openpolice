<?php
/**
  * OpenComplaintEmails is a mid-level class which handles custom emailing
  * functions, lookups, and language swaps.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use DB;
use Auth;
use App\Models\OPAllegSilver;
use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintOversight;
use OpenPolice\Controllers\OpenPoliceEvents;

class OpenComplaintEmails extends OpenPoliceEvents
{
    protected function postContactEmail($nID)
    {
        $this->postNodeLoadEmail($nID);
        if ($GLOBALS["SL"]->REQ->has('n831fld') 
            && trim($GLOBALS["SL"]->REQ->n831fld) != '') {
            return true;
        }
        $emaSubject = $this->postDumpFormEmailSubject();
        $emaContent = view(
            'vendor.openpolice.contact-form-email-admin'
        )->render();
        $this->sendEmail(
            $emaContent, 
            $emaSubject, 
            $this->v["emaTo"], 
            $this->v["emaCC"], 
            $this->v["emaBCC"],
            ['noreply@openpolice.org', 'OpenPolice.org Contact']
        );
        $emaID = ((isset($currEmail->EmailID)) 
            ? $currEmail->EmailID : -3);
        $this->logEmailSent(
            $emaContent, 
            $emaSubject, 
            $this->v["toList"], 
            $emaID, 
            $this->treeID, 
            $this->coreID,
            $this->v["uID"]
        );
        $this->manualLogContact(
            $nID, 
            $emaContent, 
            $emaSubject, 
            $this->v["toList"], 
            $GLOBALS["SL"]->REQ->n829fld
        );
        return true;
    }
    
    protected function postEmailFrom()
    {
        if ($this->treeID == 13) {
            return ['', 'OpenPolice.org Contact'];
        }
        return [];
    }
    
    protected function postDumpFormEmailSubject()
    {
        if ($this->treeID == 13 
            && $GLOBALS["SL"]->REQ->has('n829fld')) {
            return $GLOBALS["SL"]->REQ->n829fld 
                . (($GLOBALS["SL"]->REQ->has('n1879fld')) 
                    ? ': ' . $GLOBALS["SL"]->REQ->n1879fld : '')
                . (($GLOBALS["SL"]->REQ->has('n1880fld')) 
                    ? ': ' . $GLOBALS["SL"]->REQ->n1880fld : '')
                . (($GLOBALS["SL"]->REQ->has('n1881fld')) 
                    ? ': ' . $GLOBALS["SL"]->REQ->n1881fld : '')
                . (($GLOBALS["SL"]->REQ->has('n1873fld')) 
                    ? ': ' . implode(', ', 
                        $GLOBALS["SL"]->REQ->n1873fld) : '')
                . (($GLOBALS["SL"]->REQ->has('n1872fld')) 
                    ? ' -' . $GLOBALS["SL"]->REQ->n1872fld : '');
        }
        return $GLOBALS["SL"]->sysOpts["site-name"] . ': '
            . $GLOBALS["SL"]->treeRow->TreeName;
    }
    
    protected function processTokenAccessRedirExtra()
    {
        return '<style> '
            . '#blockWrap1758, #blockWrap1780 { display: none; }'
            . ' </style>';
    }
    
    public function emailRecordSwap($emaTxt)
    {
        $deptID = -3;
        $this->loadDeptStuff();
        if (isset($this->sessData->dataSets["LinksComplaintDept"]) 
            && sizeof($this->sessData->dataSets["LinksComplaintDept"]) > 0) {
            foreach ($this->sessData->dataSets["LinksComplaintDept"] as $deptLnk) {
                $this->loadDeptStuff($deptLnk->LnkComDeptDeptID);
                $deptID = $deptLnk->LnkComDeptDeptID;
            }
        }
        $emaTxt = $this->sendEmailBlurbs($emaTxt, $deptID);
        return $emaTxt;
    }
    
    public function sendEmailBlurbsCustom($emailBody, $deptID = -3)
    {
        $this->loadDeptStuff();
        if (isset($this->v["deptID"]) 
            && intVal($this->v["deptID"]) > 0
            && $deptID <= 0) {
            $deptID = $this->v["deptID"];
            $this->loadDeptStuff($deptID);
        }
        $this->emailBlurbsLoadDept($deptID);
        $this->swapBlurbsInvestigativeAgency($emailBody, $deptID);
        
        $dynamos = [
            '[{ Complaint ID }]', 
            '[{ Complaint URL }]', 
            '[{ Complaint URL Link }]', 
            '[{ Complaint URL PDF }]', 
            '[{ Complaint URL PDF Link }]', 
            '[{ Complaint URL JSON }]', 
            '[{ Complaint URL JSON Link }]', 
            '[{ Complainant Name }]', 
            '[{ Confirmation URL }]', 
            '[{ Days From Now: 7, mm/dd/yyyy }]', 
            '[{ Complaint Number of Weeks Old }]', 
            '[{ Analyst Name }]', 
            '[{ Analyst Email }]', 
            '[{ Complaint Department Submission Ways }]', 
            '[{ Complaint Police Department }]', 
            '[{ Complaint Police Department URL }]', 
            '[{ Complaint Police Department URL Link }]', 
            '[{ Complaint Police Department URL How }]', 
            '[{ Police Department State Abbr }]',
            '[{ Police Department Zip Code }]',
            '[{ Dear Primary Investigative Agency }]', 
            '[{ Complaint Investigability Score & Description }]', 
            '[{ Complaint Allegation List }]', 
            '[{ Complaint Allegation List Lower Bold }]', 
            '[{ Complaint Top Three Allegation List Lower Bold }]', 
            '[{ Complaint Worst Allegation }]', 
            '[{ Oversight Complaint Token URL Link }]', 
            '[{ Oversight Complaint Secure MFA }]',
            '[{ Complaint Department Complaint PDF }]', 
            '[{ Complaint Department Complaint Web }]', 
            '[{ Complaint Officers Reference }]', 
            '[{ Flex Article Suggestions Based On Responses }]'
        ];
        
        foreach ($dynamos as $dy) {
            if (strpos($emailBody, $dy) !== false) {
//echo 'found!.. ' . $dy . ' - deptID: ' . $deptID . ', <pre>'; print_r($GLOBALS["SL"]->x["depts"]); echo '</pre>'; exit;
                $emailBody = $this->swapBlurbsDynamo($emailBody, $dy, $deptID);
            }
        }
        
        $emailBody = str_replace('Hello Complainant,', 'Hello,', $emailBody);
        $emailBody = str_replace('Hi, [{ Complainant Name }],', 'Hi,', $emailBody);
        $emailBody = str_replace(
            'Congratulations, [{ Complainant Name }]!', 
            'Congratulations!', 
            $emailBody
        );

        return $emailBody;
    }
    
    protected function emailBlurbsLoadDept($deptID = -3)
    {
        if (!isset($GLOBALS["SL"]->x["depts"]) 
            || empty($GLOBALS["SL"]->x["depts"])) {
            $this->loadDeptStuff();
            if ($deptID > 0) {
                $this->loadDeptStuff($deptID);
            } elseif (isset($this->sessData->dataSets["LinksComplaintDept"])) {
                $depts = $this->sessData->dataSets["LinksComplaintDept"];
                if (sizeof($depts) > 0) {
                    foreach ($depts as $i => $deptLnk) {
                        $this->loadDeptStuff($deptLnk->LnkComDeptDeptID);
                        if ($i == 0) {
                            $deptID = $deptLnk->LnkComDeptDeptID;
                        }
                    }
                }
            }
        } else {
            if ($deptID <= 0) {
                foreach ($GLOBALS["SL"]->x["depts"] as $dID => $stuff) {
                    if ($deptID <= 0) {
                        $deptID = $dID;
                    }
                }
            }
            if (!isset($GLOBALS["SL"]->x["depts"][$deptID])) {
                $this->loadDeptStuff($deptID);
            }
        }
        return true;
    }
    
    protected function swapBlurbsInvestigativeAgency(&$emailBody, $deptID = -3)
    {
        $swap = '[{ Complaint Investigative Agency }]';
        if (strpos($emailBody, $swap) !== false) {
            if (isset($GLOBALS["SL"]->x["depts"][$deptID])) {
                $d = $GLOBALS["SL"]->x["depts"][$deptID];
                if (isset($d[$d["whichOver"]]) 
                    && isset($d["deptRow"]->DeptName)) {
                    $overName = trim($d[$d["whichOver"]]->OverAgncName);
                    if ($overName == '') {
                        $overName = $d["deptRow"]->DeptName;
                    }
                    $forDept = (($overName != $d["deptRow"]->DeptName) 
                        ? ' (for the ' . $d["deptRow"]->DeptName . ')' 
                        : (($d["whichOver"] == 'iaRow') 
                            ? ' Internal Affairs' : ''));
                    $splits = $GLOBALS["SL"]->mexplode(
                        $swap, 
                        $emailBody
                    );
                    $emailBody = $splits[0] . $overName . $forDept;
                    if ($d["whichOver"] == 'iaRow') {
                        $overName = 'Internal Affairs';
                    }
                    for ($i = 1; $i < sizeof($splits); $i++) {
                        $emailBody .= (($i > 1) ? $overName : '') 
                            . $splits[$i];
                    }
                }
            }
        }
        return $emailBody;
    }
    
    protected function swapBlurbsDynamo($emailBody, $dy, $deptID)
    {
        $swap = $dy;
        $dyCore = str_replace('[{ ', '', str_replace(' }]', '', $dy));
        $url = $GLOBALS["SL"]->sysOpts["app-url"];
        switch ($dy) {
            case '[{ Complaint ID }]': 
                $swap = $this->corePublicID;
                break;
            case '[{ Complaint URL }]':
                $swap = $GLOBALS["SL"]->swapURLwrap($url 
                    . '/complaint/read-' . $this->corePublicID);
                break;
            case '[{ Complaint URL Link }]':
                $swap = $url . '/complaint/read-' . $this->corePublicID;
                break;
            case '[{ Complaint URL PDF }]':
                $swap = '<a href="' . $url . '/complaint/read-' . $this->corePublicID 
                    . '/pdf" target="_blank">Download full complaint as a PDF</a>';
                break;
            case '[{ Complaint URL PDF Link }]':
                $swap = $url . '/complaint/read-' . $this->corePublicID . '/pdf';
                break;
            case '[{ Complaint URL XML }]':
            case '[{ Complaint URL JSON }]':
                $swap = '<a href="' . $url . '/complaint/read-' . $this->corePublicID 
                    . '/xml" target="_blank">Download full complaint as a XML</a>';
                break;
            case '[{ Complaint URL XML Link }]':
            case '[{ Complaint URL JSON Link }]':
                $swap = $url . '/complaint/read-' . $this->corePublicID . '/xml';
                break;
            case '[{ Complaint URL XML }]':
                $swap = '<a href="' . $url . '/complaint/read-' . $this->corePublicID 
                    . '/xml" target="_blank">Download full complaint as an OPC Data File '
                    . '(XML)</a>';
                break;
            case '[{ Complaint URL XML Link }]':
                $swap = $url . '/complaint/read-' . $this->corePublicID . '/xml';
                break;
            case '[{ Complainant Name }]':
                if (isset($this->sessData->dataSets["Civilians"])) {
                    $civ = $this->sessData->dataSets["Civilians"][0];
                    if (isset($civ->CivPersonID) && intVal($civ->CivPersonID) > 0) {
                        $contact = $this->sessData->getRowById(
                            'PersonContact', 
                            $civ->CivPersonID
                        );
                        if ($contact && isset($contact->PrsnNameFirst)) {
                            $swap = $contact->PrsnNameFirst;
                        }
                    }
                }
                break;
            case '[{ Days From Now: 7, mm/dd/yyyy }]':
                $swap = date('n/j/y', mktime(0, 0, 0, 
                    date("n"), (7+date("j")), date("Y")));
                break;
            case '[{ Complaint Number of Weeks Old }]':
                if (isset($this->sessData->dataSets["Complaints"])) {
                    $dayCount = date_diff(time(), strtotime(
                        $this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted
                        ))->format('%a');
                    $swap = floor($dayCount/7);
                }
                break;
            case '[{ Analyst Name }]':
                $swap = $this->userFormalName($this->v["user"]->id);
                break;
            case '[{ Analyst Email }]':
                $swap = $this->v["user"]->email;
                break;
            case '[{ Complaint Police Department }]':
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName)) {
                    $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName;
                }
                break;
            case '[{ Complaint Police Department URL }]':
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptSlug)) {
                    $swap = $GLOBALS["SL"]->swapURLwrap($url . '/dept/' 
                        . $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptSlug);
                }
                break;
            case '[{ Complaint Police Department URL Link }]':
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName)) {
                    $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName;
                }
                break;
            case '[{ Complaint Police Department URL How }]':
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptSlug)) {
                    $url = $url . '/dept/' 
                        . $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptSlug . '#how';
                    $swap = $GLOBALS["SL"]->swapURLwrap($url);
                }
                break;
            case '[{ Police Department State Abbr }]':
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressState)) {
                    $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressState;
                }
                break;
            case '[{ Police Department Zip Code }]':
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressZip)) {
                    $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressZip;
                }
                break;
            case '[{ Dear Primary Investigative Agency }]':
                $swap = 'To Whom It May Concern,';
                break;
            case '[{ Complaint Officers Reference }]':
                if (isset($this->sessData->dataSets["Officers"])) {
                    if (empty($this->sessData->dataSets["Officers"])) {
                        $swap = 'no officers';
                    } elseif (sizeof($this->sessData->dataSets["Officers"]) == 1) {
                        $swap = 'one officer';
                    } elseif (sizeof($this->sessData->dataSets["Officers"]) < 10) {
                        switch (sizeof($this->sessData->dataSets["Officers"])) {
                            case 2: $swap = 'two'; break;
                            case 3: $swap = 'three'; break;
                        }
                        $swap .= ' officers';
                    } else {
                        $swap = $f->format(sizeof($this->sessData->dataSets["Officers"])) 
                            . ' officers';
                    }
                }
                break;
            case '[{ Complaint Officers Count }]':
                if (isset($this->sessData->dataSets["Officers"])) {
                    $swap = sizeof($this->sessData->dataSets["Officers"]);
                }
                break;
            case '[{ Complaint Allegation List }]':
                $swap = $this->commaAllegationList();
                break;
            case '[{ Complaint Allegation List Lower Bold }]':
                $swap = '<b>' . strtolower($this->commaAllegationList()) . '</b>';
                break;
            case '[{ Complaint Top Three Allegation List Lower Bold }]':
                $swap = '<b>' . strtolower($this->commaTopThreeAllegationList()) . '</b>';
                break;
            case '[{ Complaint Worst Allegation }]':
                $this->simpleAllegationList();
                if (sizeof($this->allegations) > 0) {
                    $swap = $this->allegations[0][0];
                }
                break;
            case '[{ Oversight Complaint Token URL Link }]':
                $deptUser = $this->getDeptUser($deptID);
                if (!isset($deptUser->id)) {
                    $swap = '#';
                } else {
                    $token = $this->createToken(
                        'Sensitive', 
                        $this->treeID, 
                        $this->coreID, 
                        $deptUser->id
                    );
                    $swap = $url . '/complaint/read-' . $this->corePublicID 
                        . '/full/t-' . $token;
                }
                $swap = '<a href="' . $swap . '" target="_blank">' . $swap . '</a>';
                break;
            case '[{ Oversight Complaint Secure MFA }]':
                $deptUser = $this->getDeptUser($deptID);
                if (!isset($deptUser->id)) {
                    $swap = '<span style="color: red;">* DEPARTMENT IS '
                        . 'NOT OPENPOLICE-COMPATIBLE *</span>';
                } else {
                    $swap = $this->createToken(
                        'MFA', 
                        $this->treeID, 
                        $this->coreID, 
                        $deptUser->id
                    );
                }
                break;
            case '[{ Complaint Department Complaint PDF }]':
                if (isset($GLOBALS["SL"]->x["depts"]) && isset($deptID)
                    && isset($GLOBALS["SL"]->x["depts"][$deptID])) {
                    $d = $GLOBALS["SL"]->x["depts"];
                    $which = $d[$deptID]["whichOver"];
                    if (isset($d[$deptID][$which]->OverComplaintPDF) 
                        && trim($d[$deptID][$which]->OverComplaintPDF) != '') {
                        $swap = '';
                        if (sizeof($d) > 1) {
                            $swap .= $d[$deptID][$which]->OverAgncName;
                        }
                        $swap .= ': ' . $GLOBALS["SL"]->swapURLwrap(
                                $d[$deptID][$which]->OverComplaintPDF
                            );
                    }
                    if (sizeof($d) > 1) {
                        for ($i = 1; $i < sizeof($GLOBALS["SL"]->x["depts"]); $i++) {
                            if (trim($swap) != '') $swap .= '<br />';
                            $which = $d[$i]["whichOver"];
                            $swap .= $d[$deptID][$which]->OverAgncName . ': ' 
                                . $GLOBALS["SL"]->swapURLwrap(
                                    $d[$i][$which]->OverComplaintPDF
                                );
                        }
                    }
                }
                break;
            case '[{ Complaint Department Complaint Web }]':
                if (isset($GLOBALS["SL"]->x["depts"]) && isset($deptID)
                    && isset($GLOBALS["SL"]->x["depts"][$deptID])) {
                    $d = $GLOBALS["SL"]->x["depts"];
                    $which = $d[$deptID]["whichOver"];
                    if (isset($d[$deptID][$which]->OverComplaintWebForm) 
                        && trim($d[$deptID][$which]->OverComplaintWebForm) != '') {
                        $swap = '';
                        if (sizeof($d) > 1) {
                            $swap = $d[$deptID][$which]->OverAgncName;
                        }
                        $swap .= ': ' . $GLOBALS["SL"]->swapURLwrap(
                            $d[$deptID][$which]->OverComplaintWebForm
                        );
                    }
                    if (sizeof($d) > 1) {
                        for ($i = 1; $i < sizeof($d); $i++) {
                            if (trim($swap) != '') {
                                $swap .= '<br />';
                            }
                            $currWhich = $d[$i]["whichOver"];
                            $swap .= $d[$deptID][$which]->OverAgncName . ': ' 
                                . $GLOBALS["SL"]->swapURLwrap(
                                    $d[$i][$currWhich]->OverComplaintWebForm
                                );
                        }
                    }
                }
                break;
            case '[{ Flex Article Suggestions Based On Responses }]':
                if (isset($this->v["allUrls"])) {
                    $this->loadRelatedArticles();
                    $swap = view('vendor.openpolice.nodes.1708-report-flex-articles-email', [
                        "allUrls"     => $this->v["allUrls"], 
                        "showVidUrls" => true
                        ])->render();
                }
                break;
        }
        $swap = trim($swap);
        return str_replace($dy, $swap, $emailBody);
    }
    
    public function processEmail($emailID, $deptID = -3)
    {
        $email = [
            "rec"        => false,
            "body"       => '',
            "subject"    => '',
            "deptID"     => $deptID,
            "to"         => '',
            "cc"         => '',
            "bcc"        => '',
            "attachType" => ''
        ];
        if ($emailID > 0) {
            if (sizeof($this->v["emailList"]) > 0) {
                foreach ($this->v["emailList"] as $e) {
                    if ($e->EmailID == $emailID) {
                        $email["rec"] = $e;
                    }
                }
                if ($email["rec"] !== false && isset($email["rec"]->EmailBody) 
                    && trim($email["rec"]->EmailBody) != '') {
                    $email["body"] = $this->sendEmailBlurbsCustom(
                        $GLOBALS["SL"]->swapEmailBlurbs($email["rec"]->EmailBody), 
                        $deptID
                    );
                    $email["subject"] = $this->sendEmailBlurbsCustom(
                        $GLOBALS["SL"]->swapEmailBlurbs($email["rec"]->EmailSubject), 
                        $deptID
                    );
                }
            }
        }
        return $email;
    }
    
    protected function processTokenAccessEmail()
    {
        $ret = '';
        $deptID = -3;
        $overRow = OPOversight::where('OverEmail', $user->email)->first();
        if ($overRow && isset($overRow->OverDeptID)) {
            $deptID = $overRow->OverDeptID;
        }
        $emailRow = SLEmails::where('EmailName', '21. Fresh MFA to Investigative Agency')
            ->first();
        if ($emailRow && isset($emailRow->EmailBody)) {
            $body = $this->sendEmailBlurbsCustom($emailRow->EmailBody, $deptID);
            $subject = $this->sendEmailBlurbsCustom($emailRow->EmailSubject, $deptID);
            //echo '<h2>' . $subject . '</h2><p>to ' . $user->email 
            //    . '</p><p>' . $body . '</p>'; exit;
            $this->sendNewEmailSimple(
                $body, 
                $subject, 
                $user->email, 
                $emailRow->EmailID, 
                $this->treeID, 
                $this->coreID, 
                $user->id
            );
            $ret .= '<div class="alert alert-success mB10" role="alert">'
                . '<strong>Fresh Access Code Sent!</strong> '
                . 'Check your email (and spam folder), '
                . 'and copy the access code there.</div>';
        } else {
            $ret .= '<div class="alert alert-danger mB10" role="alert">'
                . '<strong>Email Problem!</strong> '
                . 'Something went wrong trying to email you a fresh access code. '
                . 'Please <a href="/contact">contact us</a>.</div>';
        }
        return $ret;
    }
    
    public function prepEmailComplaintData()
    {
        $cnt = 0;
        $this->v["comDepts"] = [];
        if (isset($this->sessData->dataSets["LinksComplaintDept"])) {
            $depts = $this->sessData->dataSets["LinksComplaintDept"];
            if (sizeof($depts) > 0) {
                foreach ($depts as $i => $lnk) {
                    if (isset($lnk->LnkComDeptDeptID) 
                        && intVal($lnk->LnkComDeptDeptID) > 0) {
                        $deptRow = OPDepartments::find($lnk->LnkComDeptDeptID);
                        if ($deptRow && isset($deptRow->DeptName) 
                            && trim($deptRow->DeptName) != '') {
                            $this->prepEmailComDataRow($deptRow, $lnk, $cnt);
                            $cnt++;
                        }
                    }
                }
            }
        }
        return true;
    }
    
    public function prepEmailComDataRow($deptRow, $lnk, $cnt)
    {
        $this->v["comDepts"][$cnt] = [
            "id"      => $lnk->LnkComDeptDeptID,
            "deptRow" => $deptRow
        ];
        $defTyp = 'Investigative Agency Types';
        $iaDef = $GLOBALS["SL"]->def->getID($defTyp, 'Internal Affairs');
        $civDef = $GLOBALS["SL"]->def->getID($defTyp, 'Civilian Oversight');
        $this->v["comDepts"][$cnt]["iaRow"] 
            = OPOversight::where('OverDeptID', $lnk->LnkComDeptDeptID)
            ->where('OverType', $iaDef)
            ->first();
        $this->v["comDepts"][$cnt]["civRow"] 
            = OPOversight::where('OverDeptID', $lnk->LnkComDeptDeptID)
            ->where('OverType', $civDef)
            ->first();
        if (!isset($this->v["comDepts"][$cnt]["iaRow"]) 
            || !$this->v["comDepts"][$cnt]["iaRow"]) {
            $iaRow = new OPOversight;
            $iaRow->OverDeptID       = $lnk->LnkComDeptDeptID;
            $iaRow->OverType         = $iaDef;
            $iaRow->OverAgncName     = $deptRow->DeptName;
            $iaRow->OverAddress      = $deptRow->DeptAddress;
            $iaRow->OverAddress2     = $deptRow->DeptAddress2;
            $iaRow->OverAddressCity  = $deptRow->DeptAddressCity;
            $iaRow->OverAddressState = $deptRow->DeptAddressState;
            $iaRow->OverAddressZip   = $deptRow->DeptAddressZip;
            $iaRow->OverPhoneWork    = $deptRow->DeptPhoneWork;
            $iaRow->save();
            $this->v["comDepts"][$cnt]["iaRow"] = $iaRow;
        }
        $this->v["comDepts"][$cnt]["whichOver"] = '';
        if (isset($this->v["comDepts"][0]["civRow"]) 
            && isset($this->v["comDepts"][0]["civRow"]->OverAgncName)
            && trim($this->v["comDepts"][0]["civRow"]->OverAgncName) != '') {
            $this->v["comDepts"][$cnt]["whichOver"] = "civRow";
        } elseif (isset($this->v["comDepts"][0]["iaRow"]) 
            && isset($this->v["comDepts"][0]["iaRow"]->OverAgncName)) {
            $this->v["comDepts"][$cnt]["whichOver"] = "iaRow";
        }
        $w = $this->v["comDepts"][$cnt]["whichOver"];
        $this->v["comDepts"][$cnt]["overInfo"] = '';
        if (isset($this->v["comDepts"][$cnt])) {
            if (isset($this->v["comDepts"][$cnt][$w])) {
                $this->v["comDepts"][$cnt]["overInfo"] 
                    = $this->getOversightInfo($this->v["comDepts"][$cnt][$w]);
            }
        }
        $this->v["comDepts"][$cnt]["overDates"] 
            = OPLinksComplaintOversight::where('LnkComOverComplaintID', 
                $lnk->LnkComDeptComplaintID)
            ->where('LnkComOverDeptID', $lnk->LnkComDeptDeptID)
            ->where('LnkComOverOverID', $this->v["comDepts"][0][$w]->OverID)
            ->first();
        if (!$this->v["comDepts"][$cnt]["overDates"] 
            || !isset($this->v["comDepts"][$cnt]["overDates"]->LnkComOverID)) {
            $lnk = new OPLinksComplaintOversight;
            $lnk->LnkComOverComplaintID = $lnk->LnkComDeptComplaintID;
            $lnk->LnkComOverDeptID = $lnk->LnkComDeptDeptID;
            $lnk->LnkComOverOverID = $this->v["comDepts"][0][$w]->OverID;
            $this->v["comDepts"][$cnt]["overDates"] = $lnk;
            $this->v["comDepts"][$cnt]["overDates"]->save();
        }
        return true;
    }
    
    public function getOversightInfo($overRow)
    {
        return '';
        /* return view('vendor.openpolice.nodes.1896-attorney-referral-listings', [
            "o"   => $overRow,
            "nID" => 1896
            ])->render(); */
    }
    
}