<?php
namespace OpenPolice\Controllers;

use DB;
use Cache;
use App\Models\OPComplaints;
use App\Models\OPCompliments;
use App\Models\OPIncidents;
use App\Models\OPAllegSilver;
use App\Models\OPAllegations;
use App\Models\OPDepartments;
use App\Models\OPStops;
use App\Models\OPPhysicalDescRace;
use App\Models\SLNode;
use App\Models\SLSearchRecDump;
use App\Models\OPTesterBeta;
use App\User;
use OpenPolice\Controllers\OpenAjax;

class OpenListing extends OpenAjax
{
    public function printPreviewReportCustom($isAdmin = false)
    {
        $coreAbbr = $GLOBALS["SL"]->coreTblAbbr();
        if (!isset($this->sessData->dataSets[$GLOBALS["SL"]->coreTbl]) 
            || !isset($this->sessData->dataSets["Incidents"])) {
            return '';
        }
        $complaint = $this->sessData->dataSets[$GLOBALS["SL"]->coreTbl][0];
        $where = $this->getReportWhereLine();
        $deptList = '';
        $depts = ((isset($this->sessData->dataSets["Departments"])) 
            ? $this->sessData->dataSets["Departments"] : null);
        if ($depts && sizeof($depts) > 0) {
            foreach ($depts as $i => $d) {
                if (isset($d->DeptName)) {
                    $deptList .= ((trim($deptList) != '') ? ', ' : '') 
                        . str_replace('Department', 'Dept', $d->DeptName);
                }
            }
        }
        $url = '';
        if (isset($complaint->{ $coreAbbr . 'PublicID' }) 
            && intVal($complaint->{ $coreAbbr . 'PublicID' }) > 0) {
            $url = '/' . (($coreAbbr == 'Com') ? 'complaint' : 'compliment') . '/read-'
                . $complaint->{ $coreAbbr . 'PublicID' };
        } else {
            $url = '/' . (($coreAbbr == 'Com') ? 'complaint' : 'compliment') . '/readi-'
                . $complaint->{ $coreAbbr . 'ID' };
        }
        return view('vendor.openpolice.complaint-report-preview', [
            "uID"         => $this->v["uID"],
            "storyPrev"   => $complaint->{ $coreAbbr . 'Summary' },
            "coreAbbr"    => $coreAbbr,
            "complaint"   => $this->sessData->dataSets[$GLOBALS["SL"]->coreTbl][0], 
            "incident"    => $this->sessData->dataSets["Incidents"][0], 
            "comDate"     => $this->getComplaintDate($this->sessData->dataSets["Incidents"][0], $complaint), 
            "comDateFile" => $this->getComplaintDateOPC($complaint), 
            "comWhere"    => ((isset($where[1])) ? $where[1] : ''),
            "allegations" => $this->commaAllegationListSplit(),
            "deptList"    => $deptList,
            "url"         => $url,
            "featureImg"  => ''
        ])->render();
    }
    
    protected function getComplaintDate($incident, $complaint)
    {
        $comDate = date('F Y', strtotime($incident->IncTimeStart));
//echo '<pre>'; print_r($complaint); echo '</pre>'; exit;
        if ($this->shouldPrintFullDate($complaint)) {
            $comDate = date('m/d/Y', strtotime($incident->IncTimeStart));
        }
        return $comDate;
    }
    
    protected function getComplaintDateOPC($complaint)
    {
        $comDate = date('F Y', strtotime($complaint->ComRecordSubmitted));
        if ($this->shouldPrintFullDate($complaint)) {
            $comDate = date('m/d/Y', strtotime($complaint->ComRecordSubmitted));
        }
        return $comDate;
    }
    
    protected function getComplaintPreviewByRow($complaint)
    {
        $ret = '';
        $cacheName = 'complaint' . $complaint->ComID . '-preview-' 
            . (($GLOBALS["SL"]->x["isPublicList"]) ? 'public' : 'sensitive');
        if (!$GLOBALS["SL"]->REQ->has('refresh')) {
            $ret = Cache::get($cacheName, '');
            if ($ret != '') {
                return $ret;
            }
        }
        $this->allegations = [];
        foreach (['Complaints', 'Incidents', 'AllegSilver', 'Allegations', 'Departments', 'Stops']
            as $tbl) {
            $this->sessData->dataSets[$tbl] = [];
        }
        $this->sessData->dataSets["Complaints"][0] = $complaint;
        $this->sessData->dataSets["Incidents"][0]
            = OPIncidents::find($complaint->ComIncidentID);
        $this->sessData->dataSets["AllegSilver"][0] 
            = OPAllegSilver::where('AlleSilComplaintID', $complaint->ComID)
            ->first();
        $this->sessData->dataSets["Allegations"] 
            = OPAllegations::where('AlleComplaintID', $complaint->ComID)
            ->get();
        $this->sessData->dataSets["Departments"] = DB::table('OP_Departments')
            ->join('OP_LinksComplaintDept', 'OP_Departments.DeptID', '=', 'OP_LinksComplaintDept.LnkComDeptDeptID')
            ->where('OP_LinksComplaintDept.LnkComDeptComplaintID', $complaint->ComID)
            ->select('OP_Departments.*')
            ->get();
        $this->sessData->dataSets["Stops"] = DB::table('OP_Stops')
            ->join('OP_EventSequence', 'OP_Stops.StopEventSequenceID', '=', 'OP_EventSequence.EveID')
            ->where('OP_EventSequence.EveComplaintID', $complaint->ComID)
            ->select('OP_Stops.*')
            ->get();
        $ret = $this->printPreviewReportCustom();
        Cache::put($cacheName, $ret);
        return $ret;
    }
    
    protected function printComplaintsPreviews()
    {
        $ret = '';
        //$GLOBALS["SL"]->x["pageView"] = 'public';
        $this->initSearcher();
        $this->searcher->getSearchFilts();
        $xtra = "whereIn('ComStatus', [" . implode(", ", 
            $this->getPublishedStatusList('Complaints')) . "])->";
        $this->searcher->loadAllComplaintsPublic($xtra);
        if ($this->searcher->v["allcomplaints"]->isNotEmpty()) {
            foreach ($this->searcher->v["allcomplaints"] as $i => $com) {
                $ret .= '<div class="pB20 mB10"><div class="slCard">' 
                    . $this->getComplaintPreviewByRow($com) . '</div></div>';
            }
        }
        return $ret;
    }
    
    protected function printComplaintsFilters($nID, $view = 'list')
    {
        if (!isset($this->searcher->v["sortLab"]) || $this->searcher->v["sortLab"] == '') {
            $this->searcher->v["sortLab"] = 'date';
        }
        if (!isset($this->searcher->v["sortDir"]) || $this->searcher->v["sortDir"] == '') {
            $this->searcher->v["sortDir"] = 'desc';
        }
        if (!isset($this->searcher->searchFilts["comstatus"])) {
            if (!$GLOBALS["SL"]->x["isPublicList"]) {
                $this->searcher->searchFilts["comstatus"] = [ 295, 301, 296 ];
            } else {
                $this->searcher->searchFilts["comstatus"] = [];
            }
        }
        $GLOBALS["SL"]->loadStates();
        if (!isset($this->searcher->searchFilts["states"])) {
            $this->searcher->searchFilts["states"] = [];
        }
        if ((!isset($this->searcher->searchFilts["states"]) 
            || sizeof($this->searcher->searchFilts["states"]) == 0)
            && (isset($this->searcher->searchFilts["state"]) 
            && trim($this->searcher->searchFilts["state"]) != '')) {
            $this->searcher->searchFilts["states"][] 
                = trim($this->searcher->searchFilts["state"]);
        }
        if (!isset($this->searcher->searchFilts["allegs"])) {
            $this->searcher->searchFilts["allegs"] = [];
        }
        if (!isset($this->searcher->searchFilts["victgend"])) {
            $this->searcher->searchFilts["victgend"] = [];
        }
        if (!isset($this->searcher->searchFilts["victrace"])) {
            $this->searcher->searchFilts["victrace"] = [];
        }
        if (!isset($this->searcher->searchFilts["offgend"])) {
            $this->searcher->searchFilts["offgend"] = [];
        }
        if (!isset($this->searcher->searchFilts["offrace"])) {
            $this->searcher->searchFilts["offrace"] = [];
        }
        $statusFilts = $GLOBALS["SL"]->printAccordian(
            'By Complaint Status',
            view('vendor.openpolice.complaint-listing-filters-status', [
                "srchFilts"  => $this->searcher->searchFilts
            ])->render(),
            (sizeof($this->searcher->searchFilts["comstatus"]) > 0)
        );
        $stateFilts = $GLOBALS["SL"]->printAccordian(
            'By State',
            view('vendor.openpolice.complaint-listing-filters-states', [
                "srchFilts"  => $this->searcher->searchFilts
            ])->render(),
            (sizeof($this->searcher->searchFilts["states"]) > 0)
        );
        $allegFilts = $GLOBALS["SL"]->printAccordian(
            'By Allegation',
            view('vendor.openpolice.complaint-listing-filters-allegs', [
                "allegTypes" => $this->worstAllegations,
                "srchFilts"  => $this->searcher->searchFilts
            ])->render(),
            (sizeof($this->searcher->searchFilts["allegs"]) > 0)
        );
        $victFilts = $GLOBALS["SL"]->printAccordian(
            'By Victim Description',
            view('vendor.openpolice.complaint-listing-filters-vict', [
                "races"      => $GLOBALS["SL"]->def->getSet('Races'),
                "srchFilts"  => $this->searcher->searchFilts
            ])->render(),
            (sizeof($this->searcher->searchFilts["victgend"]) > 0
                || sizeof($this->searcher->searchFilts["victrace"]) > 0)
        );
        $offFilts = $GLOBALS["SL"]->printAccordian(
            'By Officer Description',
            view('vendor.openpolice.complaint-listing-filters-off', [
                "races"      => $GLOBALS["SL"]->def->getSet('Races'),
                "srchFilts"  => $this->searcher->searchFilts
            ])->render(),
            (sizeof($this->searcher->searchFilts["offgend"]) > 0
                || sizeof($this->searcher->searchFilts["offrace"]) > 0)
        );
        return view('vendor.openpolice.complaint-listing-filters', [
            "nID"         => $nID,
            "view"        => $view,
            "statusFilts" => $statusFilts,
            "stateFilts"  => $stateFilts,
            "allegFilts"  => $allegFilts,
            "victFilts"   => $victFilts,
            "offFilts"    => $offFilts,
            "srchFilts"   => $this->searcher->searchFilts
        ])->render();
    }
    
    protected function printComplaintListing($nID, $view = 'list')
    {
        $this->v["sView"] = $view;
        if ($GLOBALS["SL"]->REQ->has('sView')) {
            $this->v["sView"] = $GLOBALS["SL"]->REQ->sView;
        } // elseif ...
        if ($GLOBALS["SL"]->x["isPublicList"]) {
            $this->v["sView"] = 'lrg';
        }
        $this->v["complaints"] = $this->v["complaintsPreviews"] = $this->v["comInfo"] 
            = $this->v["lastNodes"] = $this->v["ajaxRefreshs"] = [];
        $this->v["filtersDesc"] = '';
        $this->v["firstComplaint"] = [0, 0];
        $this->initSearcher();
        $this->searcher->getSearchFilts();
        $this->v["listPrintFilters"] = $this->printComplaintsFilters($nID, $this->v["sView"]);

        eval($this->printComplaintListQry1($nID)); // runs query into $compls1
        $compls2 = $this->printComplaintListQry2($nID, $compls1); // runs 2nd-round queries
        unset($compls1);
        $this->printComplaintFiltsDesc($nID);

        if ($compls2 && sizeof($compls2) > 0) {
            foreach ($compls2 as $i => $com) {
                if ($this->v["firstComplaint"][0] == 0) {
                    $this->v["firstComplaint"] = [
                        intVal($com->ComPublicID), 
                        $com->ComID
                    ];
                }
                $this->v["comInfo"][$com->ComPublicID] = [
                    "depts"     => '',
                    "submitted" => ''
                ];
                $dChk = DB::table('OP_LinksComplaintDept')
                    ->where('OP_LinksComplaintDept.LnkComDeptComplaintID', $com->ComID)
                    ->leftJoin('OP_Departments', 'OP_Departments.DeptID', 
                        '=', 'OP_LinksComplaintDept.LnkComDeptDeptID')
                    ->select('OP_Departments.DeptName', 'OP_Departments.DeptSlug')
                    ->orderBy('OP_Departments.DeptName', 'asc')
                    ->get();
                if ($dChk && sizeof($dChk) > 0) {
                    foreach ($dChk as $i => $d) {
                        $this->v["comInfo"][$com->ComPublicID]["depts"] .= (($i > 0) ? ', ' : '') 
                            . str_replace('Department' , 'Dept', $d->DeptName);
                    }
                }
                $comTime = strtotime($com->updated_at);
                if (trim($com->ComRecordSubmitted) != '' 
                    && $com->ComRecordSubmitted != '0000-00-00 00:00:00') {
                    $comTime = strtotime($com->ComRecordSubmitted);
                }
                if (!isset($com->ComStatus) || intVal($com->ComStatus) <= 0) {
                    $com->ComStatus = $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete');
                    OPComplaints::find($com->ComID)
                        ->update([ "ComStatus" => $com->ComStatus ]);
                }
                if (!isset($com->ComType) || intVal($com->ComType) <= 0) {
                    $com->ComType = $GLOBALS['SL']->def->getID('OPC Staff/Internal Complaint Type', 'Unreviewed');
                    OPComplaints::find($com->ComID)
                        ->update([ "ComType" => $com->ComType ]);
                }
                $cutoffTime = mktime(date("H"), date("i"), date("s"), date("m"), date("d")-1, date("Y"));
                if ($comTime < $cutoffTime) {
                    if (!isset($com->ComSummary) || trim($com->ComSummary) == '') {
                        OPComplaints::find($com->ComID)
                            ->delete();
                        $comTime = false;
                    }
                }
                if ($comTime !== false) {
                    $sortInd = $comTime;
                    $this->v["comInfo"][$com->ComPublicID]["submitted"] = date("n/j/Y", $comTime);
                    if ($com->ComStatus == $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete')) {
                        if ($com->ComSubmissionProgress > 0 
                            && !isset($this->v["lastNodes"][$com->ComSubmissionProgress])) {
                            $node = SLNode::find($com->ComSubmissionProgress);
                            if ($node && isset($node->NodePromptNotes)) {
                                $this->v["lastNodes"][$com->ComSubmissionProgress] = $node->NodePromptNotes;
                            }
                        }
                    }
                    $this->v["complaints"][$sortInd] = $com;
                }
                if (!isset($com->ComAllegList) || trim($com->ComAllegList) == '') {
                    $this->v["ajaxRefreshs"][] = $com->ComPublicID;
                }
            }
            krsort($this->v["complaints"]);
        }

        if ($this->v["sView"] == 'lrg' && sizeof($this->v["complaints"]) > 0) {
            foreach ($this->v["complaints"] as $com) {
                $ret = '';
                $cacheName = 'complaint' . $com->ComID . '-preview-' 
                    . (($GLOBALS["SL"]->x["isPublicList"]) ? 'public' : 'sensitive');
                if (!$GLOBALS["SL"]->REQ->has('refresh')) {
                    $ret = Cache::get($cacheName, '');
                }
                if ($ret == '') {
                    $this->loadAllSessData('Complaints', $com->ComID);
                    $ret = $this->printPreviewReport();
                    Cache::put($cacheName, $ret);
                    //$this->printPreviewReportCustom($isAdmin);
                }
                $this->v["complaintsPreviews"][] = '<div id="reportPreview' . $com->ComID 
                    . '" class="reportPreview">' . $ret . '</div>';
            }
        }
        $this->v["sortLab"]    = $this->searcher->v["sortLab"];
        $this->v["sortDir"]    = $this->searcher->v["sortDir"];
        $this->v["allegTypes"] = $this->worstAllegations;

        $GLOBALS["SL"]->pageAJAX 
            .= view('vendor.openpolice.nodes.1418-admin-complaints-listing-ajax', $this->v)->render();
        return view('vendor.openpolice.nodes.1418-admin-complaints-listing', $this->v)->render()
            . view('vendor.openpolice.nodes.1418-admin-complaints-listing-styles', $this->v)->render();
    }

    protected function printComplaintListQry1($nID)
    {
        $hasStateFilt = (sizeof($this->searcher->searchFilts["states"]) > 0);
        $eval = "\$compls1 = DB::table('OP_Complaints')
            ->join('OP_Incidents', function (\$joi) {
                \$joi->on('OP_Complaints.ComIncidentID', '=', 'OP_Incidents.IncID')"
                . (($hasStateFilt) ? "->whereIn('OP_Incidents.IncAddressState', ['"
                    . implode("', '", $this->searcher->searchFilts["states"]) . "'])" : "") . ";
            })";
        if ($hasStateFilt) {
            $this->v["filtersDesc"] .= ' & ' . implode(', ', $this->searcher->searchFilts["states"]);
        }

        if (sizeof($this->searcher->searchFilts["allegs"]) > 0) {
            $filtDescTmp = '';
            $eval .= "->join('OP_AllegSilver', function (\$joi) {
                \$joi->on('OP_Complaints.ComID', '=', 'OP_AllegSilver.AlleSilComplaintID')";
            foreach ($this->searcher->searchFilts["allegs"] as $i => $allegID) {
                $eval .= "->" . (($i > 0) ? "orWhere" : "where") 
                    . "('OP_AllegSilver." . $this->getAllegFldName($allegID) . "', 'Y')";
                $filtDescTmp = ' or ' . $GLOBALS["SL"]->def->getVal('Allegation Type', $allegID);
            }
            $eval .= "; })";
            $this->v["filtersDesc"] .= ' & ' . substr($filtDescTmp, 3);
        }

        $eval .= "->leftJoin('OP_Civilians', function (\$joi) {
                \$joi->on('OP_Complaints.ComID', '=', 'OP_Civilians.CivComplaintID')
                    ->where('OP_Civilians.CivIsCreator', 'Y');
            })
            ->leftJoin('OP_PersonContact', 
                'OP_Civilians.CivPersonID', '=', 'OP_PersonContact.PrsnID')";
        
        if (isset($this->v["fltIDs"]) && sizeof($this->v["fltIDs"]) > 0) {
            $fltIDs = '';
            foreach ($this->v["fltIDs"] as $ids) {
                if (sizeof($ids) > 0) {
                    foreach ($ids as $id) {
                        $fltIDs .= ', ' . $id;
                    }
                }
            }
            if (trim($fltIDs) != '') {
                $eval .= "->whereIn('OP_Complaints.ComID', [" . substr($fltIDs, 2) . "])";
            }
        }
        $eval .= $this->searcher->getSearchFiltQryStatus()
            . "->select('OP_Complaints.*', 'OP_PersonContact.PrsnNameFirst', 
            'OP_PersonContact.PrsnNameLast', 'OP_PersonContact.PrsnEmail', 'OP_Incidents.*')"
            . $this->searcher->getSearchFiltQryOrderBy()
            . "->get();";
        return $eval;
    }

    // a separate pass to further filter results, too hairy for the main filter query
    protected function printComplaintListQry2($nID, $compls1)
    {
        $GLOBALS["SL"]->xmlTree["coreTbl"] = 'Complaints';
        $compls2 = [];
        if ($compls1 && sizeof($compls1) > 0) {
            foreach ($compls1 as $com) {
                $comID = $com->ComID;
                $inFilter = true;
                if (sizeof($this->searcher->searchFilts["victgend"]) > 0
                    || sizeof($this->searcher->searchFilts["victrace"]) > 0) {
                    $chk = DB::table('OP_PhysicalDesc')
                        ->join('OP_Civilians', function ($joi) use ($comID) {
                            $joi->on('OP_PhysicalDesc.PhysID', '=', 'OP_Civilians.CivPhysDescID')
                                ->where('OP_Civilians.CivComplaintID', $comID)
                                ->where('OP_Civilians.CivRole', 'Victim');
                        })
                        ->select('OP_PhysicalDesc.PhysID', 'OP_PhysicalDesc.PhysGender')
                        ->get();
                    if ($chk->isNotEmpty()) {
                        if (sizeof($this->searcher->searchFilts["victgend"]) > 0) {
                            if (!$this->chkFiltPhysGend($chk, $this->searcher->searchFilts["victgend"])) {
                                $inFilter = false;
                            }
                        }
                        if (sizeof($this->searcher->searchFilts["victrace"]) > 0) {
                            if (!$this->chkFiltPhysRace($chk, $this->searcher->searchFilts["victrace"])) {
                                $inFilter = false;
                            }
                        }
                    }
                }
                if ($inFilter && sizeof($this->searcher->searchFilts["offgend"]) > 0
                    || sizeof($this->searcher->searchFilts["offrace"]) > 0) {
                    $chk = DB::table('OP_PhysicalDesc')
                        ->join('OP_Officers', function ($joi) use ($comID) {
                            $joi->on('OP_PhysicalDesc.PhysID', '=', 'OP_Officers.OffPhysDescID')
                                ->where('OP_Officers.OffComplaintID', $comID);
                        })
                        ->select('OP_PhysicalDesc.PhysID', 'OP_PhysicalDesc.PhysGender')
                        ->get();
                    if ($chk->isNotEmpty()) {
                        if (sizeof($this->searcher->searchFilts["offgend"]) > 0) {
                            if (!$this->chkFiltPhysGend($chk, $this->searcher->searchFilts["offgend"])) {
                                $inFilter = false;
                            }
                        }
                        if (sizeof($this->searcher->searchFilts["offrace"]) > 0) {
                            if (!$this->chkFiltPhysRace($chk, $this->searcher->searchFilts["offrace"])) {
                                $inFilter = false;
                            }
                        }
                    }
                }
                if ($inFilter && trim($this->searcher->searchTxt) != '') {
                    $dump = SLSearchRecDump::where('SchRecDmpTreeID', 1)
                        ->where('SchRecDmpRecID', $comID)
                        ->first();
                    if (!$dump || !isset($dump->SchRecDmpID)) {
                        $dump = $this->genRecDump($comID, true);
                    }
                    if (stripos($dump->SchRecDmpRecDump, $this->searcher->searchTxt) === false) {
                        $inFilter = false;
                    }
                    /*
                    } else {
                        $chk = SLSearchRecDump::where('SchRecDmpTreeID', 1)
                            ->where('SchRecDmpRecID', $comID)
                            ->where('SchRecDmpRecDump', 'LIKE', '%' . $this->searcher->searchTxt . '%')
                            ->select('SchRecDmpID')
                            ->get();
                        if ($chk->isEmpty()) {
                            $inFilter = false;
                        }
                    }
                    */
                }
                if ($inFilter) {
                    $compls2[] = $com;
                }
            }
        }
        return $compls2;
    }

    protected function printComplaintFiltsDesc($nID)
    {
        $this->v["filtersDesc"] .= $this->searcher->getSearchFiltDescPeeps()
            . $this->searcher->getSearchFiltDescStatus();
        if (trim($this->v["filtersDesc"]) != '') {
            $this->v["filtersDesc"] = substr($this->v["filtersDesc"], 2);
        }
        return true;
    }

    protected function chkFiltPhysGend($chkPhys, $matches = [])
    {
        $inFilterGend = false;
        foreach ($chkPhys as $phys) {
            if (isset($phys->PhysGender) && trim($phys->PhysGender) != '') {
                if (in_array('T', $matches)) {
                    if (!in_array($phys->PhysGender, ['M', 'F'])) {
                        $inFilterGend = true;
                    }
                } elseif (in_array('M', $matches)
                    && $phys->PhysGender == 'M') {
                    $inFilterGend = true;
                } elseif (in_array('F', $matches)
                    && $phys->PhysGender == 'F') {
                    $inFilterGend = true;
                }
            }
        }
        return $inFilterGend;
    }
    
    protected function chkFiltPhysRace($chkPhys, $matches = [])
    {
        $inFilterRace = false;
        foreach ($chkPhys as $phys) {
            $chkRace = OPPhysicalDescRace::where('PhysRacePhysDescID', $phys->PhysID)
                ->whereIn('PhysRaceRace', $matches)
                ->select('PhysRaceID')
                ->get();
            if ($chkRace->isNotEmpty()) {
                $inFilterRace = true;
            }
        }
        return $inFilterRace;
    }
    
    protected function printProfileMyComplaints($nID)
    {
        $ret = '';
        if ($this->v["uID"] > 0) { // loading records for my own profile
            $usr = User::find($this->v["uID"]);
            $name = 'Your';
            if ($usr && isset($usr->name) && trim($usr->name) != '') {
                $name = trim($usr->name) . '\'s';
            }
            $chk = OPComplaints::where('ComUserID', $this->v["uID"])
                ->where('ComStatus', '>', 0)
                ->orderBy('created_at', 'desc')
                ->get();
            if ($chk->isNotEmpty()) {
                $loadURL = '/record-prevs/1?rawids=';
                foreach ($chk as $i => $rec) {
                    $loadURL .= (($i > 0) ? ',' : '') . $rec->ComID;
                }
                $ret .= '<h2 class="slBlueDark m0">' . $name . ' Complaints</h2><div id="n' . $nID 
                    . 'ajaxLoadA" class="w100">' . $GLOBALS["SL"]->sysOpts["spinner-code"] . '</div>';
                $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID . 'ajaxLoadA").load("' . $loadURL . '");' . "\n";
            } else {
                $ret .= '<div class="p10"><i>No Complaints</i></div>';
            }
            $chk = OPCompliments::where('CompliUserID', $this->v["uID"])
                ->where('CompliStatus', '>', 0)
                ->orderBy('created_at', 'desc')
                ->get();
            if ($chk->isNotEmpty()) {
                $loadURL = '/record-prevs/5?rawids=';
                foreach ($chk as $i => $rec) {
                    $loadURL .= (($i > 0) ? ',' : '') . $rec->CompliID;
                }
                $ret .= '<div class="p20">&nbsp;</div><h2 class="slBlueDark m0">' . $name
                    . ' Compliments</h2><div id="n' . $nID . 'ajaxLoadB" class="w100">'
                    . $GLOBALS["SL"]->sysOpts["spinner-code"] . '</div>';
                $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID . 'ajaxLoadB").load("' . $loadURL . '");' . "\n";
            } else {
                $ret .= '<!-- <div class="p10"><i>No Compliments</i></div> -->';
            }
        }
        return $ret;
    }
    
    protected function printBetaTesters($nID)
    {
        if ($GLOBALS["SL"]->REQ->has('invite') && intVal($GLOBALS["SL"]->REQ->get('invite')) > 0) {
            OPTesterBeta::find(intVal($GLOBALS["SL"]->REQ->get('invite')))
                ->update([ 'BetaInvited' => date('Y-m-d') ]);
        }
        $betas = OPTesterBeta::whereNotNull('BetaEmail')
            ->where('BetaEmail', 'NOT LIKE', '')
            ->orderBy('created_at', 'desc')
            ->get();
        $empties = OPTesterBeta::whereNotNull('BetaHowHear')
            ->where('BetaHowHear', 'NOT LIKE', '')
            ->get();
        //$GLOBALS["SL"]->addHshoo("#stats");
        $GLOBALS["SL"]->x["needsPlots"] = true;
        $this->sortBetas($betas, 'betaSignups');
        $this->sortBetas($empties, 'betaClicks');
        return view('vendor.openpolice.nodes.2234-beta-listing', [
            "betas" => $betas,
            "emptyNoRef" => OPTesterBeta::whereNull('BetaHowHear')
                ->orWhere('BetaHowHear', 'LIKE', '')
                ->count(),
            "totLoads"   => OPTesterBeta::count()
        ])->render();
    }
    
    protected function sortBetas($betas, $divName)
    {
        $graph = [ "divName" => $divName, "values" => '', "labels" => '' ];
        $tots = [];
        if ($betas->isNotEmpty()) {
            foreach ($betas as $i => $beta) {
                $how = str_replace('-police-dept', '', trim($beta->BetaHowHear));
                if (!isset($tots[$how])) {
                    $tots[$how] = 0;
                }
                $tots[$how]++;
            }
            $passCnt = sizeof($tots);
            for ($i = 0; $i < $passCnt; $i++) {
                $min = 10000000000;
                $how = '';
                foreach ($tots as $howHear => $tot) {
                    if ($min > $tot) {
                        $min = $tot;
                        $how = $howHear;
                    }
                }
                $graph["values"] .= (($i > 0) ? ', ' : '') . $min;
                $graph["labels"] .= (($i > 0) ? ', ' : '') . json_encode($how);
                unset($tots[$how]);
            }
        }
        $GLOBALS["SL"]->pageJAVA .= view('vendor.survloop.reports.graph-bar-plot', [
            "graph"  => $graph,
            "height" => 700
        ])->render();
        return $graph;
    }
    
}