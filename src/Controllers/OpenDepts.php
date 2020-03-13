<?php
/**
  * OpenDepts is a mid-level class which handles most functions
  * for managing the departments database.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OPDepartments;
use App\Models\OPZeditDepartments;
use App\Models\OPZeditOversight;
use App\Models\OPzVolunTmp;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintOversight;
use App\Models\OPLinksComplimentOversight;
use OpenPolice\Controllers\DepartmentScores;
use OpenPolice\Controllers\OpenListing;

class OpenDepts extends OpenListing
{
    /**
     * Print ajax load of search results to select a police department.
     *
     * @param  int $nID
     * @return string
     */
    protected function printDeptSearch($nID)
    {
        $inc = $this->sessData->dataSets["incidents"][0];
        $this->nextBtnOverride = 'Find & Select A Department';
        $searchSuggest = [];
        $deptCitys = OPDepartments::where('dept_address_state', $inc->inc_address_state)
            ->select('dept_address_city')
            ->distinct()
            ->get();
        if ($deptCitys->isNotEmpty()) {
            foreach ($deptCitys as $dept) {
                $deptCty = $dept->dept_address_city;
                if (!in_array($deptCty, $searchSuggest)) {
                    $searchSuggest[] = json_encode($deptCty);
                }
            }
        }
        $deptCounties = OPDepartments::select('dept_address_county')
            ->distinct()
            ->where('dept_address_state', $inc->inc_address_state)
            ->get();
        if ($deptCounties->isNotEmpty()) {
            foreach ($deptCounties as $dept) {
                $deptCnty = $dept->dept_address_county;
                if (!in_array($deptCnty, $searchSuggest)) {
                    $searchSuggest[]= json_encode($deptCnty);
                }
            }
        }
        $deptFed = OPDepartments::select('dept_name')
            ->where('dept_type', 366)
            ->get();
        if ($deptFed->isNotEmpty()) {
            foreach ($deptFed as $dept) {
                if (!in_array($dept->dept_name, $searchSuggest)) {
                    $searchSuggest[] = json_encode($dept->dept_name);
                }
            }
        }
        $GLOBALS["SL"]->pageAJAX .= view(
            'vendor.openpolice.nodes.145-ajax-dept-search', 
            [
                "nID"           => $nID,
                "searchSuggest" => $searchSuggest
            ]
        )->render();
        $GLOBALS["SL"]->loadStates();
        $stateDrop = $GLOBALS["SL"]->states->stateDrop($inc->inc_address_state, true);
        return view(
            'vendor.openpolice.nodes.145-dept-search', 
            [ 
                "nID"                => $nID,
                "incAddressCity"     => $inc->inc_address_city, 
                "stateDropstateDrop" => $stateDrop
            ]
        )->render();
    }
    
    /**
     * Locate the next highest priority department for a user to work on.
     *
     * @return array
     */
    protected function getNextDept()
    {
        $this->v["nextDept"] = array(0, '', '');
        $recentDate = time(date("H")-3, date("i"), date("s"), date("n"), date("j"), date("Y"));
        $recentDate = date("Y-m-d H:i:s", $recentDate);
        /*
        OPzVolunTmp::where('tmp_type', 'zed_dept')
            ->where('tmp_date', '<', $recentDate)
            ->delete();
        // First check for department temporarily reserved for this user
        $tmpReserve = OPzVolunTmp::where('tmp_type', 'zed_dept')
            ->where('tmp_user', Auth::user()->id)
            ->first();
        if ($tmpReserve && isset($tmpReserve->tmp_val) && intVal($tmpReserve->tmp_val) > 0) {
            $nextRow = OPDepartments::where('dept_id', $tmpReserve->tmp_val)
                ->first();
            $this->v["nextDept"] = [ $nextRow->dept_id, $nextRow->dept_name, $nextRow->dept_slug ];
        } else { // no department reserved yet, find best next choice...
        */
            $this->loadDeptPriorityRows();
            if (sizeof($this->v["deptPriorityRows"]) > 0) {
                $nextRow = $this->v["deptPriorityRows"][0];
                $this->v["nextDept"] = [
                    $nextRow->dept_id, 
                    $nextRow->dept_name, 
                    $nextRow->dept_slug 
                ];
            } else {
                $nextRow = NULL;
                $qmen = [];
                $qBase = "SELECT `dept_id`, `dept_name`, `dept_slug` FROM `op_departments` WHERE ";
                $qReserves = " AND `dept_id` NOT IN (SELECT `tmp_val` FROM `op_zvolun_tmp` WHERE "
                    . "`tmp_type` LIKE 'zed_dept' AND `tmp_user` NOT LIKE '"
                    . intVal(Auth::user()->id) . "')";
                $qmen[] = $qBase . "(`dept_verified` < '2015-01-01 00:00:00' "
                    . "OR `dept_verified` IS NULL) " . $qReserves 
                    . " ORDER BY RAND()";
                $qmen[] = $qBase . "1 " . $qReserves . " ORDER BY `dept_verified`";
                $qmen[] = $qBase . "1 ORDER BY RAND()"; // worst-case backup
                for ($i = 0; ($i < sizeof($qmen) && !$nextRow); $i++) {
                    $nextRow = DB::select( DB::raw( $qmen[$i]." LIMIT 1" ) );
                }
                $this->v["nextDept"] = [
                    $nextRow[0]->dept_id, 
                    str_replace('Department', 'Dept', $nextRow[0]->dept_name), 
                    $nextRow[0]->dept_slug
                ];
                
                // Temporarily reserve this department for this user
                $newTmp = new OPzVolunTmp;
                $newTmp->tmp_user = Auth::user()->id;
                $newTmp->tmp_date = date("Y-m-d H:i:s");
                $newTmp->tmp_type = 'zed_dept';
                $newTmp->tmp_val  = $this->v["nextDept"][0];
                $newTmp->save();
            }
        //}
        return $this->v["nextDept"];
    }
    
    /**
     * Redirect the next highest priority department for a user to work on.
     *
     * @return string
     */
    public function nextDept()
    {
        $this->getNextDept();
        $url = '/dashboard/volunteer/verify/' . $this->v["nextDept"][2];
        return $this->redir($url);
    }
    
    /**
     * Redirect the next highest priority department for a user to work on.
     *
     * @param string $deptName
     * @param string $deptState
     * @return string
     */
    public function newDeptAdd($deptName = '', $deptState = '')
    {
        if (trim($deptName) != '' && trim($deptState) != '') {
            $newDept = OPDepartments::where('dept_name', $deptName)
                ->where('dept_address_state', $deptState)
                ->first();
            if ($newDept && isset($newDept->dept_slug)) {
                $url = '/dashboard/volunteer/verify/' . $newDept->dept_slug;
                return $this->redir($url);
            }
            $newDept = new OPDepartments;
            $newIA   = new OPOversight;
            $newIA->over_type      = $this->overWhichDefID('IA');
            $newDept->dept_name    = $deptName;
            $newDept->dept_status  = 1;
            $newDept->dept_user_id = Auth::user()->id;
            $newDept->dept_address_state = (($deptState != 'US') ? $deptState : '');
            $newDept->dept_slug    = $deptState . '-' . Str::slug($deptName);
            $newDept->dept_type    = (($deptState == 'US') ? 366 : 0);
            $newDept->save();
            $newIA->over_dept_id   = $newDept->dept_id;
            $newIA->over_type      = $this->overWhichDefID('IA');
            $newIA->over_user_id   = Auth::user()->id;
            $newIA->save();
            return $newDept;
        }
        return [];
    }
    
    /**
     * Load the history of edits for the current department.
     *
     * @return boolean
     */
    public function loadDeptEditsSummary()
    {
        $desc = 'Last Verified: ' . (($this->v["neverEdited"]) ? 'Never'
            : date('n/j/y', strtotime($this->v["deptRow"]->dept_verified))) 
            . ' <span class="mL20"><nobr>' . intVal($this->v["editTots"]["online"]) 
            . '<span class="fPerc80 mL3 mR3">x</span> Online Research,</nobr></span> '
            . '<span class="mL20"><nobr>' . intVal($this->v["editTots"]["callDept"]) 
            . '<span class="fPerc80 mL3 mR3">x</span> Department Calls,</nobr></span> '
            . '<span class="mL20"><nobr>' . intVal($this->v["editTots"]["callIA"]) 
            . '<span class="fPerc80 mL3 mR3">x</span> Internal Affairs Calls</nobr></span>';
        $icons = (($this->v["neverEdited"]) ? '<span class="slGrey">' : '') 
            . intVal($this->v["editTots"]["online"]) . '<i class="fa fa-laptop"></i>, ' 
            . intVal($this->v["editTots"]["callDept"]) . '<i class="fa fa-phone"></i>, ' 
            . intVal($this->v["editTots"]["callIA"]) . '<i class="fa fa-phone"></i>'
            . (($this->v["neverEdited"]) ? '</span>' : '');
        $this->v["editsSummary"] = [ $desc, $icons ];
        return true;
    }
    
    /**
     * Print out the header bar for the volunteer survey page to edit
     * police department infomation.
     *
     * @return string
     */
    protected function printDeptEditHeader()
    {
        $this->v["editsIA"] = $this->v["editsCiv"] 
            = $this->v["userEdits"] = $this->v["userNames"] = [];
        $this->v["deptRow"] = $this->sessData->dataSets["departments"][0];
        $this->v["deptSlug"] = $this->v["deptRow"]->dept_slug;
        $this->v["user"] = Auth::user();
        $this->v["neverEdited"] = false;
        $this->v["recentEdits"] = '';
        $this->v["editTots"] = [
            "notes"    => 0, 
            "online"   => 0, 
            "callDept" => 0, 
            "callIA"   => 0 
        ];
        
        if (!isset($this->v["deptRow"]->dept_id) || intVal($this->v["deptRow"]->dept_id) <= 0) {
            return $this->redir('/dashboard/volunteer');
        }
        
        $this->v["editsDept"] = OPZeditDepartments::where('zed_dept_dept_id', $this->v["deptRow"]->dept_id)
            ->orderBy('zed_dept_dept_verified', 'desc')
            ->get();
        if ($this->v["editsDept"]->isNotEmpty()) {
            foreach ($this->v["editsDept"] as $i => $edit) {
                $this->v["editsIA"][$i]  = OPZeditOversight::where('zed_over_zed_dept_id', $edit->zed_dept_id)
                    ->where('zed_over_over_type', $this->overWhichDefID('IA'))
                    ->first();
                $this->v["editsCiv"][$i] = OPZeditOversight::where('zed_over_zed_dept_id', $edit->zed_dept_id)
                    ->where('zed_over_over_type', $this->overWhichDefID('civ'))
                    ->first();
                if ($this->v["editsIA"][$i]) {
                    if (trim($this->v["editsIA"][$i]->zed_over_notes) != '') {
                        $this->v["editTots"]["notes"]++;
                    }
                    if (intVal($this->v["editsIA"][$i]->zed_over_online_research) == 1) {
                        $this->v["editTots"]["online"]++;
                    }
                    if (intVal($this->v["editsIA"][$i]->zed_over_made_dept_call) == 1) {
                        $this->v["editTots"]["callDept"]++;
                    }
                    if (intVal($this->v["editsIA"][$i]->zed_over_made_ia_call) == 1) {
                        $this->v["editTots"]["callIA"]++;
                    }
                }
                if (!isset($this->v["userNames"][$edit->zed_dept_user_id])) {
                    $this->v["userNames"][$edit->zed_dept_user_id] = $this->printUserLnk($edit->zed_dept_user_id);
                }
                if ($this->v["user"]->hasRole('administrator|staff')) {
                    $this->v["recentEdits"] .= view(
                        'vendor.openpolice.volun.admPrintDeptEdit', 
                        [
                            "user"     => $this->v["userNames"][$edit->zed_dept_user_id], 
                            "deptRow"  => $this->v["deptRow"], 
                            "deptEdit" => $edit, 
                            "iaEdit"   => $this->v["editsIA"][$i], 
                            "civEdit"  => $this->v["editsCiv"][$i],
                            "deptType" => $GLOBALS["SL"]->def->getVal('Department Types', $edit->dept_type)
                        ]
                    )->render();
                }
            }
        } else {
            $this->v["neverEdited"] = true;
        }
        $this->loadDeptEditsSummary();
        $GLOBALS["SL"]->loadStates();
        if (!isset($this->v["deptScores"])) {
            $this->v["deptScores"] = new DepartmentScores;
        }
        $GLOBALS["SL"]->addHshoos([
            '#deptContact',
            '#deptWeb',
            '#deptIA',
            '#deptCiv',
            '#deptSave',
            '#deptEdits',
            '#deptChecklist'
        ]);
        $GLOBALS["SL"]->setCurrPage('#deptContact');
        return view(
            'vendor.openpolice.nodes.1225-volun-dept-edit-header', 
            $this->v
        )->render();
    }
    
    /**
     * Print out the secondary header bar for the volunteer survey page 
     * to edit police department infomation.
     *
     * @return string
     */
    protected function printDeptEditHeader2()
    {
        return view(
            'vendor.openpolice.nodes.2162-volun-dept-edit-header2', 
            [ "deptRow" => $this->sessData->dataSets["departments"][0] ]
        )->render();
    }
    
    /**
     * Save a newly added department, submitted by the complainant.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveNewDept($nID)
    {
        $tbl = (($nID == 145) ? 'links_complaint_dept' : 'links_compliment_dept');
        $fld = 'n' . $nID . 'fld';
        $newDeptID = -3;
        if (intVal($GLOBALS["SL"]->REQ->get($fld)) > 0) {
            $newDeptID = intVal($GLOBALS["SL"]->REQ->get($fld));
            $this->sessData->logDataSave($nID, 'NEW', -3, $tbl, $newDeptID);
        } elseif ($GLOBALS["SL"]->REQ->has('newDeptName') 
            && trim($GLOBALS["SL"]->REQ->newDeptName) != '') {
            $newDept = $this->newDeptAdd(
                $GLOBALS["SL"]->REQ->newDeptName, 
                $GLOBALS["SL"]->REQ->newDeptAddressState
            );
            $newDeptID = $newDept->dept_id;
            $logTxt = $tbl . ' - !New Department Added!';
            $this->sessData->logDataSave($nID, 'NEW', -3, $logTxt, $newDeptID);
        }
        if ($newDeptID > 0) {
            $this->chkDeptLinks($newDeptID);
        }
        return true;   
    }

    /**
     * Save the ways a department accepts complaints, via the volunteer survey.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveDeptSubWays1($nID)
    {
        /*
        OPOversight::where('over_dept_id', $this->sessData->dataSets["departments"][0]->dept_id)
            ->where('over_type', $this->overWhichDefID('IA'))
            ->update([
                'over_way_sub_email' => (in_array('Email', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0)
            ]);
        */
        $found = false;
        if ($GLOBALS["SL"]->REQ->has('n1285fld')) {
            $ways = $GLOBALS["SL"]->REQ->n1285fld;
            if (is_array($ways) && is_array($ways) 
                && sizeof($ways) > 0) {
                $found = true;
                $this->sessData->currSessData(
                    $nID, 
                    'oversight', 
                    'over_way_sub_email', 
                    'update', 
                    ((in_array('Email', $ways) ) ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'oversight', 
                    'over_way_sub_verbal_phone', 
                    'update', 
                    ((in_array('VerbalPhone', $ways) ) ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'oversight', 
                    'over_way_sub_paper_mail', 
                    'update', 
                    ((in_array('PaperMail', $ways) ) ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'oversight', 
                    'over_way_sub_paper_in_person', 
                    'update', 
                    ((in_array('PaperInPerson', $ways) ) ? 1 : 0)
                );
            }
        }
        if (!$found) {
            $this->sessData->currSessData(
                $nID, 
                'oversight', 
                'over_way_sub_email', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'oversight', 
                'over_way_sub_verbal_phone', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'oversight', 
                'over_way_sub_paper_mail', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'oversight', 
                'over_way_sub_paper_in_person', 
                'update', 
                0
            );
        }
        return false;
    }
    
    /**
     * Save the second set of ways a department accepts complaints, 
     * via the volunteer survey.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveDeptSubWays2($nID)
    {
        $found = false;
        if ($GLOBALS["SL"]->REQ->has('n1287fld')) {
            $ways = $GLOBALS["SL"]->REQ->n1287fld;
            if (is_array($ways) && sizeof($ways) > 0) {
                $found = true;
                $this->sessData->currSessData(
                    $nID, 
                    'oversight', 
                    'over_official_form_not_req', 
                    'update', 
                    ((in_array('OfficialFormNotReq', $ways) ) 
                        ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'oversight', 
                    'over_official_anon', 
                    'update', 
                    ((in_array('OfficialAnon', $ways) ) 
                        ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'oversight', 
                    'over_way_sub_notary', 
                    'update', 
                    ((in_array('Notary', $ways) ) 
                        ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'oversight', 
                    'over_submit_deadline', 
                    'update', 
                    ((in_array('TimeLimit', $ways) ) 
                        ? (($GLOBALS["SL"]->REQ->has('n1288fld')) ? $GLOBALS["SL"]->REQ->n1288fld : 0) 
                        : 0)
                );
            }
        }
        if (!$found) {
            $this->sessData->currSessData(
                $nID, 
                'oversight', 
                'over_official_form_not_req', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'oversight', 
                'over_official_anon', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'oversight', 
                'over_way_sub_notary', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'oversight', 
                'over_submit_deadline', 
                'update', 
                0
            );
        }
        return false;
    }
    
    /**
     * Save a copy of this department record — and its oversigt records
     * — to log a history of changes.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveEditLog($nID)
    {
        if ($GLOBALS["SL"]->REQ->get('step') != 'next') {
            return true;
        }
        $this->sessData->currSessData(
            $nID, 
            'departments', 
            'dept_verified', 
            'update', 
            date("Y-m-d H:i:s")
        );
        $this->sessData->createTblExtendFlds(
            'departments', 
            $this->coreID, 
            'zedit_departments', 
            [
                'zed_dept_dept_id'  => $this->coreID,
                'zed_dept_user_id'  => $this->v["uID"],
                'zed_dept_duration' => (time()-intVal($GLOBALS["SL"]->REQ->formLoaded)),
                'zed_dept_dept_id'  => $this->coreID,
            ]
        );
        $over = $this->getOverRow('IA');
        $newResearch = [];
        if ($GLOBALS["SL"]->REQ->has('n1329fld') 
            && is_array($GLOBALS["SL"]->REQ->get('n1329fld'))) {
            $newResearch = $GLOBALS["SL"]->REQ->get('n1329fld');
        }
        $deptID = $this->sessData->dataSets['zedit_departments'][0]->getKey();
        $resOnline = $resCall = $resCallIA = 0;
        if (in_array('Online', $newResearch)) {
            $resOnline = 1;
        }
        if (in_array('DeptCall', $newResearch)) {
            $resCall = 1;
        }
        if (in_array('IACall', $newResearch)) {
            $resCallIA = 1;
        }
        $notes = (($GLOBALS["SL"]->REQ->has('n1334fld')) 
            ? $GLOBALS["SL"]->REQ->n1334fld : '');
        $this->sessData->createTblExtendFlds(
            'oversight', 
            $over->getKey(), 
            'zedit_oversight', 
            [
                'zed_over_zed_dept_id'     => $deptID,
                'zed_over_online_research' => $resOnline,
                'zed_over_made_dept_call'  => $resCall,
                'zed_over_made_ia_call'    => $resCallIA,
                'zed_over_notes'           => $notes
            ]
        );
        $over = $this->getOverRow('civ');
        if ($over && isset($over->over_id)) {
            
            $this->sessData->createTblExtendFlds(
                'oversight', 
                $over->getKey(), 
                'zedit_oversight', 
                [ 'zed_over_zed_dept_id' => $deptID ]
            );
        }
        return false;
    }
    
    /**
     * Redirect volunteer/staff after editing a department.
     *
     * @return string
     */
    protected function redirAfterDeptEdit()
    {
        $redir = '/dash/volunteer';
        $msg = 'Back To The Department List...';
        if ($GLOBALS["SL"]->REQ->has('n1335fld')) {
            $next = trim($GLOBALS["SL"]->REQ->get('n1335fld'));
            if ($next == 'again') {
                $redir = '/dashboard/start-' 
                    . $this->sessData->dataSets["departments"][0]->dept_id 
                    . '/volunteers-research-departments';
                $msg = 'Saving Changes...';
            } elseif ($next == 'dept-page') {
                $redir = '/dept/' . $this->sessData->dataSets["departments"][0]->dept_slug;
                $msg = 'Saving Changes...';
            } elseif ($next == 'another') {
                session()->forget('sessID36');
                session()->forget('coreID36');
                $this->getNextDept();
                $redir = '/dashboard/start-' . $this->v["nextDept"][0] 
                    . '/volunteers-research-departments';
                $msg = 'To The Next Department...';
            }
        }
        $GLOBALS["SL"]->pageJAVA .= 'setTimeout("window.location=\'' . $redir . '\'", 10); ';
        return '<center><h2 class="slBlueDark">' . $msg . '</h2>' . $GLOBALS["SL"]->sysOpts["spinner-code"]
            . '</center><style> #nodeSubBtns, #sessMgmt { display: none; } </style>';
    }

    /**
     * Redirect volunteer/staff to the next appropriate department, 
     * after editing a department.
     *
     * @return string
     */
    protected function redirNextDeptEdit()
    {
        session()->forget('sessID36');
        session()->forget('coreID36');
        $this->getNextDept();
        $redir = '/dashboard/start-' . $this->v["nextDept"][0] . '/volunteers-research-departments';
        $GLOBALS["SL"]->pageJAVA .= 'setTimeout("window.location=\'' . $redir . '\'", 10); ';
        return '<center><h2 class="slBlueDark">To The Next Department...</h2>' 
            . $GLOBALS["SL"]->sysOpts["spinner-code"] . '</center><style> '
            . '#nodeSubBtns, #sessMgmt { display: none; } </style>';
    }
    
    /**
     * Prints a Google map of department accessibility scores.
     *
     * @param  int $nID
     * @return string
     */
    protected function publicDeptAccessMap($nID = -3)
    {
        if ($GLOBALS["SL"]->REQ->has('state') && trim($GLOBALS["SL"]->REQ->get('state')) != '') {
            return '<!-- no state map yet -->';
        }
        $ret = '';
        if ($GLOBALS["SL"]->REQ->has('colorMarker')) {
            $colors = [];
            for ($i = 0; $i < 5; $i++) {
                $colors[$i] = $GLOBALS["SL"]->printColorFadeHex(
                    $i/7, 
                    '#EC2327', 
                    '#FFFFFF'
                );
            }
            for ($i = 5; $i < 11; $i++) {
                $colors[$i] = $GLOBALS["SL"]->printColorFadeHex(
                    (11-$i)/7, 
                    '#2B3493', 
                    '#FFFFFF'
                );
            }
            $ret = '<br /><br /><br /><table border=0 cellpadding=0 cellspacing=5 class="m20" ><tr>';
            foreach ($colors as $i => $c) {
                $ret .= '<td><img border=0 src="/survloop/uploads/template-map-marker.png" '
                    . 'style="width: 80px; background: ' . $c . ';" alt="Accessibility Score ' 
                    . (($i == 0) ? 0 : $i . '0') . ' out of 10"><br /><br />' . $c . '</td>';
            }
            $ret .= '</tr></table><table border=0 cellpadding=0 cellspacing=5 class="m20" ><tr>';
            foreach ($colors as $i => $c) {
                $ret .= '<td><img border=0 src="/openpolice/uploads/map-marker-redblue-' 
                    . $i . '.png" alt="Accessibility Score ' . (($i == 0) ? 0 : $i . '0') 
                    . ' out of 10"></td>';
            }
            echo $ret . '</tr></table>';
        }
        
        $this->initSearcher();
        $this->searcher->getSearchFilts();
        $GLOBALS["SL"]->loadStates();
        if (!isset($this->v["deptScores"])) {
            $this->v["deptScores"] = new DepartmentScores;
            $this->v["deptScores"]->loadAllDepts(
                $this->searcher->searchFilts
            );
        }
        if ($GLOBALS["SL"]->REQ->has('state') 
            && trim($GLOBALS["SL"]->REQ->get('state')) != '') {
            $ret .= '<!-- not yet for state filter -->';
        } elseif (isset($this->v["deptScores"]->scoreDepts)
            && $this->v["deptScores"]->scoreDepts->isNotEmpty()) {
            $cnt = 0;
            $limit = 10;
            $forStart = $this->v["deptScores"]->scoreDepts->count()-1;
            for ($i = $forStart; $i >= 0; $i--) {
                $dept = $this->v["deptScores"]->scoreDepts[$i];
                if ($cnt < $limit 
                    && (!isset($dept->dept_address_lat) || intVal($dept->dept_address_lat) == 0
                        || !isset($dept->dept_address_lng) || intVal($dept->dept_address_lng) == 0)) {
                    $addy = $GLOBALS["SL"]->printRowAddy($dept, 'Dept');
                    if (trim($addy) != '') {
                        list($lat, $lng) = $GLOBALS["SL"]->states->getLatLng($addy);
                        $this->v["deptScores"]->scoreDepts[$i]->dept_address_lat = $lat;
                        $this->v["deptScores"]->scoreDepts[$i]->dept_address_lng = $lng;
                        $this->v["deptScores"]->scoreDepts[$i]->save();
                        $cnt++;
                    }
                }
                if (isset($dept->dept_address_lat) 
                    && $dept->dept_address_lat != 0 
                    && isset($dept->dept_address_lng) 
                    && $dept->dept_address_lng != 0 
                    && $dept->dept_score_openness > 0) {
                    $grad = 'RBgradient' . round($dept->dept_score_openness/10);
                    $name = $dept->dept_name . ': ' . $dept->dept_score_openness;
                    $url = '/ajax/dept-kml-desc?deptID=' . $dept->dept_id;
                    $GLOBALS["SL"]->states->addMapMarker(
                        $dept->dept_address_lat, 
                        $dept->dept_address_lng, 
                        $grad, 
                        $name, 
                        '',
                        $url
                    );
                }
            }
            for ($g = 0; $g < 11; $g++) {
                $url = $GLOBALS["SL"]->sysOpts["app-url"] 
                    . '/openpolice/uploads/map-marker-redblue-' . $g . '.png';
                $grad = 'RBgradient' . $g;
                $GLOBALS["SL"]->states->addMarkerType($grad, $url);
            }
            $ret = $GLOBALS["SL"]->states->embedMap(
                $nID, 
                'dept-access-scores-all-' . date("Ymd"), 
                'All Department Accessibility Scores', 
                $this->embedMapDeptLegend()
            );
            if ($ret == '') {
                $ret = "\n\n <!-- no map markers found --> \n\n";
            } elseif ($nID == 2013 && $GLOBALS["SL"]->REQ->has('test')) {
                $GLOBALS["SL"]->pageAJAX .= '$("#map' . $nID 
                    . 'ajax").load("/ajax/dept-kml-desc?deptID=13668");';
            }
        }
        return $ret;
    }
    
    /**
     * Prints a lengend for the Google map of 
     * department accessibility scores.
     *
     * @return string
     */
    protected function embedMapDeptLegend()
    {
        return view('vendor.openpolice.inc-map-dept-access-legend')->render();
    }
    
    /**
     * Prints the main public listing of all departments with
     * accessibility scores.
     *
     * @param  int $nID
     * @return string
     */
    protected function printDeptOverPublic($nID)
    {
        $state = '';
        if (isset($this->searcher->searchFilts["state"])) {
            $state = $this->searcher->searchFilts["state"];
        }
        return view(
            'vendor.openpolice.nodes.859-depts-overview-public', 
            [
                "nID"        => $nID,
                "deptScores" => $this->v["deptScores"],
                "state"      => $state
            ]
        )->render();
    }
    
    /**
     * Prints the main public listing of all departments with
     * accessibility scores, updated in 2020 with just some top 50 lists!
     *
     * @param  int $nID
     * @return string
     */
    protected function printDeptOverPublicTop50s($nID)
    {

        return view(
            'vendor.openpolice.nodes.2804-depts-accessibility-overview-public', 
            [
                "nID"        => $nID,
                "deptScores" => $this->v["deptScores"]
            ]
        )->render();
    }
    
    /**
     * Prints the titles for main public listing of all departments 
     * with accessibility scores.
     *
     * @param  int $nID
     * @return string
     */
    protected function printDeptAccScoreTitleDesc($nID)
    {
        $this->initSearcher();
        $this->searcher->getSearchFilts();
        if (!isset($this->v["deptScores"])) {
            $this->v["deptScores"] = new DepartmentScores;
            $this->v["deptScores"]->loadAllDepts($this->searcher->searchFilts);
        }
        $state = '';
        if ($GLOBALS["SL"]->REQ->has('state')) {
            $state = $GLOBALS["SL"]->REQ->state;
        }
        return view(
            'vendor.openpolice.nodes.1968-accss-grades-title-desc', 
            [
                "nID"   => $nID,
                "state" => $state
            ]
        )->render();
    }
    
    /**
     * Prints the bar charts which show average adoption rates for 
     * departments' different policies.
     *
     * @param  int $nID
     * @return string
     */
    protected function printDeptAccScoreBars($nID)
    {
        if (!$GLOBALS["SL"]->REQ->has('state') || trim($GLOBALS["SL"]->REQ->get('state')) == '') {
            $param = 'onscroll="if (typeof bodyOnScroll === \'function\') bodyOnScroll();"';
            $GLOBALS["SL"]->addBodyParams($param);
        }
        return $GLOBALS["SL"]->extractStyle($this->v["deptScores"]->printTotsBars(), $nID);
        /*
        $statGrades = new SurvStatsGraph;
        $statGrades->addFilt('grade', 'Grade', 
            ['A', 'B', 'C', 'D', 'F'],
            ['A', 'B', 'C', 'D', 'F']); // a
        $statGrades->addDataType('cnt', 'Departments', ' Departments'); // a
        $statGrades->loadMap();
        if ($this->v["deptScores"]->scoreDepts->isNotEmpty()) {
            foreach ($this->v["deptScores"]->scoreDepts as $i => $dept) {
                $grade = trim($GLOBALS["SL"]->calcGrade($dept->dept_score_openness));
                if (strlen($grade) > 1) $grade = substr($grade, 0, 1);
                $statGrades->resetRecFilt();
                $statGrades->addRecFilt('grade', $grade, $dept->dept_id);
                $statGrades->addRecDat('cnt', 1, $dept->dept_id);
            }
        }
        $statGrades->calcStats();
        $blue1 = $GLOBALS["SL"]->printColorFadeHex(0.6, '#FFFFFF', '#2B3493');
        $blue2 = $GLOBALS["SL"]->printColorFadeHex(0.3, '#FFFFFF', '#2B3493');
        $red2 = $GLOBALS["SL"]->printColorFadeHex(0.3, '#FFFFFF', '#EC2327');
        $colors = [ '#2B3493', $blue1, $blue2, $red2, '#EC2327' ];
        $ret = $statGrades->piePercCntCore('grade', 0.2, $colors);
        */
    }
    
    /**
     * Load whatever's needed to print the department profile page.
     *
     * @param  int $nID
     * @return string
     */
    protected function printDeptHeaderLoad($nID)
    {
        if (!isset($this->v["deptID"]) || intVal($this->v["deptID"]) <= 0) {
            return 'Department Not Found';
        }
        //if (!isset($this->v["deptID"]) 
        //    || intVal($this->v["deptID"]) <= 0) {
        //    if ($GLOBALS["SL"]->REQ->has('d') 
        //        && intVal($GLOBALS["SL"]->REQ->get('d')) > 0) {
        //        $this->v["deptID"] = $GLOBALS["SL"]->REQ->get('d');
        //    } else {
        //        $this->v["deptID"] = -3;
        //    }
        //}
        //$this->loadDeptStuff($this->v["deptID"]);
        $roles = 'administrator|databaser|staff|partner|volunteer';
        if ($this->v["uID"] > 0 && $this->v["user"]->hasRole($roles)) {
            $url = '/dashboard/start-' . $this->v["deptID"] 
                . '/volunteers-research-departments';
            $GLOBALS["SL"]->addSideNavItem('Edit Department', $url);
        }
        return '';
    }
    
    /**
     * Prints a department's basic info and mapped location on their profile page.
     *
     * @param  int $nID
     * @return string
     */
    protected function printBasicDeptInfo($nID)
    {
        if (!isset($this->v["deptID"]) || intVal($this->v["deptID"]) <= 0) {
            return 'Department Not Found';
        }
        return view(
            'vendor.openpolice.nodes.2711-dept-page-basic-info', 
            [
                "nID" => $nID,
                "d"   => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]]
            ]
        )->render();
    }
    
    /**
     * Prints a call to action button on department's profile page.
     *
     * @param  int $nID
     * @return string
     */
    protected function printDeptCallsToAction($nID)
    {
        if (!isset($this->v["deptID"]) || intVal($this->v["deptID"]) <= 0) {
            return 'Department Not Found';
        }
        return view(
            'vendor.openpolice.nodes.2713-dept-page-calls-action', 
            [
                "nID" => $nID,
                "d"   => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]]
            ]
        )->render();
    }
    
    /**
     * Prints a department's recent complaint previews on their profile page.
     *
     * @param  int $nID
     * @return string
     */
    protected function printDeptReportsRecent($nID)
    {
        if (!isset($this->v["deptID"]) || intVal($this->v["deptID"]) <= 0) {
            return 'Department Not Found';
        }
        $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID . 'ajaxLoad").load("/record-prevs/1?d=' 
            . $this->v["deptID"] . '&limit=20");' . "\n";
        return view(
            'vendor.openpolice.nodes.2715-dept-page-recent-reports', 
            [
                "nID" => $nID,
                "d"   => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]]
            ]
        )->render();
    }
    
    /**
     * Prints a breakdown of a department's accessibility score on their profile page.
     *
     * @param  int $nID
     * @return string
     */
    protected function printDeptProfileAccScore($nID)
    {
        if (!isset($this->v["deptID"]) || intVal($this->v["deptID"]) <= 0) {
            return 'Department Not Found';
        }
        return view(
            'vendor.openpolice.nodes.2717-dept-page-accessibility-score', 
            [
                "nID" => $nID,
                "d"   => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]]
            ]
        )->render();
    }
    
    /**
     * Prints instructions on how to file a complaint with a department on their profile page.
     *
     * @param  int $nID
     * @return string
     */
    protected function printDeptProfileHowToFile($nID)
    {
        if (!isset($this->v["deptID"]) || intVal($this->v["deptID"]) <= 0) {
            return 'Department Not Found';
        }
        return view(
            'vendor.openpolice.nodes.2718-dept-page-how-to-file', 
            [
                "nID" => $nID,
                "d"   => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]]
            ]
        )->render();
    }
    
    /**
     * Print a listing of a department's complaints.
     *
     * @param  int $nID
     * @return string
     */
    protected function printDeptComplaints($nID)
    {
        $this->setUserOversightFilt();
        if (!isset($this->v["fltQry"])) {
            $this->v["fltQry"] = '';
        }
        $set = 'Complaint Status';
        $this->v["fltQry"] .= " c.`com_status` IN ("
            . $GLOBALS["SL"]->def->getID($set, 'OK to Submit to Oversight') 
            . ", " . $GLOBALS["SL"]->def->getID($set, 'Submitted to Oversight') 
            . ", " . $GLOBALS["SL"]->def->getID($set, 'Received by Oversight') 
            . ", " . $GLOBALS["SL"]->def->getID($set, 'Declined To Investigate (Closed)') 
            . ", " . $GLOBALS["SL"]->def->getID($set, 'Investigated (Closed)') 
            . ") AND ";
        $this->v["statusSkips"] = [
            $GLOBALS["SL"]->def->getID($set, 'Incomplete'),
            $GLOBALS["SL"]->def->getID($set, 'New'),
            $GLOBALS["SL"]->def->getID($set, 'Hold'),
            $GLOBALS["SL"]->def->getID($set, 'Reviewed'),
            $GLOBALS["SL"]->def->getID($set, 'Pending Attorney'),
            $GLOBALS["SL"]->def->getID($set, 'Attorney\'d'),
            $GLOBALS["SL"]->def->getID($set, 'Closed')
        ];
        return $this->printComplaintListing($nID);
    }

    /**
     * Loads the department's profile page from 
     * their slug in the page URL.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  string $deptSlug
     * @return string
     */
    public function deptPage(Request $request, $deptSlug = '')
    {
        $deptID = -3;
        $deptRow = OPDepartments::where('dept_slug', $deptSlug)
            ->first();
        if ($deptRow && isset($deptRow->dept_id)) {
            $deptID = $deptRow->dept_id;
            $request->d = $deptRow->dept_id;
        }
        $url = '/dept/' . $deptSlug;
        $this->loadPageVariation($request, 1, 25, $url);
        if ($deptID > 0) {
            $this->v["deptID"] = $deptRow->dept_id;
            $this->loadDeptStuff($deptID);
        }
        return $this->index($request);
    }
    
    /**
     * Loads the department's profile affiliate landing page to start
     * the survey process — from their slug in the page URL.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  string $deptSlug
     * @return string
     */
    public function shareStoryDept(Request $request, $deptSlug = '')
    {
        $url = '/complaint-or-compliment/' . $deptSlug;
        $this->loadPageVariation($request, 1, 24, $url);
        $deptRow = OPDepartments::where('dept_slug', $deptSlug)
            ->first();
        if ($deptRow && isset($deptRow->dept_id)) {
            session()->put('opcDeptID', $deptRow->dept_id);
        }
        return $this->index($request);
    }
    
    /**
     * Retrieves the record used to track this department's response. 
     * If it doesn't exist, this record will be created.
     *
     * @param  int $cid
     * @param  int $deptID
     * @return App\Models\OPLinksComplaintOversight
     */
    public function getOverUpdateRow($cid, $deptID)
    {
        $civDef = $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Civilian Oversight');
        if (!isset($this->v["currOverRow"])) {
            $this->v["currOverRow"] = NULL;
            $overs = OPOversight::where('over_dept_id', $deptID)
                ->orderBy('created_at', 'asc')
                ->get();
            if ($overs->isNotEmpty()) {
                if ($overs->count() == 1) {
                    $this->v["currOverRow"] = $overs[0];
                } else {
                    foreach ($overs as $i => $ovr) {
                        if ($ovr && isset($ovr->over_type) && $ovr->over_type == $civDef) {
                            $this->v["currOverRow"] = $ovr;
                        }
                    }
                }
            }
        }
        if ($this->v["currOverRow"] && isset($this->v["currOverRow"]->over_id)) {
            if ($this->treeID == 5) {
                $this->v["currOverUpdateRow"] 
                    = OPLinksComplimentOversight::where('lnk_compli_over_compliment_id', $cid)
                        ->where('lnk_compli_over_over_id', $this->v["currOverRow"]->over_id)
                        ->first();
                if (!$this->v["currOverUpdateRow"]) {
                    $this->v["currOverUpdateRow"] = new OPLinksComplimentOversight;
                    $this->v["currOverUpdateRow"]->lnk_compli_over_compliment_id = $cid;
                    $this->v["currOverUpdateRow"]->lnk_compli_over_dept_id = $deptID;
                    $this->v["currOverUpdateRow"]->lnk_compli_over_over_id = $this->v["currOverRow"]->over_id;
                    $this->v["currOverUpdateRow"]->save();
                }
            } else {
                $this->v["currOverUpdateRow"] = OPLinksComplaintOversight::where('lnk_com_over_complaint_id', $cid)
                    ->where('lnk_com_over_over_id', $this->v["currOverRow"]->over_id)
                    ->first();
                if (!$this->v["currOverUpdateRow"]) {
                    $this->v["currOverUpdateRow"] = new OPLinksComplaintOversight;
                    $this->v["currOverUpdateRow"]->lnk_com_over_complaint_id = $cid;
                    $this->v["currOverUpdateRow"]->lnk_com_over_dept_id = $deptID;
                    $this->v["currOverUpdateRow"]->lnk_com_over_over_id = $this->v["currOverRow"]->over_id;
                    $this->v["currOverUpdateRow"]->save();
                }
            }
        }
        if (!isset($this->v["currOverUpdateRow"])) {
            $this->v["currOverUpdateRow"] = null;
        }
        return $this->v["currOverUpdateRow"];
    }
    
    /**
     * Update the timestamp for when this step of the submission
     * process has been confirmed by one party.
     *
     * @param  int $cid
     * @param  int $deptID
     * @param  string $type
     * @param  array $row
     * @return boolean
     */
    public function logOverUpDate($cid, $deptID, $type = 'submitted', $row = [])
    {
        if ($this->treeID == 5) {
            if (!$row || !isset($row->lnk_compli_over_id)) {
                $row = $this->getOverUpdateRow($cid, $deptID);
            }
            $row->{ 'lnk_compli_over_' . $type } = date("Y-m-d H:i:s");
            $row->save();
        } else {
            if (!$row || !isset($row->lnk_com_over_id)) {
                $row = $this->getOverUpdateRow($cid, $deptID);
            }
            $row->{ 'lnk_com_over_' . $type } = date("Y-m-d H:i:s");
            $row->save();
        }
        return true;
    }
    
    /**
     * Converts an abbreviation for the two types of 
     * investigative agencies into full english.
     *
     * @param  string $which
     * @return string
     */
    protected function overWhichEng($which = 'IA')
    {
        return (($which == 'IA') ? 'Internal Affairs' : 'Civilian Oversight');
    }
    
    /**
     * Converts an abbreviation for the two types of 
     * investigative agencies into their definition ID.
     *
     * @param  string $which
     * @return int
     */
    protected function overWhichDefID($which = 'IA')
    {
        return $GLOBALS["SL"]->def->getID(
            'Investigative Agency Types', 
            $this->overWhichEng($which)
        );
    }
    
    /**
     * Converts an abbreviation for the two types of 
     * investigative agencies into its database record.
     *
     * @param  string $which
     * @return array
     */
    protected function getOverRow($which = 'IA')
    {
        if (isset($this->v["overRow" . $which])) {
            return $this->v["overRow" . $which];
        }
        $rows = $this->sessData->getRowIDsByFldVal(
            'oversight', 
            [ 'over_type' => $this->overWhichDefID($which) ],
            true
        );
        if (sizeof($rows) > 0) {
            $this->v["overRow" . $which] = $rows[0];
            return $this->v["overRow" . $which];
        }
        return [];
    }
    
}