<?php
/**
  * OpenReportTools is mid-level class with functions to print and process
  * administrative forms used by report owners and oversight investigators.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use App\Models\OPDepartments;
use App\Models\OPzComplaintReviews;
use SurvLoop\Controllers\SessAnalysis;
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
        $newReview->ComRevComplaint = $this->coreID;
        $newReview->ComRevUser      = $this->v["user"]->id;
        $newReview->ComRevDate      = date("Y-m-d H:i:s");
        $newReview->ComRevType      = $type;
        $newReview->ComRevNote      = $note;
        $newReview->ComRevStatus    = $status;
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
        $this->prepEmailComData();
        $depts = ((isset($this->sessData->dataSets["Departments"])) 
            ? $this->sessData->dataSets["Departments"] : []);
        $oversights = ((isset($this->sessData->dataSets["Oversight"]))
            ? $this->sessData->dataSets["Oversight"] : []);
        $overUpdates = ((isset($this->sessData->dataSets["LinksComplaintOversight"]))
            ? $this->sessData->dataSets["LinksComplaintOversight"] : []);
        $ret = view('vendor.openpolice.nodes.1714-report-inc-owner-tools', [
            "user"        => $this->v["user"],
            "complaint"   => $this->sessData->dataSets["Complaints"][0],
            "depts"       => $depts,
            "oversights"  => $oversights,
            "overUpdates" => $overUpdates,
            "overList"    => $this->oversightList(),
            "warning"     => $this->multiRecordCheckDelWarn(),
            "privacyForm" => $this->printComplaintOwnerPrivacyForm(),
            "comDepts"    => $this->v["comDepts"],
            "oversightDates" => $this->v["oversightDateLookups"]
        ])->render();
        $title = $this->getCurrComplaintEngLabel() . ': Your Toolkit';
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
        $status = $this->sessData->dataSets["Complaints"][0]->ComStatus;
        $status = $GLOBALS["SL"]->def->getVal('Complaint Status', $status);
        $tooLateForAnon = [
            'Submitted to Oversight', 
            'Received by Oversight', 
            'Declined To Investigate (Closed)', 
            'Investigated (Closed)'
        ];
        return view('vendor.openpolice.inc-static-privacy-page', [
            "complaint"  => $this->sessData->dataSets["Complaints"][0],
            "twoOptions" => in_array($status, $tooLateForAnon)
        ])->render();
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
                . view('vendor.survloop.admin.tree.tree-session-attempt-history', [
                    "core"     => $coreTots,
                    "nodeTots" => $nodeTots
                ])->render() . '<br /></div></div>';
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
                && isset($this->sessData->dataSets["Oversight"]));
            $hasPrivacy = ($GLOBALS["SL"]->REQ->has('ownerPublish') 
                && intVal($GLOBALS["SL"]->REQ->get('ownerPublish')) == 1
                && $GLOBALS["SL"]->REQ->has('n2018fld') 
                && intVal($GLOBALS["SL"]->REQ->get('n2018fld')) > 0);
            if ($hasUpdate || $hasPrivacy) {
                $this->loadOversightDateLookups();
                $this->prepEmailComData();
                if ($hasUpdate) {
                    $this->processOwnerUpdateStatus();
                } elseif ($hasPrivacy) {
                    $this->processOwnerUpdatePrivacy();
                }
                $this->clearComplaintCaches();
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
        $evalNotes = (($GLOBALS["SL"]->REQ->has('overNote')) 
            ? trim($GLOBALS["SL"]->REQ->overNote) : '')
            . $this->processComplaintOverDates();
        $overID = $this->sessData->dataSets["Oversight"][0]->OverID;
        if (isset($this->sessData->dataSets["Oversight"][1]) 
            && $this->sessData->dataSets["Oversight"][1]->OverType == 303) {
            $overID = $this->sessData->dataSets["Oversight"][1]->OverID;
        }
        $overUpdateRow = $this->getOverUpdateRow($this->coreID, $overID);
        $evalNotes .= $this->processComplaintOwnerStatus();

        $this->logComplaintReview('Owner', $evalNotes, $GLOBALS["SL"]->REQ->overStatus);
        if ($GLOBALS["SL"]->REQ->has('overStatus')) {
            if (trim($GLOBALS["SL"]->REQ->overStatus) == 'Received by Oversight') {
                $this->logOverUpDate($this->coreID, $overID, 'Received', $overUpdateRow);
                if ($this->sessData->dataSets["Complaints"][0]->ComStatus 
                    == $GLOBALS["SL"]->def->getID('Complaint Status', 
                        'OK to Submit to Oversight')) {
                    $this->sessData->dataSets["Complaints"][0]->update([ 
                        "ComStatus" => $GLOBALS["SL"]->def
                            ->getID('Complaint Status', 'Submitted to Oversight')
                    ]);
                }
            } else {
                if ($GLOBALS["SL"]->REQ->overStatus == 'Investigated (Closed)') {
                    $this->logOverUpDate(
                        $this->coreID, 
                        $overID, 
                        'Investigated', 
                        $overUpdateRow
                    );
                } elseif (in_array($GLOBALS["SL"]->REQ->overStatus, [
                    'Submitted to Oversight', 'OK to Submit to Oversight'])) {
                    if (isset($overUpdateRow->LnkComOverReceived) 
                        && $overUpdateRow->LnkComOverReceived != '') {
                        $overUpdateRow->LnkComOverReceived = NULL;
                        $overUpdateRow->save();
                    }
                }
                $this->sessData->dataSets["Complaints"][0]->update([ 
                    "ComStatus" => $GLOBALS["SL"]->def
                        ->getID('Complaint Status', $GLOBALS["SL"]->REQ->overStatus)
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
        if (isset($this->v["comDepts"]) && sizeof($this->v["comDepts"]) > 0
            && ($this->v["isOwner"] 
            || $this->v["user"]->hasRole('administrator|databaser|staff'))) {
            foreach ($this->v["comDepts"] as $c => $dept) {
                if (isset($dept["deptRow"]) && isset($dept["deptRow"]->DeptName)) {
                    $fld = 'over' . $dept["deptRow"]->DeptID . 'Status';
                    if ($GLOBALS["SL"]->REQ->has($fld) 
                        && is_array($GLOBALS["SL"]->REQ->get($fld))) {
                        $evalNotes .= $this->processComplaintOwnerStatusChecked($fld);
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
                if (isset($dept["deptRow"]) 
                    && isset($dept["deptRow"]->DeptName)) {
                    $fldID = 'over' . $dept["id"] . 'Status';
                    foreach ($this->v["oversightDateLookups"] as $d => $date) {
                        $dateFld = $fldID . $d . 'date';
                        $dbFld = $this->v["oversightDateLookups"][$d][0];
                        $oldDate = ((isset($this->v["comDepts"][$c]["overDates"]->{ $dbFld }))
                            ? $GLOBALS["SL"]->dateToTime(
                                $this->v["comDepts"][$c]["overDates"]->{ $dbFld })
                            : 0);
                        $newDate = 0;
                        if ($GLOBALS["SL"]->REQ->has($fldID) 
                            && is_array($GLOBALS["SL"]->REQ->get($fldID))
                            && in_array($d, $GLOBALS["SL"]->REQ->get($fldID)) 
                            && $GLOBALS["SL"]->REQ->has($dateFld)) {
                            $newDate = $GLOBALS["SL"]
                                ->dateToTime($GLOBALS["SL"]->REQ->get($dateFld));
                        }
                        if ($oldDate != $newDate) {
                            if (intVal($newDate) > 0) {
                                $this->v["comDepts"][$c]["overDates"]->update([
                                    $dbFld => date("Y-m-d", $newDate) . ' 00:00:00'
                                ]);
                            } else {
                                $this->v["comDepts"][$c]["overDates"]->update([
                                    $dbFld => NULL
                                ]);
                            }
                            $evalNotes .= 'Status with the ' 
                                . str_replace("Police Department", "PD", 
                                    str_replace("Sheriff's Office", "Sheriff", 
                                        $dept["deptRow"]->DeptName)) . ' date of ' 
                                . $this->v["oversightDateLookups"][$d][1] . ' changed from '
                                . str_replace('1/1', 'N/A', date('n/j', $oldDate)) . ' to ' 
                                . str_replace('1/1', 'N/A', date('n/j', $newDate)) . '. ';
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
        foreach ($this->v["oversightDateLookups"] as $d => $date) {
            if (in_array($d, $GLOBALS["SL"]->REQ->get($fld))) {
                $currStatus = $GLOBALS["SL"]->def->getVal(
                    'Complaint Status', 
                    $this->sessData->dataSets["Complaints"][0]->ComStatus
                );
                if ($date[0] == 'LnkComOverSubmitted') {
                    if ($currStatus == 'OK to Submit to Oversight') {
                        $newStatus = 'Submitted to Oversight';
                    }
                } elseif ($date[0] == 'LnkComOverReceived') {
                    if (in_array($currStatus, [
                        'OK to Submit to Oversight', 
                        'Submitted to Oversight'])) {
                        $newStatus = 'Received by Oversight';
                    }
                } elseif ($date[0] == 'LnkComOverInvestigated') {
                    if (in_array($currStatus, [
                        'OK to Submit to Oversight', 
                        'Submitted to Oversight', 
                        'Received by Oversight'])) {
                        $newStatus = 'Investigated (Closed)';
                    }
                }
                if ($newStatus != '') {
                    $def = $GLOBALS["SL"]->def->getID('Complaint Status', $newStatus);
                    $this->sessData->dataSets["Complaints"][0]->update([
                        "ComStatus" => $def
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
    protected function processOwnerUpdatePrivacy()
    {
        $evalNotes = $GLOBALS["SL"]->def
            ->getVal('Privacy Types', $GLOBALS["SL"]->REQ->get('n2018fld'))
            . ' Privacy Option Selected';
        $this->logComplaintReview('Owner', $evalNotes, 'OK to Submit to Oversight');
        $this->sessData->dataSets["Complaints"][0]->update([ 
            "ComPrivacy" => $GLOBALS["SL"]->REQ->get('n2018fld'),    
            "ComStatus"  => $GLOBALS["SL"]->def
                ->getID('Complaint Status', 'OK to Submit to Oversight')
        ]);
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
        if (!isset($this->sessData->dataSets["Complaints"][0]->ComPublicID) 
            || intVal($this->sessData->dataSets["Complaints"][0]->ComPublicID) <= 0) {
            $ret = 'Incomplete Complaint #' 
                . $this->sessData->dataSets["Complaints"][0]->ComID;
        } else {
            $ret = 'Complaint #' 
                . $this->sessData->dataSets["Complaints"][0]->ComPublicID;
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
        if (isset($this->sessData->dataSets["Oversight"]) 
            && sizeof($this->sessData->dataSets["Oversight"]) > 0) {
            $cnt = 0;
            foreach ($this->sessData->dataSets["Oversight"] as $i => $o) {
                if (isset($o->OverAgncName) && trim($o->OverAgncName) != '') {
                    $ret .= (($cnt > 0) ? ' and ' : '') . $o->OverAgncName;
                    $cnt++;
                }
            }
        }
        return $ret;
    }
    
}