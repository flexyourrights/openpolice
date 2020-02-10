<?php
/**
  * OpenVolunteers is a mid-level class with functions to
  * manage volunteers and track their research aid.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
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
            [ "leaderboard" => $this->v["leaderboard"] ]
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
                    $GLOBALS["SL"]->REQ->get('deptAddressState')
                );
                return '<script type="text/javascript"> setTimeout("window.location=\''
                    . '/dashboard/start-' . $newDept->dept_id 
                    . '/volunteers-research-departments\'", 10); </script>';
            }
            $this->v["viewType"] = (($GLOBALS["SL"]->REQ->has('sort')) 
                ? $GLOBALS["SL"]->REQ->get('sort') : 'recent');
            $this->v["deptRows"] = [];
            $this->v["searchForm"] = $this->deptSearchForm();
            $orderby = [
                [ 'dept_verified', 'desc' ],
                [ 'dept_name', 'asc' ]
            ];
            switch ($this->v["viewType"]) {
                case 'best': 
                    $orderby[0] = [ 'dept_score_openness', 'desc' ];
                    break;
                case 'name': 
                    $orderby[0] = [ 'dept_name', 'asc' ];
                    break;
                case 'city': 
                    $orderby = [
                        [ 'dept_address_state', 'asc' ],
                        [ 'dept_address_city', 'asc' ]
                    ];
                    break;
            }
            $this->v["state"] = '';
            if ($GLOBALS["SL"]->REQ->has('state')) {
                $this->v["state"] = trim($GLOBALS["SL"]->REQ->get('state'));
            } elseif (isset($this->v["yourContact"]->prsn_address_state) 
                && trim($this->v["yourContact"]->prsn_address_state) != '') {
                $this->v["state"] = trim($this->v["yourContact"]->prsn_address_state);
            }
            if ($GLOBALS["SL"]->REQ->has('s') && trim($GLOBALS["SL"]->REQ->get('s')) != '') {
                $this->chkRecsPub($GLOBALS["SL"]->REQ, 36);
                $searches = [];
                if ($GLOBALS["SL"]->REQ->has('s') && trim($GLOBALS["SL"]->REQ->get('s')) != '') {
                    $searches = $GLOBALS["SL"]->parseSearchWords($GLOBALS["SL"]->REQ->get('s'));
                }
                if (sizeof($searches) > 0) {
                    $rows = null;
                    if ($this->v["state"] == '') {
                        foreach ($searches as $s) {
                            $sP = '%' . $s . '%';
                            $rows = OPDepartments::where('dept_name', 'LIKE', $sP)
                                ->orWhere('dept_email', 'LIKE', $sP)
                                ->orWhere('dept_phone_work', 'LIKE', $sP)
                                ->orWhere('dept_address', 'LIKE', $sP)
                                ->orWhere('dept_address_city', 'LIKE', $sP)
                                ->orWhere('dept_address_zip', 'LIKE', $sP)
                                ->orWhere('dept_address_county', 'LIKE', $sP)
                                ->get();
                            $GLOBALS["SL"]->addSrchResults('depts', $rows, 'dept_id');
                        }
                    } else {
                        foreach ($searches as $s) {
                            $sP = '%' . $s . '%';
                            $rows = OPDepartments::where('dept_address_state', $this->v["state"])
                                ->where(function ($query) use ($s) {
                                    $query->where('dept_name', 'LIKE', $sP)
                                        ->orWhere('dept_email', 'LIKE', $sP)
                                        ->orWhere('dept_phone_work', 'LIKE', $sP)
                                        ->orWhere('dept_address', 'LIKE', $sP)
                                        ->orWhere('dept_address_city', 'LIKE', $sP)
                                        ->orWhere('dept_address_zip', 'LIKE', $sP)
                                        ->orWhere('dept_address_county', 'LIKE', $sP);
                                    })
                                ->get();
                            $GLOBALS["SL"]->addSrchResults('depts', $rows, 'dept_id');
                        }
                    }
                }
                $this->v["deptRows"] = $GLOBALS["SL"]->x["srchRes"]["depts"];
                unset($GLOBALS["SL"]->x["srchRes"]["depts"]);
            } elseif ($this->v["state"] != '') {
                $this->v["deptRows"] = OPDepartments::where('dept_address_state', $this->v["state"])
                    ->select('dept_id', 'dept_name', 'dept_score_openness', 
                        'dept_verified', 'dept_address_city', 'dept_address_state')
                    ->orderBy($orderby[0][0], $orderby[0][1])
                    ->get();
            } else {
                $this->v["deptRows"] = OPDepartments::select('dept_id', 'dept_name', 
                    'dept_score_openness', 'dept_verified', 'dept_address_city', 'dept_address_state')
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
        $this->v["yearold"] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")-1);
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
        $chk = DB::table('op_departments')
            ->join('op_links_complaint_dept', 'op_departments.dept_id', 
                '=', 'op_links_complaint_dept.lnk_com_dept_dept_id')
            ->join('op_complaints', 'op_links_complaint_dept.lnk_com_dept_complaint_id', 
                '=', 'op_complaints.com_id')
            ->whereIn('op_complaints.com_type', $types)
            ->whereIn('op_complaints.com_status', $statuses)
            ->where('op_complaints.com_summary', 'NOT LIKE', '')
            ->select('op_departments.*')
            ->orderBy('op_complaints.created_at', 'asc')
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $dept) {
                if (!in_array($dept->dept_id, $done)
                    && (!isset($dept->dept_score_openness)
                        || !isset($dept->dept_verified) 
                        || $dept->dept_verified < date("Y-m-d H:i:s", $this->v["yearold"]))) {
                    $this->v["deptPriorityRows"][] = $dept;
                    $done[] = $dept->dept_id;
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
        return view(
            'vendor.openpolice.volun.volunEditSearch', 
            [ 
                "deptName"  => $deptName, 
                "stateDrop" => $GLOBALS["SL"]->states->stateDrop($state) 
            ]
        )->render();
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
        $cutoff = mktime(date("H"), date("i"), date("s"), date("n"), date("j")-7, date("Y"));
        $cutoff = date("Y-m-d H:i:s", $cutoff);
        $rows = OPZeditDepartments::where('op_z_edit_departments.zed_dept_dept_verified', '>', $cutoff)
            ->leftJoin('users', 'users.id', '=', 'op_z_edit_departments.zed_dept_user_id')
            ->select('op_z_edit_departments.zed_dept_dept_id', 
                'op_z_edit_departments.zed_dept_dept_verified', 'users.id', 'users.name')
            ->orderBy('op_z_edit_departments.zed_dept_dept_verified', 'desc')
            ->get();
        if ($rows->isNotEmpty()) {
            foreach ($rows as $row) {
                if (!isset($GLOBALS["SL"]->x["recentDeptEdits"][$row->zed_dept_dept_id])) {
                    $GLOBALS["SL"]->x["recentDeptEdits"][$row->zed_dept_dept_id] = [];
                }
                if (!isset($GLOBALS["SL"]->x["recentDeptEdits"][$row->zed_dept_dept_id][$row->id])) {
                    $GLOBALS["SL"]->x["recentDeptEdits"][$row->zed_dept_dept_id][$row->id] 
                        = $row->zed_dept_dept_verified;
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
            if (isset($this->v["yourContact"]) && isset($this->v["yourContact"]->prsn_id)) {
                if ($GLOBALS["SL"]->REQ->has('newState')) {
                    $this->v["yourContact"]->update([
                        "prsn_address_state" => $GLOBALS["SL"]->REQ->get('newState')
                    ]);
                }
                if ($GLOBALS["SL"]->REQ->has('newPhone')) {
                    $this->v["yourContact"]->update([
                        "prsn_phone_mobile" => $GLOBALS["SL"]->REQ->get('newPhone')
                    ]);
                }
            }
        }
        return view(
            'vendor.openpolice.nodes.1217-volun-home-your-info', 
            $this->v
        )->render();
    }
    
    
}