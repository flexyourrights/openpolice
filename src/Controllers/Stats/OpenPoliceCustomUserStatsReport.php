<?php
/**
  * OpenPoliceCustomUserStatsReport generates a custom
  * report on the OpenPolice.org user profiles.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.3.2
  */
namespace FlexYourRights\OpenPolice\Controllers\Stats;

use App\Models\OPLinksComplaintOversight;
use App\Models\OPzComplaintReviews;
use FlexYourRights\OpenPolice\Controllers\Stats\OpenPoliceSessGroupStats;
use FlexYourRights\OpenPolice\Controllers\Stats\OpenPoliceUserStatCalcs;

class OpenPoliceCustomUserStatsReport
{
    protected $v      = [];
    protected $userID = 0;
    protected $month4 = 0;
    protected $week6  = 0;
    protected $week2  = 0;

    /**
     * Set the user for analysis.
     *
     * @return void
     */
    public function __construct($userID = 0)
    {
        $this->userID = $userID;
        $this->v["tblInnerDepts"] = '';
        $this->v["tblInnerComs"] = '';
        $this->v["groupStats"] = [];
        $this->v["calcs"] = [
            "2W"  => new OpenPoliceUserStatCalcs("2 Weeks"),
            "6W"  => new OpenPoliceUserStatCalcs("6 Weeks"),
            "4M"  => new OpenPoliceUserStatCalcs("16 Weeks")
        ];
        $this->month4 = mktime(date("H"), date("i"), date("s"), date("n")-4, date("j"), date("Y"));
        $this->week6 = mktime(date("H"), date("i"), date("s"), date("n"), date("j")-42, date("Y"));
        $this->week2 = mktime(date("H"), date("i"), date("s"), date("n"), date("j")-14, date("Y"));
    }

    /**
     * Add department-specific stats to the user's profile.
     *
     * @return string
     */
    public function printProfileStatsReport($logs)
    {
        if (!$GLOBALS["SL"]->isStaffOrAdmin()) {
            return '';
        }
        $this->v["logs"] = $logs;
        $url = 'printProfileStatsCustom?user=' . $this->userID;
        if ($GLOBALS["SL"]->REQ->has('refresh')) {
            $GLOBALS["SL"]->forgetAllCachesOfType('stats');
        }
        $ret = $GLOBALS["SL"]->chkCache($url, 'stats', 0);
        if (trim($ret) == '') {
            set_time_limit(300);
            $ret = $this->printProfileStatsDepts();
            $GLOBALS["SL"]->putCache($url, $ret, 'stats', 0);
        }
        return $ret;
    }

    /**
     * Add department-specific stats to the user's profile.
     *
     * @return string
     */
    private function printProfileStatsDepts()
    {
        if (isset($this->v["logs"]->groups)
            && sizeof($this->v["logs"]->groups) > 0) {
            foreach ($this->v["logs"]->groups as $g => $group) {
                if (sizeof($group->logInds) > 0 && $group->timeStart >= $this->month4) {
                    $this->v["groupStats"][$g] = $this->loadAllSessionsGroupedStats($group);
                    $totDur = $this->addSessionsGroupedStats($group, $g);
                    $this->v["tblInnerDepts"] .= view(
                        'vendor.openpolice.admin.inc-user-stats-dept-row',
                        [
                            "group"      => $group,
                            "groupStats" => $this->v["groupStats"][$g],
                            "totDur"     => $totDur
                        ]
                    )->render();
                    $this->v["tblInnerComs"] .= view(
                        'vendor.openpolice.admin.inc-user-stats-com-row',
                        [
                            "group"      => $group,
                            "groupStats" => $this->v["groupStats"][$g],
                            "totDur"     => $totDur
                        ]
                    )->render();
                }
            }
            foreach ($this->v["calcs"] as $time => $calcs) {
                $this->checkCompletions($time);
                $this->mergeComEdits($time);
            }
        }
        return view('vendor.openpolice.admin.inc-user-stats', $this->v)->render();
    }
    /**
     * Calculate the duration of this session, in hours,
     * and add the totals to larger time periods.
     *
     * @param  RockHopSoft\Survloop\Controllers\Admin\ActivityLogGroup $group
     * @param  int $g
     * @return int
     */
    private function addSessionsGroupedStats($group, $g)
    {
        $totDur = (($group->timeEnd-$group->timeStart)/(60*60));
        $this->addSessTotsStats('4M', $g, $totDur);
        if ($group->timeStart >= $this->week6) {
            $this->addSessTotsStats('6W', $g, $totDur);
            if ($group->timeStart >= $this->week2) {
                $this->addSessTotsStats('2W', $g, $totDur);
            }
        }
        return $totDur;
    }

    /**
     * Load a user's grouped session logs.
     *
     * @param  RockHopSoft\Survloop\Controllers\Admin\ActivityLogGroup $group
     * @return OpenPoliceSessGroupStats
     */
    private function loadAllSessionsGroupedStats($group)
    {
        $groupStats = new OpenPoliceSessGroupStats($this->userID);
        foreach ($group->logInds as $i => $ind) {
            if (isset($this->v["logs"]->logs[$ind])) {
                $url = $this->v["logs"]->logs[$ind]->url;
                $time = $this->v["logs"]->logs[$ind]->time;
                $found = $groupStats->loadAllSessGroupedLogsDept($url, $time);
                if (!$found) {
                    $groupStats->loadAllSessGroupedLogsCom($url, $time);
                }
            }
        }
        $groupStats->loadAllDepts($group->timeStart, $group->timeEnd);
        $groupStats->loadAllComplaints($group->timeStart, $group->timeEnd);
        return $groupStats;
    }

    /**
     * Add session to large block of time.
     *
     * @param  string $time
     * @param  int $g
     * @param  int $sessTotDur
     * @return void
     */
    private function addSessTotsStats($time, $g, $sessTotDur)
    {
        $this->v["calcs"][$time]->sessDur += $sessTotDur;
        foreach ($this->v["groupStats"][$g]->cats->emails as $cat => $emaIDs) {
            if (!isset($this->v["calcs"][$time]->comCats[$cat])) {
                $this->v["calcs"][$time]->comCats[$cat] = [];
            }
            if (!isset($this->v["calcs"][$time]->catsDur[$cat])) {
                $this->v["calcs"][$time]->catsDur[$cat] = 0;
            }
        }
        if (sizeof($this->v["groupStats"][$g]->deptIDs) > 0) {
            foreach ($this->v["groupStats"][$g]->deptIDs as $ind => $deptID) {
                if (!in_array($deptID, $this->v["calcs"][$time]->deptIDs)) {
                    $this->v["calcs"][$time]->deptIDs[] = $deptID;
                }
            }
            $this->v["calcs"][$time]->deptDur     += $this->v["groupStats"][$g]->tots->deptDur;
            $this->v["calcs"][$time]->origDur     += $this->v["groupStats"][$g]->tots->origDur;
            $this->v["calcs"][$time]->cntOnline   += $this->v["groupStats"][$g]->tots->cntOnline;
            $this->v["calcs"][$time]->cntCallDept += $this->v["groupStats"][$g]->tots->cntCallDept;
            $this->v["calcs"][$time]->cntCallIA   += $this->v["groupStats"][$g]->tots->cntCallIA;
            if (sizeof($this->v["groupStats"][$g]->tots->orig) > 0) {
                foreach ($this->v["groupStats"][$g]->tots->orig as $orig) {
                    if (!in_array($orig, $this->v["calcs"][$time]->orig)) {
                        $this->v["calcs"][$time]->orig[] = $orig;
                    }
                }
            }
        }
        $this->addSessComplaintStats($time, $g);
    }

    /**
     * Add session to large block of time.
     *
     * @param  string $time
     * @param  int $g
     * @return void
     */
    private function addSessComplaintStats($time, $g)
    {
        if (sizeof($this->v["groupStats"][$g]->comIDs) > 0) {
            foreach ($this->v["groupStats"][$g]->comIDs as $cID) {
                if (!in_array($cID, $this->v["calcs"][$time]->comIDs)) {
                    $this->v["calcs"][$time]->comIDs[] = $cID;
                }
            }
            foreach ($this->v["groupStats"][$g]->cats->emails as $cat => $emaIDs) {
                if (sizeof($this->v["groupStats"][$g]->tots->comCats[$cat]) > 0) {
                    foreach ($this->v["groupStats"][$g]->tots->comCats[$cat] as $comID) {
                        if (!in_array($comID, $this->v["calcs"][$time]->comCats[$cat])) {
                            $this->v["calcs"][$time]->comCats[$cat][] = $comID;
                        }
                    }
                }
            }

        }
    }

    /**
     * Add session to large block of time.
     *
     * @param  string $time
     * @return void
     */
    private function checkCompletions($time)
    {
        $date = date("Y-m-d", $this->month4);
        if ($time == "6W") {
            $date = date("Y-m-d", $this->week6);
        } elseif ($time == "2W") {
            $date = date("Y-m-d", $this->week2);
        }
        foreach ($this->v["calcs"][$time]->comCats as $cat => $comIDs) {
            if ($cat != 'Filed & Published') {
                $keep = [];
                foreach ($comIDs as $comID) {
                    $chk = OPLinksComplaintOversight::where('lnk_com_over_complaint_id', $comID)
                        ->where('lnk_com_over_submitted', '>=', $date . ' 00:00:00')
                        ->get();
                    if ($chk->isNotEmpty()) {
                        $this->v["calcs"][$time]->comCats['Filed & Published'][] = $comID;
                    } else {
                        $keep[] = $comID;
                    }
                }
                $this->v["calcs"][$time]->comCats[$cat] = $keep;
            }
        }
    }

    /**
     * Add session to large block of time.
     *
     * @param  string $time
     * @return void
     */
    private function mergeComEdits($time)
    {
        $merge = [
            'Initial Reviews' => [
                'Attorney Instructions',
                'File & Publish Instructions',
                'Filed & Published'
            ],
            'Attorney Instructions' => [
                'Filed & Published'
            ],
            'Attorney? Followup' => [
                'Filed & Published'
            ],
            'Filed Yet? Followup' => [
                'Filed & Published'
            ]
        ];
        foreach ($merge as $child => $adults) {
            if (isset($this->v["calcs"][$time]->comCats[$child])
                && sizeof($this->v["calcs"][$time]->comCats[$child]) > 0) {
                $keep = [];
                foreach ($this->v["calcs"][$time]->comCats[$child] as $comID) {
                    $found = false;
                    foreach ($adults as $adult) {
                        if (in_array($comID, $this->v["calcs"][$time]->comCats[$adult])) {
                            $found = true;
                        }
                    }
                    if (!$found) {
                        $keep[] = $comID;
                    }
                }
                $this->v["calcs"][$time]->comCats[$child] = $keep;
            }
        }
        foreach ($this->v["groupStats"][0]->cats->emails as $cat => $emaIDs) {
            if (isset($this->v["calcs"][$time]->comCats[$cat])
                && sizeof($this->v["calcs"][$time]->comCats[$cat]) > 0) {
                foreach ($this->v["calcs"][$time]->comCats[$cat] as $comID) {
                    $this->v["calcs"][$time]->catsDur[$cat] += $this->getComDuration($comID);
                }
            }
        }
    }

    /**
     * Calculate how long this complaint was open during this session.
     *
     * @param  int $comID
     * @return void
     */
    private function getComDuration($comID)
    {
        if (sizeof($this->v["groupStats"]) > 0) {
            foreach ($this->v["groupStats"] as $g => $groupStats) {
                foreach ($groupStats->comIDs as $c => $cID) {
                    if ($cID == $comID) {
                        return $GLOBALS["SL"]->diffTimeArr($groupStats->comTimes[$c]);
                    }
                }
            }
        }
        return 0;
    }


}