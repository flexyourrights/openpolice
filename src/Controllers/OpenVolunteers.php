<?php
/**
  * OpenVolunteers is a mid-level class with functions to
  * manage volunteers and track their research aid.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\OPPartners;
use App\Models\OPDepartments;
use App\Models\OPZeditDepartments;
use OpenPolice\Controllers\VolunteerLeaderboard;
use OpenPolice\Controllers\OpenDevelopment;

class OpenVolunteers extends OpenDevelopment
{
    /**
     * Print the highlight reel of the volunteer leaderboard
     * for a volunteer's admin menu.
     *
     * @return string
     */
    protected function printSidebarLeaderboard() 
    {
        $this->v["leaderboard"] = new VolunteerLeaderboard;
        return view(
            'vendor.openpolice.volun.volun-sidebar-leaderboard', 
            [
                "leaderboard" => $this->v["leaderboard"]
            ]
        )->render();
    }
    
    /**
     * Prepare and pre-load volunteer research data.
     *
     * @return boolean
     */
    public function chkVolunInitLists()
    {
        if (!isset($this->v["chkVolunInitLists"])) {
            $this->v["chkVolunInitLists"] = true;
            $this->loadDeptPriorityRows();
            if ($GLOBALS["SL"]->REQ->has('newDept') 
                && intVal($GLOBALS["SL"]->REQ->get('newDept')) == 1
                && $GLOBALS["SL"]->REQ->has('deptName') 
                && trim($GLOBALS["SL"]->REQ->get('deptName')) != '') {
                $newDept = $this->newDeptAdd(
                    $GLOBALS["SL"]->REQ->get('deptName'), 
                    $GLOBALS["SL"]->REQ->get('DeptAddressState')
                );
                return '<script type="text/javascript"> '
                    . 'setTimeout("window.location=\''
                    . '/dashboard/start-' . $newDept->DeptID 
                    . '/volunteers-research-departments\'", 10); '
                    . '</script>';
            }
            $this->v["viewType"] = (($GLOBALS["SL"]->REQ->has('sort')) 
                ? $GLOBALS["SL"]->REQ->get('sort') : 'recent');
            $this->v["deptRows"] = [];
            $this->v["searchForm"] = $this->deptSearchForm();
            $orderby = [
                [ 'DeptVerified', 'desc' ],
                [ 'DeptName', 'asc' ]
            ];
            switch ($this->v["viewType"]) {
                case 'best': 
                    $orderby[0] = [ 'DeptScoreOpenness', 'desc' ];
                    break;
                case 'name': 
                    $orderby[0] = [ 'DeptName', 'asc' ];
                    break;
                case 'city': 
                    $orderby = [
                        [ 'DeptAddressState', 'asc' ],
                        [ 'DeptAddressCity', 'asc' ]
                    ];
                    break;
            }
            $this->v["state"] = '';
            if ($GLOBALS["SL"]->REQ->has('state')) {
                $this->v["state"] = trim($GLOBALS["SL"]->REQ->get('state'));
            } elseif (isset($this->v["yourContact"]->PrsnAddressState) 
                && trim($this->v["yourContact"]->PrsnAddressState) != '') {
                $this->v["state"] 
                    = trim($this->v["yourContact"]->PrsnAddressState);
            }
            if ($GLOBALS["SL"]->REQ->has('s') 
                && trim($GLOBALS["SL"]->REQ->get('s')) != '') {
                $this->chkRecsPub($GLOBALS["SL"]->REQ, 36);
                $searches = [];
                if ($GLOBALS["SL"]->REQ->has('s') 
                    && trim($GLOBALS["SL"]->REQ->get('s')) != '') {
                    $searches = $GLOBALS["SL"]->parseSearchWords(
                        $GLOBALS["SL"]->REQ->get('s'));
                }
                if (sizeof($searches) > 0) {
                    $rows = null;
                    if ($this->v["state"] == '') {
                        foreach ($searches as $s) {
                            $sP = '%' . $s . '%';
                            $rows = OPDepartments::where('DeptName', 'LIKE', $sP)
                                ->orWhere('DeptEmail', 'LIKE', $sP)
                                ->orWhere('DeptPhoneWork', 'LIKE', $sP)
                                ->orWhere('DeptAddress', 'LIKE', $sP)
                                ->orWhere('DeptAddressCity', 'LIKE', $sP)
                                ->orWhere('DeptAddressZip', 'LIKE', $sP)
                                ->orWhere('DeptAddressCounty', 'LIKE', $sP)
                                ->get();
                            $GLOBALS["SL"]->addSrchResults('depts', $rows, 'DeptID');
                        }
                    } else {
                        foreach ($searches as $s) {
                            $sP = '%' . $s . '%';
                            $rows = OPDepartments::where('DeptAddressState', 
                                    $this->v["state"])
                                ->where(function ($query) use ($s) {
                                    $query->where('DeptName', 'LIKE', $sP)
                                        ->orWhere('DeptEmail', 'LIKE', $sP)
                                        ->orWhere('DeptPhoneWork', 'LIKE', $sP)
                                        ->orWhere('DeptAddress', 'LIKE', $sP)
                                        ->orWhere('DeptAddressCity', 'LIKE', $sP)
                                        ->orWhere('DeptAddressZip', 'LIKE', $sP)
                                        ->orWhere('DeptAddressCounty', 'LIKE', $sP);
                                    })
                                ->get();
                            $GLOBALS["SL"]->addSrchResults('depts', $rows, 'DeptID');
                        }
                    }
                }
                $this->v["deptRows"] = $GLOBALS["SL"]->x["srchRes"]["depts"];
                unset($GLOBALS["SL"]->x["srchRes"]["depts"]);
            } elseif ($this->v["state"] != '') {
                $this->v["deptRows"] = OPDepartments::select('DeptID', 'DeptName', 
                    'DeptScoreOpenness', 'DeptVerified', 
                    'DeptAddressCity', 'DeptAddressState')
                    ->where('DeptAddressState', $this->v["state"])
                    ->orderBy($orderby[0][0], $orderby[0][1])
                    ->get();
            } else {
                $this->v["deptRows"] = OPDepartments::select('DeptID', 'DeptName', 
                    'DeptScoreOpenness', 'DeptVerified', 
                    'DeptAddressCity', 'DeptAddressState')
                    ->orderBy($orderby[0][0], $orderby[0][1])
                    ->get();
            }
            $this->loadRecentDeptEdits();
            $GLOBALS["SL"]->loadStates();
        }
        return true;
    }
    
    /**
     * Print the priority deparments in most need of research by volunteers.
     *
     * @return string
     */
    public function printVolunPriorityList()
    {
        $this->chkVolunInitLists();
        return view(
            'vendor.openpolice.nodes.1755-volun-home-priority-depts', 
            $this->v
        )->render();
    }
    
    /**
     * Print the list of all deparments (within one state) for volunteers.
     *
     * @return string
     */
    public function printVolunAllList()
    {
        if ($GLOBALS["SL"]->REQ->has('refresh')) {
            $this->chkAllOfficerVerifiedRecords();
            $this->v["deptScores"] = new DepartmentScores;
            $this->v["deptScores"]->recalcAllDepts();
            unset($this->v["chkVolunInitLists"]);
        }
        $this->chkVolunInitLists();
        return view(
            'vendor.openpolice.nodes.1211-volun-home-all-depts', 
            $this->v
        )->render();
    }
    
    /**
     * Prepare the list of departments which need most urgent help with research.
     *
     * @return string
     */
    protected function loadDeptPriorityRows()
    {
        $this->v["deptPriorityRows"] = $done = $rejects = [];
        $this->v["yearold"] = mktime(date("H"), date("i"), date("s"), 
            date("m"), date("d"), date("Y")-1);
        $set = 'Complaint Status';
        $statuses = [
            $GLOBALS["SL"]->def->getID($set, 'New'),
            //$GLOBALS["SL"]->def->getID($set, 'Hold'),
            $GLOBALS["SL"]->def->getID($set, 'Reviewed'),
            $GLOBALS["SL"]->def->getID($set, 'Needs More Work'),
            $GLOBALS["SL"]->def->getID($set, 'Pending Attorney'),
            $GLOBALS["SL"]->def->getID($set, 'Attorney\'d'),
            $GLOBALS["SL"]->def->getID($set, 'OK to Submit to Oversight')
        ];
        $set = 'Complaint Type';
        $types = [
            $GLOBALS["SL"]->def->getID($set, 'Unreviewed'),
            $GLOBALS["SL"]->def->getID($set, 'Police Complaint')
        ];
        $chk = DB::table('OP_Departments')
            ->join('OP_LinksComplaintDept', 'OP_Departments.DeptID', 
                '=', 'OP_LinksComplaintDept.LnkComDeptDeptID')
            ->join('OP_Complaints', 
                'OP_LinksComplaintDept.LnkComDeptComplaintID', 
                '=', 'OP_Complaints.ComID')
            ->whereIn('OP_Complaints.ComType', $types)
            ->whereIn('OP_Complaints.ComStatus', $statuses)
            ->where('OP_Complaints.ComSummary', 'NOT LIKE', '')
            ->select('OP_Departments.*')
            ->orderBy('OP_Complaints.created_at', 'asc')
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $dept) {
                if (!in_array($dept->DeptID, $done)
                    && (!isset($dept->DeptScoreOpenness)
                        || !isset($dept->DeptVerified) 
                        || $dept->DeptVerified < date("Y-m-d H:i:s", 
                            $this->v["yearold"]))) {
                    $this->v["deptPriorityRows"][] = $dept;
                    $done[] = $dept->DeptID;
                } else {
                    $rejects[] = $dept;
                }
            }
        }
//echo 'done: <pre>'; print_r($done); echo '</pre>rejects:<pre>'; print_r($rejects); echo '</pre>'; exit;
        return $this->v["deptPriorityRows"];
    }
    
    /**
     * Print the shorm form for volunteers and staff to filter the list
     * of 18,000 police departments.
     *
     * @param  string $state
     * @param  string $deptName
     * @return string
     */
    protected function deptSearchForm($state = '', $deptName = '')
    {
        $GLOBALS["SL"]->loadStates();
        return view('vendor.openpolice.volun.volunEditSearch', [ 
            "deptName"  => $deptName, 
            "stateDrop" => $GLOBALS["SL"]->states->stateDrop($state) 
            ])->render();
    }
    
    /**
     * Print the shorm form for volunteers and staff to filter the list
     * of 18,000 police departments.
     *
     * @return array
     */
    protected function loadRecentDeptEdits()
    {
        if (!isset($GLOBALS["SL"]->x["usernames"])) {
            $GLOBALS["SL"]->x["usernames"] = [];
        }
        $GLOBALS["SL"]->x["recentDeptEdits"] = [];
        $cutoff = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), 
            date("n"), date("j")-7, date("Y")));
        $rows = OPZeditDepartments::where(
            'OP_Zedit_Departments.ZedDeptDeptVerified', '>', $cutoff)
            ->leftJoin('users', 'users.id', 
                '=', 'OP_Zedit_Departments.ZedDeptUserID')
            ->select('OP_Zedit_Departments.ZedDeptDeptID', 'OP_Zedit_Departments.ZedDeptDeptVerified', 
                'users.id', 'users.name')
            ->orderBy('OP_Zedit_Departments.ZedDeptDeptVerified', 'desc')
            ->get();
        if ($rows->isNotEmpty()) {
            foreach ($rows as $row) {
                if (!isset($GLOBALS["SL"]->x["recentDeptEdits"][$row->ZedDeptDeptID])) {
                    $GLOBALS["SL"]->x["recentDeptEdits"][$row->ZedDeptDeptID] = [];
                }
                if (!isset($GLOBALS["SL"]->x["recentDeptEdits"][$row->ZedDeptDeptID][$row->id])) {
                    $GLOBALS["SL"]->x["recentDeptEdits"][$row->ZedDeptDeptID][$row->id] = $row->ZedDeptDeptVerified;
                }
                if (!isset($GLOBALS["SL"]->x["usernames"][$row->id])) {
                    $GLOBALS["SL"]->x["usernames"][$row->id] = $row->name;
                }
            }
        }
        return $GLOBALS["SL"]->x["recentDeptEdits"];
    }
    
    /**
     * Print the shorm form for volunteers to share their state
     * for a default filter of all departments.
     *
     * @return string
     */
    public function printVolunLocationForm()
    {
        $GLOBALS["SL"]->loadStates();
        $this->loadYourContact();
        if ($GLOBALS["SL"]->REQ->has('saveDefaultState')) {
            if (isset($this->v["yourContact"]) && isset($this->v["yourContact"]->PrsnID)) {
                if ($GLOBALS["SL"]->REQ->has('newState')) {
                    $this->v["yourContact"]->update([ "PrsnAddressState" => $GLOBALS["SL"]->REQ->get('newState') ]);
                }
                if ($GLOBALS["SL"]->REQ->has('newPhone')) {
                    $this->v["yourContact"]->update([ "PrsnPhoneMobile" => $GLOBALS["SL"]->REQ->get('newPhone') ]);
                }
            }
        }
        return view('vendor.openpolice.nodes.1217-volun-home-your-info', $this->v)->render();
    }
    
    
}