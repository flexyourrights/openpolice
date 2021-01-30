<?php
/**
  * OpenReportToolsOversight is mid-level class with functions to 
  * print and process administrative forms used by OpenPolice.org staff.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.2.4
  */
namespace FlexYourRights\OpenPolice\Controllers;

use App\Models\OPOversight;
use FlexYourRights\OpenPolice\Controllers\OpenReportTools;

class OpenReportToolsOversight extends OpenReportTools
{
    /**
     * Print oversight admin tools for managing one complaint.
     *
     * @return string
     */
    protected function printComplaintOversight()
    {
        $overRow = OPOversight::where('over_email', $this->v["user"]->email)
            ->first();
        return view(
            'vendor.openpolice.nodes.1711-report-inc-oversight-tools', 
            [
                "user"      => $this->v["user"],
                "complaint" => $this->sessData->dataSets["complaints"][0],
                "overRow"   => $overRow
            ]
        )->render();
    }

    /**
     * Print the MFA dialogue for investigative agencies to access this conduct report.
     *
     * @return string
     */
    protected function printMfaInstruct()
    {
        if (isset($this->v["tokenUser"]) && $this->v["tokenUser"]) {
            return view(
                'vendor.openpolice.nodes.1780-mfa-instructions', 
                [
                    "user" => $this->v["tokenUser"],
                    "mfa"  => $this->processTokenAccess(false)
                ]
            )->render();
        }
        return '';
    }

    /**
     * Save and process oversight use of admin tools for managing one complaint.
     *
     * @return boolean
     */
    protected function saveComplaintOversight()
    {
        if (!isset($this->v["user"]) || !isset($this->v["user"]->email)) {
            return false;
        }
        $overRow = OPOversight::where('over_email', $this->v["user"]->email)
            ->first();
        if ($this->chkOverUserHasCore()) {

            if ($GLOBALS["SL"]->REQ->has('overUpdate') 
                && intVal($GLOBALS["SL"]->REQ->get('overUpdate')) == 1
                && $overRow 
                && isset($overRow->over_dept_id)) {
                $deptID = $overRow->over_dept_id;

                $overUpdateRow = $GLOBALS["SL"]->x["depts"][$deptID]["overUpdate"];
                $status = '';
                $evalNotes = (($GLOBALS["SL"]->REQ->has('overNote')) 
                    ? trim($GLOBALS["SL"]->REQ->overNote) : '');
                if ($GLOBALS["SL"]->REQ->has('overStatus')) { 
                    $status = trim($GLOBALS["SL"]->REQ->overStatus);
                    if ($status == 'Received by Oversight') {
                        $this->logOverUpDate($this->coreID, $deptID, 'received');
                    } elseif ($status == 'Investigated (Closed)') {
                        $this->logOverUpDate($this->coreID, $deptID, 'investigated');
                    }
                    $statusID = $GLOBALS["SL"]->def->getID('Complaint Status', $status);
                    if ($statusID > 0) {
                        $this->sessData->dataSets["complaints"][0]->com_status = $statusID;
                        $this->sessData->dataSets["complaints"][0]->save();
                    }
                }
                $this->logComplaintReview('Oversight', $evalNotes, $status);
                $this->clearComplaintCaches();
                echo $this->redir('?refresh=1', true);
                exit;

            } elseif ($GLOBALS["SL"]->REQ->has('upResult') 
                && intVal($GLOBALS["SL"]->REQ->get('upResult')) == 1) {
                
                $this->clearComplaintCaches();

            }
        }
        return true;
    }

}