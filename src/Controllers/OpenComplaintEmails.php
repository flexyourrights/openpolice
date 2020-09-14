<?php
/**
  * OpenComplaintEmails is a mid-level class which handles custom emailing
  * functions, lookups, and language swaps.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
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
        $emaContent = view('vendor.openpolice.contact-form-email-admin')->render();
        $this->sendEmail(
            $emaContent, 
            $emaSubject, 
            $this->v["emaTo"], 
            $this->v["emaCC"], 
            $this->v["emaBCC"],
            [ 'noreply@openpolice.org', 'OpenPolice.org Contact' ]
        );
        $emaID = ((isset($currEmail->email_id)) ? $currEmail->email_id : -3);
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
            return [ '', 'OpenPolice.org Contact' ];
        }
        return [];
    }
    
    protected function postDumpFormEmailSubject()
    {
        if ($this->treeID == 13 && $GLOBALS["SL"]->REQ->has('n829fld')) {
            $ret = $GLOBALS["SL"]->REQ->n829fld;
            if ($GLOBALS["SL"]->REQ->has('n1879fld')) {
                $ret .= ': ' . $GLOBALS["SL"]->REQ->n1879fld;
            }
            if ($GLOBALS["SL"]->REQ->has('n1880fld')) {
                $ret .= ': ' . $GLOBALS["SL"]->REQ->n1880fld;
            }
            if ($GLOBALS["SL"]->REQ->has('n1881fld')) {
                $ret .= ': ' . $GLOBALS["SL"]->REQ->n1881fld;
            }
            if ($GLOBALS["SL"]->REQ->has('n1873fld')) {
                $ret .= ': ' . implode(', ', $GLOBALS["SL"]->REQ->n1873fld);
            }
            if ($GLOBALS["SL"]->REQ->has('n1872fld')) {
                $ret .= ' -' . $GLOBALS["SL"]->REQ->n1872fld;
            }
            return $ret;
        }
        return $GLOBALS["SL"]->sysOpts["site-name"] . ': ' 
            . $GLOBALS["SL"]->treeRow->tree_name;
    }
    
    protected function processTokenAccessRedirExtra()
    {
        return '<style> #blockWrap1758, #blockWrap1780 { display: none; } </style>';
    }
    
    public function emailRecordSwap($emaTxt)
    {
        $deptID = -3;
        $this->loadDeptStuff();
        if (isset($this->sessData->dataSets["links_complaint_dept"]) 
            && sizeof($this->sessData->dataSets["links_complaint_dept"]) > 0) {
            foreach ($this->sessData->dataSets["links_complaint_dept"] as $deptLnk) {
                $this->loadDeptStuff($deptLnk->lnk_com_dept_dept_id);
                $deptID = $deptLnk->lnk_com_dept_dept_id;
            }
        }
        $emaTxt = $this->sendEmailBlurbs($emaTxt, $deptID);
        return $emaTxt;
    }
    
    public function sendEmailBlurbsCustom($emailBody, $deptID = -3)
    {
        $this->loadDeptStuff();
        if (isset($this->v["deptID"]) && intVal($this->v["deptID"]) > 0 && $deptID <= 0) {
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
            '[{ Complaint Auto-Sent For Investigation }]', 
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
        if (!isset($GLOBALS["SL"]->x["depts"]) || empty($GLOBALS["SL"]->x["depts"])) {
            $this->loadDeptStuff();
            if ($deptID > 0) {
                $this->loadDeptStuff($deptID);
            } elseif (isset($this->sessData->dataSets["links_complaint_dept"])) {
                $depts = $this->sessData->dataSets["links_complaint_dept"];
                if (sizeof($depts) > 0) {
                    foreach ($depts as $i => $deptLnk) {
                        $this->loadDeptStuff($deptLnk->lnk_com_dept_dept_id);
                        if ($i == 0) {
                            $deptID = $deptLnk->lnk_com_dept_dept_id;
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
            if ($GLOBALS["SL"]->REQ->has('d') && intVal($GLOBALS["SL"]->REQ->get('d')) > 0) {
                $deptID = intVal($GLOBALS["SL"]->REQ->get('d'));
            }
            if (isset($GLOBALS["SL"]->x["depts"][$deptID])) {
                $d = $GLOBALS["SL"]->x["depts"][$deptID];
                if (isset($d[$d["whichOver"]]) && isset($d["deptRow"]->dept_name)) {
                    $overName = trim($d[$d["whichOver"]]->over_agnc_name);
                    if ($overName == '') {
                        $overName = $d["deptRow"]->dept_name;
                    }
                    $forDept = (($overName != $d["deptRow"]->dept_name) 
                        ? ' (for the ' . $d["deptRow"]->dept_name . ')' 
                        : (($d["whichOver"] == 'iaRow') ? ' Internal Affairs' : ''));
                    $splits = $GLOBALS["SL"]->mexplode($swap, $emailBody);
                    $emailBody = $splits[0] . $overName . $forDept;
                    if ($d["whichOver"] == 'iaRow') {
                        $overName = 'Internal Affairs';
                    }
                    for ($i = 1; $i < sizeof($splits); $i++) {
                        $emailBody .= (($i > 1) ? $overName : '') . $splits[$i];
                    }
                }
            }
        }
        $emailBody = str_replace(
            '[{ Complaint Investigative Agency }]', 
            '<span></span>', 
            $emailBody
        );
        return $emailBody;
    }
    
    protected function swapBlurbsSentForInvestigation($deptID = -3)
    {
        if (sizeof($GLOBALS["SL"]->x["depts"]) == 1) {
            return '';
        }
        if ($GLOBALS["SL"]->REQ->has('d') && intVal($GLOBALS["SL"]->REQ->get('d')) > 0) {
            $deptID = intVal($GLOBALS["SL"]->REQ->get('d'));
        }
        list($submitted, $compliant) = $this->chkDeptSubmissionStatus();
        $submittedDepts = [];
        foreach ($GLOBALS["SL"]->x["depts"] as $dID => $dept) {
            if ($dID != $deptID
                && in_array($dID, $compliant) 
                && in_array($dID, $submitted)) {
                $submittedDepts[] = $dept["deptRow"]->dept_name;
            }
        }
        if (sizeof($submittedDepts) > 0) {
            $ret = ' We just attempted to email your complaint to the ';
            foreach ($submittedDepts as $i => $d) {
                if ($i > 0) {
                    if ($i == sizeof($submittedDepts)-1) {
                        $ret .= ', and the ';
                    } else {
                        $ret .= ', the ';
                    }
                }
                $ret .= str_replace('Department', 'Dept.', $d);
            }
            return $ret . '.';
        }
        return '';
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
                $swap = $GLOBALS["SL"]->swapURLwrap($url . '/complaint/read-' . $this->corePublicID);
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
                    . '/xml" target="_blank">Download full complaint as an XML</a>';
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
                if (isset($this->sessData->dataSets["civilians"])) {
                    $civ = $this->sessData->dataSets["civilians"][0];
                    if (isset($civ->civ_person_id) && intVal($civ->civ_person_id) > 0) {
                        $contact = $this->sessData->getRowById('person_contact', $civ->civ_person_id);
                        if ($contact && isset($contact->prsn_name_first)) {
                            $swap = $contact->prsn_name_first;
                        }
                    }
                }
                break;
            case '[{ Days From Now: 7, mm/dd/yyyy }]':
                $swap = date('n/j/y', mktime(0, 0, 0, 
                    date("n"), (7+date("j")), date("Y")));
                break;
            case '[{ Complaint Number of Weeks Old }]':
                if (isset($this->sessData->dataSets["complaints"])) {
                    $dayCount = $this->sessData->dataSets["complaints"][0]->com_record_submitted;
                    $dayCount = date_diff(time(), strtotime($dayCount))->format('%a');
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
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_name)) {
                    $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_name;
                }
                break;
            case '[{ Complaint Police Department URL }]':
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_slug)) {
                    $swap = $GLOBALS["SL"]->swapURLwrap($url . '/dept/' 
                        . $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_slug);
                }
                if (trim($swap) == '') {
                    $swap = $GLOBALS["SL"]->swapURLwrap($url . '/departments');
                }
                break;
            case '[{ Complaint Police Department URL Link }]':
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_name)) {
                    $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_name;
                }
                if (trim($swap) == '') {
                    $swap = $url . '/departments';
                }
                break;
            case '[{ Complaint Police Department URL How }]':
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_slug)) {
                    $url = $url . '/dept/' 
                        . $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_slug . '#how';
                    $swap = $GLOBALS["SL"]->swapURLwrap($url);
                }
                break;
            case '[{ Police Department State Abbr }]':
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_address_state)) {
                    $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_address_state;
                }
                break;
            case '[{ Police Department Zip Code }]':
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_address_zip)) {
                    $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->dept_address_zip;
                }
                break;
            case '[{ Dear Primary Investigative Agency }]':
                $swap = 'To Whom It May Concern,';
                break;
            case '[{ Complaint Auto-Sent For Investigation }]':
                $swap = $this->swapBlurbsSentForInvestigation($deptID);
                break;
            case '[{ Complaint Officers Reference }]':
                if (!isset($this->sessData->dataSets["officers"])
                    || sizeof($this->sessData->dataSets["officers"]) == 0) {
                    $swap = 'no specific officers';
                } elseif (sizeof($this->sessData->dataSets["officers"]) == 1) {
                    $swap = 'one officer';
                } elseif (sizeof($this->sessData->dataSets["officers"]) < 10) {
                    switch (sizeof($this->sessData->dataSets["officers"])) {
                        case 2: $swap = 'two'; break;
                        case 3: $swap = 'three'; break;
                    }
                    $swap .= ' officers';
                } else {
                    $swap = sizeof($this->sessData->dataSets["officers"]) . ' officers';
                }
                break;
            case '[{ Complaint Officers Count }]':
                if (isset($this->sessData->dataSets["officers"])) {
                    $swap = sizeof($this->sessData->dataSets["officers"]);
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
                    $swap = $url . '/complaint/read-' 
                        . $this->corePublicID . '/full/t-' . $token;
                }
                $swap = '<a href="' . $swap . '" target="_blank">' . $swap . '</a>';
                break;
            case '[{ Oversight Complaint Secure MFA }]':
                $deptUser = $this->getDeptUser($deptID);
                if (!isset($deptUser->id)) {
                    $swap = '<span style="color: red;">* '
                        . 'DEPARTMENT IS NOT OPENPOLICE-COMPATIBLE *</span>';
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
                if (isset($GLOBALS["SL"]->x["depts"]) 
                    && isset($deptID)
                    && isset($GLOBALS["SL"]->x["depts"][$deptID])) {
                    $d = $GLOBALS["SL"]->x["depts"];
                    $which = $d[$deptID]["whichOver"];
                    if (isset($d[$deptID][$which]->over_complaint_pdf) 
                        && trim($d[$deptID][$which]->over_complaint_pdf) != '') {
                        $swap = '';
                        if (sizeof($d) > 1) {
                            $swap .= $d[$deptID][$which]->over_agnc_name;
                        }
                        $swap .= ': ' . $GLOBALS["SL"]->swapURLwrap($d[$deptID][$which]->over_complaint_pdf);
                    }
                    if (sizeof($d) > 1) {
                        for ($i = 1; $i < sizeof($GLOBALS["SL"]->x["depts"]); $i++) {
                            if (trim($swap) != '') {
                                $swap .= '<br />';
                            }
                            $which = $d[$i]["whichOver"];
                            $swap .= $d[$deptID][$which]->over_agnc_name . ': ' 
                                . $GLOBALS["SL"]->swapURLwrap($d[$i][$which]->over_complaint_pdf);
                        }
                    }
                }
                if (trim($swap) == '') {
                    $swap = '<!-- Complaint Department Complaint PDF -->';
                }
                break;
            case '[{ Complaint Department Complaint Web }]':
                if (isset($GLOBALS["SL"]->x["depts"]) && isset($deptID)
                    && isset($GLOBALS["SL"]->x["depts"][$deptID])) {
                    $d = $GLOBALS["SL"]->x["depts"];
                    $which = $d[$deptID]["whichOver"];
                    if (isset($d[$deptID][$which]->over_complaint_web_form) 
                        && trim($d[$deptID][$which]->over_complaint_web_form) != '') {
                        $swap = '';
                        if (sizeof($d) > 1) {
                            $swap = $d[$deptID][$which]->over_agnc_name;
                        }
                        $swap .= ': ' . $GLOBALS["SL"]->swapURLwrap($d[$deptID][$which]->over_complaint_web_form);
                    }
                    if (sizeof($d) > 1) {
                        for ($i = 1; $i < sizeof($d); $i++) {
                            if (trim($swap) != '') {
                                $swap .= '<br />';
                            }
                            $currWhich = $d[$i]["whichOver"];
                            $swap .= $d[$deptID][$which]->over_agnc_name . ': ' 
                                . $GLOBALS["SL"]->swapURLwrap($d[$i][$currWhich]->over_complaint_web_form);
                        }
                    }
                }
                if (trim($swap) == '') {
                    $swap = '<!-- Complaint Department Complaint Web -->';
                }
                break;
            case '[{ Flex Article Suggestions Based On Responses }]':
                if (isset($this->v["allUrls"])) {
                    $this->loadRelatedArticles();
                    $swap = view(
                        'vendor.openpolice.nodes.1708-report-flex-articles-email', 
                        [
                            "allUrls"     => $this->v["allUrls"], 
                            "showVidUrls" => true
                        ]
                    )->render();
                }
                if (trim($swap) == '') {
                    $swap = '<!-- Flex Article Suggestions Based On Responses -->';
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
            "bcc"        => ''
        ];
        if ($emailID > 0) {
            if (sizeof($this->v["emailList"]) > 0) {
                foreach ($this->v["emailList"] as $e) {
                    if ($e->email_id == $emailID) {
                        $email["rec"] = $e;
                    }
                }
                if ($email["rec"] !== false 
                    && isset($email["rec"]->email_body) 
                    && trim($email["rec"]->email_body) != '') {
                    $email["body"] = $this->sendEmailBlurbsCustom(
                        $GLOBALS["SL"]->swapEmailBlurbs($email["rec"]->email_body), 
                        $deptID
                    );
                    $email["subject"] = $this->sendEmailBlurbsCustom(
                        $GLOBALS["SL"]->swapEmailBlurbs($email["rec"]->email_subject), 
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
        $overRow = OPOversight::where('over_email', $user->email)
            ->first();
        if ($overRow && isset($overRow->over_dept_id)) {
            $deptID = $overRow->over_dept_id;
        }
        $emailRow = SLEmails::where('email_name', '21. Fresh MFA to Investigative Agency')
            ->first();
        if ($emailRow && isset($emailRow->email_body)) {
            $body = $this->sendEmailBlurbsCustom($emailRow->email_body, $deptID);
            $subject = $this->sendEmailBlurbsCustom($emailRow->email_subject, $deptID);
            //echo '<h2>' . $subject . '</h2><p>to ' . $user->email 
            //    . '</p><p>' . $body . '</p>'; exit;
            $this->sendNewEmailSimple(
                $body, 
                $subject, 
                $user->email, 
                $emailRow->email_id, 
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
        if (isset($this->sessData->dataSets["links_complaint_dept"])) {
            $this->getOversightLinksChk();
            if (sizeof($this->sessData->dataSets["links_complaint_dept"]) > 0) {
                foreach ($this->sessData->dataSets["links_complaint_dept"] as $i => $lnk) {
                    if (isset($lnk->lnk_com_dept_dept_id) 
                        && intVal($lnk->lnk_com_dept_dept_id) > 0) {
                        $deptRow = OPDepartments::find($lnk->lnk_com_dept_dept_id);
                        if ($deptRow 
                            && isset($deptRow->dept_name) 
                            && trim($deptRow->dept_name) != '') {
                            $this->prepEmailComDataRow($deptRow, $cnt);
                            $cnt++;
                        }
                    }
                }
            }
        }
//echo '<pre>'; print_r($this->v["comDepts"]); echo '</pre>'; exit;
        return true;
    }
    
    public function prepEmailComDataRow($deptRow, $cnt)
    {
        $this->v["comDepts"][$cnt] = [
            "id"      => $deptRow->dept_id,
            "name"    => $deptRow->dept_name,
            "deptRow" => $deptRow
        ];
        $defTyp = 'Investigative Agency Types';
        $iaDef  = $GLOBALS["SL"]->def->getID($defTyp, 'Internal Affairs');
        $civDef = $GLOBALS["SL"]->def->getID($defTyp, 'Civilian Oversight');
        $this->v["comDepts"][$cnt]["iaRow"] = OPOversight::where('over_type', $iaDef)
            ->where('over_dept_id', $deptRow->dept_id)
            ->first();
        $this->v["comDepts"][$cnt]["civRow"] = OPOversight::where('over_type', $civDef)
            ->where('over_dept_id', $deptRow->dept_id)
            ->first();
        if (!isset($this->v["comDepts"][$cnt]["iaRow"]) 
            || !$this->v["comDepts"][$cnt]["iaRow"]) {
            $iaRow = new OPOversight;
            $iaRow->over_dept_id       = $deptRow->dept_id;
            $iaRow->over_type          = $iaDef;
            $iaRow->over_agnc_name     = $deptRow->dept_name;
            $iaRow->over_address       = $deptRow->dept_address;
            $iaRow->over_address2      = $deptRow->dept_address2;
            $iaRow->over_address_city  = $deptRow->dept_address_city;
            $iaRow->over_address_state = $deptRow->dept_address_state;
            $iaRow->over_address_zip   = $deptRow->dept_address_zip;
            $iaRow->over_phone_work    = $deptRow->dept_phone_work;
            $iaRow->save();
            $this->v["comDepts"][$cnt]["iaRow"] = $iaRow;
        }
        $this->v["comDepts"][$cnt]["whichOver"] = '';
        if (isset($this->v["comDepts"][0]["civRow"]) 
            && isset($this->v["comDepts"][0]["civRow"]->over_agnc_name)
            && trim($this->v["comDepts"][0]["civRow"]->over_agnc_name) != '') {
            $this->v["comDepts"][$cnt]["whichOver"] = "civRow";
        } elseif (isset($this->v["comDepts"][0]["iaRow"]) 
            && isset($this->v["comDepts"][0]["iaRow"]->over_agnc_name)) {
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
            = OPLinksComplaintOversight::where('lnk_com_over_complaint_id', $this->coreID)
            ->where('lnk_com_over_dept_id', $deptRow->dept_id)
            //->where('lnk_com_over_over_id', $this->v["comDepts"][0][$w]->over_id)
            ->orderBy('created_at', 'asc')
            ->first();
//echo 'cnt: ' . $cnt . ', coreID: ' . $this->coreID . ', dept_id: ' . $deptRow->dept_id . '<pre>'; print_r($this->v["comDepts"][$cnt]["overDates"]); echo '</pre>'; exit;
        if (!$this->v["comDepts"][$cnt]["overDates"] 
            || !isset($this->v["comDepts"][$cnt]["overDates"]->lnk_com_over_dept_id)) {
            $lnk = new OPLinksComplaintOversight;
            $lnk->lnk_com_over_complaint_id = $this->coreID;
            $lnk->lnk_com_over_dept_id      = $deptRow->dept_id;
            $lnk->lnk_com_over_over_id      = $this->v["comDepts"][0][$w]->over_id;
            $lnk->save();
            $this->v["comDepts"][$cnt]["overDates"] = $lnk;
        }
        return true;
    }
    
    protected function getOversightLinksChk()
    {
        /*
        if (sizeof($this->sessData->dataSets["links_complaint_dept"]) > 0) {
            $deptIDs = [];
            foreach ($this->sessData->dataSets["links_complaint_dept"] as $i => $lnk) {
                if (isset($lnk->lnk_com_dept_dept_id) 
                    && intVal($lnk->lnk_com_dept_dept_id) > 0) {
                    $deptIDs[] = $lnk->lnk_com_dept_dept_id;
                }
            }
            foreach ($deptIDs as $deptID) {
                $chk = OPLinksComplaintOversight::where('lnk_com_over_complaint_id', $this->coreID)
                    ->where('lnk_com_over_dept_id', $deptID)
                    ->orderBy('created_at', 'asc')
                    ->first();
                if (!$chk && !isset($chk->lnk_com_over_over_id)) {
                    $lnk = new OPLinksComplaintOversight;
                    $lnk->lnk_com_over_complaint_id = $this->coreID;
                    $lnk->lnk_com_over_dept_id = $deptID;
                    $lnk->lnk_com_over_over_id
                    ->first();
                }
            }
        }
        */
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
    
    protected function chkDeptSubmissionStatus()
    {
        $submitted = $compliant = [];
        if (isset($GLOBALS["SL"]->x["depts"]) && sizeof($GLOBALS["SL"]->x["depts"]) > 0) {
            $cutoff = strtotime('2015-01-01 00:00:00');
            foreach ($GLOBALS["SL"]->x["depts"] as $deptID => $dept) {
                if ($dept["overUpdate"] 
                    && isset($dept["overUpdate"]->lnk_com_over_submitted)
                    && strtotime($dept["overUpdate"]->lnk_com_over_submitted) > $cutoff) {
                    $submitted[] = $deptID;
                }
                if (isset($dept["deptRow"]->dept_op_compliant)
                    && intVal($dept["deptRow"]->dept_op_compliant) == 1) {
                    $compliant[] = $deptID;
                }
            }
        }
        return [ $submitted, $compliant ];
    }

    
}