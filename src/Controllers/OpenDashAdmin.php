<?php
/**
  * OpenDashAdmin is a side-class which handles calculations and printing
  * of graphs and reports for the main staff dashboard.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use DB;
use App\Models\User;
use App\Models\OPComplaints;
use App\Models\OPzComplaintReviews;
use App\Models\OPLinksComplaintOversight;
use App\Models\OPDepartments;
use App\Models\OPzEditDepartments;
use App\Models\OPzEditOversight;
use App\Models\OPzVolunStatDays;
use App\Models\OPTesterBeta;
use SurvLoop\Controllers\Stats\SurvTrends;

// temp...
use App\Models\OPEventSequence;
use App\Models\OPStops;
use App\Models\OPSearches;
use App\Models\OPArrests;
use App\Models\OPForce;

class OpenDashAdmin
{
    public $v = [];
    
    public function printDashTopLevStats()
    {
        $this->transitionData();
        $stats = [
            "betas"      => 0,
            "incomplete" => 0,
            "complete"   => 0,
            "processed"  => 0,
            "submitted"  => 0
        ];
        $stats["betas"] = DB::table('op_tester_beta')
            ->whereNotNull('beta_invited')
            ->distinct('beta_email')
            ->count();
        $chk = OPComplaints::select('com_id', 'com_public_id', 'com_status', 'com_record_submitted')
            ->where('com_status', '>', 0)
            ->whereNotNull('com_summary')
            ->where('com_summary', 'NOT LIKE', '')
            ->whereIn('com_type', [
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Police Complaint'),
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Unreviewed'),
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Not Sure')
            ])
            ->get();
        if ($chk->isNotEmpty()) {
            $defSet = 'Complaint Status';
            foreach ($chk as $com) {
                switch (intVal($com->com_status)) {
                    case $GLOBALS["SL"]->def->getID($defSet, 'Incomplete'):
                        $stats["incomplete"]++;
                        break;
                    case $GLOBALS["SL"]->def->getID($defSet, 'New'):
                    case $GLOBALS["SL"]->def->getID($defSet, 'Hold'):
                    case $GLOBALS["SL"]->def->getID($defSet, 'Reviewed'):
                    case $GLOBALS["SL"]->def->getID($defSet, 'Needs More Work'):
                    case $GLOBALS["SL"]->def->getID($defSet, 'Pending Attorney'):
                        $stats["complete"]++;
                        break;
                    case $GLOBALS["SL"]->def->getID($defSet, 'Attorney\'d'):
                    case $GLOBALS["SL"]->def->getID($defSet, 'OK to Submit to Oversight'):
                        $stats["processed"]++;
                        $stats["complete"]++;
                        break;
                    case $GLOBALS["SL"]->def->getID($defSet, 'Submitted to Oversight'):
                    case $GLOBALS["SL"]->def->getID($defSet, 'Received by Oversight'):
                    case $GLOBALS["SL"]->def->getID($defSet, 'Declined To Investigate (Closed)'):
                    case $GLOBALS["SL"]->def->getID($defSet, 'Investigated (Closed)'):
                    case $GLOBALS["SL"]->def->getID($defSet, 'Closed'):
                        $stats["submitted"]++;
                        $stats["processed"]++;
                        $stats["complete"]++;
                        break;
                }
            }
        }
        $statsWeek = [
            "activeU"   => [],
            "contactsU" => []
        ];
        $chk = OPComplaints::select('com_id', 'com_user_id', 
                'com_status', 'com_record_submitted')
            ->where('com_status', '>', 0)
            ->whereNotNull('com_summary')
            ->where('com_summary', 'NOT LIKE', '')
            ->whereIn('com_type', [
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Police Complaint'),
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Unreviewed'),
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Not Sure')
            ])
            ->where('created_at', '>', $GLOBALS["SL"]->pastDateTimeStr(7))
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $com) {
                if (intVal($com->com_user_id) > 0) {
                    $statsWeek["activeU"][] = $com->com_user_id;
                }
            }
        }
        /* $contacts = DB::table('op_z_complaint_reviews')
            ->join('op_z_complaint_reviews', 'op_complaints.com_id', 
                '=', 'op_z_complaint_reviews.ComRevComplaint')
            ->where('op_z_complaint_reviews.com_rev_type', 'LIKE', 'Update')
            ->whereNotNull('op_z_complaint_reviews.com_rev_note')
            ->where('op_z_complaint_reviews.com_rev_note', 'NOT LIKE', 'Update')
            ->where('op_z_complaint_reviews.created_at', '>', $GLOBALS["SL"]->pastDateTimeStr(7))
            ->select('op_complaints.com_user_id', 'op_z_complaint_reviews.*')
            ->get(); */
        $contacts = OPzComplaintReviews::where('com_rev_type', 'LIKE', 'Update')
            ->whereNotNull('com_rev_note')
            ->where('com_rev_note', 'NOT LIKE', 'Update')
            ->where('created_at', '>', $GLOBALS["SL"]->pastDateTimeStr(7))
            ->get();
        if ($contacts->isNotEmpty()) {
            foreach ($contacts as $i => $rec) {
                $com = OPComplaints::find($rec->com_rev_complaint);
                if ($com && isset($com->com_user_id) && intVal($com->com_user_id) > 0) {
                    if (!in_array($com->com_user_id, $statsWeek["activeU"])) {
                        $statsWeek["activeU"][] = $com->com_user_id;
                    }
                    if (!in_array($com->com_user_id, $statsWeek["contactsU"])) {
                        $statsWeek["contactsU"][] = $com->com_user_id;
                    }
                }
            }
        }
        return view(
            'vendor.openpolice.nodes.2345-dash-top-stats', 
            [
                "stats"     => $stats,
                "statsWeek" => $statsWeek
            ]
        )->render();
    }
    
    public function printDashSessGraph()
    {
        $this->v["isDash"] = true;
        $grapher = new SurvTrends('' . rand(1000000, 10000000) . '', '', 28);
        $grapher->addDataLineType('complete', 'Complete', '', '#29B76F', '#29B76F');
        $grapher->addDataLineType('incomplete', 'Incomplete', '', '#EC2327', '#EC2327');
        $grapher->addDataLineType('submitted', 'Submitted to Oversight', '', '#2B3493', '#2B3493');
        $grapher->addDataLineType('received', 'Received by Oversight', '', '#63C6FF', '#63C6FF');
        $grapher->addDataLineType('contacts', 'Staff Emails', '', '#416CBD', '#416CBD');
        $grapher->addDataLineType('notes', 'Staff Notes', '', '#333333', '#333333');
        $grapher->addDataLineType('owners', 'User Followups', '', '#F0AD4E', '#F0AD4E');
        $grapher->addDataLineType('oversights', 'Oversight Followups', '', '#EB9316', '#EB9316');
        $startDate = $grapher->getPastStartDate() . ' 00:00:00';
        $recentAttempts = OPComplaints::whereNotNull('com_summary')
            ->where('com_summary', 'NOT LIKE', '')
            ->whereIn('com_type', [
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Police Complaint'),
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Unreviewed'),
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Not Sure')
            ])
            ->where('com_record_submitted', '>=', $startDate)
            ->select('com_status', 'com_record_submitted')
            ->get();
        if ($recentAttempts->isNotEmpty()) {
            foreach ($recentAttempts as $i => $rec) {
                if ($rec->com_status == $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) {
                    $grapher->addDayTally('incomplete', $rec->com_record_submitted);
                } else {
                    $grapher->addDayTally('complete', $rec->com_record_submitted);
                }
            }
        }
        $recentAttempts = OPLinksComplaintOversight::select('lnk_com_over_submitted')
            ->where('lnk_com_over_submitted', '>=', $startDate)
            ->get();
        if ($recentAttempts->isNotEmpty()) {
            foreach ($recentAttempts as $i => $rec) {
                $grapher->addDayTally('submitted', $rec->lnk_com_over_submitted);
            }
        }
        $recentAttempts = OPLinksComplaintOversight::select('lnk_com_over_received')
            ->where('lnk_com_over_received', '>=', $startDate)
            ->get();
        if ($recentAttempts->isNotEmpty()) {
            foreach ($recentAttempts as $i => $rec) {
                $grapher->addDayTally('received', $rec->lnk_com_over_received);
            }
        }
        $contacts = DB::table('sl_emailed')
            ->where('emailed_tree', 1)
            ->whereNotNull('emailed_body')
            ->whereIn('emailed_tree', [1, 42])
            ->whereNotIn('emailed_email_id', [1, 5, 28])
            ->where('created_at', '>=', $startDate)
            ->distinct('emailed_rec_id')
            ->get();
        if ($contacts->isNotEmpty()) {
            foreach ($contacts as $i => $rec) {
                $grapher->addDayTally('contacts', $rec->created_at);
            }
        }

        $notes = DB::table('op_z_complaint_reviews')
            ->where('com_rev_type', 'LIKE', 'Update')
            ->whereNotNull('com_rev_note')
            ->whereIn('com_rev_note', ['Update', 'First'])
            ->where('created_at', '>=', $startDate)
            ->distinct('com_rev_complaint')
            ->get();
        if ($notes->isNotEmpty()) {
            foreach ($notes as $i => $rec) {
                $grapher->addDayTally('notes', $rec->created_at);
            }
        }
        $notes = DB::table('op_z_complaint_reviews')
            ->where('com_rev_type', 'LIKE', 'Owner')
            ->where('created_at', '>=', $startDate)
            ->distinct('com_rev_complaint')
            ->get();
        if ($notes->isNotEmpty()) {
            foreach ($notes as $i => $rec) {
                $grapher->addDayTally('owners', $rec->created_at);
            }
        }
        $notes = DB::table('op_z_complaint_reviews')
            ->where('com_rev_type', 'LIKE', 'Oversight')
            ->where('created_at', '>=', $startDate)
            ->distinct('com_rev_complaint')
            ->get();
        if ($notes->isNotEmpty()) {
            foreach ($notes as $i => $rec) {
                $grapher->addDayTally('oversights', $rec->created_at);
            }
        }

        return '<div id="dailyGraphWrap">' 
            . $grapher->printDailyGraph(420) . '</div>';
    }
    
    public function printDashPercCompl()
    {
        $this->v["isDash"] = true;
        $GLOBALS["SL"]->x["needsCharts"] = true;
        $GLOBALS["SL"]->pageAJAX .= '$("#1342graph").load("'
            . '/dashboard/surv-1/sessions/graph-durations"); ';
        return '<div id="1342graph" class="w100" style="height: 740px;"></div>'
            . '<div class="p10">&nbsp;</div>'
            . '<div class="pT10"><a href="/dashboard/surv-1/sessions?refresh=1">'
            . 'Full Session Stats Report</a></div>';
    }
    
    public function printDashTopStats()
    {
        $this->v["isDash"] = true;
        $this->v["statRanges"] = [
            [
                'Last 24 Hrs',
                mktime(date("H")-24, date("i"), date("s"), date("n"), date("j"), date("Y"))
            ], [
                'This Week',
                mktime(date("H"), 0, 0, date("n"), date("j")-7, date("Y"))
            ], [
                'All-Time Totals', 
                mktime(0, 0, 0, 1, 1, 1900)
            ]
        ];
        $this->v["statusDefs"] = $GLOBALS["SL"]->def->getSet('Complaint Status');
        $this->v["dashTopStats"] = [];
        foreach ($this->v["statRanges"] as $j => $range) {
            $this->v["dashTopStats"][$j] = [];
            foreach ($this->v["statusDefs"] as $def) {
                $this->v["dashTopStats"][$j][$def->def_id] = 0;
            }
        }
        $chk = OPComplaints::select('com_id', 'com_public_id', 'com_status', 'com_record_submitted')
            ->where('com_status', '>', 0)
            ->whereNotNull('com_summary')
            ->where('com_summary', 'NOT LIKE', '')
            ->whereIn('com_type', [
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Police Complaint'),
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Unreviewed'),
                $GLOBALS["SL"]->def->getID('Complaint Type', 'Not Sure')
            ])
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $i => $complaint) {
                foreach ($this->v["statRanges"] as $j => $range) {
                    if (strtotime($complaint->com_record_submitted) > $range[1]) {
                        $this->v["dashTopStats"][$j][$complaint->com_status]++;
                    }
                }
            }
        }
        $this->v["dashBetaStats"] = [0, 0, 0];
        $chk = DB::table('op_tester_beta')
            ->whereNotNull('beta_invited')
            ->distinct('beta_email')
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $i => $complaint) {
                foreach ($this->v["statRanges"] as $j => $range) {
                    if (strtotime($complaint->beta_invited) > $range[1]) {
                        $this->v["dashBetaStats"][$j]++;
                    }
                }
            }
        }
        return view(
            'vendor.openpolice.nodes.1361-dash-top-stats', 
            $this->v
        )->render();
    }
    
    public function volunDeptsRecent()
    {
        $this->v["isDash"] = true;
        $this->v["statTots"] = [];
        $statRanges = [
            [
                'Last 24 Hours', 
                date("Y-m-d H:i:s", mktime(date("H")-24, date("i"), date("s"), 
                    date("n"), date("j"), date("Y")))
            ], [
                'This Week', 
                date("Y-m-d H:i:s", mktime(date("H"), 0, 0, 
                    date("n"), date("j")-7, date("Y")))
            ], [
                'All-Time Totals', 
                date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, 1000))
            ]
        ];
        foreach ($statRanges as $i => $stat) {
            $this->v["statTots"][$i] = [ $stat[0] ];
            $this->v["statTots"][$i][] = OPzEditDepartments::distinct('zed_dept_user_id')
                ->where('zed_dept_dept_verified', '>', $stat[1])
                ->count();
            $this->v["statTots"][$i][] = OPzEditDepartments::select('zed_dept_id')
                ->where('zed_dept_dept_verified', '>', $stat[1])
                ->count();
            $overType = " `zed_over_over_type` LIKE '303'";
            $overQry = ((strpos($stat[1], "WHERE") === false) ? " WHERE" : " AND") . $overType;
            $res = DB::select(
                DB::raw("SELECT SUM(`zed_over_online_research`) as `tot` FROM `op_z_edit_oversight` 
                    WHERE `zed_over_over_verified` > '" . $stat[1] . "' AND" . $overType)
            );
            $this->v["statTots"][$i][] = $res[0]->tot;
            $res = DB::select(
                DB::raw("SELECT SUM(`zed_over_made_dept_call`) as `tot` FROM `op_z_edit_oversight` 
                    WHERE `zed_over_over_verified` > '" . $stat[1] . "' AND" . $overType)
            );
            $this->v["statTots"][$i][] = $res[0]->tot;
            $res = DB::select(
                DB::raw("SELECT SUM(`zed_over_made_ia_call`) as `tot` FROM `op_z_edit_oversight` 
                    WHERE `zed_over_over_verified` > '" . $stat[1] . "' AND" . $overType)
            );
            $this->v["statTots"][$i][] = $res[0]->tot;
            $res = DB::select(
                DB::raw("SELECT DISTINCT `zed_dept_dept_id` FROM `op_z_edit_departments` 
                    WHERE `zed_dept_dept_verified` > '" . $stat[1] . "'")
            );
            $this->v["statTots"][$i][] = (($res) ? sizeof($res) : 0);
        }
        return true;
    }
        
    public function volunDepts()
    {
        $this->volunDeptsRecent();
        $deptEdits = [];
        $recentEdits = OPzEditDepartments::take(100)
            ->orderBy('zed_dept_dept_verified', 'desc')
            ->get();
        if ($recentEdits->isNotEmpty()) {
            foreach ($recentEdits as $i => $edit) {
                $iaEdit  = OPzEditOversight::where('zed_over_zed_dept_id', $edit->zed_dept_id)
                    ->where('zed_over_over_type', 303)
                    ->first();
                $civEdit = OPzEditOversight::where('zed_over_zed_dept_id', $edit->zed_dept_id)
                    ->where('zed_over_over_type', 302)
                    ->first();
                $userObj = User::find($edit->zed_dept_user_id);
                $deptEdits[] = [
                    ($userObj) ? $userObj->printUsername() : '', 
                    $edit, 
                    $iaEdit, 
                    $civEdit
                ];
            }
        }
        //echo '<pre>'; print_r($deptEdits); echo '</pre>';
        $this->v["recentEdits"] = '';
        foreach ($deptEdits as $deptEdit) {
            $deptType = $GLOBALS["SL"]->def->getVal('Department Types', $deptEdit[1]->zed_dept_type);
            $this->v["recentEdits"] .= view(
                'vendor.openpolice.volun.admPrintDeptEdit', 
                [
                    "user"     => $deptEdit[0], 
                    "deptRow"  => OPDepartments::find($deptEdit[1]->zed_dept_dept_id), 
                    "deptEdit" => $deptEdit[1], 
                    "deptType" => $deptType,
                    "iaEdit"   => $deptEdit[2], 
                    "civEdit"  => $deptEdit[3]
                ]
            )->render();
        }
        $this->v["volunDataGraph"] = $this->volunStatsDailyGraph();
        return view(
            'vendor.openpolice.nodes.1351-admin-volun-edit-history', 
            $this->v
        )->render();
    }
    
    public function volunStatsDailyGraph()
    {
        $this->v["isDash"] = true;
        if (!isset($this->v["statTots"])) {
            $this->volunDeptsRecent();
        }
        $this->recalcVolunStats();
        $grapher = new SurvTrends('1349', 'volun_stat_date', 28);
        $grapher->addDataLineType('depts', 'Unique Depts', 'volun_stat_depts_unique', '#2b3493', '#2b3493');
        $grapher->addDataLineType('users', 'Unique Users', 'volun_stat_users_unique', '#63c6ff', '#63c6ff');
        $grapher->addDataLineType('edits', 'Total Edits', 'volun_stat_total_edits', '#c3ffe1', '#c3ffe1');
        $grapher->addDataLineType('calls', 'Total Calls', 'volun_stat_calls_tot', '#29B76F', '#29B76F');
        $grapher->addDataLineType('signup', 'Signups', 'volun_stat_signups', '#ffd2c9', '#ffd2c9');
        $grapher->processRawDataResults(
            OPzVolunStatDays::where('volun_stat_date', '>=', $grapher->getPastStartDate())
                ->orderBy('volun_stat_date', 'asc')
                ->get()
        );
        return $grapher->printDailyGraph(350);
    }
    
    public function volunStatsTable()
    {
        return view('vendor.openpolice.nodes.2100-volun-table', $this->v)->render();
    }
    
    public function volunStatsInitDay()
    {
        return [
            'signups'         => 0, 
            'logins'          => 0, 
            'usersUnique'     => 0, 
            'deptsUnique'     => 0, 
            'onlineResearch'  => 0, 
            'callsDept'       => 0, 
            'callsIA'         => 0, 
            'callsTot'        => 0, 
            'totalEdits'      => 0,
            'onlineResearchV' => 0, 
            'callsDeptV'      => 0, 
            'callsIAV'        => 0, 
            'callsTotV'       => 0, 
            'totalEditsV'     => 0,
            'users'           => [], 
            'depts'           => []
        ];
    }
    
    public function recalcVolunStats()
    {
        $past = 35;
        $startDate = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j")-$past, date("Y")));
        $days = [];
        for ($i = 0; $i < $past; $i++) {
            $day = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j")-$i, date("Y")));
            $days[$day] = $this->volunStatsInitDay();
        }
        
        $volunteers = [];
        $users = DB::table('users')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('sl_users_roles')
                    ->where('sl_users_roles.role_user_rid', 17) // 'volunteer'
                    ->whereRaw('sl_users_roles.role_user_uid = users.id');
            })
            ->get();
        if ($users->isNotEmpty()) {
            foreach ($users as $i => $u) {
                $volunteers[] = $u->id;
                if (strtotime($u->created_at) > strtotime($startDate)) {
                    $dataInd = date("Y-m-d", strtotime($u->created_at));
                    if (isset($days[$dataInd])) {
                        $days[$dataInd]["signups"]++;
                    }
                }
            }
        }
        
        $edits  = OPzEditOversight::where('op_z_edit_oversight.zed_over_over_type', 303)
            ->join('op_z_edit_departments', 'op_z_edit_departments.zed_dept_id', '=', 'op_z_edit_oversight.zed_over_zed_dept_id')
            ->where('op_z_edit_oversight.zed_over_over_verified', 
                '>', date("Y-m-d", strtotime($startDate)).' 00:00:00')
            ->select('op_z_edit_oversight.*', 'op_z_edit_departments.zed_dept_user_id')
            ->get();
        if ($edits->isNotEmpty()) {
            foreach ($edits as $i => $e) {
                $day = date("Y-m-d", strtotime($e->zed_over_over_verified));
                if (!isset($days[$day])) {
                    $days[$day] = $this->volunStatsInitDay();
                }
                $days[$day]["totalEdits"]++;
                $days[$day]["onlineResearch"] += intVal($e->zed_over_online_research);
                $days[$day]["callsDept"]      += intVal($e->zed_over_made_dept_call);
                $days[$day]["callsIA"]        += intVal($e->zed_over_made_ia_call);
                $days[$day]["callsTot"]       += intVal($e->zed_over_made_dept_call)
                    +intVal($e->zed_over_made_ia_call);
                if (in_array($e->zed_dept_user_id, $volunteers)) {
                    $days[$day]["totalEditsV"]++;
                    $days[$day]["onlineResearchV"] += intVal($e->zed_over_online_research);
                    $days[$day]["callsDeptV"]      += intVal($e->zed_over_made_dept_call);
                    $days[$day]["callsIAV"]        += intVal($e->zed_over_made_ia_call);
                    $days[$day]["callsTotV"]       += intVal($e->zed_over_made_dept_call)
                        +intVal($e->zed_over_made_ia_call);
                }
                if (!in_array($e->zed_dept_user_id, $days[$day]["users"])) {
                    $days[$day]["users"][] = $e->zed_dept_user_id;
                }
                if (!in_array($e->zed_over_dept_id, $days[$day]["depts"])) {
                    $days[$day]["depts"][] = $e->zed_over_dept_id;
                }
            }
        }
        
        OPzVolunStatDays::where('volun_stat_date', '>=', $startDate)
            ->delete();
        foreach ($days as $day => $stats) {
            $newDay = new OPzVolunStatDays;
            $newDay->volun_stat_date              = $day;
            $newDay->volun_stat_signups           = $stats["signups"];
            $newDay->volun_stat_logins            = $stats["logins"];
            $newDay->volun_stat_users_unique      = sizeof($stats["users"]);
            $newDay->volun_stat_depts_unique      = sizeof($stats["depts"]);
            $newDay->volun_stat_online_research   = $stats["onlineResearch"];
            $newDay->volun_stat_calls_dept        = $stats["callsDept"];
            $newDay->volun_stat_calls_ia          = $stats["callsIA"];
            $newDay->volun_stat_calls_tot         = $stats["callsTot"];
            $newDay->volun_stat_total_edits       = $stats["totalEdits"];
            $newDay->volun_stat_online_research_v = $stats["onlineResearchV"];
            $newDay->volun_stat_calls_dept_v      = $stats["callsDeptV"];
            $newDay->volun_stat_calls_ia_v        = $stats["callsIAV"];
            $newDay->volun_stat_calls_tot_v       = $stats["callsTotV"];
            $newDay->volun_stat_total_edits_v     = $stats["totalEditsV"];
            $newDay->save();
        }
        
        return true;
    }


    // For March 2020 transition. Should be deleted soon
    protected function transitionData()
    {
        if ($GLOBALS["SL"]->REQ->has('trans')) {
            ini_set('max_execution_time', '300');
            $transition = intVal($GLOBALS["SL"]->REQ->get('trans'));
            if ($transition == 1) {
                $defPub = $GLOBALS["SL"]->def->getID(
                    'Privacy Types', 
                    'Submit Publicly'
                );
                $defMid = $GLOBALS["SL"]->def->getID(
                    'Privacy Types', 
                    'Names Visible to Police but not Public'
                );
                $chk = OPComplaints::whereNotNull('com_privacy')
                    ->whereNull('com_publish_user_name')
                    ->select('com_id', 'com_privacy', 'com_anon', 
                        'com_publish_user_name',
                        'com_publish_officer_name')
                    ->get();
                if ($chk->isNotEmpty()) {
                    foreach ($chk as $com) {
                        if ($com->com_privacy == $defPub) {
                            $com->com_anon                 = 0;
                            $com->com_publish_user_name    = 1;
                            $com->com_publish_officer_name = 1;
                        } elseif ($com->com_privacy == $defMid) {
                            $com->com_anon                 = 0;
                            $com->com_publish_user_name    = 0;
                            $com->com_publish_officer_name = 0;
                        } else {
                            $com->com_anon                 = 1;
                            $com->com_publish_user_name    = 0;
                            $com->com_publish_officer_name = 0;
                        }
                        $com->save();
                    }
                }
            } elseif ($transition == 2) {
                $chk = DB::table('op_stops')
                    ->join('op_event_sequence', 'op_stops.stop_event_sequence_id', 
                        '=', 'op_event_sequence.eve_id')
                    ->whereNull('op_stops.stop_com_id')
                    ->orWhere('op_stops.stop_com_id', '<=', 0)
                    ->select('op_stops.*', 'op_event_sequence.eve_complaint_id')
                    ->get();
                if ($chk->isNotEmpty()) {
                    foreach ($chk as $stop) {
                        DB::table('op_stops')
                            ->where('stop_id', $stop->stop_id)
                            ->update([ 'stop_com_id' => $stop->eve_complaint_id ]);
                    }
                }
            } elseif ($transition == 3) {
                $chk = DB::table('op_searches')
                    ->join('op_event_sequence', 'op_searches.srch_event_sequence_id', 
                        '=', 'op_event_sequence.eve_id')
                    ->whereNull('op_searches.srch_com_id')
                    ->orWhere('op_searches.srch_com_id', '<=', 0)
                    ->select('op_searches.*', 'op_event_sequence.eve_complaint_id')
                    ->get();
                if ($chk->isNotEmpty()) {
                    foreach ($chk as $search) {
                        DB::table('op_searches')
                            ->where('srch_id', $search->srch_id)
                            ->update([ 'srch_com_id' => $search->eve_complaint_id ]);
                    }
                }
            } elseif ($transition == 4) {
                $chk = DB::table('op_arrests')
                    ->join('op_event_sequence', 'op_arrests.arst_event_sequence_id', 
                        '=', 'op_event_sequence.eve_id')
                    ->whereNull('op_arrests.arst_com_id')
                    ->orWhere('op_arrests.arst_com_id', '<=', 0)
                    ->select('op_arrests.*', 'op_event_sequence.eve_complaint_id')
                    ->get();
                if ($chk->isNotEmpty()) {
                    foreach ($chk as $arrest) {
                        DB::table('op_arrests')
                            ->where('arst_id', $arrest->arst_id)
                            ->update([ 'arst_com_id' => $arrest->eve_complaint_id ]);
                    }
                }
            } elseif ($transition == 5) {
                $chk = DB::table('op_force')
                    ->join('op_event_sequence', 'op_force.for_event_sequence_id', 
                        '=', 'op_event_sequence.eve_id')
                    ->whereNull('op_force.for_com_id')
                    ->orWhere('op_force.for_com_id', '<=', 0)
                    ->select('op_force.*', 'op_event_sequence.eve_complaint_id')
                    ->get();
                if ($chk->isNotEmpty()) {
                    foreach ($chk as $force) {
                        DB::table('op_force')
                            ->where('for_id', $force->for_id)
                            ->update([ 'for_com_id' => $force->eve_complaint_id ]);
                    }
                }
            }
            $transition++;
            echo '<a href="?trans=' . $transition . '">Next</a>';
            exit;
        }
        return true;
    }
}