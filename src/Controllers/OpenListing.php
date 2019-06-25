<?php
namespace OpenPolice\Controllers;

use DB;
use App\Models\OPComplaints;
use App\Models\OPCompliments;
use App\Models\OPIncidents;
use App\Models\OPAllegSilver;
use App\Models\OPAllegations;
use App\Models\OPDepartments;
use App\Models\OPStops;
use App\Models\SLNode;
use App\Models\OPTesterBeta;
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
        $comDate = $this->getComplaintPrevDate($this->sessData->dataSets["Incidents"][0], $complaint);
        $comDateFiledOPC = $this->getComplaintPrevDateFiledOPC($this->sessData->dataSets["Incidents"][0], $complaint);
        $where = $this->getReportWhereLine();
        $deptList = '';
        $depts = ((isset($this->sessData->dataSets["Departments"])) ? $this->sessData->dataSets["Departments"] : null);
        if ($depts && sizeof($depts) > 0) {
            foreach ($depts as $i => $d) {
                if (isset($d->DeptName)) {
                    $deptList .= ((trim($deptList) != '') ? ', ' : '') 
                        . str_replace('Department', 'Dept', $d->DeptName);
                }
            }
        }
        return view('vendor.openpolice.complaint-report-preview', [
            "uID"         => $this->v["uID"],
            "storyPrev"   => $complaint->{ $coreAbbr . 'Summary' },
            "coreAbbr"    => $coreAbbr,
            "complaint"   => $this->sessData->dataSets[$GLOBALS["SL"]->coreTbl][0], 
            "incident"    => $this->sessData->dataSets["Incidents"][0], 
            "comDate"     => $comDate, 
            "comDateFile" => $comDateFiledOPC, 
            "comWhere"    => ((isset($where[1])) ? $where[1] : ''),
            "allegations" => $this->commaAllegationListSplit(),
            "featureImg"  => '',
            "deptList"    => $deptList
        ])->render();
    }
    
    protected function getComplaintPrevDate($incident, $complaint)
    {
        $comDate = date('F Y', strtotime($incident->IncTimeStart));
        if ($complaint->ComPrivacy == 304 || $this->v["isAdmin"]) {
            $comDate = date('m/d/Y', strtotime($incident->IncTimeStart));
        }
        return $comDate;
    }
    
    protected function getComplaintPrevDateFiledOPC($incident, $complaint)
    {
        $comDate = date('F Y', strtotime($incident->ComRecordSubmitted));
        if ($complaint->ComPrivacy == 304 || $this->v["isAdmin"]) {
            $comDate = date('m/d/Y', strtotime($incident->ComRecordSubmitted));
        }
        return $comDate;
    }
    
    protected function getComplaintPreviewByRow($complaint)
    {
        $this->allegations = [];
        foreach (['Complaints', 'Incidents', 'AllegSilver', 'Allegations', 'Departments', 'Stops']
            as $tbl) {
            $this->sessData->dataSets[$tbl] = [];
        }
        $this->sessData->dataSets["Complaints"][0] = $complaint;
        $this->sessData->dataSets["Incidents"][0] = OPIncidents::find($complaint->ComIncidentID);
        $this->sessData->dataSets["AllegSilver"][0] = OPAllegSilver::where('AlleSilComplaintID', $complaint->ComID)
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
        return $this->printPreviewReportCustom();
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
    
    protected function printComplaintsFilters($nID, $view = 'all')
    {
        $GLOBALS["SL"]->loadStates();
        $state = '';
        return view('vendor.openpolice.complaint-listing-filters', [
            "nID"        => $nID,
            "view"       => $view,
            "fltStatus"  => $this->v["fltStatus"],
            "stateDrop"  => $GLOBALS["SL"]->states->stateDrop($state),
            "allegTypes" => $this->worstAllegations,
            "races"      => $GLOBALS["SL"]->def->getSet('Races')
        ])->render();
    }
    
    protected function printComplaintListing($nID, $view = 'all')
    {
        $this->v["listView"] = $view;
        if ($GLOBALS["SL"]->REQ->has('fltView')) {
            $this->v["listView"] = $GLOBALS["SL"]->REQ->fltView;
        }
        $this->v["fltStatus"] = (($GLOBALS["SL"]->REQ->has('fltStatus')) 
            ? $GLOBALS["SL"]->REQ->fltStatus : 0);
        $this->v["listPrintFilters"] = $this->printComplaintsFilters($nID, $view);

        $sort = "date";
        $qman = "SELECT c.*, p.`PrsnNameFirst`, p.`PrsnNameLast`, p.`PrsnEmail`, i.* 
            FROM `OP_Complaints` c 
            JOIN `OP_Incidents` i ON c.`ComIncidentID` LIKE i.`IncID` 
            LEFT OUTER JOIN `OP_Civilians` civ ON c.`ComID` LIKE civ.`CivComplaintID` 
            LEFT OUTER JOIN `OP_PersonContact` p ON p.`PrsnID` LIKE civ.`CivPersonID` WHERE "
            . ((isset($this->v["fltQry"])) ? $this->v["fltQry"] : "");
        if (isset($this->v["fltIDs"]) && sizeof($this->v["fltIDs"]) > 0) {
            foreach ($this->v["fltIDs"] as $ids) {
                $qman .= " c.`ComID` IN (" . implode(', ', $ids) . ") AND ";
            }
        }
        $qman .= " civ.`CivIsCreator` LIKE 'Y' ";
        $comTypes = [];
        if ($GLOBALS["SL"]->REQ->has('type')) {
            switch (trim($GLOBALS["SL"]->REQ->get('type'))) {
                case 'notsure':
                    $GLOBALS["SL"]->setCurrPage('/dash/all-complete-complaints?type=notsure');
                    $comTypes = [
                        $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Not Sure')
                    ];
                    break;
                case 'notpolice':
                    $GLOBALS["SL"]->setCurrPage('/dash/all-complete-complaints?type=notpolice');
                    $comTypes = [ $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Not About Police') ];
                    break;
                case 'spam':
                    $GLOBALS["SL"]->setCurrPage('/dash/all-complete-complaints?type=spam');
                    $comTypes = [
                        $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Abuse'),
                        $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Spam'),
                        $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Test')
                    ];
                    break;
            }
        }
        if (sizeof($comTypes) == 0) {
            $comTypes = [
                $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Unreviewed'),
                $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Police Complaint'),
                $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Not Sure')
            ];
        }
        $qman .= " AND c.`ComType` IN ('" . implode("', '", $comTypes) . "') ";
        if ($this->v["fltStatus"] > 0) {
            $qman .= " AND c.`ComStatus` LIKE '" . $this->v["fltStatus"] . "' ";
        }
        switch ($this->v["listView"]) {
            case 'review':         
                $qman .= " AND (c.`ComStatus` LIKE '" . $GLOBALS["SL"]->def->getID('Complaint Status', 'New') . "' 
                    OR (c.`ComType` IN ('" . $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Unreviewed')
                    . "', '" . $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Not Sure') 
                    . "') AND c.`ComStatus` NOT LIKE '" . $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete') 
                    . "') )"; 
                break;
            case 'mine':     
                $qman .= " AND c.`ComAdminID` LIKE '" . $this->v["user"]->id . "' 
                    AND c.`ComStatus` NOT LIKE '" . $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete') . "'";
                break;
            case 'flagged':
                $qman .= " AND (c.`ComStatus` IN ('" . $GLOBALS["SL"]->def->getID('Complaint Status', 'Hold') . "', '" 
                    . $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney') . "') )"; 
                break;
            case 'waiting':
                $qman .= " AND (c.`ComStatus` IN ('" . $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d') 
                    . "', '" . $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight') . "', '" 
                    . $GLOBALS["SL"]->def->getID('Complaint Status', 'Received by Oversight') . "', '" 
                    . $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Oversight Investigation') . "') )"; 
                break;
            case 'incomplete':     
                $qman .= " AND c.`ComStatus` LIKE '" 
                    . $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete') . "'";
                break;
            case 'all':     
            default:
                $qman .= " AND c.`ComStatus` NOT LIKE '" 
                    . $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete') . "'";
                break;
        }
        $qman .= " ORDER BY c.`ComRecordSubmitted` DESC";
        $this->v["complaints"] = $this->v["comInfo"] = $this->v["lastNodes"] 
            = $this->v["ajaxRefreshs"] = [];
        $compls = DB::select( DB::raw($qman) );
        if ($compls && sizeof($compls) > 0) {
            foreach ($compls as $com) {
                $this->v["comInfo"][$com->ComPublicID] = [ "depts" => '', "submitted" => '' ];
                $dChk = DB::table('OP_LinksComplaintDept')
                    ->where('OP_LinksComplaintDept.LnkComDeptComplaintID', $com->ComID)
                    ->leftJoin('OP_Departments', 'OP_Departments.DeptID', '=', 'OP_LinksComplaintDept.LnkComDeptDeptID')
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
                if (trim($com->ComRecordSubmitted) != '' && $com->ComRecordSubmitted != '0000-00-00 00:00:00') {
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
        $GLOBALS["SL"]->pageAJAX 
            .= view('vendor.openpolice.nodes.1418-admin-complaints-listing-ajax', $this->v)->render();
        return view('vendor.openpolice.nodes.1418-admin-complaints-listing', $this->v)->render();
    }
    
    protected function printProfileMyComplaints($nID)
    {
        $ret = '';
        if ($this->v["uID"] > 0) { // loading records for my own profile
            $chk = OPComplaints::where('ComUserID', $this->v["uID"])
                ->where('ComStatus', '>', 0)
                ->orderBy('created_at', 'desc')
                ->get();
            if ($chk->isNotEmpty()) {
                $loadURL = '/record-prevs/1?rawids=';
                foreach ($chk as $i => $rec) {
                    $loadURL .= (($i > 0) ? ',' : '') . $rec->ComID;
                }
                $ret .= '<h2 class="slBlueDark m0">Your Complaints</h2><div id="n' . $nID 
                    . 'ajaxLoadA" class="w100">' . $GLOBALS["SL"]->sysOpts["spinner-code"] . '</div>';
                $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID . 'ajaxLoadA").load("' . $loadURL . '");' . "\n";
            } else {
                $ret .= '<div class="p10"><i>No Complaints</i></div>';
            }
            $ret .= '<div class="p20">&nbsp;</div>';
            $chk = OPCompliments::where('CompliUserID', $this->v["uID"])
                ->where('CompliStatus', '>', 0)
                ->orderBy('created_at', 'desc')
                ->get();
            if ($chk->isNotEmpty()) {
                $loadURL = '/record-prevs/5?rawids=';
                foreach ($chk as $i => $rec) {
                    $loadURL .= (($i > 0) ? ',' : '') . $rec->CompliID;
                }
                $ret .= '<h2 class="slBlueDark m0">Your Compliments</h2><div id="n' . $nID 
                    . 'ajaxLoadB" class="w100">' . $GLOBALS["SL"]->sysOpts["spinner-code"] . '</div>';
                $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID . 'ajaxLoadB").load("' . $loadURL . '");' . "\n";
            } else {
                $ret .= '<div class="p10"><i>No Compliments</i></div>';
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