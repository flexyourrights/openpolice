<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use OpenPolice\Models\OPAllegSilver;
use OpenPolice\Models\OPDepartments;
use OpenPolice\Models\OPOversight;
use OpenPolice\Controllers\OpenPoliceUtils;

class OpenComplaintEmails extends OpenPoliceUtils
{
    protected function postContactEmail($nID)
    {
        $this->postNodeLoadEmail($nID);
        if ($GLOBALS["SL"]->REQ->has('n831fld') && trim($GLOBALS["SL"]->REQ->n831fld) != '') {
            return true;
        }
        $emaSubject = $this->postDumpFormEmailSubject();
        $emaContent = view('vendor.openpolice.contact-form-email-admin')->render();
        $this->sendEmail($emaContent, $emaSubject, $this->v["emaTo"], $this->v["emaCC"], $this->v["emaBCC"],
            ['noreply@openpolice.org', 'OPC Contact']);
        $emaID = ((isset($currEmail->EmailID)) ? $currEmail->EmailID : -3);
        $this->logEmailSent($emaContent, $emaSubject, $this->v["toList"], $emaID, $this->treeID, $this->coreID,
            $this->v["uID"]);
        $this->manualLogContact($nID, $emaContent, $emaSubject, $this->v["toList"], $GLOBALS["SL"]->REQ->n829fld);
        return true;
    }
    
    protected function postEmailFrom()
    {
        if ($this->treeID == 13) {
            return ['', 'OPC Contact'];
        }
        return [];
    }
    
    protected function postDumpFormEmailSubject()
    {
        if ($this->treeID == 13 && $GLOBALS["SL"]->REQ->has('n829fld')) {
            return $GLOBALS["SL"]->REQ->n829fld 
                . (($GLOBALS["SL"]->REQ->has('n1879fld')) ? ': ' . $GLOBALS["SL"]->REQ->n1879fld : '')
                . (($GLOBALS["SL"]->REQ->has('n1880fld')) ? ': ' . $GLOBALS["SL"]->REQ->n1880fld : '')
                . (($GLOBALS["SL"]->REQ->has('n1881fld')) ? ': ' . $GLOBALS["SL"]->REQ->n1881fld : '')
                . (($GLOBALS["SL"]->REQ->has('n1873fld')) ? ': ' . implode(', ', $GLOBALS["SL"]->REQ->n1873fld) : '')
                . (($GLOBALS["SL"]->REQ->has('n1872fld')) ? ' -' . $GLOBALS["SL"]->REQ->n1872fld : '');
        }
        return $GLOBALS["SL"]->sysOpts["site-name"] . ': ' . $GLOBALS["SL"]->treeRow->TreeName;
    }
    
    protected function processTokenAccessRedirExtra()
    {
        return '<style> #blockWrap1758, #blockWrap1780 { display: none; } </style>';
    }
    
    public function emailRecordSwap($emaTxt)
    {
        $deptID = -3;
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
        if (!isset($GLOBALS["SL"]->x["depts"]) || empty($GLOBALS["SL"]->x["depts"])) {
            if ($deptID > 0) {
                $this->loadDeptStuff($deptID);
            } elseif (isset($this->sessData->dataSets["LinksComplaintDept"]) 
                && sizeof($this->sessData->dataSets["LinksComplaintDept"]) > 0) {
                foreach ($this->sessData->dataSets["LinksComplaintDept"] as $i => $deptLnk) {
                    $this->loadDeptStuff($deptLnk->LnkComDeptDeptID);
                    if ($i == 0) $deptID = $deptLnk->LnkComDeptDeptID;
                }
            }
        } else {
            if ($deptID <= 0) {
                foreach ($GLOBALS["SL"]->x["depts"] as $dID => $stuff) {
                    if ($deptID <= 0) $deptID = $dID;
                }
            }
            if (!isset($GLOBALS["SL"]->x["depts"][$deptID])) {
                $this->loadDeptStuff($deptID);
            }
        }
        if (strpos($emailBody, '[{ Complaint Investigative Agency }]') !== false) {
            if (isset($GLOBALS["SL"]->x["depts"][$deptID])) {
                $wchOvr = $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"];
                if (isset($GLOBALS["SL"]->x["depts"][$deptID][$wchOvr]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName)) {
                    $overName = trim($GLOBALS["SL"]->x["depts"][$deptID][$wchOvr]->OverAgncName);
                    if ($overName == '') {
                        $overName = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName;
                    }
                    $forDept = (($overName != $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName) 
                        ? ' (for the ' . $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName . ')' 
                        : (($wchOvr == 'iaRow') ? ' Internal Affairs' : ''));
                    $splits = $GLOBALS["SL"]->mexplode('[{ Complaint Investigative Agency }]', $emailBody);
                    $emailBody = $splits[0] . $overName . $forDept;
                    if ($wchOvr == 'iaRow') {
                        $overName = 'Internal Affairs';
                    }
                    for ($i = 1; $i < sizeof($splits); $i++) {
                        $emailBody .= (($i > 1) ? $overName : '') . $splits[$i];
                    }
                }
            }
        }
        
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
            '[{ Police Department State Abbr }]',
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
                $swap = $dy;
                $dyCore = str_replace('[{ ', '', str_replace(' }]', '', $dy));
                switch ($dy) {
                    case '[{ Complaint ID }]': 
                        $swap = $this->corePublicID;
                        break;
                    case '[{ Complaint URL }]':
                        $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' 
                            . $this->corePublicID);
                        break;
                    case '[{ Complaint URL Link }]':
                        $swap = $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $this->corePublicID;
                        break;
                    case '[{ Complaint URL PDF }]':
                        $swap = '<a href="' . $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' 
                            . $this->corePublicID . '/pdf" target="_blank">Download full complaint as a PDF</a>';
                        break;
                    case '[{ Complaint URL PDF Link }]':
                        $swap = $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $this->corePublicID . '/pdf';
                        break;
                    case '[{ Complaint URL XML }]':
                    case '[{ Complaint URL JSON }]':
                        $swap = '<a href="' . $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' 
                            . $this->corePublicID . '/xml" target="_blank">Download full complaint as a XML</a>';
                        break;
                    case '[{ Complaint URL XML Link }]':
                    case '[{ Complaint URL JSON Link }]':
                        $swap = $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $this->corePublicID . '/xml';
                        break;
                    case '[{ Complaint URL XML }]':
                        $swap = '<a href="' . $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' 
                            . $this->corePublicID . '/xml" target="_blank">Download full complaint as an OPC Data File '
                            . '(XML)</a>';
                        break;
                    case '[{ Complaint URL XML Link }]':
                        $swap = $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $this->corePublicID . '/xml';
                        break;
                    case '[{ Complainant Name }]':
                        if (isset($this->sessData->dataSets["Civilians"][0]->CivPersonID) 
                            && intVal($this->sessData->dataSets["Civilians"][0]->CivPersonID) > 0) {
                            $contact = $this->sessData->getRowById('PersonContact', 
                                $this->sessData->dataSets["Civilians"][0]->CivPersonID);
                            if ($contact && isset($contact->PrsnNameFirst)) {
                                $swap = $contact->PrsnNameFirst;
                            }
                        }
                        break;
                    case '[{ Days From Now: 7, mm/dd/yyyy }]':
                        $swap = date('n/j/y', mktime(0, 0, 0, date("n"), (7+date("j")), date("Y")));
                        break;
                    case '[{ Complaint Number of Weeks Old }]':
                        $dayCount = date_diff(time(), strtotime(
                            $this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted
                            ))->format('%a');
                        $swap = floor($dayCount/7);
                        break;
                    case '[{ Analyst Name }]':
                        $swap = $this->userFormalName($this->v["user"]->id);
                        break;
                    case '[{ Analyst Email }]':
                        $swap = $this->v["user"]->email;
                        break;
                    case '[{ Complaint Police Department }]':
                        if (isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                            && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName)) {
                            $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName;
                        }
                        break;
                    case '[{ Complaint Police Department URL }]':
                        if (isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                            && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptSlug)) {
                            $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . '/dept/' 
                                . $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptSlug);
                        }
                        break;
                    case '[{ Complaint Police Department URL Link }]':
                        if (isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                            && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName)) {
                            $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName;
                        }
                        break;
                    case '[{ Police Department State Abbr }]':
                        if (isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                            && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressState)) {
                            $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressState;
                        }
                        break;
                    case '[{ Dear Primary Investigative Agency }]':
                        $swap = 'To Whom It May Concern,';
                        break;
                    case '[{ Complaint Officers Reference }]':
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
                            $swap = $f->format(sizeof($this->sessData->dataSets["Officers"])) . ' officers';
                        }
                        break;
                    case '[{ Complaint Officers Count }]':
                        $swap = sizeof($this->sessData->dataSets["Officers"]);
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
                            $token = $this->createToken('Sensitive', $this->treeID, $this->coreID, $deptUser->id);
                            $swap = $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $this->corePublicID 
                                . '/full/t-' . $token;
                        }
                        $swap = '<a href="' . $swap . '" target="_blank">' . $swap . '</a>';
                        break;
                    case '[{ Oversight Complaint Secure MFA }]':
                        $deptUser = $this->getDeptUser($deptID);
                        if (!isset($deptUser->id)) {
                            $swap = '<span style="color: red;">* DEPARTMENT IS NOT OPC-COMPLIANT *</span>';
                        } else {
                            $swap = $this->createToken('MFA', $this->treeID, $this->coreID, $deptUser->id);
                        }
                        break;
                    case '[{ Complaint Department Complaint PDF }]':
                        $which = $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"];
                        if (isset($GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintPDF) 
                            && trim($GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintPDF) != '') {
                            $swap = '';
                            if (sizeof($GLOBALS["SL"]->x["depts"]) > 1) {
                                $swap .= $GLOBALS["SL"]->x["depts"][$deptID][$which]->OverAgncName;
                            }
                            $swap .= ': ' 
                                . $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintPDF);
                        }
                        if (sizeof($GLOBALS["SL"]->x["depts"]) > 1) {
                            for ($i = 1; $i < sizeof($GLOBALS["SL"]->x["depts"]); $i++) {
                                if (trim($swap) != '') $swap .= '<br />';
                                $which = $GLOBALS["SL"]->x["depts"][$i]["whichOver"];
                                $swap .= $GLOBALS["SL"]->x["depts"][$deptID][$which]->OverAgncName . ': ' 
                                    . $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->x["depts"][$i][$which]->OverComplaintPDF);
                            }
                        }
                        break;
                    case '[{ Complaint Department Complaint Web }]':
                        $which = $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"];
                        if (isset($GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintWebForm) 
                            && trim($GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintWebForm) != '') {
                            $swap = '';
                            if (sizeof($GLOBALS["SL"]->x["depts"]) > 1) {
                                $swap = $GLOBALS["SL"]->x["depts"][$deptID][$which]->OverAgncName;
                            }
                            $swap .= ': ' . $GLOBALS["SL"]->swapURLwrap(
                                $GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintWebForm);
                        }
                        if (sizeof($GLOBALS["SL"]->x["depts"]) > 1) {
                            for ($i = 1; $i < sizeof($GLOBALS["SL"]->x["depts"]); $i++) {
                                if (trim($swap) != '') $swap .= '<br />';
                                $currWhich = $GLOBALS["SL"]->x["depts"][$i]["whichOver"];
                                $swap .= $GLOBALS["SL"]->x["depts"][$deptID][$which]->OverAgncName . ': ' 
                                    . $GLOBALS["SL"]->swapURLwrap(
                                        $GLOBALS["SL"]->x["depts"][$i][$currWhich]->OverComplaintWebForm);
                            }
                        }
                        break;
                    case '[{ Flex Article Suggestions Based On Responses }]':
                        $this->loadRelatedArticles();
                        $swap = view('vendor.openpolice.nodes.1708-report-flex-articles-email', [
                            "allUrls"     => $this->v["allUrls"], 
                            "showVidUrls" => true
                            ])->render();
                        break;
                }
                $emailBody = str_replace($dy, $swap, $emailBody);
            }
        }
        
        $emailBody = str_replace('Hello Complainant,', 'Hello,', $emailBody);
        $emailBody = str_replace('Congratulations, [{ Complainant Name }]!', 'Congratulations!', $emailBody);

        return $emailBody;
    }
    
    public function processEmail($emailID, $deptID = -3)
    {
        $email = [
            "rec"     => false,
            "body"    => '',
            "subject" => '',
            "deptID"  => $deptID
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
                    $email["body"] = $GLOBALS["SL"]->swapEmailBlurbs($email["rec"]->EmailBody);
                    $email["body"] = $this->sendEmailBlurbsCustom($email["body"], $deptID);
                    $email["subject"] = $GLOBALS["SL"]->swapEmailBlurbs($email["rec"]->EmailSubject);
                    $email["subject"] = $this->sendEmailBlurbsCustom($email["subject"], $deptID);
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
        if ($overRow && isset($overRow->OverDeptID)) $deptID = $overRow->OverDeptID;
        $emailRow = SLEmails::where('EmailName', '21. Fresh MFA to Investigative Agency')->first();
        if ($emailRow && isset($emailRow->EmailBody)) {
            $body = $this->sendEmailBlurbsCustom($emailRow->EmailBody, $deptID);
            $subject = $this->sendEmailBlurbsCustom($emailRow->EmailSubject, $deptID);
            echo '<h2>' . $subject . '</h2><p>to ' . $user->email . '</p><p>' . $body . '</p>'; exit;
            $this->sendNewEmailSimple($body, $subject, $user->email, $emailRow->EmailID, 
                $this->treeID, $this->coreID, $user->id);
            $ret .= '<div class="alert alert-success mB10" role="alert">'
                . '<strong>Fresh Access Code Sent!</strong> '
                . 'Check your email (and spam folder), and copy the access code there.</div>';
        } else {
            $ret .= '<div class="alert alert-danger mB10" role="alert"><strong>Email Problem!</strong> '
                . 'Something went wrong trying to email you a fresh access code. '
                . 'Please <a href="/contact">contact us</a>.</div>';
        }
        return $ret;
    }
    
    public function prepEmailComData()
    {
        $cnt = 0;
        $this->v["comDepts"] = [];
        if (isset($this->sessData->dataSets["LinksComplaintDept"]) 
            && sizeof($this->sessData->dataSets["LinksComplaintDept"]) > 0) {
            foreach ($this->sessData->dataSets["LinksComplaintDept"] as $i => $lnk) {
                if (isset($lnk->LnkComDeptDeptID) && intVal($lnk->LnkComDeptDeptID) > 0) {
                    $deptRow = OPDepartments::find($lnk->LnkComDeptDeptID);
                    if ($deptRow && isset($deptRow->DeptName) && trim($deptRow->DeptName) != '') {
                        $this->v["comDepts"][$cnt] = [ "id" => $lnk->LnkComDeptDeptID ];
                        $this->v["comDepts"][$cnt]["deptRow"] = $deptRow;
                        $this->v["comDepts"][$cnt]["iaRow"] = OPOversight::where('OverDeptID', $lnk->LnkComDeptDeptID)
                            ->where('OverType', $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Internal Affairs'))
                            ->first();
                        $this->v["comDepts"][$cnt]["civRow"] = OPOversight::where('OverDeptID', $lnk->LnkComDeptDeptID)
                            ->where('OverType', $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Civilian Oversight'))
                            ->first();
                        if (!isset($this->v["comDepts"][$cnt]["iaRow"]) || !$this->v["comDepts"][$cnt]["iaRow"]) {
                            $this->v["comDepts"][$cnt]["iaRow"] = new OPOversight;
                            $this->v["comDepts"][$cnt]["iaRow"]->OverDeptID = $lnk->LnkComDeptDeptID;
                            $this->v["comDepts"][$cnt]["iaRow"]->OverType
                                = $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Internal Affairs');
                            $this->v["comDepts"][$cnt]["iaRow"]->OverAgncName
                                = $this->v["comDepts"][$cnt]["deptRow"]->DeptName;
                            $this->v["comDepts"][$cnt]["iaRow"]->OverAddress
                                = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddress;
                            $this->v["comDepts"][$cnt]["iaRow"]->OverAddress2
                                = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddress2;
                            $this->v["comDepts"][$cnt]["iaRow"]->OverAddressCity
                                = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddressCity;
                            $this->v["comDepts"][$cnt]["iaRow"]->OverAddressState
                                = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddressState;
                            $this->v["comDepts"][$cnt]["iaRow"]->OverAddressZip
                                = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddressZip;
                            $this->v["comDepts"][$cnt]["iaRow"]->OverPhoneWork
                                = $this->v["comDepts"][$cnt]["deptRow"]->DeptPhoneWork;
                            $this->v["comDepts"][$cnt]["iaRow"]->save();
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
                        $this->v["comDepts"][$cnt]["overInfo"] = '';
                        if (isset($this->v["comDepts"][$cnt])) {
                            $w = $this->v["comDepts"][$cnt]["whichOver"];
                            if (isset($this->v["comDepts"][$cnt][$w])) {
                                $this->v["comDepts"][$cnt]["overInfo"] 
                                    = $this->getOversightInfo($this->v["comDepts"][$cnt][$w]);
                            }
                        }
                        $cnt++;
                    }
                }
            }
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