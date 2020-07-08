<?php
/**
  * OpenListing is a mid-level class which handles the printing
  * of listings of conduct reports, mostly in preview.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.12
  */
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
use App\Models\User;
use OpenPolice\Controllers\OpenAjax;

class OpenListing extends OpenAjax
{
    /**
     * Override printing preivews of full reports for complaints
     * and compliments.
     *
     * @param  boolean $isAdmin
     * @return array
     */
    public function printPreviewReportCustom($isAdmin = false)
    {
        $coreTbl = $GLOBALS["SL"]->coreTbl;
        $coreAbbr = $GLOBALS["SL"]->coreTblAbbr();
        if (!isset($this->sessData->dataSets[$coreTbl]) 
            || !isset($this->sessData->dataSets["incidents"])) {
            return '';
        }
        $coreRec = $this->sessData->dataSets[$coreTbl][0];
        $incident = $this->sessData->dataSets["incidents"][0];
        $titleWho = '';
        if ($this->canPrintFullReportByRecordSpecs(
                $this->sessData->dataSets[$coreTbl][0])) {
            $titleWho = $this->getCivName(
                'Civilians', 
                $this->sessData->dataSets["civilians"][0], 
                0
            );
            $titleWho = str_replace('(Victim #1)', '', 
                str_replace('(Witness #1)', '', $titleWho));

        }
        $where = $this->getReportWhereLine(0, true);
        $deptList = '';
        $depts = ((isset($this->sessData->dataSets["departments"])) 
            ? $this->sessData->dataSets["departments"] : null);
        if ($depts && sizeof($depts) > 0) {
            foreach ($depts as $i => $d) {
                if (isset($d->dept_name)
                    && trim($d->dept_name) != 'Not sure about department') {
                    $deptList .= ((trim($deptList) != '') ? ', ' : '') 
                        . '<a href="/dept/' . $d->dept_slug . '">'
                        . str_replace('Department', 'Dept', $d->dept_name) 
                        . '</a>';
                }
            }
        }
        $editable = $this->recordIsEditable(
            $coreTbl, 
            $coreRec->getKey(), 
            $coreRec
        );
        $url = '';
        if (isset($coreRec->{ $coreAbbr . 'public_id' }) 
            && intVal($coreRec->{ $coreAbbr . 'public_id' }) > 0) {
            $url = '/' . (($coreAbbr == 'com_') ? 'complaint' : 'compliment')
                . '/read-' . $coreRec->{ $coreAbbr . 'public_id' };
        } else {
            $url = '/' . (($coreAbbr == 'com_') ? 'complaint' : 'compliment')
                . '/readi-' . $coreRec->{ $coreAbbr . 'id' };
        }
        $storyPrev = '';
        if ($this->canPrintFullReport()) {
            $storyPrev = $coreRec->{ $coreAbbr . 'summary' };
        }
        return view(
            'vendor.openpolice.complaint-report-preview', 
            [
                "uID"         => $this->v["uID"],
                "editable"    => $editable,
                "storyPrev"   => $storyPrev,
                "coreAbbr"    => $coreAbbr,
                "complaint"   => $coreRec, 
                "incident"    => $incident, 
                "comDate"     => $this->getComplaintDate($incident, $coreRec),
                "comDateAb"   => $this->getComplaintDate($incident, $coreRec, 'M'), 
                "comDateFile" => $this->getComplaintDateOPC($coreRec), 
                "comDateFileAb" => $this->getComplaintDateOPC($coreRec, 'M'), 
                "comUser"     => User::find($coreRec->com_user_id),
                "titleWho"    => $titleWho,
                "comWhere"    => ((isset($where[1])) ? $where[1] : ''),
                "allegations" => $this->commaAllegationListSplit(),
                "deptList"    => $deptList,
                "url"         => $url,
                "featureImg"  => ''
            ]
        )->render();
    }
    
    /**
     * Retrieve the date of the current complaint's incident.
     *
     * @param  App\Models\OPIncidents $inc
     * @param  App\Models\OPComplaints $com
     * @return string
     */
    protected function getComplaintDate($inc, $com, $monthStyle = 'F')
    {
        $comDate = date($monthStyle . ' Y', strtotime($inc->inc_time_start));
        if ($this->shouldPrintFullDate($com)) {
            $comDate = date('m/d/Y', strtotime($inc->inc_time_start));
        }
        return $comDate;
    }
    
    /**
     * Retrieve the date the current complaint 
     * was submitted to OpenPolice.org.
     *
     * @param  App\Models\OPComplaints $com
     * @return string
     */
    protected function getComplaintDateOPC($com, $monthStyle = 'F')
    {
        $comDate = date($monthStyle . ' Y', strtotime($com->com_record_submitted));
        if ($this->shouldPrintFullDate($com)) {
            $comDate = date('m/d/Y', strtotime($com->com_record_submitted));
        }
        return $comDate;
    }
    
    /**
     * Printing preivews of full reports when only provide 
     * the complaint data record.
     *
     * @param  App\Models\OPComplaints $com
     * @return string
     */
    protected function getComplaintPreviewByRow($com)
    {
        $ret = '';
        $cacheName = 'complaint' . $com->com_id . '-preview-' 
            . (($GLOBALS["SL"]->x["isPublicList"]) ? 'public' : 'sensitive');
        if (!$GLOBALS["SL"]->REQ->has('refresh')) {
            $ret = Cache::get($cacheName, '');
            if ($ret != '') {
                return $ret;
            }
        }
        $tbls = [
            'complaints', 
            'incidents', 
            'alleg_silver', 
            'allegations', 
            'departments', 
            'stops'
        ];
        $this->allegations = [];
        foreach ($tbls as $tbl) {
            $this->sessData->dataSets[$tbl] = [];
        }
        $this->sessData->dataSets["complaints"][0] = $com;
        $this->sessData->dataSets["incidents"][0]
            = OPIncidents::find($com->com_incident_id);
        $this->sessData->dataSets["alleg_silver"][0] 
            = OPAllegSilver::where('alle_sil_complaint_id', $com->com_id)
                ->first();
        $this->sessData->dataSets["allegations"] 
            = OPAllegations::where('alle_complaint_id', $com->com_id)
                ->get();
        $this->sessData->dataSets["departments"] = DB::table('op_departments')
            ->join('op_links_complaint_dept', 'op_departments.dept_id', 
                '=', 'op_links_complaint_dept.lnk_com_dept_dept_id')
            ->where('op_links_complaint_dept.lnk_com_dept_complaint_id', $com->com_id)
            ->select('op_departments.*')
            ->get();
        $this->sessData->dataSets["stops"] = DB::table('op_stops')
            ->join('op_event_sequence', 'op_stops.stop_event_sequence_id', 
                '=', 'op_event_sequence.eve_id')
            ->where('op_event_sequence.eve_complaint_id', $com->com_id)
            ->select('op_stops.*')
            ->get();
        $ret = $this->printPreviewReportCustom();
        Cache::put($cacheName, $ret);
        return $ret;
    }
    
    /**
     * Printing general preivew listings of complaints.
     *
     * @return string
     */
    protected function printComplaintsPreviews()
    {
        $ret = '';
        //$GLOBALS["SL"]->pageView = 'public';
        $this->initSearcher();
        $this->searcher->getSearchFilts();
        $typeDef = $GLOBALS["SL"]->def->getID(
            'Complaint Type', 
            'Police Complaint'
        );
        $xtra = "whereIn('com_status', [" 
            . implode(", ", $this->getPublishedStatusList('complaints')) . "])->"
            . "where('com_type', " . $typeDef . ")->";
        $this->searcher->loadAllComplaintsPublic($xtra);
        if ($this->searcher->v["allcomplaints"]->isNotEmpty()) {
            foreach ($this->searcher->v["allcomplaints"] as $i => $com) {
                $ret .= '<div class="pB20 mB10"><div class="slCard">' 
                    . $this->getComplaintPreviewByRow($com) . '</div></div>';
            }
        }
        return $ret;
    }
    
    /**
     * Print all the standard filters used to manage listings
     * of complaints or compliments.
     *
     * @param  int $nID
     * @param  string $view
     * @return string
     */
    protected function printComplaintsFilters($nID, $view = 'list')
    {
        if (!isset($this->searcher->v["sortLab"]) 
            || $this->searcher->v["sortLab"] == '') {
            $this->searcher->v["sortLab"] = 'date';
        }
        if (!isset($this->searcher->v["sortDir"]) 
            || $this->searcher->v["sortDir"] == '') {
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
            view(
                'vendor.openpolice.complaint-listing-filters-status', 
                [ "srchFilts"  => $this->searcher->searchFilts ]
            )->render(),
            (sizeof($this->searcher->searchFilts["comstatus"]) > 0)
        );
        $stateFilts = $GLOBALS["SL"]->printAccordian(
            'By State',
            view(
                'vendor.openpolice.complaint-listing-filters-states', 
                [ "srchFilts"  => $this->searcher->searchFilts ]
            )->render(),
            (sizeof($this->searcher->searchFilts["states"]) > 0)
        );
        $allegFilts = $GLOBALS["SL"]->printAccordian(
            'By Allegation',
            view(
                'vendor.openpolice.complaint-listing-filters-allegs', 
                [
                    "allegTypes" => $this->worstAllegations,
                    "srchFilts"  => $this->searcher->searchFilts
                ]
            )->render(),
            (sizeof($this->searcher->searchFilts["allegs"]) > 0)
        );
        $victFilts = $GLOBALS["SL"]->printAccordian(
            'By Victim Description',
            view(
                'vendor.openpolice.complaint-listing-filters-vict', 
                [
                    "races"      => $GLOBALS["SL"]->def->getSet('Races'),
                    "srchFilts"  => $this->searcher->searchFilts
                ]
            )->render(),
            (sizeof($this->searcher->searchFilts["victgend"]) > 0
                || sizeof($this->searcher->searchFilts["victrace"]) > 0)
        );
        $offFilts = $GLOBALS["SL"]->printAccordian(
            'By Officer Description',
            view(
                'vendor.openpolice.complaint-listing-filters-off', 
                [
                    "races"      => $GLOBALS["SL"]->def->getSet('Races'),
                    "srchFilts"  => $this->searcher->searchFilts
                ]
            )->render(),
            (sizeof($this->searcher->searchFilts["offgend"]) > 0
                || sizeof($this->searcher->searchFilts["offrace"]) > 0)
        );
        return view(
            'vendor.openpolice.complaint-listing-filters', 
            [
                "nID"         => $nID,
                "view"        => $view,
                "statusFilts" => $statusFilts,
                "stateFilts"  => $stateFilts,
                "allegFilts"  => $allegFilts,
                "victFilts"   => $victFilts,
                "offFilts"    => $offFilts,
                "srchFilts"   => $this->searcher->searchFilts
            ]
        )->render();
    }
    
    /**
     * Print the management page for complaints, 
     * with multiple view options.
     *
     * @param  int $nID
     * @param  string $view
     * @return string
     */
    protected function printComplaintListing($nID, $view = 'list')
    {
        $pageUrl = $_SERVER["REQUEST_URI"];
        if (isset($this->v["isAdmin"]) && $this->v["isAdmin"]) {
            $pageUrl .= '—ADMIN';
        } elseif (!isset($this->v["uID"]) && $this->v["uID"] <= 0) {
            $pageUrl .= '—PUBLIC';
        }
        $ret = $GLOBALS["SL"]->chkCache($pageUrl, 'search-html', 1);
        if ($GLOBALS["SL"]->REQ->has('showPreviews')
            && !$GLOBALS["SL"]->REQ->has('refresh')
            && trim($ret) != '') {
            return $ret;
        }
        if (!isset($GLOBALS["SL"]->x["isHomePage"])) {
            $GLOBALS["SL"]->x["isHomePage"] = false;
        }
        if ($GLOBALS["SL"]->REQ->has('update')) {
            $this->updateNewPrivacy();
        }
        $this->v["sView"] = $view;
        if ($GLOBALS["SL"]->REQ->has('sView')) {
            $this->v["sView"] = $GLOBALS["SL"]->REQ->sView;
        } // elseif ...
        if ($GLOBALS["SL"]->x["isPublicList"]) {
            $this->v["sView"] = 'lrg';
        }
        $this->v["complaints"] 
            = $this->v["complaintsPreviews"] 
            = $this->v["comInfo"] 
            = $this->v["lastNodes"] 
            = $this->v["ajaxRefreshs"] 
            = [];
        $this->v["filtersDesc"] = '';
        $this->v["firstComplaint"] = [ 0, 0 ];
        $this->initSearcher();
        $this->searcher->getSearchFilts();
        $this->v["listPrintFilters"] = str_replace(
            'btn-sm updateSearchFilts', 
            'btn-sm searchDeetFld', 
            $this->printComplaintsFilters($nID, $this->v["sView"])
        );

        $listings = $this->printComplaintListingResults($nID, $view);
        if ($GLOBALS["SL"]->REQ->has('showPreviews')) {
            return $listings;
        //} elseif ($nID == 1418) {
            //echo $listings;
            //exit;
        }

        $this->printComplaintFiltDescPrev();
        $this->searcher->searchFiltsURL();
        $this->v["searchFiltsURL"] = $this->searcher->v["searchFiltsURL"];
        $this->v["sortLab"]        = $this->searcher->v["sortLab"];
        $this->v["sortDir"]        = $this->searcher->v["sortDir"];
        $this->v["allegTypes"]     = $this->worstAllegations;

        if (in_array($nID, [1418, 2384])) { // !$GLOBALS["SL"]->x["isHomePage"]) {
            $GLOBALS["SL"]->pageAJAX .= view(
                'vendor.openpolice.nodes.1418-admin-complaints-listing-ajax', 
                $this->v
            )->render();
        }
//echo '<pre>'; print_r($this->v["complaints"]); echo '</pre>'; exit;
        $ret = view(
                'vendor.openpolice.nodes.1418-admin-complaints-listing', 
                $this->v
            )->render() . view(
                'vendor.openpolice.nodes.1418-admin-complaints-listing-styles', 
                $this->v
            )->render();
        $GLOBALS["SL"]->putCache($pageUrl, $ret, 'search-html', 1);
        if ($GLOBALS["SL"]->REQ->has('ajax')) {
            echo $ret;
            exit;
        }
        return $ret;
    }
    
    /**
     * Print the actual search results for managing complaints.
     *
     * @param  int $nID
     * @param  string $view
     * @return string
     */
    protected function printComplaintListingResults($nID, $view = 'list')
    {
        $cacheKey = $this->searcher->searchFiltsURL() 
            . $GLOBALS["SL"]->getCacheSffxAdds();
        $cache = $GLOBALS["SL"]->chkCache($cacheKey, 'srch-results', 1);
        if ($cache && isset($cache->cach_value)) {
            return $cache->cach_value;
        }

        // run query into $compls1
        $eval = $this->printComplaintListQry1($nID);
        eval($eval);

        // run 2nd-round queries
        $compls2 = $this->printComplaintListQry2($nID, $compls1);

        unset($compls1);
        $this->printComplaintFiltsDesc($nID);

        if ($compls2 && sizeof($compls2) > 0) {
            foreach ($compls2 as $i => $com) {
                $this->printComplaintListingResultsAdd($com);
            }
            krsort($this->v["complaints"]);
        }
        if ($this->v["sView"] == 'lrg') {
            $this->printComplaintListingResultsPreviews();
            $this->printComplaintFiltDescPrev();
            $content = view(
                    'vendor.openpolice.nodes.1418-admin-complaints-listing-previews', 
                    $this->v
                )->render();
            $GLOBALS["SL"]->putCache($cacheKey, $content, 'srch-results', 1);
            return $content;
        }
        return '<!-- -->';
    }
    
    /**
     * Add complaint to search results for managing complaints.
     *
     * @param  OPComplaints $com
     * @return string
     */
    protected function printComplaintListingResultsAdd($com)
    {
        if ($this->v["firstComplaint"][0] == 0) {
            $this->v["firstComplaint"] = [
                intVal($com->com_public_id), 
                $com->com_id
            ];
        }
        $this->v["comInfo"][$com->com_public_id] = [
            "depts"     => '',
            "submitted" => ''
        ];
        $dChk = DB::table('op_links_complaint_dept')
            ->where('op_links_complaint_dept.lnk_com_dept_complaint_id', 
                $com->com_id)
            ->leftJoin('op_departments', 'op_departments.dept_id', 
                '=', 'op_links_complaint_dept.lnk_com_dept_dept_id')
            ->select('op_departments.dept_name', 'op_departments.dept_slug')
            ->orderBy('op_departments.dept_name', 'asc')
            ->get();
        if ($dChk && sizeof($dChk) > 0) {
            foreach ($dChk as $i => $d) {
                $comma = (($i > 0) ? ', ' : '');
                $this->v["comInfo"][$com->com_public_id]["depts"] .= $comma
                    . str_replace('Department' , 'Dept', $d->dept_name);
            }
        }
        $comTime = strtotime($com->updated_at);
        if (trim($com->com_record_submitted) != '' 
            && $com->com_record_submitted != '0000-00-00 00:00:00') {
            $comTime = strtotime($com->com_record_submitted);
        }
        if (!isset($com->com_status) || intVal($com->com_status) <= 0) {
            $com->com_status = $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete');
            OPComplaints::find($com->com_id)
                ->update([ "com_status" => $com->com_status ]);
        }
        if (!isset($com->com_type) || intVal($com->com_type) <= 0) {
            $com->com_type = $GLOBALS['SL']->def->getID('Complaint Type', 'Unreviewed');
            OPComplaints::find($com->com_id)
                ->update([ "com_type" => $com->com_type ]);
        }
        $cutoffTime = mktime(date("H"), date("i"), date("s"), 
            date("m"), date("d")-1, date("Y"));
        if ($comTime < $cutoffTime) {
            if (!isset($com->com_summary) || trim($com->com_summary) == '') {
                OPComplaints::find($com->com_id)
                    ->delete();
                $comTime = false;
            }
        }
        if ($comTime !== false) {
            $sortInd = $comTime;
            $this->v["comInfo"][$com->com_public_id]["submitted"] 
                = date("n/j/Y", $comTime);
            $incDef = $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete');
            if ($com->com_status == $incDef) {
                if ($com->com_submission_progress > 0 
                    && !isset($this->v["lastNodes"][$com->com_submission_progress])) {
                    $node = SLNode::find($com->com_submission_progress);
                    if ($node && isset($node->node_prompt_notes)) {
                        $this->v["lastNodes"][$com->com_submission_progress] 
                            = $node->node_prompt_notes;
                    }
                }
            }
            $this->v["complaints"][$sortInd] = $com;
        }
        if (!isset($com->com_alleg_list) || trim($com->com_alleg_list) == '') {
            $this->v["ajaxRefreshs"][] = $com->com_public_id;
        }
        return true;
    }
    
    /**
     * Generate previews for search results.
     *
     * @return boolean
     */
    protected function printComplaintListingResultsPreviews()
    {
        if (sizeof($this->v["complaints"]) > 0) {
            foreach ($this->v["complaints"] as $i => $com) {
                if (!$GLOBALS["SL"]->x["isHomePage"] 
                    || sizeof($this->v["complaintsPreviews"]) < 6) {
                    $ret = '';
                    $view = (($GLOBALS["SL"]->x["isPublicList"]) ? 'public' : 'sensitive');
                    $cacheName = 'complaint' . $com->com_id . '-preview-' . $view;
                    if (!$GLOBALS["SL"]->REQ->has('refresh')) {
                        $ret = Cache::get($cacheName, '');
                    }
                    if ($ret == '') {
                        $this->loadAllSessData('complaints', $com->com_id);
                        $ret = $this->printPreviewReport();
                        Cache::put($cacheName, $ret);
                        //$this->printPreviewReportCustom($isAdmin);
                    }
                    $this->v["complaintsPreviews"][] = '<div id="reportPreview' 
                        . $com->com_id . '" class="reportPreview">' . $ret . '</div>';
                }
            }
        }
        return true;
    }

    /**
     * Updates complaint records for the new atomized privacy options.
     *
     * @return boolean
     */
    protected function updateNewPrivacy()
    {
        /*
        $chk = OPComplaints::whereNotNull('com_privacy')
            ->where('com_privacy', '>', 0)
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $com) {
                $prv = $GLOBALS["SL"]->def->getVal(
                    'Privacy Types', 
                    intVal($com->com_privacy)
                );
                if (!isset($com->com_anon) || $com->com_anon === null) {
                    $com->com_anon = 0;
                    if (in_array($prv, ['Completely Anonymous', 'Anonymized'])) {
                        $com->com_anon = 1;
                    }
                }
                if (!isset($com->com_publish_user_name) 
                    || $com->com_publish_user_name === null) {
                    $com->com_publish_user_name = 0;
                    if ($prv == 'Submit Publicly') {
                        $com->com_publish_user_name = 1;
                    }
                }
                if (!isset($com->com_publish_officer_name) 
                    || $com->com_publish_officer_name === null) {
                    $com->com_publish_officer_name = 0;
                    if ($prv == 'Submit Publicly') {
                        $com->com_publish_officer_name = 1;
                    }
                }
                $com->save();
            }
        }
        */
        return true;
    }

    /**
     * Creates a preview of the search results count for the top of the page.
     *
     * @return string
     */
    protected function printComplaintFiltDescPrev()
    {
        $this->v["complaintFiltDescPrev"] = '';
        if (isset($this->v["complaints"]) 
            && is_array($this->v["complaints"])) {
            $this->v["complaintFiltDescPrev"] 
                .= number_format(sizeof($this->v["complaints"])) . ' Found';
        }
        if (isset($this->v["filtersDesc"]) 
            && trim($this->v["filtersDesc"]) != '') {
            $this->v["complaintFiltDescPrev"] .= '<span class="mL5 slGrey">'
                . $GLOBALS["SL"]->wordLimitDotDotDot(trim($this->v["filtersDesc"]), 20) 
                . '</span>';
        }
        return $this->v["complaintFiltDescPrev"];
    }

    /**
     * First main complaint management listings query.
     *
     * @param  int $nID
     * @return DB
     */
    protected function printComplaintListQry1($nID)
    {
        $hasStateFilt = (sizeof($this->searcher->searchFilts["states"]) > 0);
        $eval = "\$compls1 = DB::table('op_complaints')
            ->join('op_incidents', function (\$joi) {
                \$joi->on('op_complaints.com_incident_id', '=', 'op_incidents.inc_id')"
                . (($hasStateFilt) ? "->whereIn('op_incidents.inc_address_state', ['"
                        . implode("', '", $this->searcher->searchFilts["states"]) . "'])" 
                    : "") . ";
            })";
        if ($hasStateFilt) {
            $this->v["filtersDesc"] .= ' & ' 
                . implode(', ', $this->searcher->searchFilts["states"]);
        }

        if (sizeof($this->searcher->searchFilts["allegs"]) > 0) {
            $filtDescTmp = '';
            $eval .= "->join('op_alleg_silver', function (\$joi) {
                \$joi->on('op_complaints.com_id', 
                    '=', 'op_alleg_silver.alle_sil_complaint_id')";
            foreach ($this->searcher->searchFilts["allegs"] as $i => $allegID) {
                $eval .= "->" . (($i > 0) ? "orWhere" : "where") 
                    . "('op_alleg_silver." . $this->getAllegFldName($allegID) 
                    . "', 'Y')";
                $filtDescTmp = ' or ' 
                    . $GLOBALS["SL"]->def->getVal('Allegation Type', $allegID);
            }
            $eval .= "; })";
            $this->v["filtersDesc"] .= ' & ' . substr($filtDescTmp, 3);
        }

        $eval .= "->leftJoin('op_civilians', function (\$joi) {
                \$joi->on('op_complaints.com_id', '=', 'op_civilians.civ_complaint_id')
                    ->where('op_civilians.civ_is_creator', 'Y');
            })
            ->leftJoin('op_person_contact', 'op_civilians.civ_person_id', 
                '=', 'op_person_contact.prsn_id')";
        
        if (isset($this->v["fltIDs"]) 
            && sizeof($this->v["fltIDs"]) > 0) {
            $fltIDs = '';
            foreach ($this->v["fltIDs"] as $ids) {
                if (sizeof($ids) > 0) {
                    foreach ($ids as $id) {
                        $fltIDs .= ', ' . $id;
                    }
                }
            }
            if (trim($fltIDs) != '') {
                $eval .= "->whereIn('op_complaints.com_id', [" 
                    . substr($fltIDs, 2) . "])";
            }
        }
        if (isset($GLOBALS["SL"]->x["reqPics"]) && $GLOBALS["SL"]->x["reqPics"]) {
            $eval .= "->whereIn('op_complaints.com_user_id', [" 
                . implode(", ", $GLOBALS["SL"]->getComplaintWithProfilePics())
                . "])";
        }
        $eval .= $this->searcher->getSearchFiltQryStatus()
            . "->select('op_complaints.*', 'op_person_contact.prsn_name_first', 
            'op_person_contact.prsn_name_last', 'op_person_contact.prsn_email', 'op_incidents.*')"
            . $this->searcher->getSearchFiltQryOrderBy() 
            . "->get();";
        return $eval;
    }

    /**
     * Second main complaint management listings query. A separate pass
     * to further filter results, too hairy for the main filter query.
     *
     * @param  int $nID
     * @param  array $compls1
     * @return DB
     */
    protected function printComplaintListQry2($nID, $compls1)
    {
        $GLOBALS["SL"]->xmlTree["coreTbl"] = 'complaints';
        $compls2 = [];
        if ($compls1 && sizeof($compls1) > 0) {
            foreach ($compls1 as $com) {
                $comID = $com->com_id;
                $inFilter = true;
                if (sizeof($this->searcher->searchFilts["victgend"]) > 0
                    || sizeof($this->searcher->searchFilts["victrace"]) > 0) {
                    $chk = DB::table('op_physical_desc')
                        ->join('op_civilians', function ($joi) use ($comID) {
                            $joi->on('op_physical_desc.phys_id', '=', 'op_civilians.civ_phys_desc_id')
                                ->where('op_civilians.civ_complaint_id', $comID)
                                ->where('op_civilians.civ_role', 'Victim');
                        })
                        ->select('op_physical_desc.phys_id', 'op_physical_desc.phys_gender')
                        ->get();
                    if ($chk->isNotEmpty()) {
                        $flt = $this->searcher->searchFilts["victgend"];
                        if (sizeof($flt) > 0) {
                            if (!$this->chkFiltPhysGend($chk, $flt)) {
                                $inFilter = false;
                            }
                        }
                        $flt = $this->searcher->searchFilts["victrace"];
                        if (sizeof($flt) > 0) {
                            if (!$this->chkFiltPhysRace($chk, $flt)) {
                                $inFilter = false;
                            }
                        }
                    }
                }
                if ($inFilter 
                    && (sizeof($this->searcher->searchFilts["offgend"]) > 0
                        || sizeof($this->searcher->searchFilts["offrace"]) > 0)) {
                    $chk = DB::table('op_physical_desc')
                        ->join('op_officers', function ($joi) use ($comID) {
                            $joi->on('op_physical_desc.phys_id', '=', 'op_officers.off_phys_desc_id')
                                ->where('op_officers.off_complaint_id', $comID);
                        })
                        ->select('op_physical_desc.phys_id', 'op_physical_desc.phys_gender')
                        ->get();
                    if ($chk->isNotEmpty()) {
                        $flt = $this->searcher->searchFilts["offgend"];
                        if (sizeof($flt) > 0) {
                            if (!$this->chkFiltPhysGend($chk, $flt)) {
                                $inFilter = false;
                            }
                        }
                        $flt = $this->searcher->searchFilts["offrace"];
                        if (sizeof($flt) > 0) {
                            if (!$this->chkFiltPhysRace($chk, $flt)) {
                                $inFilter = false;
                            }
                        }
                    }
                }
                if ($inFilter && trim($this->searcher->searchTxt) != '') {
                    $dump = SLSearchRecDump::where('sch_rec_dmp_tree_id', 1)
                        ->where('sch_rec_dmp_rec_id', $comID)
                        ->first();
                    if (!$dump || !isset($dump->sch_rec_dmp_id)) {
                        $dump = $this->genRecDump($comID, true);
                    }
                    $pos = stripos($dump->sch_rec_dmp_rec_dump, $this->searcher->searchTxt);
                    if ($pos === false) {
                        $inFilter = false;
                    }
                    /*
                    } else {
                        $chk = SLSearchRecDump::where('sch_rec_dmp_tree_id', 1)
                            ->where('sch_rec_dmp_rec_id', $comID)
                            ->where('sch_rec_dmp_rec_dump', 'LIKE', '%' 
                                . $this->searcher->searchTxt . '%')
                            ->select('sch_rec_dmp_id')
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

    /**
     * Load a written description of the current filters applied to
     * the complaint listings on this page load.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function printComplaintFiltsDesc($nID)
    {
        $this->v["filtersDesc"] .= $this->searcher->getSearchFiltDescPeeps()
            . $this->searcher->getSearchFiltDescStatus();
        if (trim($this->v["filtersDesc"]) != '') {
            $this->v["filtersDesc"] = substr($this->v["filtersDesc"], 2);
        }
        return true;
    }

    /**
     * Check whether or not the current gender filters match
     * and physical description records passed in.
     *
     * @param  array $chkPhys
     * @param  array $matches
     * @return boolean
     */
    protected function chkFiltPhysGend($chkPhys, $matches = [])
    {
        $inFilterGend = false;
        foreach ($chkPhys as $phys) {
            if (isset($phys->phys_gender) && trim($phys->phys_gender) != '') {
                if (in_array('T', $matches)) {
                    if (!in_array($phys->phys_gender, ['M', 'F'])) {
                        $inFilterGend = true;
                    }
                } elseif (in_array('M', $matches) && $phys->phys_gender == 'M') {
                    $inFilterGend = true;
                } elseif (in_array('F', $matches) && $phys->phys_gender == 'F') {
                    $inFilterGend = true;
                }
            }
        }
        return $inFilterGend;
    }
    
    /**
     * Check whether or not the current race filters match
     * and physical description records passed in.
     *
     * @param  array $chkPhys
     * @param  array $matches
     * @return boolean
     */
    protected function chkFiltPhysRace($chkPhys, $matches = [])
    {
        $inFilterRace = false;
        foreach ($chkPhys as $phys) {
            $chkRace = OPPhysicalDescRace::where('phys_race_phys_desc_id', $phys->phys_id)
                ->whereIn('phys_race_race', $matches)
                ->select('phys_race_id')
                ->get();
            if ($chkRace->isNotEmpty()) {
                $inFilterRace = true;
            }
        }
        return $inFilterRace;
    }
    
    /**
     * Load all complaints for the current user 
     * to appear on their profile.
     *
     * @param  int $nID
     * @return string
     */
    protected function printProfileMyComplaints($nID)
    {
        $ret = '';
        $isOwner = true;
        $uID = $this->v["uID"];
        if (isset($this->v["profileUser"]) 
            && isset($this->v["profileUser"]->id)
            && intVal($this->v["profileUser"]->id) > 0
            && $uID != $this->v["profileUser"]->id) {
            $uID = intVal($this->v["profileUser"]->id);
            $isOwner = false;
        }
        $typeDef = $GLOBALS["SL"]->def->getID(
            'Complaint Type', 
            'Police Complaint'
        );
        if ($uID > 0) {
            $usr = User::find($uID);
            /* $name = 'Your';
            if ($usr && isset($usr->name) && trim($usr->name) != '') {
                $name = trim($usr->name) . '\'s';
            } */
            $complaints = $compliments = null;
            if ($isOwner 
                || (isset($this->v["isAdmin"]) && $this->v["isAdmin"])) {
                $complaints = OPComplaints::where('com_user_id', $uID)
                    ->where('com_status', '>', 0)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $status = $this->getPublishedStatusList('complaints');
                $complaints = OPComplaints::where('com_user_id', $uID)
                    ->whereIn('com_status', $status)
                    ->where('com_type', $typeDef)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            if ($complaints->isNotEmpty()) {
                $loadURL = '/record-prevs/1?rawids=';
                foreach ($complaints as $i => $rec) {
                    $loadURL .= (($i > 0) ? ',' : '') . $rec->com_id;
                }
                $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID 
                    . 'ajaxLoadA").load("' . $loadURL . '");' . "\n";
            }
            if ($isOwner 
                || (isset($this->v["isAdmin"]) && $this->v["isAdmin"])) {
                $compliments = OPCompliments::where('compli_user_id', $uID)
                    ->where('compli_status', '>', 0)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $status = $this->getPublishedStatusList('compliments');
                $compliments = OPCompliments::where('compli_user_id', $uID)
                    ->where('compli_status', $status)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            if ($compliments->isNotEmpty()) {
                $loadURL = '/record-prevs/5?rawids=';
                foreach ($compliments as $i => $rec) {
                    $loadURL .= (($i > 0) ? ',' : '') . $rec->compli_id;
                }
                $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID 
                    . 'ajaxLoadB").load("' . $loadURL . '");' . "\n";
            }
        }
        return view(
            'vendor.openpolice.nodes.1893-profile-complaints-compliments', 
            [
                "nID"         => $nID,
                "complaints"  => $complaints,
                "compliments" => $compliments
            ]
        )->render();
    }
    
    /**
     * Print a listing of all the beta testers who signed up,
     * with links to send them the invite emails,
     * and some general stats.
     *
     * @param  int $nID
     * @return string
     */
    protected function printBetaTesters($nID)
    {
        if ($GLOBALS["SL"]->REQ->has('invited')) {
            $invited = $GLOBALS["SL"]->REQ->get('invited');
            if (intVal($invited) > 0) {
                OPTesterBeta::find(intVal($invited))->update([ 'beta_invited' => date('Y-m-d') ]);
            }
        }
        $betas = OPTesterBeta::whereNotNull('beta_email')
            ->where('beta_email', 'NOT LIKE', '')
            ->orderBy('created_at', 'desc')
            ->get();
        $empties = OPTesterBeta::whereNotNull('beta_how_hear')
            ->where('beta_how_hear', 'NOT LIKE', '')
            ->get();
        //$GLOBALS["SL"]->addHshoo("#stats");
        $GLOBALS["SL"]->x["needsPlots"] = true;
        $this->sortBetas($betas, 'betaSignups');
        $this->sortBetas($empties, 'betaClicks');
        $betaLinks = [];
        if (sizeof($betas) > 0) {
            foreach ($betas as $i => $beta) {
                $bcc = '';
                if ($this->v["user"]->email != 'morgan'.'@'.'flexyourrights.org') {
                    $bcc = 'morgan'.'@'.'flexyourrights.org';
                }
                $betaLinks[$beta->beta_id] = '/dashboard/send-email?emaTemplate=28&emaTo='
                    . urlencode($beta->beta_email) . '&emaCC=' 
                    . urlencode($this->v["user"]->email) . '&emaBCC=' . $bcc 
                    . '&emaSwapName=' . urlencode($beta->beta_name);
                if (isset($this->v["yourUserContact"])) {
                    $you = $this->v["yourUserContact"];
                    $analystName = ((isset($you->prsn_name_first)) ? $you->prsn_name_first : '')
                        . ' ' . ((isset($you->prsn_name_last)) ? $you->prsn_name_last : '');
                    $betaLinks[$beta->beta_id] .= '&emaSwapAnalyst=' . urlencode(trim($analystName));
                }
                $betaLinks[$beta->beta_id] .= '&redir=' . urlencode('/dash/beta-test-signups?invited='
                        . $beta->beta_id . '#beta' . $beta->beta_id);
            }
        }
        $emptyNoRef = OPTesterBeta::whereNull('beta_how_hear')
            ->orWhere('beta_how_hear', 'LIKE', '')
            ->count();
        $tots = [
            "invited" => 0, 
            "waiting" => 0, 
            "emails"  => [] 
        ];
        foreach ($betas as $beta) {
            if (isset($beta->beta_invited) 
                && trim($beta->beta_invited) != ''
                && isset($beta->beta_email)
                && !in_array(strtolower($beta->beta_email), $tots["emails"])) {
                $tots["emails"][] = strtolower($beta->beta_email);
                $tots["invited"]++;
            } else {
                $tots["waiting"]++;
            }
        }
        return view(
            'vendor.openpolice.nodes.2234-beta-listing', 
            [
                "betas"      => $betas,
                "tots"       => $tots,
                "emptyNoRef" => $emptyNoRef,
                "totLoads"   => OPTesterBeta::count(),
                "betaLinks"  => $betaLinks
            ]
        )->render();
    }
    
    /**
     * Sort the sources for beta signups 
     * in order of most referrals.
     *
     * @param  int $nID
     * @return string
     */
    protected function sortBetas($betas, $divName)
    {
        $graph = [
            "divName" => $divName, 
            "values"  => '', 
            "labels"  => '' 
        ];
        $tots = [];
        if ($betas->isNotEmpty()) {
            foreach ($betas as $i => $beta) {
                $how = trim($beta->beta_how_hear);
                $how = str_replace('-police-dept', '', $how);
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
        $GLOBALS["SL"]->pageJAVA .= view(
            'vendor.survloop.reports.graph-bar-plot', 
            [
                "graph"  => $graph,
                "height" => 700
            ]
        )->render();
        return $graph;
    }
    
}