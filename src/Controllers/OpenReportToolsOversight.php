<?php
/**
  * OpenReportToolsOversight is mid-level class with functions to 
  * print and process administrative forms used by OpenPolice.org staff.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.2.4
  */
namespace OpenPolice\Controllers;

use App\Models\OPOversight;
use OpenPolice\Controllers\OpenReportTools;

class OpenReportToolsOversight extends OpenReportTools
{
    /**
     * Print oversight admin tools for managing one complaint.
     *
     * @return string
     */
    protected function printComplaintOversight()
    {
        $overRow = OPOversight::where('OverEmail', $this->v["user"]->email)
            ->first();
        return view(
            'vendor.openpolice.nodes.1711-report-inc-oversight-tools', 
            [
                "user"      => $this->v["user"],
                "complaint" => $this->sessData->dataSets["Complaints"][0],
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
        $overRow = OPOversight::where('OverEmail', $this->v["user"]->email)
            ->first();
        if ($this->chkOverUserHasCore()) {
            if ($GLOBALS["SL"]->REQ->has('overUpdate') 
                && intVal($GLOBALS["SL"]->REQ->get('overUpdate')) == 1
                && $overRow && isset($overRow->OverDeptID)) {
                $overUpdateRow = $this
                    ->getOverUpdateRow($this->coreID, $overRow->OverID);
                $status = 0;
                $evalNotes = (($GLOBALS["SL"]->REQ->has('overNote')) 
                    ? trim($GLOBALS["SL"]->REQ->overNote) : '');
                if ($GLOBALS["SL"]->REQ->has('overStatus')) { 
                    if ($GLOBALS["SL"]->REQ->overStatus 
                        == 'Received by Oversight') {
                        $this->logOverUpDate(
                            $this->coreID, 
                            $overRow->OverID, 
                            'Received', 
                            $overUpdateRow
                        );
                    } elseif ($GLOBALS["SL"]->REQ->overStatus 
                        == 'Investigated (Closed)') {
                        $this->logOverUpDate(
                            $this->coreID, 
                            $overRow->OverID, 
                            'Investigated', 
                            $overUpdateRow
                        );
                    }
                    $statusID = $GLOBALS["SL"]->def->getID(
                        'Complaint Status', 
                        trim($GLOBALS["SL"]->REQ->overStatus)
                    );
                    $this->sessData->dataSets["Complaints"][0]->update([
                        "ComStatus" => $statusID
                    ]);
                    $status = $GLOBALS["SL"]->REQ->overStatus;
                }
                $this->logComplaintReview('Oversight', $evalNotes, $status);
                $this->clearComplaintCaches();
            } elseif ($GLOBALS["SL"]->REQ->has('upResult') 
                && intVal($GLOBALS["SL"]->REQ->get('upResult')) == 1) {
                
                
                $this->clearComplaintCaches();
            }
        }
        return true;
    }

}