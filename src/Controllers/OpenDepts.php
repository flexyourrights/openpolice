<?php
/**
  * OpenDepts is a mid-level class which handles most functions
  * for managing the departments database.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
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
        $inc = $this->sessData->dataSets["Incidents"][0];
        $this->nextBtnOverride = 'Find & Select A Department';
        $searchSuggest = [];
        $deptCitys = OPDepartments::select('DeptAddressCity')
            ->distinct()
            ->where('DeptAddressState', $inc->IncAddressState)
            ->get();
        if ($deptCitys->isNotEmpty()) {
            foreach ($deptCitys as $dept) {
                $deptCty = $dept->DeptAddressCity;
                if (!in_array($deptCty, $searchSuggest)) {
                    $searchSuggest[] = json_encode($deptCty);
                }
            }
        }
        $deptCounties = OPDepartments::select('DeptAddressCounty')
            ->distinct()
            ->where('DeptAddressState', $inc->IncAddressState)
            ->get();
        if ($deptCounties->isNotEmpty()) {
            foreach ($deptCounties as $dept) {
                $deptCnty = $dept->DeptAddressCounty;
                if (!in_array($deptCnty, $searchSuggest)) {
                    $searchSuggest[]= json_encode($deptCnty);
                }
            }
        }
        $deptFed = OPDepartments::select('DeptName')
            ->where('DeptType', 366)
            ->get();
        if ($deptFed->isNotEmpty()) {
            foreach ($deptFed as $dept) {
                if (!in_array($dept->DeptName, $searchSuggest)) {
                    $searchSuggest[] = json_encode($dept->DeptName);
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
        $stateDrop = $GLOBALS["SL"]->states->stateDrop(
            $inc->IncAddressState,
            true
        );
        return view(
            'vendor.openpolice.nodes.145-dept-search', 
            [ 
                "nID" => $nID,
                "IncAddressCity" => $inc->IncAddressCity, 
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
        $recentDate = date("Y-m-d H:i:s", 
            time(date("H")-3, date("i"), date("s"), 
                date("n"), date("j"), date("Y")));
        /*
        OPzVolunTmp::where('TmpType', 'ZedDept')
            ->where('TmpDate', '<', $recentDate)
            ->delete();
        // First check for department temporarily reserved for this user
        $tmpReserve = OPzVolunTmp::where('TmpType', 'ZedDept')
            ->where('TmpUser', Auth::user()->id)
            ->first();
        if ($tmpReserve && isset($tmpReserve->TmpVal) && intVal($tmpReserve->TmpVal) > 0) {
            $nextRow = OPDepartments::where('DeptID', $tmpReserve->TmpVal)
                ->first();
            $this->v["nextDept"] = [ $nextRow->DeptID, $nextRow->DeptName, $nextRow->DeptSlug ];
        } else { // no department reserved yet, find best next choice...
        */
            $this->loadDeptPriorityRows();
            if (sizeof($this->v["deptPriorityRows"]) > 0) {
                $nextRow = $this->v["deptPriorityRows"][0];
                $this->v["nextDept"] = [
                    $nextRow->DeptID, 
                    $nextRow->DeptName, 
                    $nextRow->DeptSlug 
                ];
            } else {
                $nextRow = NULL;
                $qmen = [];
                $qBase = "SELECT `DeptID`, `DeptName`, `DeptSlug` "
                    . "FROM `OP_Departments` WHERE ";
                $qReserves = " AND `DeptID` NOT IN (SELECT `TmpVal` "
                    . "FROM `OP_zVolunTmp` WHERE "
                    . "`TmpType` LIKE 'ZedDept' AND "
                    . "`TmpUser` NOT LIKE '" . Auth::user()->id . "')";
                $qmen[] = $qBase . "(`DeptVerified` < '2015-01-01 00:00:00' "
                    . "OR `DeptVerified` IS NULL) " . $qReserves 
                    . " ORDER BY RAND()";
                $qmen[] = $qBase . "1 " . $qReserves . " ORDER BY `DeptVerified`";
                $qmen[] = $qBase . "1 ORDER BY RAND()"; // worst-case backup
                for ($i = 0; ($i < sizeof($qmen) && !$nextRow); $i++) {
                    $nextRow = DB::select( DB::raw( 
                        $qmen[$i]." LIMIT 1" ) );
                }
                $this->v["nextDept"] = [
                    $nextRow[0]->DeptID, 
                    str_replace('Department', 'Dept', $nextRow[0]->DeptName), 
                    $nextRow[0]->DeptSlug
                ];
                
                // Temporarily reserve this department for this user
                $newTmp = new OPzVolunTmp;
                $newTmp->TmpUser = Auth::user()->id;
                $newTmp->TmpDate = date("Y-m-d H:i:s");
                $newTmp->TmpType = 'ZedDept';
                $newTmp->TmpVal  = $this->v["nextDept"][0];
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
        $url = '/dashboard/volunteer/verify/' 
            . $this->v["nextDept"][2];
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
            $newDept = OPDepartments::where('DeptName', $deptName)
                ->where('DeptAddressState', $deptState)
                ->first();
            if ($newDept && isset($newDept->DeptSlug)) {
                $url = '/dashboard/volunteer/verify/' 
                    . $newDept->DeptSlug;
                return $this->redir($url);
            }
            $newDept = new OPDepartments;
            $newIA   = new OPOversight;
            $newIA->OverType           = $this->overWhichDefID('IA');
            $newDept->DeptName         = $deptName;
            $newDept->DeptStatus       = 1;
            $newDept->DeptUserID       = Auth::user()->id;
            $newDept->DeptAddressState 
                = (($deptState != 'US') ? $deptState : '');
            $newDept->DeptSlug
                = $deptState . '-' . Str::slug($deptName);
            $newDept->DeptType
                = (($deptState == 'US') ? 366 : 0);
            $newDept->save();
            $newIA->OverDeptID         = $newDept->DeptID;
            $newIA->OverType           = $this->overWhichDefID('IA');
            $newIA->OverUserID         = Auth::user()->id;
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
        $desc = 'Last Verified: ' . (($this->v["neverEdited"]) 
                ? 'Never' : date('n/j/y', strtotime(
                    $this->v["deptRow"]->DeptVerified))) 
            . ' <span class="mL20"><nobr>' 
            . intVal($this->v["editTots"]["online"]) 
            . '<span class="fPerc80 mL3 mR3">x</span> '
            . 'Online Research,</nobr></span> '
            . '<span class="mL20"><nobr>' 
            . intVal($this->v["editTots"]["callDept"]) 
            . '<span class="fPerc80 mL3 mR3">x</span> '
            . 'Department Calls,</nobr></span> '
            . '<span class="mL20"><nobr>' 
            . intVal($this->v["editTots"]["callIA"]) 
            . '<span class="fPerc80 mL3 mR3">x</span> '
            . 'Internal Affairs Calls</nobr></span>';
        $icons = (($this->v["neverEdited"]) ? '<span class="slGrey">' : '') 
            . intVal($this->v["editTots"]["online"]) 
            . '<i class="fa fa-laptop"></i>, ' 
            . intVal($this->v["editTots"]["callDept"]) 
            . '<i class="fa fa-phone"></i>, ' 
            . intVal($this->v["editTots"]["callIA"]) 
            . '<i class="fa fa-phone"></i>'
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
            = $this->v["userEdits"] = $this->v["userNames"] 
            = [];
        $this->v["deptRow"] = $this->sessData
            ->dataSets["Departments"][0];
        $this->v["deptSlug"] = $this->v["deptRow"]->DeptSlug;
        $this->v["user"] = Auth::user();
        $this->v["neverEdited"] = false;
        $this->v["recentEdits"] = '';
        $this->v["editTots"] = [
            "notes"    => 0, 
            "online"   => 0, 
            "callDept" => 0, 
            "callIA"   => 0 
        ];
        
        if (!isset($this->v["deptRow"]->DeptID) 
            || intVal($this->v["deptRow"]->DeptID) <= 0) {
            return $this->redir('/dashboard/volunteer');
        }
        
        $this->v["editsDept"] = OPZeditDepartments::where(
                'ZedDeptDeptID', $this->v["deptRow"]->DeptID)
            ->orderBy('ZedDeptDeptVerified', 'desc')
            ->get();
        if ($this->v["editsDept"]->isNotEmpty()) {
            foreach ($this->v["editsDept"] as $i => $edit) {
                $this->v["editsIA"][$i]  = OPZeditOversight::where(
                    'ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', $this->overWhichDefID('IA'))
                    ->first();
                $this->v["editsCiv"][$i] = OPZeditOversight::where(
                    'ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', $this->overWhichDefID('Civ'))
                    ->first();
                if ($this->v["editsIA"][$i]) {
                    if (trim($this->v["editsIA"][$i]
                        ->ZedOverNotes) != '') {
                        $this->v["editTots"]["notes"]++;
                    }
                    if (intVal($this->v["editsIA"][$i]
                        ->ZedOverOnlineResearch) == 1) {
                        $this->v["editTots"]["online"]++;
                    }
                    if (intVal($this->v["editsIA"][$i]
                        ->ZedOverMadeDeptCall) == 1) {
                        $this->v["editTots"]["callDept"]++;
                    }
                    if (intVal($this->v["editsIA"][$i]
                        ->ZedOverMadeIACall) == 1) {
                        $this->v["editTots"]["callIA"]++;
                    }
                }
                if (!isset($this->v["userNames"][$edit->ZedDeptUserID])) {
                    $this->v["userNames"][$edit->ZedDeptUserID] 
                        = $this->printUserLnk($edit->ZedDeptUserID);
                }
                if ($this->v["user"]->hasRole('administrator|staff')) {
                    $this->v["recentEdits"] .= view(
                        'vendor.openpolice.volun.admPrintDeptEdit', 
                        [
                            "user"     => $this
                                ->v["userNames"][$edit->ZedDeptUserID], 
                            "deptRow"  => $this->v["deptRow"], 
                            "deptEdit" => $edit, 
                            "iaEdit"   => $this->v["editsIA"][$i], 
                            "civEdit"  => $this->v["editsCiv"][$i],
                            "deptType" => $GLOBALS["SL"]->def
                                ->getVal('Department Types', $edit->DeptType)
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
            [
                "deptRow" => $this->sessData->dataSets["Departments"][0]
            ]
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
        $tbl = (($nID == 145) ? 'LinksComplaintDept' 
            : 'LinksComplimentDept');
        $fld = 'n' . $nID . 'fld';
        $newDeptID = -3;
        if (intVal($GLOBALS["SL"]->REQ->get($fld)) > 0) {
            $newDeptID = intVal($GLOBALS["SL"]->REQ->get($fld));
            $this->sessData->logDataSave(
                $nID, 
                'NEW', 
                -3, 
                $tbl, 
                $newDeptID
            );
        } elseif ($GLOBALS["SL"]->REQ->has('newDeptName') 
            && trim($GLOBALS["SL"]->REQ->newDeptName) != '') {
            $newDept = $this->newDeptAdd(
                $GLOBALS["SL"]->REQ->newDeptName, 
                $GLOBALS["SL"]->REQ->newDeptAddressState
            );
            $newDeptID = $newDept->DeptID;
            $logTxt = $tbl . ' - !New Department Added!';
            $this->sessData->logDataSave(
                $nID, 
                'NEW', 
                -3, 
                $logTxt, 
                $newDeptID
            );
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
        OPOversight::where('OverDeptID', $this->sessData->dataSets["Departments"][0]->DeptID)
            ->where('OverType', $this->overWhichDefID('IA'))
            ->update([
                'OverWaySubEmail' => (in_array('Email', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0)
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
                    'Oversight', 
                    'OverWaySubEmail', 
                    'update', 
                    ((in_array('Email', $ways) ) 
                        ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'Oversight', 
                    'OverWaySubVerbalPhone', 
                    'update', 
                    ((in_array('VerbalPhone', $ways) ) 
                        ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'Oversight', 
                    'OverWaySubPaperMail', 
                    'update', 
                    ((in_array('PaperMail', $ways) ) 
                        ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'Oversight', 
                    'OverWaySubPaperInPerson', 
                    'update', 
                    ((in_array('PaperInPerson', $ways) ) 
                        ? 1 : 0)
                );
            }
        }
        if (!$found) {
            $this->sessData->currSessData(
                $nID, 
                'Oversight', 
                'OverWaySubEmail', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'Oversight', 
                'OverWaySubVerbalPhone', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'Oversight', 
                'OverWaySubPaperMail', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'Oversight', 
                'OverWaySubPaperInPerson', 
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
                    'Oversight', 
                    'OverOfficialFormNotReq', 
                    'update', 
                    ((in_array('OfficialFormNotReq', $ways) ) 
                        ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'Oversight', 
                    'OverOfficialAnon', 
                    'update', 
                    ((in_array('OfficialAnon', $ways) ) 
                        ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'Oversight', 
                    'OverWaySubNotary', 
                    'update', 
                    ((in_array('Notary', $ways) ) 
                        ? 1 : 0)
                );
                $this->sessData->currSessData(
                    $nID, 
                    'Oversight', 
                    'OverSubmitDeadline', 
                    'update', 
                    ((in_array('TimeLimit', $ways) ) 
                        ? (($GLOBALS["SL"]->REQ->has('n1288fld')) 
                            ? $GLOBALS["SL"]->REQ->n1288fld : 0) 
                        : 0)
                );
            }
        }
        if (!$found) {
            $this->sessData->currSessData(
                $nID, 
                'Oversight', 
                'OverOfficialFormNotReq', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'Oversight', 
                'OverOfficialAnon', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'Oversight', 
                'OverWaySubNotary', 
                'update', 
                0
            );
            $this->sessData->currSessData(
                $nID, 
                'Oversight', 
                'OverSubmitDeadline', 
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
            'Departments', 
            'DeptVerified', 
            'update', 
            date("Y-m-d H:i:s")
        );
        $this->sessData->createTblExtendFlds(
            'Departments', 
            $this->coreID, 
            'Zedit_Departments', 
            [
                'ZedDeptDeptID'   => $this->coreID,
                'ZedDeptUserID'   => $this->v["uID"],
                'ZedDeptDuration' => (time()-intVal(
                    $GLOBALS["SL"]->REQ->formLoaded)),
                'ZedDeptDeptID'   => $this->coreID,
            ]
        );
        $over = $this->getOverRow('IA');
        $newResearch = [];
        if ($GLOBALS["SL"]->REQ->has('n1329fld') 
            && is_array($GLOBALS["SL"]->REQ->get('n1329fld'))) {
            $newResearch = $GLOBALS["SL"]->REQ->get('n1329fld');
        }
        $deptID = $this->sessData
            ->dataSets['Zedit_Departments'][0]->getKey();
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
            'Oversight', 
            $over->getKey(), 
            'Zedit_Oversight', 
            [
                'ZedOverZedDeptID'      => $deptID,
                'ZedOverOnlineResearch' => $resOnline,
                'ZedOverMadeDeptCall'   => $resCall,
                'ZedOverMadeIACall'     => $resCallIA,
                'ZedOverNotes'          => $notes
            ]
        );
        $over = $this->getOverRow('Civ');
        if ($over && isset($over->OverID)) {
            
            $this->sessData->createTblExtendFlds(
                'Oversight', 
                $over->getKey(), 
                'Zedit_Oversight', 
                [
                    'ZedOverZedDeptID' => $deptID
                ]
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
                $redir = '/dashboard/start-' . $this->sessData
                        ->dataSets["Departments"][0]->DeptID 
                    . '/volunteers-research-departments';
                $msg = 'Saving Changes...';
            } elseif ($next == 'dept-page') {
                $redir = '/dept/' . $this->sessData
                        ->dataSets["Departments"][0]->DeptSlug;
                $msg = 'Saving Changes...';
            } elseif ($next == 'another') {
                session()->forget('sessID36');
                session()->forget('coreID36');
                $this->getNextDept();
                $redir = '/dashboard/start-' 
                    . $this->v["nextDept"][0] 
                    . '/volunteers-research-departments';
                $msg = 'To The Next Department...';
            }
        }
        $GLOBALS["SL"]->pageJAVA .= 'setTimeout("'
            . 'window.location=\'' . $redir . '\'", 10); ';
        return '<center><h2 class="slBlueDark">' . $msg . '</h2>' 
            . $GLOBALS["SL"]->sysOpts["spinner-code"]
            . '</center><style> #nodeSubBtns, #sessMgmt '
            . '{ display: none; } </style>';
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
        $redir = '/dashboard/start-' . $this->v["nextDept"][0] 
            . '/volunteers-research-departments';
        $GLOBALS["SL"]->pageJAVA .= 'setTimeout("'
            . 'window.location=\'' . $redir . '\'", 10); ';
        return '<center><h2 class="slBlueDark">'
            . 'To The Next Department...</h2>' 
            . $GLOBALS["SL"]->sysOpts["spinner-code"]
            . '</center><style> #nodeSubBtns, #sessMgmt '
            . '{ display: none; } </style>';
    }
    
    /**
     * Prints a Google map of department accessibility scores.
     *
     * @param  int $nID
     * @return string
     */
    protected function publicDeptAccessMap($nID = -3)
    {
        if ($GLOBALS["SL"]->REQ->has('state') 
            && trim($GLOBALS["SL"]->REQ->get('state')) != '') {
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
            $ret = '<br /><br /><br /><table border=0 '
                . 'cellpadding=0 cellspacing=5 class="m20" ><tr>';
            foreach ($colors as $i => $c) {
                $ret .= '<td><img border=0 src="'
                    . '/survloop/uploads/template-map-marker.png" '
                    . 'style="width: 80px; background: ' . $c . ';"'
                    . ' alt="Accessibility Score ' 
                    . (($i == 0) ? 0 : $i . '0') 
                    . ' out of 10"><br /><br />' 
                    . $c . '</td>';
            }
            $ret .= '</tr></table><table border=0 '
                . 'cellpadding=0 cellspacing=5 class="m20" ><tr>';
            foreach ($colors as $i => $c) {
                $ret .= '<td><img border=0 src="'
                    . '/openpolice/uploads/map-marker-redblue-' 
                    . $i . '.png" alt="Accessibility Score ' 
                    . (($i == 0) ? 0 : $i . '0') 
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
                if ($cnt < $limit && (!isset($dept->DeptAddressLat) 
                    || intVal($dept->DeptAddressLat) == 0
                    || !isset($dept->DeptAddressLng) 
                    || intVal($dept->DeptAddressLng) == 0)) {
                    $addy = $GLOBALS["SL"]->printRowAddy($dept, 'Dept');
                    if (trim($addy) != '') {
                        list(
                            $this->v["deptScores"]
                                ->scoreDepts[$i]->DeptAddressLat, 
                            $this->v["deptScores"]
                                ->scoreDepts[$i]->DeptAddressLng
                        ) = $GLOBALS["SL"]->states->getLatLng($addy);
                        $this->v["deptScores"]->scoreDepts[$i]->save();
                        $cnt++;
                    }
                }
                if (isset($dept->DeptAddressLat) 
                    && $dept->DeptAddressLat != 0 
                    && isset($dept->DeptAddressLng) 
                    && $dept->DeptAddressLng != 0 
                    && $dept->DeptScoreOpenness > 0) {
                    $grad = 'RBgradient' 
                        . round($dept->DeptScoreOpenness/10);
                    $name = $dept->DeptName . ': ' 
                        . $dept->DeptScoreOpenness;
                    $url = '/ajax/dept-kml-desc?deptID=' 
                        . $dept->DeptID;
                    $GLOBALS["SL"]->states->addMapMarker(
                        $dept->DeptAddressLat, 
                        $dept->DeptAddressLng, 
                        $grad, 
                        $name, 
                        '',
                        $url
                    );
                }
            }
            for ($g = 0; $g < 11; $g++) {
                $url = $GLOBALS["SL"]->sysOpts["app-url"] 
                    . '/openpolice/uploads/map-marker-redblue-' 
                    . $g . '.png';
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
            } elseif ($nID == 2013 
                && $GLOBALS["SL"]->REQ->has('test')) {
                $GLOBALS["SL"]->pageAJAX .= '$("#map' . $nID 
                    . 'ajax").load("/ajax/dept-kml-desc'
                    . '?deptID=13668");';
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
        return view(
            'vendor.openpolice.inc-map-dept-access-legend'
        )->render();
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
            $this->v["deptScores"]->loadAllDepts(
                $this->searcher->searchFilts
            );
        }
        return view(
            'vendor.openpolice.nodes.1968-accss-grades-title-desc', 
            [
                "nID"   => $nID,
                "state" => (($GLOBALS["SL"]->REQ->has('state')) 
                    ? $GLOBALS["SL"]->REQ->state : '')
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
        if (!$GLOBALS["SL"]->REQ->has('state') 
            || trim($GLOBALS["SL"]->REQ->get('state')) == '') {
            $param = 'onscroll="if (typeof bodyOnScroll '
                . '=== \'function\') bodyOnScroll();"';
            $GLOBALS["SL"]->addBodyParams($param);
        }
        return $GLOBALS["SL"]->extractStyle(
            $this->v["deptScores"]->printTotsBars(), 
            $nID
        );
        /*
        $statGrades = new SurvStatsGraph;
        $statGrades->addFilt('grade', 'Grade', 
            ['A', 'B', 'C', 'D', 'F'],
            ['A', 'B', 'C', 'D', 'F']); // a
        $statGrades->addDataType('cnt', 'Departments', ' Departments'); // a
        $statGrades->loadMap();
        if ($this->v["deptScores"]->scoreDepts->isNotEmpty()) {
            foreach ($this->v["deptScores"]->scoreDepts as $i => $dept) {
                $grade = trim($GLOBALS["SL"]->calcGrade($dept->DeptScoreOpenness));
                if (strlen($grade) > 1) $grade = substr($grade, 0, 1);
                $statGrades->resetRecFilt();
                $statGrades->addRecFilt('grade', $grade, $dept->DeptID);
                $statGrades->addRecDat('cnt', 1, $dept->DeptID);
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
        if ($this->v["uID"] > 0 && $this->v["user"]
            ->hasRole('administrator|databaser|staff|partner|volunteer')) {
            $url = '/dashboard/start-' . $this->v["deptID"] 
                . '/volunteers-research-departments';
            $GLOBALS["SL"]->addSideNavItem(
                'Edit Department', 
                $url
            );
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
                "d" => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]]
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
                "d" => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]]
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
        $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID 
            . 'ajaxLoad").load("/record-prevs/1?d=' 
            . $this->v["deptID"] . '&limit=20");' . "\n";
        return view(
            'vendor.openpolice.nodes.2715-dept-page-recent-reports', 
            [
                "nID" => $nID,
                "d" => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]]
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
                "d" => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]]
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
                "d" => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]]
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
        $this->v["fltQry"] .= " c.`ComStatus` IN ("
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
        $deptRow = OPDepartments::where('DeptSlug', $deptSlug)
            ->first();
        if ($deptRow && isset($deptRow->DeptID)) {
            $deptID = $deptRow->DeptID;
            $request->d = $deptRow->DeptID;
        }
        $url = '/dept/' . $deptSlug;
        $this->loadPageVariation($request, 1, 25, $url);
        if ($deptID > 0) {
            $this->v["deptID"] = $deptRow->DeptID;
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
        $deptRow = OPDepartments::where('DeptSlug', $deptSlug)
            ->first();
        if ($deptRow && isset($deptRow->DeptID)) {
            session()->put('opcDeptID', $deptRow->DeptID);
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
        $civDef = $GLOBALS["SL"]->def->getID(
            'Investigative Agency Types', 
            'Civilian Oversight'
        );
        if (!isset($this->v["currOverRow"])) {
            $this->v["currOverRow"] = NULL;
            $overs = OPOversight::where('OverDeptID', $deptID)
                ->orderBy('created_at', 'asc')
                ->get();
            if ($overs->isNotEmpty()) {
                if ($overs->count() == 1) {
                    $this->v["currOverRow"] = $overs[0];
                } else {
                    foreach ($overs as $i => $ovr) {
                        if ($ovr && isset($ovr->OverType) 
                            && $ovr->OverType == $civDef) {
                            $this->v["currOverRow"] = $ovr;
                        }
                    }
                }
            }
        }
        if ($this->v["currOverRow"] 
            && isset($this->v["currOverRow"]->OverID)) {
            if ($this->treeID == 5) {
                $this->v["currOverUpdateRow"] 
                    = OPLinksComplimentOversight::where(
                        'LnkCompliOverComplimentID', $cid)
                    ->where('LnkCompliOverOverID', 
                        $this->v["currOverRow"]->OverID)
                    ->first();
                if (!$this->v["currOverUpdateRow"]) {
                    $this->v["currOverUpdateRow"] 
                        = new OPLinksComplimentOversight;
                    $this->v["currOverUpdateRow"]
                        ->LnkCompliOverComplimentID = $cid;
                    $this->v["currOverUpdateRow"]
                        ->LnkCompliOverDeptID = $deptID;
                    $this->v["currOverUpdateRow"]
                        ->LnkCompliOverOverID 
                        = $this->v["currOverRow"]->OverID;
                    $this->v["currOverUpdateRow"]->save();
                }
            } else {
                $this->v["currOverUpdateRow"] 
                    = OPLinksComplaintOversight::where(
                        'LnkComOverComplaintID', $cid)
                    ->where('LnkComOverOverID', 
                        $this->v["currOverRow"]->OverID)
                    ->first();
                if (!$this->v["currOverUpdateRow"]) {
                    $this->v["currOverUpdateRow"] 
                        = new OPLinksComplaintOversight;
                    $this->v["currOverUpdateRow"]
                        ->LnkComOverComplaintID = $cid;
                    $this->v["currOverUpdateRow"]
                        ->LnkComOverDeptID = $deptID;
                    $this->v["currOverUpdateRow"]
                        ->LnkComOverOverID 
                        = $this->v["currOverRow"]->OverID;
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
    public function logOverUpDate($cid, $deptID, $type = 'Submitted', $row = [])
    {
        if ($this->treeID == 5) {
            if (!$row || !isset($row->LnkCompliOverID)) {
                $row = $this->getOverUpdateRow($cid, $deptID);
            }
            $row->{ 'LnkCompliOver' . $type } = date("Y-m-d H:i:s");
            $row->save();
        } else {
            if (!$row || !isset($row->LnkComOverID)) {
                $row = $this->getOverUpdateRow($cid, $deptID);
            }
            $row->{ 'LnkComOver' . $type } = date("Y-m-d H:i:s");
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
        return (($which == 'IA') 
            ? 'Internal Affairs' : 'Civilian Oversight');
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
            'Oversight', 
            [
                'OverType' => $this->overWhichDefID($which)
            ],
            true
        );
        if (sizeof($rows) > 0) {
            $this->v["overRow" . $which] = $rows[0];
            return $this->v["overRow" . $which];
        }
        return [];
    }
    
}