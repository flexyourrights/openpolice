<?php
/**
  * OpenReportTools is mid-level class with functions to print and process
  * administrative forms used by report owners and oversight investigators.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use App\Models\OPDepartments;
use App\Models\OPzComplaintReviews;
use SurvLoop\Controllers\Stats\SessAnalysis;
use OpenPolice\Controllers\OpenReport;

class OpenReportTools extends OpenReport
{
    /**
     * Logs a review, notes, change of status, or other procedural changes
     * to a complaint (via its report page).
     *
     * @param  string $type
     * @param  string $note
     * @param  int $status
     * @return void
     */
    protected function logComplaintReview($type, $note, $status = 0)
    {
        $newReview = new OPzComplaintReviews;
        $newReview->com_rev_complaint = $this->coreID;
        $newReview->com_rev_user      = $this->v["user"]->id;
        $newReview->com_rev_date      = date("Y-m-d H:i:s");
        $newReview->com_rev_type      = $type;
        $newReview->com_rev_note      = $note;
        $newReview->com_rev_status    = $status;
        $newReview->save();
    }

    /**
     * Clear all SurvLoop-level report caches of the current complaint.
     *
     * @return boolean
     */
    protected function clearComplaintCaches()
    {
        return $GLOBALS["SL"]->forgetAllItemCaches(42, $this->coreID);
    }
    
    /**
     * Print owner admin tools for managing one complaint.
     *
     * @return string
     */
    protected function printComplaintOwner()
    {
        $this->loadOversightDateLookups();
        $this->prepEmailComplaintData();
        $depts = $oversights = $overUpdates = [];
        if (isset($this->sessData->dataSets["departments"])) {
            $depts = $this->sessData->dataSets["departments"];
        }
        if (isset($this->sessData->dataSets["oversight"])) {
            $oversights = $this->sessData->dataSets["oversight"];
        }
        if (isset($this->sessData->dataSets["links_complaint_oversight"])) {
            $overUpdates = $this->sessData->dataSets["links_complaint_oversight"];
        }
        $comStatus = $GLOBALS["SL"]->def->getVal(
            'Complaint Status', 
            $this->sessData->dataSets["complaints"][0]->com_status
        );
        $hasCompatible = $hideUpdate = false;
        $charged = $this->sessData->dataSets["complaints"][0]->com_all_charges_resolved;
        if ($comStatus == 'Pending Attorney'
            && ($charged != 'Y' || !in_array($charged, ['N', '?']))) {
            $hideUpdate = true;
        }
        if (isset($GLOBALS["SL"]->x["depts"])
            && sizeof($GLOBALS["SL"]->x["depts"]) > 0) {
            foreach ($GLOBALS["SL"]->x["depts"] as $d) {
                if (isset($d["deptRow"]->dept_op_compliant) 
                    && intVal($d["deptRow"]->dept_op_compliant) == 1) {
                    $hasCompatible = true;
                    if ($comStatus == 'OK to Submit to Oversight') {
                        $hideUpdate = true;
                    }
                }
            }
        }
        $ret = view(
            'vendor.openpolice.nodes.1714-report-inc-owner-tools', 
            [
                "user"           => $this->v["user"],
                "complaint"      => $this->sessData->dataSets["complaints"][0],
                "depts"          => $depts,
                "oversights"     => $oversights,
                "overUpdates"    => $overUpdates,
                "overList"       => $this->oversightList(),
                "warning"        => $this->multiRecordCheckDelWarn(),
                "privacyForm"    => $this->printComplaintOwnerPrivacyForm(),
                "comDepts"       => $this->v["comDepts"],
                "oversightDates" => $this->v["oversightDateLookups"],
                "comStatus"      => $comStatus,
                "hasCompatible"  => $hasCompatible,
                "hideUpdate"     => $hideUpdate
            ]
        )->render();
        $title = '<span class="slBlueDark">' . $this->getCurrComplaintEngLabel() 
            . ': Your Toolkit</span>';
        return '<div class="pT20 pB20">' 
            . $GLOBALS["SL"]->printAccard($title, $ret, true) 
            . '</div>';
    }
    
    /**
     * Print a dialogue for the complainant to select their privacy setting.
     *
     * @return string
     */
    protected function printComplaintOwnerPrivacyForm()
    {
        $status = $this->sessData->dataSets["complaints"][0]->com_status;
        $status = $GLOBALS["SL"]->def->getVal('Complaint Status', $status);
        $tooLateForAnon = [
            'Submitted to Oversight', 
            'Received by Oversight', 
            'Declined To Investigate (Closed)', 
            'Investigated (Closed)'
        ];
        $offCnt = 0;
        if (isset($this->sessData->dataSets["officers"])) {
            $offCnt = sizeof($this->sessData->dataSets["officers"]);
        }
        return view(
            'vendor.openpolice.inc-static-privacy-page', 
            [
                "complaint"  => $this->sessData->dataSets["complaints"][0],
                "offCnt"     => $offCnt,
                "twoOptions" => in_array($status, $tooLateForAnon)
            ]
        )->render();
    }

    /**
     * Lists and provides access to previous/current incomplete survey
     * sessions the user might want to resume or delete.
     *
     * @return string
     */
    protected function printComplaintSessPath()
    {
        if ($this->v["isOwner"] 
            || $this->v["user"]->hasRole('administrator|databaser|staff')) {
            $this->loadCustLoop($GLOBALS["SL"]->REQ, 1, 1);
            $this->custReport->loadTree(1);
            $analyze = new SessAnalysis(1);
            $nodeTots = $analyze->loadNodeTots($this->custReport);
            $coreTots = $analyze->analyzeCoreSessions($this->coreID);
            return '<div class="pT20 pB20"><div class="slCard mT20 mB20">'
                . '<h2>Incomplete: Session Attempt History</h2>'
                . view(
                    'vendor.survloop.admin.tree.tree-session-attempt-history', 
                    [
                        "core"     => $coreTots,
                        "nodeTots" => $nodeTots
                    ]
                )->render() . '<br /></div></div>';
        }
        return '';
    }

    /**
     * Save and process all updates made by the complainant for this report.
     *
     * @return boolean
     */
    protected function processOwnerUpdate()
    {
        if ($this->v["isOwner"]) {
            $hasUpdate = ($GLOBALS["SL"]->REQ->has('ownerUpdate') 
                && intVal($GLOBALS["SL"]->REQ->get('ownerUpdate')) == 1 
                && isset($this->sessData->dataSets["oversight"]));
            $hasPublish = ($GLOBALS["SL"]->REQ->has('ownerPublish') 
                && intVal($GLOBALS["SL"]->REQ->get('ownerPublish')) == 1);
            if ($hasUpdate || $hasPublish) {
                $this->loadOversightDateLookups();
                $this->prepEmailComplaintData();
                if ($hasUpdate) {
                    $this->processOwnerUpdateStatus();
                } elseif ($hasPublish) {
                    $this->processOwnerUpdatePublish();
                }
                $this->clearComplaintCaches();
                $redir = '?refresh=1';
                $this->redir($redir, true);
                return true;
            }
        }
        return false;
    }

    /**
     * Save and process status updates made by the complainant for this report.
     *
     * @return boolean
     */
    protected function processOwnerUpdateStatus()
    {
        $evalNotes = '';
        if ($GLOBALS["SL"]->REQ->has('overNote')) {
            $evalNotes .= trim($GLOBALS["SL"]->REQ->overNote) . ' — ';
        }
        $evalNotes .= $this->processComplaintOverDates();
        $deptID = $this->sessData->dataSets["oversight"][0]->over_dept_id;
        $overID = $this->sessData->dataSets["oversight"][0]->over_id;
        if (isset($this->sessData->dataSets["oversight"][1]) 
            && $this->sessData->dataSets["oversight"][1]->over_type == 303) {
            $overID = $this->sessData->dataSets["oversight"][1]->over_id;
        }
        $overUpdateRow = $this->getOverUpdateRow($this->coreID, $deptID);
        $evalNotes .= $this->processComplaintOwnerStatus();

        $this->logComplaintReview('Owner', $evalNotes, $GLOBALS["SL"]->REQ->overStatus);
        if ($GLOBALS["SL"]->REQ->has('overStatus')
            && trim($GLOBALS["SL"]->REQ->overStatus) != '') {
            $defSet = 'Complaint Status';
            $okdDef = $GLOBALS["SL"]->def->getID($defSet, 'OK to Submit to Oversight');
            $subDef = $GLOBALS["SL"]->def->getID($defSet, 'Submitted to Oversight');
            $recDef = $GLOBALS["SL"]->def->getID($defSet, 'Received by Oversight');

            if (trim($GLOBALS["SL"]->REQ->overStatus) == 'Received by Oversight') {
                $this->logOverUpDate($this->coreID, $deptID, 'received', $overUpdateRow);
                if ($this->sessData->dataSets["complaints"][0]->com_status == $okDef) {
                    $this->sessData->dataSets["complaints"][0]->update([ 
                        "com_status" => $subDef
                    ]);
                }
            } else {
                $okTypes = [
                    'Submitted to Oversight', 
                    'OK to Submit to Oversight'
                ];
                if ($GLOBALS["SL"]->REQ->overStatus == 'Investigated (Closed)') {
                    $this->logOverUpDate($this->coreID, $deptID, 'investigated', $overUpdateRow);
                } elseif (in_array($GLOBALS["SL"]->REQ->overStatus, $okTypes)) {
                    if (isset($overUpdateRow->lnk_com_over_received) 
                        && $overUpdateRow->lnk_com_over_received != '') {
                        $overUpdateRow->lnk_com_over_received = NULL;
                        $overUpdateRow->save();
                    }
                }
                $this->sessData->dataSets["complaints"][0]->update([ 
                    "com_status" => $GLOBALS["SL"]->def->getID(
                        'Complaint Status', 
                        $GLOBALS["SL"]->REQ->overStatus
                    )
                ]);
            }
        }
        return true;
    }

    /**
     * Save and process changes to the complaint status by the complainant.
     * Returns any relevant notes to be logged.
     *
     * @return string
     */
    protected function processComplaintOwnerStatus()
    {
        $evalNotes = '';
        if (isset($this->v["comDepts"]) 
            && sizeof($this->v["comDepts"]) > 0
            && ($this->v["isOwner"] 
                || $this->v["user"]->hasRole('administrator|databaser|staff'))) {
            foreach ($this->v["comDepts"] as $c => $dept) {
                if (isset($dept["deptRow"]) && isset($dept["deptRow"]->dept_name)) {
                    $fld = 'over' . $dept["deptRow"]->dept_id . 'Status';
                    if ($GLOBALS["SL"]->REQ->has($fld) 
                        && is_array($GLOBALS["SL"]->REQ->get($fld))) {
                        $evalNotes .= ' ' . $this->processComplaintOwnerStatusChecked($fld) . ' ';
                    }
                }
            }
        }
        return $evalNotes;
    }
    
    /**
     * Save and process changes to dates related to filing reports
     * formally with investigative agencies. 
     * Returns any relevant notes to be logged.
     *
     * @return string
     */
    protected function processComplaintOverDates()
    {
        $evalNotes = '';
        if (($this->v["isOwner"] 
                || $this->v["user"]->hasRole('administrator|databaser|staff')) 
            && isset($this->v["comDepts"]) 
            && sizeof($this->v["comDepts"]) > 0) {
            foreach ($this->v["comDepts"] as $c => $dept) {
                if (isset($dept["deptRow"]) && isset($dept["deptRow"]->dept_name)) {
                    $fldID = 'over' . $dept["id"] . 'Status';
                    foreach ($this->v["oversightDateLookups"] as $d => $date) {
                        $dateFld = $fldID . $d . 'date';
                        $dbFld = $this->v["oversightDateLookups"][$d][0];
                        $newDate = $oldDate = 0;
                        if (isset($this->v["comDepts"][$c]["overDates"]->{ $dbFld })) {
                            $oldDate = $this->v["comDepts"][$c]["overDates"]->{ $dbFld };
                            $oldDate = $GLOBALS["SL"]->dateToTime($oldDate);
                        }
                        if ($GLOBALS["SL"]->REQ->has($fldID) 
                            && is_array($GLOBALS["SL"]->REQ->get($fldID))
                            && in_array($d, $GLOBALS["SL"]->REQ->get($fldID)) 
                            && $GLOBALS["SL"]->REQ->has($dateFld)) {
                            $newDate = $GLOBALS["SL"]->REQ->get($dateFld);
                            $newDate = $GLOBALS["SL"]->dateToTime($newDate);
                        }
                        if ($oldDate != $newDate) {
                            if (!isset($this->v["comDepts"][$c]["overDates"]->lnk_com_over_dept_id)) {
                                $evalNotes .= '<br />Failed to save update on department #'
                                    . $dept["id"] . '.<br />';
                            } else {
                                if (intVal($newDate) > 0) {
                                    $this->v["comDepts"][$c]["overDates"]->update([
                                        $dbFld => date("Y-m-d", $newDate) . ' 00:00:00'
                                    ]);
                                } else {
                                    $this->v["comDepts"][$c]["overDates"]->update([
                                        $dbFld => NULL
                                    ]);
                                }
                                $evalNotes .= view(
                                    'vendor.openpolice.nodes.1712-report-inc-date-eval-note', 
                                    [
                                        "deptName"  => $dept["deptRow"]->dept_name,
                                        "dateLabel" => $this->v["oversightDateLookups"][$d][1],
                                        "oldDate"   => $oldDate,
                                        "newDate"   => $newDate
                                    ]
                                )->render();
                            }
                        }
                    }
                }
            }
        }
        return $evalNotes;
    }

    /**
     * Save and process changes to the complaint status by the complainant.
     * Returns any relevant notes to be logged.
     *
     * @param  array $fld
     * @return string
     */
    protected function processComplaintOwnerStatusChecked($fld)
    {
        $evalNotes = $newStatus = '';
//echo '<pre>'; print_r($this->v["oversightDateLookups"]); echo '</pre>'; exit;
        foreach ($this->v["oversightDateLookups"] as $d => $date) {
            if (in_array($d, $GLOBALS["SL"]->REQ->get($fld))) {
                $currStatus = $GLOBALS["SL"]->def->getVal(
                    'Complaint Status', 
                    $this->sessData->dataSets["complaints"][0]->com_status
                );
                if ($date[0] == 'lnk_com_over_submitted') {
                    $newStatus = 'Submitted to Oversight';
                } elseif ($date[0] == 'lnk_com_over_received') {
                    $newStatus = 'Received by Oversight';
                } elseif ($date[0] == 'lnk_com_over_still_no_response') {
                    $newStatus = 'Still No Response from Agency';
                } elseif ($date[0] == 'lnk_com_over_investigated') {
                    $newStatus = 'Investigated (Closed)';
                }
//echo 'had ' . $currStatus . ', now ' .  $newStatus . '<br />';
                if ($newStatus != '') {
                    $def = $GLOBALS["SL"]->def->getID('Complaint Status', $newStatus);
//echo 'had ' . $currStatus . ', now ' .  $newStatus . ' - ' . $def . '<br />';
                    $this->sessData->dataSets["complaints"][0]->update([
                        "com_status" => $def
                    ]);
                }
            }
        }
        return '';
    }

    /**
     * Save and process a privacy setting update made by the complainant for this report.
     *
     * @return boolean
     */
    protected function processOwnerUpdatePublish()
    {
        $evalNotes = ' ';
        $pubOwn = $pubOff = 0;
        if ($GLOBALS["SL"]->REQ->has('n2787fld')) {
            $pubOwn = intVal($GLOBALS["SL"]->REQ->n2787fld);
            if ($pubOwn == 1) {
                $evalNotes .= 'Publish Owner\'s Name. ';
            } else {
                $evalNotes .= 'Do Not Publish Owner\'s Name. ';
            }
        }
        if ($GLOBALS["SL"]->REQ->has('n2789fld')) {
            $pubOff = intVal($GLOBALS["SL"]->REQ->n2789fld);
            if ($pubOff == 1) {
                $evalNotes .= 'Publish Officer Names. ';
            } else {
                $evalNotes .= 'Do Not Publish Officer Names. ';
            }
        }
        $this->logComplaintReview('Owner', trim($evalNotes), 'Publishing Settings');
        $this->sessData->dataSets["complaints"][0]->com_publish_user_name = $pubOwn;
        $this->sessData->dataSets["complaints"][0]->com_publish_officer_name = $pubOff;

        $attDef = $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney');
        $okDef = $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight');
        if ($this->sessData->dataSets["complaints"][0]->com_status == $attDef
            && $GLOBALS["SL"]->REQ->has('n2787fld')) {
            $this->sessData->dataSets["complaints"][0]->com_status = $okDef;
        }
        $this->sessData->dataSets["complaints"][0]->save();
        return true;
    }

    /**
     * Print the complaint's english title for this report.
     *
     * @return boolean
     */
    protected function getCurrComplaintEngLabel()
    {
        $ret = '';
        if (!isset($this->sessData->dataSets["complaints"][0]->com_public_id) 
            || intVal($this->sessData->dataSets["complaints"][0]->com_public_id) <= 0) {
            $ret = 'Incomplete Complaint #' 
                . $this->sessData->dataSets["complaints"][0]->com_id;
        } else {
            $ret = 'Complaint #' 
                . $this->sessData->dataSets["complaints"][0]->com_public_id;
        }
        return $ret;
    }
    
    /**
     * Print the list of investigative agencies related to this conduct report.
     *
     * @return string
     */
    protected function oversightList()
    {
        $ret = '';
        if (isset($this->sessData->dataSets["oversight"]) 
            && sizeof($this->sessData->dataSets["oversight"]) > 0) {
            $cnt = 0;
            foreach ($this->sessData->dataSets["oversight"] as $i => $o) {
                if (isset($o->over_agnc_name) && trim($o->over_agnc_name) != '') {
                    $ret .= (($cnt > 0) ? ' and ' : '') . $o->over_agnc_name;
                    $cnt++;
                }
            }
        }
        return $ret;
    }
    
}