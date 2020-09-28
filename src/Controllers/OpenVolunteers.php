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
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OPPartners;
use App\Models\OPDepartments;
use App\Models\OPzEditDepartments;
use App\Models\SLSess;
use App\Models\SLSessPage;
use FlexYourRights\OpenPolice\Controllers\VolunteerLeaderboard;
use FlexYourRights\OpenPolice\Controllers\OpenDevelopment;

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
                [ 'dept_name',     'asc'  ]
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
                        [ 'dept_address_city',  'asc' ]
                    ];
                    break;
            }
            $this->v["state"] = '';
            if ($GLOBALS["SL"]->REQ->has('state') 
                && trim($GLOBALS["SL"]->REQ->get('state')) != '') {
                $this->v["state"] = trim($GLOBALS["SL"]->REQ->get('state'));
            } elseif (isset($this->v["yourContact"]->prsn_address_state) 
                && trim($this->v["yourContact"]->prsn_address_state) != '') {
                $this->v["state"] = trim($this->v["yourContact"]->prsn_address_state);
            }
            $fedDef = $GLOBALS["SL"]->def->getID(
                'Department Types',
                'Federal Law Enforcement'
            );
            if ($GLOBALS["SL"]->REQ->has('s') 
                && trim($GLOBALS["SL"]->REQ->get('s')) != '') {
                $this->chkRecsPub($GLOBALS["SL"]->REQ, 36);
                $searches = [];
                if ($GLOBALS["SL"]->REQ->has('s') 
                    && trim($GLOBALS["SL"]->REQ->get('s')) != '') {
                    $searches = $GLOBALS["SL"]->parseSearchWords(
                        $GLOBALS["SL"]->REQ->get('s')
                    );
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
                        if ($this->v["state"] == 'US') {
                            foreach ($searches as $s) {
                                $sP = '%' . $s . '%';
                                $rows = OPDepartments::where('dept_type', $fedDef)
                                    ->where(function ($query) use ($sP) {
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
                        } else {
                            foreach ($searches as $s) {
                                $sP = '%' . $s . '%';
                                $rows = OPDepartments::where('dept_type', 'NOT LIKE', $fedDef)
                                    ->where('dept_address_state', $this->v["state"])
                                    ->where(function ($query) use ($sP) {
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
                }
                $this->v["deptRows"] = $GLOBALS["SL"]->x["srchRes"]["depts"];
                unset($GLOBALS["SL"]->x["srchRes"]["depts"]);
            } elseif ($this->v["state"] != '') {
                if ($this->v["state"] == 'US') {
                    $this->v["deptRows"] = OPDepartments::where('dept_type', $fedDef)
                        ->select('dept_id', 'dept_name', 'dept_score_openness', 
                            'dept_verified', 'dept_address_city', 'dept_address_state')
                        ->orderBy($orderby[0][0], $orderby[0][1])
                        ->get();
                } else {
                    $this->v["deptRows"] = OPDepartments::where('dept_address_state', $this->v["state"])
                        ->where('dept_type', 'NOT LIKE', $fedDef)
                        ->select('dept_id', 'dept_name', 'dept_score_openness', 
                            'dept_verified', 'dept_address_city', 'dept_address_state')
                        ->orderBy($orderby[0][0], $orderby[0][1])
                        ->get();
                }
            } else {
                $this->v["deptRows"] = OPDepartments::select('dept_id', 'dept_name', 
                    'dept_score_openness', 'dept_verified', 'dept_address_city', 'dept_address_state')
                    ->orderBy($orderby[0][0], $orderby[0][1])
                    ->get();
            }
            /* if ($this->v["deptRows"] && sizeof($this->v["deptRows"]) > 0) {
                foreach ($this->v["deptRows"] as $dept) {
                    $this->isDeptBeingEdited($dept->dept_id);
                }
            } */
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
            date("m")-18, date("d"), date("Y"));
        $this->v["twomonths"] = mktime(
            date("H"), date("i"), date("s"), date("m")-2, date("d"), date("Y")
        );
        $set = 'Complaint Status';
        $statuses = [
            $GLOBALS["SL"]->def->getID($set, 'New'),
            //$GLOBALS["SL"]->def->getID($set, 'Hold'),
            $GLOBALS["SL"]->def->getID($set, 'Reviewed'),
            $GLOBALS["SL"]->def->getID($set, 'Needs More Work'),
            $GLOBALS["SL"]->def->getID($set, 'Pending Attorney'),
            $GLOBALS["SL"]->def->getID($set, 'Has Attorney'),
            $GLOBALS["SL"]->def->getID($set, 'OK to Submit to Oversight')
        ];
        $set = 'Complaint Type';
        $types = [
            $GLOBALS["SL"]->def->getID($set, 'Unverified'),
            $GLOBALS["SL"]->def->getID($set, 'Police Complaint')
        ];
        $chk = DB::table('op_departments')
            ->join('op_links_complaint_dept', 'op_departments.dept_id', 
                '=', 'op_links_complaint_dept.lnk_com_dept_dept_id')
            ->join('op_complaints', 'op_links_complaint_dept.lnk_com_dept_complaint_id', 
                '=', 'op_complaints.com_id')
            //->where('op_departments.dept_name', 'NOT LIKE', 'Not sure about department')
            ->whereIn('op_complaints.com_type', $types)
            ->whereIn('op_complaints.com_status', $statuses)
            ->where('op_complaints.com_summary', 'NOT LIKE', '')
            //->where('op_complaints.created_at', '>', date("Y-m-d", $this->v["twomonths"]))
            ->select('op_departments.*', 'op_complaints.created_at')
            ->orderBy('op_complaints.created_at', 'asc')
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $dept) {
//echo 'um? ' . $dept->dept_name . ', ' . $dept->created_at . ' is ' . strtotime($dept->created_at) . ' ? > ' . $this->v["twomonths"] . '<br />';
                if (!in_array($dept->dept_id, $done)
                    && strtotime($dept->created_at) > $this->v["twomonths"]
                    && (!isset($dept->dept_score_openness)
                        || !isset($dept->dept_verified) 
                        || strtotime($dept->dept_verified) < $this->v["yearold"])) {
                    //$this->isDeptBeingEdited($dept->dept_id);
                    $this->v["deptPriorityRows"][] = $dept;
                    $done[] = $dept->dept_id;
                } else {
                    $rejects[] = $dept;
                }
            }
        }
//echo 'priority: <pre>'; print_r($this->v["deptPriorityRows"]); print_r($chk); echo '</pre>'; exit;
        return $this->v["deptPriorityRows"];
    }
    
    /**
     * Prepare the list of departments which need most urgent help with research.
     *
     * @return boolean
     */
    protected function isDeptBeingEdited($deptID)
    {
        if ($deptID <= 0) {
            return '';
        }
        if (!isset($GLOBALS["SL"]->x["deptEditing"])) {
            $GLOBALS["SL"]->x["deptEditing"] = [];
        }
        if (!isset($GLOBALS["SL"]->x["deptEditing"][$deptID])) {
            $GLOBALS["SL"]->x["deptEditing"][$deptID] = '';
            $this->v["twohrs"] = mktime(
                date("H")-2, date("i"), date("s"), date("m"), date("d"), date("Y")
            );
            $chk = SLSess::where('sess_tree', 36)
                ->where('sess_core_id', $deptID)
                ->where('sess_is_active', 1)
                ->where('sess_user_id', 'NOT LIKE', $this->v["uID"])
                ->orderBy('updated_at', 'desc')
                ->get();
            if ($chk->isNotEmpty()) {
                foreach ($chk as $sess) {
//echo '<pre>'; print_r($sess); echo '</pre>';
                    $editing = 0;
                    if ($this->v["twohrs"] < strtotime($sess->updated_at)) {
                        $editing = strtotime($sess->updated_at);
//echo 'A editing: ' . $editing . '<br />';
                    } else {
                        $save = SLSessPage::where('sess_page_sess_id', $sess->sess_id)
                            ->where('updated_at', '>', date("Y-m-d H:i:s", $this->v["twohrs"]))
                            ->first();
                        if ($save && isset($save->sess_page_id)) {
                            if ($editing < strtotime($save->updated_at)) {
                                $editing = strtotime($save->updated_at);
//echo 'B editing: ' . $editing . '<br />';
                            }
                        }
                    }
//echo 'C editing: ' . $editing . '<br />';
                    // check for completion since latest timestamp
                    if ($editing > 0) {
                        $edit = OPzEditDepartments::where('zed_dept_dept_id', $deptID)
                            ->where('zed_dept_user_id', $chk->sess_user_id)
                            ->first();
                        if ($edit 
                            && isset($edit->created_at)
                            && $editing < strtotime($edit->created_at)) {
                            $editing = 0;
//echo 'D editing: ' . $editing . '<br />';
                        }
                    }
                    // if still presumed editing, then lock it in
                    if ($editing > 0) {
                        $u = User::find($chk->sess_user_id);
                        if ($u) {
                            $GLOBALS["SL"]->x["deptEditing"][$deptID] = $u->printUsername();
                        }
                    }
//exit;
                }
            }
        }
        return $GLOBALS["SL"]->x["deptEditing"][$deptID];
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
        $cutoff = mktime(date("H"), date("i"), date("s"), date("n")-1, date("j"), date("Y"));
        $cutoff = date("Y-m-d H:i:s", $cutoff);
        //where('op_z_edit_departments.zed_dept_dept_verified', '>', $cutoff)
        $rows = OPzEditDepartments::leftJoin('users', 'users.id', 
                '=', 'op_z_edit_departments.zed_dept_user_id')
            ->select('op_z_edit_departments.zed_dept_dept_id', 'users.id', 
                'op_z_edit_departments.zed_dept_dept_verified', 'users.name')
            ->orderBy('op_z_edit_departments.zed_dept_dept_verified', 'desc')
            ->get();
        if ($rows->isNotEmpty()) {
            foreach ($rows as $row) {
                if (!isset($GLOBALS["SL"]->x["recentDeptEdits"][$row->zed_dept_dept_id])) {
                    $GLOBALS["SL"]->x["recentDeptEdits"][$row->zed_dept_dept_id] = $row->id;
                    if (!isset($GLOBALS["SL"]->x["usernames"][$row->id])) {
                        $usr = User::find($row->id);
                        if ($usr) {
                            $GLOBALS["SL"]->x["usernames"][$row->id] = $usr->printUsername();
                        }
                    }
                }
            }
        }
        return $GLOBALS["SL"]->x["recentDeptEdits"];
    }
    
    /**
     * Print the short form for volunteers to share their state
     * for a default filter of all departments.
     *
     * @return string
     */
    public function printVolunLocationForm()
    {
        $GLOBALS["SL"]->loadStates();
        $this->loadYourContact();
        return view(
            'vendor.openpolice.nodes.1217-volun-home-your-info', 
            $this->v
        )->render();
    }
    
    /**
     * Save the state volunteers are in.
     *
     * @return string
     */
    public function saveVolunLocationForm(Request $request)
    {
        $this->survLoopInit($request, '/ajax/save-default-state/');
        $this->loadYourContact();
        if (isset($this->v["yourContact"]) 
            && isset($this->v["yourContact"]->prsn_id)) {
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
        return '<h1 class="slGreenDark">'
            . '<i class="fa fa-check-circle" aria-hidden="true"></i></h1>';
    }
    
    
}