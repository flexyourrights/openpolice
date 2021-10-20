<?php
/**
  * OpenDeptStats is a mid-level class which manages
  * aggregate calculations related to department performance.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.2.18
  */
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OPDepartments;
use App\Models\OPzEditDepartments;
use App\Models\OPzEditOversight;
use App\Models\OPzVolunTmp;
use App\Models\OPOversight;
use App\Models\OPDeptStats;
use App\Models\OPLinksComplaintOversight;
use App\Models\OPLinksComplimentOversight;
use RockHopSoft\Survloop\Controllers\PageLoadUtils;
use RockHopSoft\Survloop\Controllers\Globals\ObjSort;
use FlexYourRights\OpenPolice\Controllers\OpenPolice;
use FlexYourRights\OpenPolice\Controllers\DepartmentScores;
use FlexYourRights\OpenPolice\Controllers\OpenOfficers;

class OpenDeptStats extends OpenOfficers
{
    /**
     * Print the departments with the greatest number of complaints filed.
     *
     * @param  int $nID
     * @return string
     */
    protected function printTopComplaintDepts($nID)
    {
        //$ret = $GLOBALS["SL"]->chkCache('/department-responsiveness-to-complaints', 'list-cust', 1);
        //if (trim($ret) == '' || $GLOBALS["SL"]->REQ->has('refresh')) {
            $GLOBALS["SL"]->loadStates();
            $this->calcTopComplaintDepts($nID);
            $this->v["deptStats"] = DB::table('op_dept_stats')
                ->join('op_departments', 'op_departments.dept_id',
                    '=', 'op_dept_stats.dept_stat_dept_id')
                ->orderBy('op_dept_stats.dept_stat_submitted_op', 'desc')
                ->select('op_departments.dept_name', 'op_departments.dept_slug',
                    'op_departments.dept_address_state', 'op_dept_stats.*')
                ->get();
            $this->calcTopComplaintDeptSums();
            $this->v["isStaff"] = ($nID == 3058);
            $ret = view(
                'vendor.openpolice.nodes.2375-departments-most-complaints',
                $this->v
            )->render();
            //$GLOBALS["SL"]->putCache('/list-all-departments', $ret, 'list-cust', 1);
        //}
        return $ret;
    }

    /**
     * Calculates departments' complaint totals and stored them in the database.
     *
     * @param  int $nID
     * @return string
     */
    private function calcTopComplaintDepts($nID)
    {
        $stats = $ids = [];
        $defPublished = $this->getPublishedStatusList('complaints');
        $defSet = 'Complaint Status';
        $defPublished[] = $GLOBALS["SL"]->def->getID($defSet, 'New');
        $defPublished[] = $GLOBALS["SL"]->def->getID($defSet, 'Hold');
        $defPublished[] = $GLOBALS["SL"]->def->getID($defSet, 'Reviewed');
        $defPublished[] = $GLOBALS["SL"]->def->getID($defSet, 'Needs More Work');
        $defPublished[] = $GLOBALS["SL"]->def->getID($defSet, 'Wants Attorney');
        $defPublished[] = $GLOBALS["SL"]->def->getID($defSet, 'Pending Attorney');
        $defPublished[] = $GLOBALS["SL"]->def->getID($defSet, 'Has Attorney');
        $lnkOvers = DB::table('op_links_complaint_oversight')
            ->join('op_complaints', 'op_complaints.com_id',
                '=', 'op_links_complaint_oversight.lnk_com_over_complaint_id')
            ->where('op_links_complaint_oversight.lnk_com_over_dept_id', '>', 0)
            ->whereIn('op_complaints.com_status', $defPublished)
            ->select('op_links_complaint_oversight.*', 'op_complaints.com_status')
            ->get();
        if ($lnkOvers->isNotEmpty()) {
            foreach ($lnkOvers as $lnk) {
                $deptID = $lnk->lnk_com_over_dept_id;
                if (!isset($stats[$deptID])) {
                    $ids[] = $deptID;
                    $stats[$deptID] = new DeptComplaintStats($deptID);
                }
                $stats[$deptID]->addDates($lnk);
            }
            foreach ($stats as $stat) {
                $stat->saveStats();
            }
        }
        OPDeptStats::whereNotIn('dept_stat_dept_id', $ids)
            ->delete();
        return true;
    }

    /**
     * Sum the department stats.
     *
     * @return boolean
     */
    private function calcTopComplaintDeptSums()
    {
        $this->v["deptTots"] = new OPDeptStats;
        $flds = [
            'dept_stat_submitted_op', 'dept_stat_attorneys', 'dept_stat_ok_to_file',
            'dept_stat_investigate_submitted', 'dept_stat_investigate_received',
            'dept_stat_investigate_no_response', 'dept_stat_investgated',
            'dept_stat_investigate_declined'
        ];
        foreach ($flds as $fld) {
            $this->v["deptTots"]->{ $fld } = 0;
        }
        if ($this->v["deptStats"]->isNotEmpty()) {
            foreach ($this->v["deptStats"] as $d) {
                foreach ($flds as $fld) {
                    if (isset($d->{ $fld }) && intVal($d->{ $fld }) > 0) {
                        $this->v["deptTots"]->{ $fld } += intVal($d->{ $fld });
                    }
                }
            }
        }
        return true;
    }

}

class DeptComplaintStats
{
    public $deptID        = 0;
    public $submittedOP   = 0;
    public $attorneys     = 0;
    public $okToFile      = 0;
    public $submittedOvr  = 0;
    public $receivedOvr   = 0;
    public $noResponseOvr = 0;
    public $investOvr     = 0;
    public $declinedOvr   = 0;
    public $publicTotal   = 0;

    /**
     * Initialize this object with the department ID.
     *
     * @param  int $isStaff
     */
    public function __construct($deptID = 0)
    {
        $this->deptID = $deptID;
    }

    /**
     * Check the different activity timestamps to increment department totals.
     *
     * @param  App\Models\OPLinksComplaintOversight $lnk
     * @return string
     */
    public function addDates($lnk = null)
    {
        $this->submittedOP++;
        $defSet = 'Complaint Status';
        $defAtts = [
            $GLOBALS["SL"]->def->getID($defSet, 'Wants Attorney'),
            $GLOBALS["SL"]->def->getID($defSet, 'Pending Attorney'),
            $GLOBALS["SL"]->def->getID($defSet, 'Has Attorney')
        ];
        if (in_array($lnk->com_status, $defAtts)) {
            $this->attorneys++;
        }
        if ($lnk->com_status
            == $GLOBALS["SL"]->def->getID($defSet, 'OK to Submit to Oversight')) {
            $this->okToFile++;
            $this->publicTotal++;
        }
        if (isset($lnk->lnk_com_over_submitted)
            && trim($lnk->lnk_com_over_submitted) != '') {
            $this->submittedOvr++;
            $this->publicTotal++;
        }
        if (isset($lnk->lnk_com_over_received)
            && trim($lnk->lnk_com_over_received) != '') {
            $this->receivedOvr++;
            $this->publicTotal++;
        }
        if (isset($lnk->lnk_com_over_still_no_response)
            && trim($lnk->lnk_com_over_still_no_response) != '') {
            $this->noResponseOvr++;
            $this->publicTotal++;
        }
        if (isset($lnk->lnk_com_over_investigated)
            && trim($lnk->lnk_com_over_investigated) != '') {
            $this->investOvr++;
            $this->publicTotal++;
        }
        if (isset($lnk->lnk_com_over_declined)
            && trim($lnk->lnk_com_over_declined) != '') {
            $this->declinedOvr++;
            $this->publicTotal++;
        }
        return true;
    }

    /**
     * Store the different department totals to the database.
     *
     * @return boolean
     */
    public function saveStats()
    {
        $stats = OPDeptStats::where('dept_stat_dept_id', $this->deptID)
            ->first();
        if (!$stats || !isset($stats->dept_stat_dept_id)) {
            $stats = new OPDeptStats;
            $stats->dept_stat_dept_id = $this->deptID;
        }
        $stats->dept_stat_submitted_op            = $this->submittedOP;
        $stats->dept_stat_attorneys               = $this->attorneys;
        $stats->dept_stat_ok_to_file              = $this->okToFile;
        $stats->dept_stat_investigate_submitted   = $this->submittedOvr;
        $stats->dept_stat_investigate_received    = $this->receivedOvr;
        $stats->dept_stat_investigate_no_response = $this->noResponseOvr;
        $stats->dept_stat_investgated             = $this->investOvr;
        $stats->dept_stat_investigate_declined    = $this->declinedOvr;
        $stats->save();
        return true;
    }

}
