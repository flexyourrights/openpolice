<?php
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
use OpenPolice\Controllers\DepartmentScores;
use OpenPolice\Controllers\OpenListing;

class OpenDepts extends OpenListing
{
    protected function printDeptSearch($nID)
    {
        $this->nextBtnOverride = 'Find & Select A Department';
        $searchSuggest = [];
        $deptCitys = OPDepartments::select('DeptAddressCity')
            ->distinct()
            ->where('DeptAddressState', $this->sessData->dataSets["Incidents"][0]->IncAddressState)
            ->get();
        if ($deptCitys->isNotEmpty()) {
            foreach ($deptCitys as $dept) {
                if (!in_array($dept->DeptAddressCity, $searchSuggest)) {
                    $searchSuggest[] = json_encode($dept->DeptAddressCity);
                }
            }
        }
        $deptCounties = OPDepartments::select('DeptAddressCounty')
            ->distinct()
            ->where('DeptAddressState', $this->sessData->dataSets["Incidents"][0]->IncAddressState)
            ->get();
        if ($deptCounties->isNotEmpty()) {
            foreach ($deptCounties as $dept) {
                if (!in_array($dept->DeptAddressCounty, $searchSuggest)) {
                    $searchSuggest[] = json_encode($dept->DeptAddressCounty);
                }
            }
        }
        $deptFed = OPDepartments::select('DeptName')->where('DeptType', 366)
            ->get();
        if ($deptFed->isNotEmpty()) {
            foreach ($deptFed as $dept) {
                if (!in_array($dept->DeptName, $searchSuggest)) {
                    $searchSuggest[] = json_encode($dept->DeptName);
                }
            }
        }
        $GLOBALS["SL"]->pageAJAX .= view('vendor.openpolice.nodes.145-ajax-dept-search', [
            "nID"           => $nID,
            "searchSuggest" => $searchSuggest
        ])->render();
        $GLOBALS["SL"]->loadStates();
        return view('vendor.openpolice.nodes.145-dept-search', [ 
            "nID"                => $nID,
            "IncAddressCity"     => $this->sessData->dataSets["Incidents"][0]->IncAddressCity, 
            "stateDropstateDrop" => $GLOBALS["SL"]->states->stateDrop(
                $this->sessData->dataSets["Incidents"][0]->IncAddressState, true) 
            ])->render();
    }
    
    protected function getNextDept()
    {
        $this->v["nextDept"] = array(0, '', '');
        $recentDate = date("Y-m-d H:i:s", time(date("H")-3, date("i"), date("s"), date("n"), date("j"), date("Y")));
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
                $this->v["nextDept"] = [ $nextRow->DeptID, $nextRow->DeptName, $nextRow->DeptSlug ];
            } else {
                $nextRow = NULL;
                $qmen = [];
                $qBase = "SELECT `DeptID`, `DeptName`, `DeptSlug` FROM `OP_Departments` WHERE ";
                $qReserves = " AND `DeptID` NOT IN (SELECT `TmpVal` FROM `OP_zVolunTmp` WHERE "
                    . "`TmpType` LIKE 'ZedDept' AND `TmpUser` NOT LIKE '" . Auth::user()->id . "')";
                $qmen[] = $qBase . "(`DeptVerified` < '2015-01-01 00:00:00' OR `DeptVerified` IS NULL) " 
                    . $qReserves . " ORDER BY RAND()";
                $qmen[] = $qBase . "1 " . $qReserves . " ORDER BY `DeptVerified`";
                $qmen[] = $qBase . "1 ORDER BY RAND()"; // worst-case backup
                for ($i = 0; ($i < sizeof($qmen) && !$nextRow); $i++) {
                    $nextRow = DB::select( DB::raw( $qmen[$i]." LIMIT 1" ) );
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
    
    public function nextDept()
    {
        $this->getNextDept();
        return $this->redir('/dashboard/volunteer/verify/' . $this->v["nextDept"][2]);
    }
    
    public function newDeptAdd($deptName = '', $deptState = '')
    {
        if (trim($deptName) != '' && trim($deptState) != '') {
            $newDept = OPDepartments::where('DeptName', $deptName)
                ->where('DeptAddressState', $deptState)
                ->first();
            if ($newDept && isset($newDept->DeptSlug)) {
                return $this->redir('/dashboard/volunteer/verify/'.$newDept->DeptSlug);
            }
            $newDept = new OPDepartments;
            $newIA   = new OPOversight;
            $newIA->OverType           = $this->overWhichDefID('IA');
            $newDept->DeptName         = $deptName;
            $newDept->DeptAddressState = (($deptState != 'US') ? $deptState : '');
            $newDept->DeptSlug         = $deptState . '-' . Str::slug($deptName);
            $newDept->DeptType         = (($deptState == 'US') ? 366 : 0);
            $newDept->DeptStatus       = 1;
            $newDept->DeptUserID       = Auth::user()->id;
            $newDept->save();
            $newIA->OverDeptID         = $newDept->DeptID;
            $newIA->OverType           = $this->overWhichDefID('IA');
            $newIA->OverUserID         = Auth::user()->id;
            $newIA->save();
            return $newDept;
        }
        return [];
    }
    
    public function loadDeptEditsSummary()
    {
        $this->v["editsSummary"] = [
            '<b class="mL20">Last Verified: ' 
            . (($this->v["neverEdited"]) ? 'Never' : date('n/j/y', strtotime($this->v["deptRow"]->DeptVerified))) 
            . '</b> <span class="mL20"><nobr>' . intVal($this->v["editTots"]["online"]) 
            . '<span class="fPerc80">x</span><i class="fa fa-laptop"></i> Online Research,</nobr></span> '
            . '<span class="mL20"><nobr>' . intVal($this->v["editTots"]["callDept"]) 
            . '<span class="fPerc80">x</span><i class="fa fa-phone"></i> Department Calls,</nobr></span> '
            . '<span class="mL20"><nobr>' . intVal($this->v["editTots"]["callIA"]) 
            . '<span class="fPerc80">x</span><i class="fa fa-phone"></i> Internal Affairs Calls</nobr></span>',
            
            (($this->v["neverEdited"]) ? '<span class="slGrey">' : '') 
            . intVal($this->v["editTots"]["online"]) . '<i class="fa fa-laptop"></i>, ' 
            . intVal($this->v["editTots"]["callDept"]) . '<i class="fa fa-phone"></i>, ' 
            . intVal($this->v["editTots"]["callIA"]) . '<i class="fa fa-phone"></i>'
            . (($this->v["neverEdited"]) ? '</span>' : '')
            ];
        return true;
    }
    
    protected function genDeptAdmTopMenu()
    {
        $lnks = [
            [ 'deptContact',   'Contact Info' ],
            [ 'deptWeb',       'Web Presence' ],
            [ 'deptIA',        'Internal Affairs' ],
            [ 'deptCiv',       'Civilian Oversight' ],
            [ 'deptSave',      '<i class="fa fa-floppy-o mR5" aria-hidden="true"></i> Save Changes' ],
            [ 'deptEdits',     'Dept Edit History' ],
            [ 'deptChecklist', 'Checklist & Scripts' ]
            ];
        foreach ($lnks as $lnk) {
            $GLOBALS["SL"]->pageJAVA .= 'addHshoo("#' . $lnk[0] . '"); ';
        }
        return view('vendor.openpolice.volun.volun-dept-edit-adm-menu', [
            "deptRow" => $this->v["deptRow"],
            "lnks"    => $lnks
            ])->render();
    }
    
    protected function printDeptEditHeader()
    {
        $this->v["deptRow"]     = $this->sessData->dataSets["Departments"][0];
        $this->v["deptSlug"]    = $this->v["deptRow"]->DeptSlug;
        $this->v["editsIA"]     = $this->v["editsCiv"] = $this->v["userEdits"] = $this->v["userNames"] = [];
        $this->v["editTots"]    = ["notes" => 0, "online" => 0, "callDept" => 0, "callIA" => 0];
        $this->v["user"]        = Auth::user();
        $this->v["neverEdited"] = false;
        $this->v["recentEdits"] = '';
        
        if (!isset($this->v["deptRow"]->DeptID) || intVal($this->v["deptRow"]->DeptID) <= 0) {
            return $this->redir('/dashboard/volunteer');
        }
        
        $this->v["editsDept"] = OPZeditDepartments::where('ZedDeptDeptID', $this->v["deptRow"]->DeptID)
            ->orderBy('ZedDeptDeptVerified', 'desc')
            ->get();
        if ($this->v["editsDept"]->isNotEmpty()) {
            foreach ($this->v["editsDept"] as $i => $edit) {
                $this->v["editsIA"][$i]  = OPZeditOversight::where('ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', $this->overWhichDefID('IA'))
                    ->first();
                $this->v["editsCiv"][$i] = OPZeditOversight::where('ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', $this->overWhichDefID('Civ'))
                    ->first();
                if ($this->v["editsIA"][$i]) {
                    if (trim($this->v["editsIA"][$i]->ZedOverNotes) != '')           $this->v["editTots"]["notes"]++;
                    if (intVal($this->v["editsIA"][$i]->ZedOverOnlineResearch) == 1) $this->v["editTots"]["online"]++;
                    if (intVal($this->v["editsIA"][$i]->ZedOverMadeDeptCall) == 1)   $this->v["editTots"]["callDept"]++;
                    if (intVal($this->v["editsIA"][$i]->ZedOverMadeIACall) == 1)     $this->v["editTots"]["callIA"]++;
                }
                if (!isset($this->v["userNames"][$edit->ZedDeptUserID])) {
                    $this->v["userNames"][$edit->ZedDeptUserID] = $this->printUserLnk($edit->ZedDeptUserID);
                }
                if ($this->v["user"]->hasRole('administrator|staff')) {
                    $this->v["recentEdits"] .= view('vendor.openpolice.volun.admPrintDeptEdit', [
                        "user"     => $this->v["userNames"][$edit->ZedDeptUserID], 
                        "deptRow"  => $this->v["deptRow"], 
                        "deptEdit" => $edit, 
                        "deptType" => $GLOBALS["SL"]->def->getVal('Department Types', $edit->DeptType),
                        "iaEdit"   => $this->v["editsIA"][$i], 
                        "civEdit"  => $this->v["editsCiv"][$i]
                    ])->render();
                }
            }
        } else {
            $this->v["neverEdited"] = true;
        }
        $this->loadDeptEditsSummary();
        $GLOBALS["SL"]->loadStates();
        $GLOBALS["SL"]->x["admMenuCustom"] = $this->genDeptAdmTopMenu();
        if (!isset($this->v["deptScores"])) {
            $this->v["deptScores"] = new DepartmentScores;
        }
        return view('vendor.openpolice.nodes.1225-volun-dept-edit-header', $this->v)->render();
    }
    
    protected function printDeptEditHeader2()
    {
        return view('vendor.openpolice.nodes.2162-volun-dept-edit-header2', 
            [ "deptRow" => $this->sessData->dataSets["Departments"][0] ])->render();
    }
    
    protected function printDeptEditContact($nID)
    {
        $this->getOverRow('IA');
        $this->getOverRow('Civ');
        $which = [ 'IA', 1305 ];
        $hasCiv = false;
        if ($nID == 1229) {
            if (isset($this->v["overRowCiv"]->OverAgncName) && trim($this->v["overRowCiv"]->OverAgncName) != '') {
                $hasCiv = true;
            }
            $which = [ 'Civ', 1267 ];
        }
        $overRow = $this->getOverRow($which[0]);
        $hasC = ((isset($overRow->OverNameFirst) && trim($overRow->OverNameFirst) != '')
            || (isset($overRow->OverNameLast) && trim($overRow->OverNameLast) != '') 
            || (isset($overRow->OverTitle) && trim($overRow->OverTitle) != ''));
        return view('vendor.openpolice.volun.volun-dept-edit-ia-contact', [
            "type"   => $this->overWhichEng($which[0]),
            "whch"   => $which[0],
            "n"      => $which[1],
            "hasCiv" => $hasCiv,
            "hasC"   => $hasC
            ])->render();
    }
    
    protected function saveNewDept($nID)
    {
        $newDeptID = -3;
        if (intVal($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld')) > 0) {
            $newDeptID = intVal($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'));
            $this->sessData->logDataSave($nID, 'NEW', -3, 'ComplaintDeptLinks', 
                $GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'));
        } elseif ($GLOBALS["SL"]->REQ->has('newDeptName') && trim($GLOBALS["SL"]->REQ->newDeptName) != '') {
            $newDept = $this->newDeptAdd($GLOBALS["SL"]->REQ->newDeptName, 
                $GLOBALS["SL"]->REQ->newDeptAddressState);
            $newDeptID = $newDept->DeptID;
            $logTxt = 'ComplaintDeptLinks - !New Department Added!';
            $this->sessData->logDataSave($nID, 'NEW', -3, $logTxt, $newDeptID);
        }
        if ($newDeptID > 0) $this->chkDeptLinks($newDeptID);
        return true;   
    }
    
    protected function saveInitDeptOversight($nID)
    {
//echo '<br /><br /><br />checking 1229: ' . (($GLOBALS["SL"]->REQ->has('n1341fld')) ? 'has name' : 'no name') . '<br />';
        if ($GLOBALS["SL"]->REQ->has('n1341fld') && trim($GLOBALS["SL"]->REQ->n1341fld) != ''
            && sizeof($this->sessData->dataSets['Oversight']) == 1) {
            $new = $this->sessData->newDataRecord('Oversight', 'OverDeptID', 
                $this->sessData->dataSets['Departments'][0]->DeptID, true);
            $new->OverType = 302;
            $new->save();
            $this->sessData->refreshDataSets();
        }
        return false;
    }

    protected function saveDeptSubWays1($nID)
    {
        /*
        OPOversight::where('OverDeptID', $this->sessData->dataSets["Departments"][0]->DeptID)
            ->where('OverType', $this->overWhichDefID('IA'))
            ->update([
                'OverWaySubEmail' => (in_array('Email', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0)
            ]);
        */
        if ($GLOBALS["SL"]->REQ->has('n1285fld') && is_array($GLOBALS["SL"]->REQ->n1285fld)
            && is_array($GLOBALS["SL"]->REQ->n1285fld) && sizeof($GLOBALS["SL"]->REQ->n1285fld) > 0) {
            $this->sessData->currSessData($nID, 'Oversight', 'OverWaySubEmail', 'update', 
                ((in_array('Email', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0));
            $this->sessData->currSessData($nID, 'Oversight', 'OverWaySubVerbalPhone', 'update', 
                ((in_array('VerbalPhone', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0));
            $this->sessData->currSessData($nID, 'Oversight', 'OverWaySubPaperMail', 'update', 
                ((in_array('PaperMail', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0));
            $this->sessData->currSessData($nID, 'Oversight', 'OverWaySubPaperInPerson', 'update', 
                ((in_array('PaperInPerson', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0));
        }
        return false;
    }
    
    protected function saveDeptSubWays2($nID)
    {
        if ($GLOBALS["SL"]->REQ->has('n1287fld') && is_array($GLOBALS["SL"]->REQ->n1287fld)
            && sizeof($GLOBALS["SL"]->REQ->n1287fld) > 0) {
            $this->sessData->currSessData($nID, 'Oversight', 'OverOfficialFormNotReq', 'update', 
                ((in_array('OfficialFormNotReq', $GLOBALS["SL"]->REQ->n1287fld) ) ? 1 : 0));
            $this->sessData->currSessData($nID, 'Oversight', 'OverOfficialAnon', 'update', 
                ((in_array('OfficialAnon', $GLOBALS["SL"]->REQ->n1287fld) ) ? 1 : 0));
            $this->sessData->currSessData($nID, 'Oversight', 'OverWaySubNotary', 'update', 
                ((in_array('Notary', $GLOBALS["SL"]->REQ->n1287fld) ) ? 1 : 0));
            $this->sessData->currSessData($nID, 'Oversight', 'OverSubmitDeadline', 'update', 
                ((in_array('TimeLimit', $GLOBALS["SL"]->REQ->n1287fld) ) 
                    ? (($GLOBALS["SL"]->REQ->has('n1288fld')) ? $GLOBALS["SL"]->REQ->n1288fld : 0) : 0));
        }
        return false;
    }
    
    protected function saveEditLog($nID)
    {
        if ($GLOBALS["SL"]->REQ->get('step') != 'next') return true;
        if ($GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') && is_array($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'))
            && sizeof($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld')) > 0) {
            if (in_array('IACall', $GLOBALS["SL"]->REQ->n1329fld)) {
                $this->sessData->currSessData($nID, 'Departments', 'DeptVerified', 'update', date("Y-m-d H:i:s"));
            }
        }
        $this->sessData->createTblExtendFlds('Departments', $this->coreID, 'Zedit_Departments', [
            'ZedDeptDeptID'   => $this->coreID,
            'ZedDeptUserID'   => $this->v["uID"],
            'ZedDeptDuration' => (time()-intVal($GLOBALS["SL"]->REQ->formLoaded)),
            'ZedDeptDeptID'   => $this->coreID,
            ]);
        $over = $this->getOverRow('IA');
        $this->sessData->createTblExtendFlds('Oversight', $over->getKey(), 'Zedit_Oversight', [
            'ZedOverZedDeptID'      => $this->sessData->dataSets['Zedit_Departments'][0]->getKey(),
            'ZedOverOnlineResearch' => (($GLOBALS["SL"]->REQ->has('n1329fld') 
                && in_array('Online', $GLOBALS["SL"]->REQ->n1329fld)) ? 0 : 1),
            'ZedOverMadeDeptCall'   => (($GLOBALS["SL"]->REQ->has('n1329fld') 
                && in_array('DeptCall', $GLOBALS["SL"]->REQ->n1329fld)) ? 0 : 1),
            'ZedOverMadeIACall'     => (($GLOBALS["SL"]->REQ->has('n1329fld') 
                && in_array('IACall', $GLOBALS["SL"]->REQ->n1329fld)) ? 0 : 1),
            'ZedOverNotes'          => (($GLOBALS["SL"]->REQ->has('n1334fld')) ? $GLOBALS["SL"]->REQ->n1334fld : '')
            ]);
        $over = $this->getOverRow('Civ');
        if ($over && isset($over->OverID)) {
            $this->sessData->createTblExtendFlds('Oversight', $over->getKey(), 'Zedit_Oversight', [
                'ZedOverZedDeptID'      => $this->sessData->dataSets['Zedit_Departments'][0]->getKey()
                ]);
        }
        return false;
    }
    
    protected function redirAfterDeptEdit()
    {
        $redir = '/dash/volunteer';
        $msg = 'Back To The Department List...';
        if ($GLOBALS["SL"]->REQ->has('n1335fld')) {
            if (trim($GLOBALS["SL"]->REQ->get('n1335fld')) == 'again') {
                $redir = '/dashboard/start-' . $this->sessData->dataSets["Departments"][0]->DeptID 
                    . '/volunteers-research-departments';
                $msg = 'Saving Changes...';
            } elseif (trim($GLOBALS["SL"]->REQ->get('n1335fld')) == 'another') {
                session()->forget('sessID36');
                session()->forget('coreID36');
                $this->getNextDept();
                $redir = '/dashboard/start-' . $this->v["nextDept"][0] . '/volunteers-research-departments';
                $msg = 'To The Next Department...';
            }
        }
        $GLOBALS["SL"]->pageJAVA .= 'setTimeout("window.location=\'' . $redir . '\'", 10); ';
        return '<center><h2 class="slBlueDark">' . $msg . '</h2>' . $GLOBALS["SL"]->sysOpts["spinner-code"]
            . '</center><style> #nodeSubBtns, #sessMgmt { display: none; } </style>';
    }

    protected function redirNextDeptEdit()
    {
        session()->forget('sessID36');
        session()->forget('coreID36');
        $this->getNextDept();
        $redir = '/dashboard/start-' . $this->v["nextDept"][0] . '/volunteers-research-departments';
        $GLOBALS["SL"]->pageJAVA .= 'setTimeout("window.location=\'' . $redir . '\'", 10); ';
        return '<center><h2 class="slBlueDark">To The Next Department...</h2>' . $GLOBALS["SL"]->sysOpts["spinner-code"]
            . '</center><style> #nodeSubBtns, #sessMgmt { display: none; } </style>';
    }
    
    protected function publicDeptAccessMap($nID = -3)
    {
        if ($GLOBALS["SL"]->REQ->has('state') && trim($GLOBALS["SL"]->REQ->get('state')) != '') {
            return '<!-- no state map yet -->';
        }
        $ret = '';
        if ($GLOBALS["SL"]->REQ->has('colorMarker')) {
            $colors = [];
            for ($i = 0; $i < 5; $i++) {
                $colors[$i] = $GLOBALS["SL"]->printColorFadeHex($i/7, '#EC2327', '#FFFFFF');
            }
                
            for ($i = 5; $i < 11; $i++) {
                $colors[$i] = $GLOBALS["SL"]->printColorFadeHex((11-$i)/7, '#2B3493', '#FFFFFF');
            }
            echo '<br /><br /><br /><table border=0 cellpadding=0 cellspacing=5 class="m20" ><tr>';
            foreach ($colors as $i => $c) {
                echo '<td><img src="/survloop/uploads/template-map-marker.png" border=0 '
                    . 'style="width: 80px; background: ' . $c . ';"
                    alt="Accessibility Score ' . (($i == 0) ? 0 : $i . '0') . ' out of 10"><br /><br />' . $c . '</td>';
            }
            echo '</tr></table><table border=0 cellpadding=0 cellspacing=5 class="m20" ><tr>';
            foreach ($colors as $i => $c) {
                echo '<td><img src="/openpolice/uploads/map-marker-redblue-' . $i . '.png" border=0
                    alt="Accessibility Score ' . (($i == 0) ? 0 : $i . '0') . ' out of 10"></td>';
            }
            echo '</tr></table>';
        }
        
        $this->initSearcher();
        $this->searcher->getSearchFilts();
        $GLOBALS["SL"]->loadStates();
        if (!isset($this->v["deptScores"])) {
            $this->v["deptScores"] = new DepartmentScores;
            $this->v["deptScores"]->loadAllDepts($this->searcher->searchOpts);
        }
        if ($GLOBALS["SL"]->REQ->has('state') && trim($GLOBALS["SL"]->REQ->get('state')) != '') {
            $ret .= '<!-- not yet for state filter -->';
        } else {
            $cnt = 0;
            $limit = 10;
//echo '<pre>'; print_r($this->v["deptScores"]->scoreDepts); echo '</pre>';
            for ($i = sizeof($this->v["deptScores"]->scoreDepts)-1; $i >= 0; $i--) {
                $dept = $this->v["deptScores"]->scoreDepts[$i];
//echo $dept->DeptID . ' - ' . $GLOBALS["SL"]->printRowAddy($dept, 'Dept') . ' - ' . $dept->DeptAddressLat . ', ' . $dept->DeptAddressLng . '<br />';
                if ($cnt < $limit && !isset($dept->DeptAddressLat) || intVal($dept->DeptAddressLat) == 0) {
                    $addy = $GLOBALS["SL"]->printRowAddy($dept, 'Dept');
                    if (trim($addy) != '') {
                        list($this->v["deptScores"]->scoreDepts[$i]->DeptAddressLat, 
                            $this->v["deptScores"]->scoreDepts[$i]->DeptAddressLng) 
                            = $GLOBALS["SL"]->states->getLatLng($addy);
                        $this->v["deptScores"]->scoreDepts[$i]->save();
                        $cnt++;
                    }
                }
                if (isset($dept->DeptAddressLat) && $dept->DeptAddressLat != 0 && isset($dept->DeptAddressLng) 
                    && $dept->DeptAddressLng != 0 && $dept->DeptScoreOpenness > 0) {
                    $GLOBALS["SL"]->states->addMapMarker($dept->DeptAddressLat, $dept->DeptAddressLng, 
                        'RBgradient' . round($dept->DeptScoreOpenness/10), 
                        $dept->DeptName . ': ' . $dept->DeptScoreOpenness, '',
                        '/ajax/dept-kml-desc?deptID=' . $dept->DeptID);
                }
            }
            for ($g = 0; $g < 11; $g++) {
                $GLOBALS["SL"]->states->addMarkerType('RBgradient' . $g,
                    $GLOBALS["SL"]->sysOpts["app-url"] . '/openpolice/uploads/map-marker-redblue-' . $g . '.png');
            }
            $ret = $GLOBALS["SL"]->states->embedMap($nID, 'dept-access-scores-all-' . date("Ymd"), 
                'All Department Accessibility Scores', $this->embedMapDeptLegend());
            if ($ret == '') {
                $ret = "\n\n <!-- no map markers found --> \n\n";
            } elseif ($nID == 2013 && $GLOBALS["SL"]->REQ->has('test')) {
                $GLOBALS["SL"]->pageAJAX .= '$("#map' . $nID . 'ajax").load("/ajax/dept-kml-desc?deptID=13668");';
            }
        }
        return $ret;
    }
    
    protected function embedMapDeptLegend()
    {
        return view('vendor.openpolice.inc-map-dept-access-legend')->render();
    }
    
    protected function printDeptOverPublic($nID)
    {
        $state = '';
        if (isset($this->searcher->searchOpts["state"])) {
            $state = $this->searcher->searchOpts["state"];
        }
        return view('vendor.openpolice.nodes.859-depts-overview-public', [
            "nID"        => $nID,
            "deptScores" => $this->v["deptScores"],
            "state"      => $state
            ])->render();
    }
    
    protected function printDeptAccScoreTitleDesc($nID)
    {
        $this->initSearcher();
        $this->searcher->getSearchFilts();
        if (!isset($this->v["deptScores"])) {
            $this->v["deptScores"] = new DepartmentScores;
            $this->v["deptScores"]->loadAllDepts($this->searcher->searchOpts);
        }
        return view('vendor.openpolice.nodes.1968-accss-grades-title-desc', [
            "nID"   => $nID,
            "state" => (($GLOBALS["SL"]->REQ->has('state')) ? $GLOBALS["SL"]->REQ->state : '')
            ])->render();
    }
    
    protected function printDeptAccScoreBars($nID)
    {
        if (!$GLOBALS["SL"]->REQ->has('state') || trim($GLOBALS["SL"]->REQ->get('state')) == '') {
            $GLOBALS["SL"]->addBodyParams('onscroll="if (typeof bodyOnScroll === \'function\') bodyOnScroll();"');
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
    
    protected function printDeptPage1099($nID)
    {
        /*
        if (!isset($this->v["deptID"]) || intVal($this->v["deptID"]) <= 0) {
            if ($GLOBALS["SL"]->REQ->has('d') && intVal($GLOBALS["SL"]->REQ->get('d')) > 0) {
                $this->v["deptID"] = $GLOBALS["SL"]->REQ->get('d');
            } else {
                $this->v["deptID"] = -3;
            }
        }
        $this->loadDeptStuff($this->v["deptID"]);
        */
        if ($this->v["uID"] > 0 && $this->v["user"]->hasRole('administrator|databaser|staff|partner|volunteer')) {
            $GLOBALS["SL"]->addTopNavItem('pencil', 
                '/dashboard/start-' . $this->v["deptID"] . '/volunteers-research-departments');
        }
        $previews = '<div id="n' . $nID . 'ajaxLoad" class="w100">' . $GLOBALS["SL"]->sysOpts["spinner-code"] 
            . '</div>';
        $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID . 'ajaxLoad").load("/record-prevs/1?d=' . $this->v["deptID"] 
            . '&limit=20");' . "\n";
        
        /*
        if (trim($previews) == '') {
            $previews = '<p><i>No complaints have been submitted for this deparment.</i></p>';
        }
        */
        return view('vendor.openpolice.dept-page', [
            "nID"      => $nID,
            "d"        => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]],
            "previews" => $previews
            ])->render();
    }
    
    protected function printDeptComplaints($nID)
    {
        $this->setUserOversightFilt();
        if (!isset($this->v["fltQry"])) $this->v["fltQry"] = '';
        $this->v["fltQry"] .= " c.`ComStatus` IN ("
            . $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight') . ", "
            . $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight') . ", "
            . $GLOBALS["SL"]->def->getID('Complaint Status', 'Received by Oversight') . ", "
            . $GLOBALS["SL"]->def->getID('Complaint Status', 'Declined To Investigate (Closed)') . ", "
            . $GLOBALS["SL"]->def->getID('Complaint Status', 'Investigated (Closed)') . ") AND ";
        $this->v["statusSkips"] = [
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'New'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Hold'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Reviewed'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d'),
            $GLOBALS["SL"]->def->getID('Complaint Status', 'Closed')
            ];
        return $this->printComplaintListing();
    }

    public function deptPage(Request $request, $deptSlug = '')
    {
        $deptID = -3;
        $deptRow = OPDepartments::where('DeptSlug', $deptSlug)->first();
        if ($deptRow && isset($deptRow->DeptID)) {
            $deptID = $deptRow->DeptID;
            $request->d = $deptRow->DeptID;
        }
        $this->loadPageVariation($request, 1, 25, '/dept/' . $deptSlug);
        if ($deptID > 0) {
            $this->v["deptID"] = $deptRow->DeptID;
            $this->loadDeptStuff($deptID);
        }
        return $this->index($request);
    }
    
    public function shareStoryDept(Request $request, $deptSlug = '')
    {
        $this->loadPageVariation($request, 1, 24, '/complaint-or-compliment/' . $deptSlug);
        $deptRow = OPDepartments::where('DeptSlug', $deptSlug)->first();
        if ($deptRow && isset($deptRow->DeptID)) session()->put('opcDeptID', $deptRow->DeptID);
        return $this->index($request);
    }
    
    public function getOverUpdateRow($cid, $deptID)
    {
        if (!isset($this->v["currOverRow"])) {
            $this->v["currOverRow"] = NULL;
            $overs = OPOversight::where('OverDeptID', $deptID)
                ->get();
            if ($overs->isNotEmpty()) {
                if ($overs->count() == 1) {
                    $this->v["currOverRow"] = $overs[0];
                } else {
                    foreach ($overs as $i => $ovr) {
                        if ($ovr && isset($ovr->OverType) && $ovr->OverType 
                            == $GLOBALS["SL"]->def->getID('Oversight Agency Types', 'Civilian Oversight')) {
                            $this->v["currOverRow"] = $ovr;
                        }
                    }
                }
            }
        }
        if ($this->v["currOverRow"] && isset($this->v["currOverRow"]->OverID)) {
            $this->v["currOverUpdateRow"] = OPLinksComplaintOversight::where('LnkComOverComplaintID', $cid)
                ->where('LnkComOverOverID', $this->v["currOverRow"]->OverID)
                ->first();
            if (!$this->v["currOverUpdateRow"]) {
                $this->v["currOverUpdateRow"] = new OPLinksComplaintOversight;
                $this->v["currOverUpdateRow"]->LnkComOverComplaintID = $cid;
                $this->v["currOverUpdateRow"]->LnkComOverDeptID = $deptID;
                $this->v["currOverUpdateRow"]->LnkComOverOverID = $this->v["currOverRow"]->OverID;
                $this->v["currOverUpdateRow"]->save();
            }
        }
        if (!isset($this->v["currOverUpdateRow"])) $this->v["currOverUpdateRow"] = null;
        return $this->v["currOverUpdateRow"];
    }
    
    public function logOverUpDate($cid, $deptID, $type = 'Submitted', $row = [])
    {
        if (!$row || !isset($row->LnkComOverID)) $row = $this->getOverUpdateRow($cid, $deptID);
        $row->{ 'LnkComOver' . $type } = date("Y-m-d H:i:s");
        $row->save();
        return true;
    }
    
    protected function overWhichEng($which = 'IA')
    {
        return (($which == 'IA') ? 'Internal Affairs' : 'Civilian Oversight');
    }
    
    protected function overWhichDefID($which = 'IA')
    {
        return $GLOBALS["SL"]->def->getID('Oversight Agency Types', $this->overWhichEng($which));
    }
    
    protected function getOverRow($which = 'IA')
    {
        if (isset($this->v["overRow" . $which])) return $this->v["overRow" . $which];
        $rows = $this->sessData->getRowIDsByFldVal('Oversight', [ 'OverType' => $this->overWhichDefID($which) ], true);
        if (sizeof($rows) > 0) {
            $this->v["overRow" . $which] = $rows[0];
            return $this->v["overRow" . $which];
        }
        return [];
    }
    
    
}