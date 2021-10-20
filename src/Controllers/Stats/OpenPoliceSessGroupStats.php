<?php
/**
  * OpenPoliceUserStatCalcs totals up multiple user stats.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.3.2
  */
namespace FlexYourRights\OpenPolice\Controllers\Stats;

use DB;
use App\Models\SLEmailed;
use App\Models\OPDepartments;
use App\Models\OPzEditOversight;
use App\Models\OPzComplaintReviews;

class OpenPoliceSessGroupStats
{
    private $userID   = 0;
    public $cats      = null;
    public $tots      = null;
    public $comIDs    = [];
    public $deptIDs   = [];
    public $deptTimes = [];
    public $comTimes  = [];

    public $deptResearch = [];

    public function __construct($userID = 0)
    {
        $this->userID = $userID;
        $this->cats = new OpenPoliceSessComplaintStatCats;
    }

    public function loadAllDepts($timeStart, $timeEnd)
    {
        $actions = DB::table('op_z_edit_oversight')
            ->leftJoin('op_z_edit_departments', 'op_z_edit_oversight.zed_over_zed_dept_id',
                '=', 'op_z_edit_departments.zed_dept_id')
            ->where('op_z_edit_departments.zed_dept_user_id', $this->userID)
            ->where('op_z_edit_oversight.zed_over_over_type', 303)
            ->where('op_z_edit_oversight.created_at', '>=', date("Y-m-d H:i:s", $timeStart))
            ->where('op_z_edit_oversight.created_at', '<=', date("Y-m-d H:i:s", $timeEnd))
            ->select('op_z_edit_departments.zed_dept_duration',
                'op_z_edit_oversight.zed_over_over_dept_id',
                'op_z_edit_oversight.zed_over_online_research',
                'op_z_edit_oversight.zed_over_made_dept_call',
                'op_z_edit_oversight.zed_over_made_ia_call',
                'op_z_edit_oversight.created_at')
            ->get();
        if ($actions->isNotEmpty()) {
            foreach ($actions as $edit) {
                $this->addDeptResearch($edit);
            }
        }
        $this->totalDeptStats();
    }

    public function addDept($deptID, $time)
    {
        if (!in_array($deptID, $this->deptIDs)) {
            $this->deptIDs[] = $deptID;
            $this->deptTimes[] = [];
        }
        foreach ($this->deptIDs as $d => $dID) {
            if ($dID == $deptID && !in_array($time, $this->deptTimes[$d])) {
                $this->deptTimes[$d][] = $time;
            }
        }
    }

    public function addDeptResearch($edit)
    {
        $deptID = $edit->zed_over_over_dept_id;
        if (!isset($this->deptResearch[$deptID])) {
            $this->deptResearch[$deptID] = new OpenPoliceSessUserStats;
        }
        if ($edit->zed_over_online_research
            && intVal($edit->zed_over_online_research) > 0) {
            $this->deptResearch[$deptID]->cntOnline = 1;
        }
        if ($edit->zed_over_made_dept_call
            && intVal($edit->zed_over_made_dept_call) > 0) {
            $this->deptResearch[$deptID]->cntCallDept = 1;
        }
        if ($edit->zed_over_made_ia_call
            && intVal($edit->zed_over_made_ia_call) > 0) {
            $this->deptResearch[$deptID]->cntCallIA = 1;
        }
        if ($edit->zed_dept_duration
            && intVal($edit->zed_dept_duration) > 0) {
            $this->deptResearch[$deptID]->deptDur += intVal($edit->zed_dept_duration);
        }
        // Check if this user did the first round of research on this department
        $chkFirst = OPzEditOversight::where('zed_over_zed_dept_id', $deptID)
            ->where(function($query) {
                $query->where('zed_over_online_research', '>', 0)
                      ->orWhere('zed_over_made_dept_call', '>', 0)
                      ->orWhere('zed_over_made_ia_call', '>', 0);
            })
            ->where('op_z_edit_oversight.zed_over_over_type', 303)
            ->where('created_at', '<', $edit->created_at)
            ->first();
        if (!$chkFirst || !isset($chkFirst->created_at)) {
            $this->deptResearch[$deptID]->orig[] = $deptID;
            $this->deptResearch[$deptID]->origDur += intVal($edit->zed_dept_duration);
        }
    }

    public function totalDeptStats()
    {
        $this->tots = new OpenPoliceSessUserStats;
        if (sizeof($this->deptResearch) > 0) {
            foreach ($this->deptResearch as $deptID => $stats) {
                $this->tots->cntOnline   += $stats->cntOnline;
                $this->tots->cntCallDept += $stats->cntCallDept;
                $this->tots->cntCallIA   += $stats->cntCallIA;
                $this->tots->deptDur     += $stats->deptDur;
                $this->tots->origDur     += $stats->origDur;
                if (sizeof($stats->orig) > 0) {
                    foreach ($stats->orig as $orig) {
                        if (!in_array($orig, $this->tots->orig)) {
                            $this->tots->orig[] = $orig;
                        }
                    }
                }
            }
        }
    }

    public function loadAllComplaints($timeStart, $timeEnd)
    {
        $actions = OPzComplaintReviews::where('com_rev_user', $this->userID)
            ->where('created_at', '>=', date("Y-m-d H:i:s", $timeStart))
            ->where('created_at', '<=', date("Y-m-d H:i:s", $timeEnd))
            ->get();
        if ($actions->isNotEmpty()) {
            foreach ($actions as $review) {
                $this->addReview($review);
            }
        }
        $actions = SLEmailed::whereIn('emailed_tree', [1, 42, 197])
            ->whereNotIn('emailed_email_id', [1, 5])
            ->where('emailed_from_user', $this->userID)
            ->where('created_at', '>=', date("Y-m-d H:i:s", $timeStart))
            ->where('created_at', '<=', date("Y-m-d H:i:s", $timeEnd))
            ->get();
        if ($actions->isNotEmpty()) {
            foreach ($actions as $email) {
                $this->addEmail($email);
            }
        }
        $this->totalComStats();
    }

    private function addCom($comID, $time)
    {
        if (!in_array($comID, $this->comIDs)) {
            $this->comIDs[] = $comID;
            $this->comTimes[] = [];
        }
        foreach ($this->comIDs as $c => $cID) {
            if ($cID == $comID && !in_array($time, $this->comTimes[$c])) {
                $this->comTimes[$c][] = $time;
            }
        }
    }

    private function addReview($review)
    {
        $comID = $review->com_rev_complaint;
        $time = strtotime($review->com_rev_date);
        $this->addCom($comID, $time);
        $this->cats->loadReview($review);
    }

    private function addEmail($email)
    {
        $comID = $email->emailed_rec_id;
        $time = strtotime($email->created_at);
        $this->addCom($comID, $time);
        $this->cats->loadEmail($email);
    }

    private function totalComStats()
    {
        $this->tots->comCats = $this->tots->catsDur = [];
        foreach ($this->cats->emails as $cat => $emaIDs) {
            $this->tots->comCats[$cat] = [];
            $this->tots->catsDur[$cat] = 0;
        }
        if (sizeof($this->cats->coms) > 0) {
            foreach ($this->cats->coms as $comID => $cats) {
                foreach ($this->cats->emails as $cat => $emaIDs) {
                    if (sizeof($cats[$cat]) > 0) {
                        sort($cats[$cat]);
                        if ($cat == 'Initial Reviews'
                            || $this->isFirstTime($comID, $cat, $cats[$cat][0])) {
                            if (!in_array($comID, $this->tots->comCats[$cat])) {
                                $this->tots->comCats[$cat][] = $comID;
                            }
                        }
                    }
                }
            }
        }
    }

    private function isFirstTime($comID, $cat, $time)
    {
        $chk = OPzComplaintReviews::where('com_rev_complaint', $comID)
            ->whereIn('com_rev_status', $this->cats->status[$cat])
            ->where('created_at', '<', date("Y-m-d H:i:s", $time))
            ->first();
        if ($chk && isset($chk->created_at)) {
            return false;
        }
        $chk = SLEmailed::where('emailed_rec_id', $comID)
            ->whereIn('emailed_email_id', $this->cats->emails[$cat])
            ->whereIn('emailed_tree', [1, 42])
            ->where('created_at', '<', date("Y-m-d H:i:s", $time))
            ->first();
        if ($chk && isset($chk->created_at)) {
            return false;
        }
        return true;
    }



    /**
     * Load a user's grouped session logs from department editing.
     *
     * @param  string $url
     * @param  int $time
     * @return boolean
     */
    public function loadAllSessGroupedLogsDept($url, $time)
    {
        $match = '/dash/u/volunteers-research-departments/form?cid=';
        if (strpos($url, $match) !== false) {
            $deptID = intVal(trim(str_replace($match, '', $url)));
            $this->addDept($deptID, $time);
            return true;
        }
        $match = 'Sess Tree 36, Core ';
        if (strpos($url, $match) !== false) {
            $deptID = intVal(trim(str_replace($match, '', $url)));
            $this->addDept(intVal($deptID), $time);
            return true;
        }
        $match = 'Survey Tree 36, Core ';
        if (strpos($url, $match) !== false) {
            $deptID = trim(str_replace($match, '', $url));
            $commaPos = strpos($deptID, ',');
            if ($commaPos !== false) {
                $deptID = trim(substr($deptID, 0, $commaPos+1));
            }
            $this->addDept(intVal($deptID), $time);
            return true;
        }
        if (strpos($url, '/dept/') === 0) {
            $spacePos = strpos($url, ' ');
            if ($spacePos !== false) {
                $deptSlug = substr($url, 6, $spacePos-6);
                $deptChk = OPDepartments::where('dept_slug', $deptSlug)
                    ->first();
                if ($deptChk && isset($deptChk->dept_id)) {
                    $deptID = intVal($deptChk->dept_id);
                    $this->addDept($deptID, $time);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Load a user's grouped session logs from complaint interactions.
     *
     * @param  string $url
     * @param  int $time
     * @return boolean
     */
    public function loadAllSessGroupedLogsCom($url, $time)
    {
        $match = '/dash/complaint-toolbox-email-form/readi-';
        if (strpos($url, $match) !== false) {
            $comID = str_replace($match, '', $url);
            $spacePos = strpos($comID, ' ');
            if ($spacePos !== false) {
                $comID = intVal(trim(substr($comID, 0, $spacePos)));
                $this->addCom($comID, $time);
            }
            return true;
        }
        $match = '/dash/complaint-toolbox-status-forms/readi-';
        if (strpos($url, $match) !== false) {
            $comID = str_replace($match, '', $url);
            $spacePos = strpos($comID, ' ');
            if ($spacePos !== false) {
                $comID = intVal(trim(substr($comID, 0, $spacePos)));
                $this->addCom($comID, $time);
            }
            return true;
        }
        $match = '/dash/complaint-toolbox-edit-details/readi-';
        if (strpos($url, $match) !== false) {
            $comID = str_replace($match, '', $url);
            $spacePos = strpos($comID, '?');
            if ($spacePos !== false) {
                $comID = intVal(trim(substr($comID, 0, $spacePos)));
                $this->addCom($comID, $time);
            }
            return true;
        }
        return false;
    }



}

class OpenPoliceSessUserStats
{
    public $comIDs      = [];
    public $comCats     = [];

    public $cntOnline   = 0;
    public $cntCallDept = 0;
    public $cntCallIA   = 0;
    public $deptDur     = 0;
    public $origDur     = 0;
    public $orig        = [];

}

class OpenPoliceSessComplaintStatCats
{
    public $coms   = [];
    public $emails = [];
    public $status = [];

    public function __construct()
    {
        $this->emails = [
            "Initial Reviews"             => [ 25, 26, 29, 30 ],
            "Attorney Instructions"       => [ 22, 32, 18, 38, 39, 46, 44 ],
            "File & Publish Instructions" => [ 9, 47, 40, 48 ],
            "Attorney? Followup"          => [ 35, 42 ],
            "Filed Yet? Followup"         => [ 33, 34 ],
            "Filed & Published"           => [ 12, 7, 45, 24, 43 ],
            "Investigated Yet? Followup"  => [ 16 ]
        ];

        $this->status = [
            "Initial Reviews" => [
                'Police Complaint',
                'Not About Police',
                'Not Sure',
                'Incomplete',
                'Hold: Not Sure',
                'Needs More Work',
                'Wants Attorney',
                'Pending Attorney',
                'Pending Attorney: Needed',
                'Pending Attorney: Hook-Up',
                'Pending Attorney: Defense Needed',
                'Pending Attorney: Civil Rights Needed/Wanted',
                'OK to Submit to Oversight'
            ],
            "Attorney Instructions"       => [],
            "File & Publish Instructions" => [],
            "Attorney? Followup"          => [],
            "Filed Yet? Followup"         => [],
            "Filed & Published"           => [ 'Submitted to Oversight', 'Received by Oversight' ],
            "Investigated Yet? Followup"  => [ 'Investigated (Closed)' ]
        ];
    }

    public function loadReview($review)
    {
        $comID = $review->com_rev_complaint;
        if (!isset($this->coms[$comID])) {
            $this->coms[$comID] = [];
            foreach ($this->status as $cat => $stat) {
                $this->coms[$comID][$cat] = [];
            }
        }
        foreach ($this->status as $cat => $statuses) {
            if (in_array($review->com_rev_status, $statuses)
                && !in_array(strtotime($review->com_rev_date), $this->coms[$comID][$cat])) {
                $this->coms[$comID][$cat][] = strtotime($review->com_rev_date);
            }
        }
    }

    public function loadEmail($email)
    {
        $comID = $email->emailed_rec_id;
        if (!isset($this->coms[$comID])) {
            $this->coms[$comID] = [];
            foreach ($this->emails as $cat => $emaIDs) {
                $this->coms[$comID][$cat] = [];
            }
        }
        foreach ($this->emails as $cat => $emaIDs) {
            if (in_array($email->emailed_email_id, $emaIDs)
                && !in_array(strtotime($email->created_at), $this->coms[$comID][$cat])) {
                $this->coms[$comID][$cat][] = strtotime($email->created_at);
            }
        }
    }

}


