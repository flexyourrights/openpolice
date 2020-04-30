<?php
/**
  * OpenReportToolsAdmin is mid-level class with functions to print and process
  * administrative forms used by OpenPolice.org staff.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.2.4
  */
namespace OpenPolice\Controllers;

use App\Models\User;
use App\Models\SLEmails;
use App\Models\SLEmailed;
use App\Models\OPComplaints;
use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksComplaintOversight;
use App\Models\OPzComplaintReviews;
use OpenPolice\Controllers\OpenReportToolsOversight;

class OpenReportToolsAdmin extends OpenReportToolsOversight
{
    /**
     * Prints a jQuery load of this complaint report within the 
     * admin dashboard.
     *
     * @param  int $nID
     * @return string
     */
    protected function printComplaintReportForAdmin($nID)
    {
        if ($this->coreID > 0) {
            $src = '/complaint/readi-' . $this->coreID . '/full?ajax=1&wdg=1'
                . $GLOBALS["SL"]->getAnyReqParams();
            $GLOBALS["SL"]->pageAJAX .= '$("#admDashReportWrap").load("' . $src . '");';
        }
        return view(
            'vendor.openpolice.nodes.2377-admin-dash-load-complaint', 
            [ "coreID" => $this->coreID ]
        )->render();
    }

    /**
     * Print OpenPolice.org staff admin tools for managing one complaint.
     *
     * @return string
     */
    protected function printComplaintAdmin()
    {
        $this->loadReportUploadTypes();
        $this->loadOversightDateLookups();
        $this->loadComplaintAdminHistory();
        $this->prepEmailComplaintData();
        $hasEmailLoaded = $this->prepAdminComplaintEmailing();
        if ($GLOBALS["SL"]->REQ->has('open')) {
            $hasEmailLoaded = true;
        }
        $hasEmailSent = 0;
        if ($GLOBALS["SL"]->REQ->has('emailSent')) {
            $hasEmailSent = intVal($GLOBALS["SL"]->REQ->has('emailSent'));
        }
        $GLOBALS["SL"]->loadStates();
        $this->v["needsWsyiwyg"] = true;
        $this->v["incidentState"] = trim($this->sessData
            ->dataSets["incidents"][0]->inc_address_state);
        $this->v["complaintRec"] = $this->sessData->dataSets["complaints"][0];

        if ($GLOBALS["SL"]->REQ->has('ajaxEmaForm')) {
            return view(
                'vendor.openpolice.nodes.1712-report-inc-staff-tools-email-form', 
                $this->v
            )->render();
        }

        $title = $this->getCurrComplaintEngLabel() . ': Admin Toolkit';
        $tools = view(
            'vendor.openpolice.nodes.1712-report-inc-staff-tools', 
            $this->v
        )->render();
        $status = $GLOBALS['SL']->def->getVal(
            'Complaint Status', 
            $this->v["complaintRec"]->com_status
        );
        $openToolbox = ($hasEmailLoaded || $hasEmailSent || $status == 'New');
        return '<div class="pT20 pB20">' 
            . $GLOBALS["SL"]->printAccard($title, $tools, $openToolbox)
            . '</div>';
    }
    
    /**
     * Prepare everything needed for staff to select and send emails
     * to complainants from the top of their complaint report.
     *
     * @return boolean
     */
    protected function prepAdminComplaintEmailing()
    {
        $isOverCompatible = false;
        $w = '';
        if (isset($this->v["comDepts"][0])) {
            $w = $this->v["comDepts"][0]["whichOver"];
            if (isset($this->v["comDepts"][0][$w])) {
                $isOverCompatible = $this->isOverCompatible(
                    $this->v["comDepts"][0][$w]
                );
            }
        }
        $this->v["emailsTo"] = [
            "To Complainant" => [],
            "To Oversight"   => []
        ];
        $userID = $this->sessData->dataSets["complaints"][0]->com_user_id;
        $complainantUser = User::find($userID);
        if ($complainantUser && isset($complainantUser->email)) {
            $name = $complainantUser->name;
            if (isset($this->sessData->dataSets["person_contact"])) {
                $pers = $this->sessData->dataSets["person_contact"];
                if (sizeof($pers) > 0 && isset($pers[0]->prsn_name_first)) {
                    $name = $pers[0]->prsn_name_first . ' ' 
                        . $pers[0]->prsn_name_last;
                }
            }
            $this->v["emailsTo"]["To Complainant"][] = [
                $complainantUser->email,
                $name,
                true
            ];
        }
        if ($isOverCompatible) {
            $this->v["emailsTo"]["To Oversight"][] = [
                $this->v["comDepts"][0][$w]->over_email,
                $this->v["comDepts"][0][$w]->over_agnc_name,
                true
            ];
        }
        $this->v["emailMap"] = [ // 'Review Status' => Email ID#
            'Submitted to Oversight'    => [7, 12], 
            'Hold: Go Gold'             => [6],
            'Pending Attorney: Needed'  => [17],
            'Pending Attorney: Hook-Up' => [18]
        ];
        $this->v["emailID"] = ($GLOBALS["SL"]->REQ->has('email') 
            ? intVal($GLOBALS["SL"]->REQ->email) : -3);
        $this->autoloadAdminComplaintEmail($isOverCompatible);
        
        $this->loadDeptStuff();
        $this->v["currEmail"] = [];
        if (isset($this->sessData->dataSets["links_complaint_dept"])) {
            $depts = $this->sessData->dataSets["links_complaint_dept"];
            if (sizeof($depts) > 0) {
                foreach ($depts as $i => $deptLnk) {
                    if (!isset($deptLnk->lnk_com_dept_dept_id) 
                        || intVal($deptLnk->lnk_com_dept_dept_id) <= 0) {
                        // "Not sure about department"
                        $deptLnk->lnk_com_dept_dept_id = 18124;
                        $deptLnk->save();
                        $this->sessData->dataSets["links_complaint_dept"][$i]
                            ->lnk_com_dept_dept_id = 18124; 
                    }
                    $this->loadDeptStuff($deptLnk->lnk_com_dept_dept_id);
                    $this->v["currEmail"][] = $this->processEmail(
                        $this->v["emailID"], 
                        $deptLnk->lnk_com_dept_dept_id
                    );
                }
            }
        }
        $hasEmailLoaded = ($this->v["emailID"] > 0 
            && sizeof($this->v["currEmail"]) > 0);
        if ($hasEmailLoaded) {
            $this->v["needsWsyiwyg"] = true;
            foreach ($this->v["currEmail"] as $j => $email) {
                $GLOBALS["SL"]->pageAJAX .= ' $("#emailBodyCust' . $j 
                    . 'ID").summernote({ height: 350 }); ';
            }
        }
        return $hasEmailLoaded;
    }
    
    /**
     * If an email template has not already been selected, 
     * check for suggested emails for staff to send next.
     *
     * @return int
     */
    protected function autoloadAdminComplaintEmail($isOverCompatible)
    {
        if ($this->v["emailID"] <= 0) {
            $defSet = 'Complaint Status';
            switch ($this->sessData->dataSets["complaints"][0]->com_status) {
                case $GLOBALS["SL"]->def->getID($defSet, 'Incomplete'):
                    $this->v["emailID"] = 36; // Incomplete Complaint Check-In
                    break;
                case $GLOBALS["SL"]->def->getID($defSet, 'OK to Submit to Oversight'):
                    if ($isOverCompatible) {
                        $this->v["emailID"] = 12; // Send to investigative agency
                    } else {
                        $this->v["emailID"] = 9; // How to manually submit
                    }
                    break;
                case $GLOBALS["SL"]->def->getID($defSet, 'Submitted to Oversight'):
                case $GLOBALS["SL"]->def->getID($defSet, 'Received by Oversight'):
                    $chk = SLEmailed::whereIn('emailed_tree', [1, 42])
                        ->where('emailed_rec_id', $this->coreID)
                        ->where('emailed_email_id', 7)
                        ->first();
                    if (!$chk || !isset($chk->created_at)) {
                        $this->v["emailID"] = 7; // Sent to investigative agency
                    }
                    break;
            }
        }
        return $this->v["emailID"];
    }
    
    /**
     * Save and process staff use of admin tools for managing one complaint.
     *
     * @return boolean
     */
    protected function saveComplaintAdmin()
    {
//if ($GLOBALS["SL"]->REQ->has('firstReview')) { echo '<pre>'; print_r($GLOBALS["SL"]->REQ->all()); echo '</pre>'; exit; }
        $hasSave = $GLOBALS["SL"]->REQ->has('save');
        $hasFirstReview = ($GLOBALS["SL"]->REQ->has('firstReview') 
            && $GLOBALS["SL"]->REQ->has('n1712fld') 
            && intVal($GLOBALS["SL"]->REQ->n1712fld) > 0);
        $hasReportUpload = ($GLOBALS["SL"]->REQ->has('reportUp') 
            && $GLOBALS["SL"]->REQ->has('reportUpType')
            && trim($GLOBALS["SL"]->REQ->reportUpType) != '');
        $hasFixDepts = $GLOBALS["SL"]->REQ->has('fixDepts');
        $hasEmailSend = ($GLOBALS["SL"]->REQ->has('emailID') 
            && intVal($GLOBALS["SL"]->REQ->emailID) > 0
            && $GLOBALS["SL"]->REQ->has('send') );
        if ($hasFirstReview
            || $hasSave
            || $hasReportUpload
            || $hasFixDepts
            || $hasEmailSend) {
            $this->loadReportUploadTypes();
            $this->loadOversightDateLookups();
            $this->prepEmailComplaintData();
            if ($hasFirstReview) {
                $this->saveComplaintAdminFirstReview();
            } elseif ($hasSave) {
                $this->saveComplaintAdminStatus();
            } elseif ($hasReportUpload) {
                $this->saveComplaintAdminUpload();
            } elseif ($hasFixDepts) {
                $this->processComplaintFixDepts();
            } elseif ($hasEmailSend) {
                if ($this->sendComplaintAdminEmail()) {
                    $emaID = -1;
                    if ($GLOBALS["SL"]->REQ->has('emailID')) {
                        $emaID = intVal($GLOBALS["SL"]->REQ->emailID);
                    }
                    return view(
                        'vendor.openpolice.nodes.1712-report-inc-staff-tools-email-redir', 
                        [
                            "coreID" => $this->coreID,
                            "emaID"  => $emaID
                        ]
                    )->render();
                } else {
                    return 'Sorry, something went wrong trying to send the email.';
                }
            }
            $this->clearComplaintCaches();
        } elseif ($GLOBALS["SL"]->REQ->has('refresh')
            && intVal($GLOBALS["SL"]->REQ->has('refresh')) == 2) {
            $this->clearComplaintCaches();
        }
        return true;
    }
    
    /**
     * Save and process the first pass-fail review performed by staff.
     *
     * @return boolean
     */
    protected function saveComplaintAdminFirstReview()
    {
        if ($GLOBALS["SL"]->REQ->has('n1712fld')) {
            $type = intVal($GLOBALS["SL"]->REQ->n1712fld);
            $newTypeVal = $GLOBALS["SL"]->def->getVal('Complaint Type', $type);
            $this->logComplaintReview('First', '', $newTypeVal);
            $this->sessData->dataSets["complaints"][0]->com_type = $type;
            $this->sessData->dataSets["complaints"][0]->save();
            $this->v["firstRevDone"] = true;
            $this->v["firstReview"] = false;
        }
        return true;
    }
    
    /**
     * Save and process a change in complaint status assigned by staff.
     *
     * @return boolean
     */
    protected function saveComplaintAdminStatus()
    {
        $status = 0;
        $evalNotes = '';
        if ($GLOBALS["SL"]->REQ->has('revNote')) {
            $evalNotes = trim($GLOBALS["SL"]->REQ->revNote) . ' <br />';
        }
        $evalNotes .= $this->processComplaintOverDates();
        if ($GLOBALS["SL"]->REQ->has('revStatus')) {
            $defSet = 'Complaint Status';
            $status = $GLOBALS["SL"]->REQ->revStatus;
            $holds = [ 'Hold: Go Gold', 'Hold: Not Sure' ];
            if (in_array($status, $holds)) {
                $this->sessData->dataSets["complaints"][0]->com_status 
                    = $GLOBALS["SL"]->def->getID($defSet, 'Hold');
            } elseif ($status == 'Needs More Work') {
                $this->sessData->dataSets["complaints"][0]->com_status 
                    = $GLOBALS["SL"]->def->getID($defSet, 'Needs More Work');
            } elseif (in_array($status, 
                [ 'Pending Attorney: Needed', 'Pending Attorney: Hook-Up' ])) {
                $this->sessData->dataSets["complaints"][0]->com_status 
                    = $GLOBALS["SL"]->def->getID($defSet, 'Pending Attorney');
            } elseif (in_array($status, [ 'Attorney\'d' ])) {
                $this->sessData->dataSets["complaints"][0]->com_status 
                    = $GLOBALS["SL"]->def->getID($defSet, 'Attorney\'d');
            } else {
                $this->sessData->dataSets["complaints"][0]->com_status 
                    = $GLOBALS["SL"]->def->getID($defSet, $status);
            }
        }
        if ($GLOBALS["SL"]->REQ->has('revComplaintType')) {
            $incDef = $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete');
            if ($GLOBALS["SL"]->REQ->revComplaintType == $incDef) {
                $this->sessData->dataSets["complaints"][0]->com_status = $incDef;
                $this->sessData->dataSets["complaints"][0]->com_type 
                    = $GLOBALS["SL"]->def->getID('Complaint Type', 'Unreviewed');
                $status = 'Incomplete';
            } else { 
                $newTypeVal = $GLOBALS["SL"]->def->getVal(
                    'Complaint Type', 
                    $GLOBALS["SL"]->REQ->revComplaintType
                );
                if ($newTypeVal != 'Police Complaint') {
                    $status = $newTypeVal;
                }
                $this->sessData->dataSets["complaints"][0]->com_type 
                    = intVal($GLOBALS["SL"]->REQ->revComplaintType);
            }
        }
        $this->logComplaintReview('Update', $evalNotes, $status);
        $this->sessData->dataSets["complaints"][0]->save();
//echo '<pre>'; print_r($this->sessData->dataSets["complaints"][0]); echo '</pre>'; exit;
        return true;
    }
    
    /**
     * Save and process a report upload done by staff.
     *
     * @return boolean
     */
    protected function saveComplaintAdminUpload()
    {
        $ret = '';
        if ($GLOBALS["SL"]->REQ->hasFile('reportToUpload')) { // file upload
            $upRow->UpUploadFile = $GLOBALS["SL"]->REQ->file('reportToUpload')
                ->getClientOriginalName();
            $extension = $GLOBALS["SL"]->REQ->file('reportToUpload')
                ->getClientOriginalExtension();
            $mimetype = $GLOBALS["SL"]->REQ->file('reportToUpload')->getMimeType();
            $size = $GLOBALS["SL"]->REQ->file('reportToUpload')->getSize();
            if (strtolower($extension) == "pdf" && strtolower($mimetype) == "application/pdf") {
                if (!$GLOBALS["SL"]->REQ->file('reportToUpload')->isValid()) {
                    $ret .= '<div class="txtDanger">Upload Error.' 
                        . /* $_FILES["up" . $nID . "File"]["error"] . */ '</div>';
                } else {
                    $upFold = $this->v["reportUploadFolder"];
                    $this->mkNewFolder($upFold);
                    $filename = $this->sessData->dataSets["complaints"][0]->com_id 
                        . '-' . $GLOBALS["SL"]->REQ->reportUpType . '.pdf';
                    //if ($GLOBALS["SL"]->debugOn) { $ret .= "saving as filename: " . $upFold . $filename . "<br>"; }
                    if (file_exists($upFold . $filename)) {
                        Storage::delete($upFold . $filename);
                    }
                    $GLOBALS["SL"]->REQ->file('reportToUpload')->move($upFold, $filename);
                }
            } else {
                $ret .= '<div class="txtDanger">'
                    . 'Invalid file. Please check the format and try again.</div>';
            }
        }
        return true;
    }
    
    /**
     * Send an email send by staff, and log with this conduct report.
     *
     * @return boolean
     */
    protected function sendComplaintAdminEmail()
    {
        $emailSent = false;
        $emaInd = 0;
        while ($GLOBALS["SL"]->REQ->has('emailID') 
            && $GLOBALS["SL"]->REQ->has('emailTo' . $emaInd . '') 
            && trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . '')) != '') {
            $userToID = -3;
            $email = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . ''));
            $chk = User::where('email', $email)
                ->first();
            if ($chk && isset($chk->id)) {
                $userToID = $chk->id;
            }
            $coreID = ((isset($this->coreID)) ? $this->coreID : -3);
            $emaTo = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . ''));
            if ($emaTo == '--CUSTOM--') {
                $emaTo = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . 'CustEmail'));
                //trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . 'CustName'))
            }
            $cc = $bcc = '';
            if (trim($GLOBALS["SL"]->REQ->get('emailCC' . $emaInd . '')) != '') {
                $cc = trim($GLOBALS["SL"]->REQ->get('emailCC' . $emaInd . ''));
            }
            if (trim($GLOBALS["SL"]->REQ->get('emailBCC' . $emaInd . '')) != '') {
                $bcc = trim($GLOBALS["SL"]->REQ->get('emailBCC' . $emaInd . ''));
            }
            $this->sendNewEmailFromCurrUser(
                trim($GLOBALS["SL"]->REQ->get('emailBodyCust' . $emaInd . '')), 
                trim($GLOBALS["SL"]->REQ->get('emailSubj' . $emaInd . '')), 
                $emaTo, 
                $GLOBALS["SL"]->REQ->get('emailID'), 
                $GLOBALS["SL"]->treeID, 
                $coreID, 
                $userToID,
                $cc,
                $bcc
            );
            if (intVal($GLOBALS["SL"]->REQ->get('emailID')) == 12) {
                $this->sessData->dataSets["complaints"][0]->update([ 
                    "com_status" => $GLOBALS["SL"]->def->getID(
                        'Complaint Status', 
                        'Submitted to Oversight'
                    )
                ]);
                $deptID = $this->v["currEmail"][$emaInd]["deptID"];
                if (isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["whichOver"])) {
                    $whichOver = $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"];
                    $whichRow = $GLOBALS["SL"]->x["depts"][$deptID][$whichOver];
                    if ($whichRow && isset($whichRow->over_id)) {
                        $this->logOverUpDate($coreID, $deptID, 'Submitted');
                    }
                }
                $newRev = new OPzComplaintReviews;
                $newRev->com_rev_complaint = $this->coreID;
                $newRev->com_rev_user      = $this->v["user"]->id;
                $newRev->com_rev_date      = date("Y-m-d H:i:s");
                $newRev->com_rev_type      = 'Update';
                $newRev->com_rev_status    = 'Submitted to Oversight';
                $newRev->save();
            }
            $emailSent = true;
            $emaInd++;
        }
        return $emailSent;
    }
    
    /**
     * Initialize the loading of the widget for the complaint toolbox.
     *
     * @return boolean
     */
    protected function initComplaintToolbox()
    {
        $this->v["firstRevDone"] = false;
        $this->v["firstReview"]  = true;
        $this->v["lastReview"]   = true;
        $this->v["history"]      = [];
        return true;
    }
    
    /**
     * Prepare full history of this conduct report.
     *
     * @return boolean
     */
    protected function loadComplaintAdminHistory()
    {
        $allUserNames = [];
        $reviews = OPzComplaintReviews::where('com_rev_complaint', '=', $this->coreID)
            ->where('com_rev_type', 'NOT LIKE', 'Draft')
            ->orderBy('com_rev_date', 'desc')
            ->get();
        if ($reviews->isNotEmpty()) {
            foreach ($reviews as $i => $r) {
                if ($i == 0) {
                    $this->v["lastReview"] = $r;
                }
                $this->v["firstReview"] = false;
                if (!isset($allUserNames[$r->com_rev_user])) {
                    $allUserNames[$r->com_rev_user] = $this->printUserLnk($r->com_rev_user);
                }
                $desc = '<span class="slBlueDark">';
                if (isset($r->com_rev_next_action) 
                    && trim($r->com_rev_next_action) == 'Complaint Received'
                    && $r->com_rev_status == 'Submitted to Oversight') {
                    $desc .= $r->com_rev_next_action;
                } else {
                    $desc .= $r->com_rev_status;
                }
                $desc .= '</span>';
                $this->v["history"][] = [
                    "type" => 'Status', 
                    "date" => strtotime($r->com_rev_date), 
                    "desc" => $desc, 
                    "who"  => $allUserNames[$r->com_rev_user],
                    "note" => ((isset($r->com_rev_note)) ? trim($r->com_rev_note) : '')
                ];
            }
        }
        $this->v["emailList"] = SLEmails::orderBy('email_name', 'asc')
            ->orderBy('email_type', 'asc')
            ->get();
        $emails = SLEmailed::whereIn('emailed_tree', [1, 42, 197])
            ->where('emailed_rec_id', $this->coreID) //corePublicID
            ->orderBy('created_at', 'asc')
            ->get();
        if ($emails->isNotEmpty()) {
            foreach ($emails as $i => $e) {
                if (!isset($allUserNames[$e->emailed_from_user])) {
                    $allUserNames[$e->emailed_from_user] = $this->printUserLnk($e->emailed_from_user);
                }
                $desc = '<a href="javascript:;" id="hidivBtnEma' . $e->emailed_id . '" class="hidivBtn">"' 
                    . $e->emailed_subject . '"</a><br />sent to ' . $e->emailed_to 
                    . '<div id="hidivEma' . $e->emailed_id . '" class="disNon p10">' 
                    . $e->emailed_body . '</div><div style="margin-bottom: -36px;"></div>';
                $this->v["history"][] = [
                    "type" => 'Email', 
                    "date" => strtotime($e->created_at), 
                    "desc" => $desc, 
                    "who"  => $allUserNames[$e->emailed_from_user]
                ];
            }
        }
        $this->v["history"] = $GLOBALS["SL"]->sortArrByKey($this->v["history"], 'date', 'desc');
        return true;
    }

    /**
     * Process form submission to correct a complaint's associated departments.
     *
     * @return booleon
     */
    protected function processComplaintFixDepts()
    {
        if ($this->v["user"]->hasRole('administrator|databaser|staff')) {
            $evalNotes = '';
            $keepDepts = $delDepts = [];
            if ($GLOBALS["SL"]->REQ->has('keepDepts') 
                && sizeof($GLOBALS["SL"]->REQ->keepDepts) > 0) {
                $keepDepts = $GLOBALS["SL"]->REQ->keepDepts;
            }
            foreach ($GLOBALS["SL"]->x["depts"] as $deptID => $d) {
                if (!in_array($deptID, $keepDepts)) {
                    $delDepts[] = $deptID;
                    $evalNotes .= $this->fixDeptsRemove($d["deptRow"]);
                }
            }
            if ($GLOBALS["SL"]->REQ->has('keepDeptNew')) {
                $newID = intVal($GLOBALS["SL"]->REQ->keepDeptNew);
                if ($newID > 0) {
                    $deptRow = OPDepartments::where('dept_id', $newID)
                        ->select('dept_id', 'dept_name')
                        ->first();
                    if ($deptRow && isset($deptRow->dept_name)) {
                        $evalNotes .= $this->fixDeptsAdd($deptRow);
                    }
                }
            }
            $status = $this->sessData->dataSets["complaints"][0]->com_status;
            $status = $GLOBALS["SL"]->def->getVal('Complaint Status', $status);
            $this->logComplaintReview('Staff', $evalNotes, $status);
            $this->fixDeptsClean();
            $redir = '/complaint/read-' . $this->corePublicID 
                . '?refresh=1' . $GLOBALS['SL']->getReqParams();
            $this->redir($redir, true);
            exit;
        }
        return false;
    }

    /**
     * Remove this department's association with this conduct report.
     *
     * @param  App\Models\OPDepartments $deptRow
     * @return string
     */
    protected function fixDeptsAdd($deptRow)
    {
        if (!isset($deptRow->dept_id) || !isset($deptRow->dept_name)) {
            return '';
        }
        $newLnk = new OPLinksComplaintDept;
        $newLnk->lnk_com_dept_complaint_id = $this->coreID;
        $newLnk->lnk_com_dept_dept_id = $deptRow->dept_id;
        $newLnk->save();
        return ', <i>' . $deptRow->dept_name . '</i> Added';
    }

    /**
     * Remove this department's association with this conduct report.
     *
     * @param  App\Models\OPDepartments $deptRow
     * @return string
     */
    protected function fixDeptsRemove($deptRow)
    {
        if (!isset($deptRow->dept_id) || !isset($deptRow->dept_name)) {
            return '';
        }
        OPLinksComplaintDept::where('lnk_com_dept_complaint_id', $this->coreID)
            ->where('lnk_com_dept_dept_id', $deptRow->dept_id)
            ->delete();
        OPLinksComplaintOversight::where('lnk_com_over_complaint_id', $this->coreID)
            ->where('lnk_com_over_dept_id', $deptRow->dept_id)
            ->delete();
        return ', <i>' . $deptRow->dept_name . '</i> Removed';
    }

    /**
     * Delete empty, useless or error-prone department and oversight records.
     *
     * @return string
     */
    protected function fixDeptsClean()
    {
        $deptIDs = [];
        $depts = OPLinksComplaintDept::where('lnk_com_dept_complaint_id', $this->coreID)
            ->get();
        if ($depts->count() > 0) {
            foreach ($depts as $d) {
                $deptIDs[] = $d->lnk_com_dept_dept_id;
                $overs = OPOversight::where('over_dept_id', $d->lnk_com_dept_dept_id)
                    ->get();
                if ($overs->count() > 0) {
                    foreach ($overs as $over) {
                        $chk = OPLinksComplaintOversight::where(
                                'lnk_com_over_complaint_id', $this->coreID)
                            ->where('lnk_com_over_dept_id', $d->lnk_com_dept_dept_id)
                            ->where('lnk_com_over_over_id', $over->over_id)
                            ->first();
                        if (!$chk || !isset($chk->LnkComOverID)) {
                            $newLnk = new OPLinksComplaintOversight;
                            $newLnk->lnk_com_over_complaint_id = $this->coreID;
                            $newLnk->lnk_com_over_dept_id = $d->lnk_com_dept_dept_id;
                            $newLnk->lnk_com_over_over_id = $over->over_id;
                            $newLnk->save();
                        }
                    }
                }
            }
        }
        OPLinksComplaintOversight::where('lnk_com_over_complaint_id', $this->coreID)
            ->whereNotIn('lnk_com_over_dept_id', $deptIDs)
            ->delete();
        OPLinksComplaintOversight::whereNull('lnk_com_over_dept_id')
            ->orWhere('lnk_com_over_dept_id', '<=', 0)
            ->delete();
        OPLinksComplaintDept::whereNull('lnk_com_dept_dept_id')
            ->orWhere('lnk_com_dept_dept_id', '<=', 0)
            ->delete();
        return '';
    }

}
