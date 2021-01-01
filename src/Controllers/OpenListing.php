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
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use Cache;
use App\Models\OPComplaints;
use App\Models\OPCompliments;
use App\Models\OPIncidents;
use App\Models\OPAllegSilver;
use App\Models\OPAllegations;
use App\Models\SLNode;
use App\Models\OPTesterBeta;
use App\Models\User;
use FlexYourRights\OpenPolice\Controllers\OpenListFilters;

class OpenListing extends OpenListFilters
{
    /**
     * Override printing preivews of full reports for complaints and compliments.
     *
     * @param  boolean $isAdmin
     * @return array
     */
    public function printPreviewReportCustom($isAdmin = false, $view = '')
    {
        $coreTbl = $GLOBALS["SL"]->coreTbl;
        $coreAbbr = $GLOBALS["SL"]->coreTblAbbr();
        if (!isset($this->sessData->dataSets[$coreTbl]) 
            || !isset($this->sessData->dataSets["incidents"])) {
            return '';
        }
        $com = $this->sessData->dataSets[$coreTbl][0];
        $incident = $this->sessData->dataSets["incidents"][0];
        $where = $this->getReportWhereLine(0, true);
        $storyPrev = '';
        if ($this->isStaffOrAdmin()
            || $this->canPrintFullReportByRecordSpecs($com)) {
            // $this->canPrintFullReport()) {
            $storyPrev = $com->{ $coreAbbr . 'summary' };
        }
        return view(
            'vendor.openpolice.complaint-report-preview', 
            [
                "uID"           => $this->v["uID"],
                "storyPrev"     => $storyPrev,
                "coreAbbr"      => $coreAbbr,
                "complaint"     => $com, 
                "incident"      => $incident, 
                "comDate"       => $this->getComplaintDate($incident, $com),
                "comDateAb"     => $this->getComplaintDate($incident, $com, 'M'), 
                "comDateFile"   => $this->getComplaintDateOPC($com), 
                "comDateFileAb" => $this->getComplaintDateOPC($com, 'M'), 
                "comUser"       => User::find($com->com_user_id),
                "titleWho"      => $this->printPreviewReportCustomWho($com),
                "comWhere"      => ((isset($where[1])) ? $where[1] : ''),
                "allegations"   => $this->commaAllegationListSplit(),
                "deptList"      => $this->printPreviewReportCustomDepts(),
                "url"           => $this->printPreviewReportCustomUrl($com, $coreAbbr),
                "editable"      => $this->recordIsEditable($coreTbl, $com->getKey(), $com),
                "featureImg"    => ''
            ]
        )->render();
    }

    /**
     * Print complainant's name in complaint/compliment preview.
     *
     * @param  App\Models\OPComplaints $com
     * @return string
     */
    private function printPreviewReportCustomWho($com)
    {
        $titleWho = '';
        if ($this->canPrintFullReportByRecordSpecs($com)) {
            $civRow = $this->sessData->dataSets["civilians"][0];
            $titleWho = $this->getCivName('Civilians', $civRow, 0);
            $titleWho = str_replace('(Victim #1)',  '', $titleWho);
            $titleWho = str_replace('(Witness #1)', '', $titleWho);
            $titleWho = str_replace('(Helper #1)',  '', $titleWho);
        }
        return $titleWho;
    }

    /**
     * Print department names in complaint/compliment preview.
     *
     * @return string
     */
    private function printPreviewReportCustomDepts()
    {
        $deptList = '';
        $depts = ((isset($this->sessData->dataSets["departments"])) 
            ? $this->sessData->dataSets["departments"] : null);
        $notSure = 'Not sure about department';
        if ($depts && sizeof($depts) > 0) {
            foreach ($depts as $i => $d) {
                if (isset($d->dept_name) && trim($d->dept_name) != $notSure) {
                    $deptList .= ((trim($deptList) != '') ? ', ' : '') 
                        . '<a href="/dept/' . $d->dept_slug . '">'
                        . $d->dept_name . '</a>';
                }
            }
        }
        return $deptList;
    }

    /**
     * Print complaint URL in complaint/compliment preview.
     *
     * @param  App\Models\OPComplaints $com
     * @param  string $coreAbbr
     * @return string
     */
    private function printPreviewReportCustomUrl($com, $coreAbbr)
    {
        $url = '';
        if (isset($com->{ $coreAbbr . 'public_id' }) 
            && intVal($com->{ $coreAbbr . 'public_id' }) > 0) {
            $url = '/' . (($coreAbbr == 'com_') ? 'complaint' : 'compliment')
                . '/read-' . $com->{ $coreAbbr . 'public_id' };
        } else {
            $url = '/' . (($coreAbbr == 'com_') ? 'complaint' : 'compliment')
                . '/readi-' . $com->{ $coreAbbr . 'id' };
        }
        return $url;
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
     * Printing preivews of full reports when only provided 
     * the complaint data record.
     *
     * @param  App\Models\OPComplaints $com
     * @return string
     */
    protected function getComplaintPreviewByRow($com)
    {
        $ret = '';
        $cacheName = 'complaint' . $com->com_id . '-preview-';
        if ($this->isStaffOrAdmin()) {
            $cacheName .= 'sensitive';
        } else {
            $cacheName .= 'public';
        }
        if (!$GLOBALS["SL"]->REQ->has('refresh')) {
            $ret = Cache::get($cacheName, '');
            if ($ret != '') {
                return $ret;
            }
        }
        $this->getComplaintPreviewByRowInit();
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
     * Initial tables to print preivews of full reports 
     * when only provided the complaint data record.
     *
     * @return boolean
     */
    protected function getComplaintPreviewByRowInit()
    {
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
        return true;
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
        $pageUrl = $_SERVER["REQUEST_URI"] . '?nID=' . $nID . '&view=' . $view;
        if ($GLOBALS["SL"]->REQ->has('dashResults')) {
            $pageUrl .= '&dashResults=1';
        }
        $this->printComplaintListingInit($nID, $view);
        $this->searcher->searchFiltsURL();
        $pageUrl .= $this->searcher->v["searchFiltsURL"];
        if ($this->isStaffOrAdmin()) {
            $pageUrl .= '—ADMIN';
        } else { // if (!isset($this->v["uID"]) && $this->v["uID"] <= 0)
            $pageUrl .= '—PUBLIC';
        }
        $ret = $GLOBALS["SL"]->chkCache($pageUrl, 'search-html', 1);
        if (trim($ret) == '' || $GLOBALS["SL"]->REQ->has('refresh')) {
            $listings = $this->printComplaintListingResults($nID, $view);
            $this->printComplaintFiltDescPrev();
            $this->searcher->searchFiltsURL();
            $this->v["searchFiltsURL"] = $this->searcher->v["searchFiltsURL"];
            $this->v["sortLab"]        = $this->searcher->v["sortLab"];
            $this->v["sortDir"]        = $this->searcher->v["sortDir"];
            $this->v["allegTypes"]     = $this->worstAllegations;
            if ($nID == 2384) {
                $this->v["sView"] = 'lrg';
                $url = '/ajax/search-complaint-previews?dashResults=1&ajax=1&limit=0';
                $GLOBALS["SL"]->setAutoRunSearch();
                $GLOBALS["SL"]->setDashSearchDiv('complaintResultsWrap');
                $GLOBALS["SL"]->setDashSearchUrl($url);
            }
            if ($GLOBALS["SL"]->REQ->has('dashResults')) {
                if ($nID == 2384) {
                    //$ret = $listings;
                    $ret = $this->printComplaintListingResultsLrg($nID);
                } else {
                    $ret = view(
                        'vendor.openpolice.nodes.1418-admin-complaints-dash-results', 
                        $this->v
                    )->render();
                }
            } else {
                $blade = 'vendor.openpolice.nodes.1418-admin-complaints-listing';
                if (in_array($nID, [1418, 2384])) { // !$GLOBALS["SL"]->x["isHomePage"]) {
                    $GLOBALS["SL"]->pageAJAX .= view($blade . '-ajax', $this->v)->render();
                }
                $ret = view($blade, $this->v)->render() 
                    . view($blade . '-styles', $this->v)->render();
            }
            $GLOBALS["SL"]->putCache($pageUrl, $ret, 'search-html', 1);
        }
        if ($GLOBALS["SL"]->REQ->has('ajax')) {
            echo $ret;
            exit;
        }
        return $ret;
    }
    
    /**
     * Initialize the management page for complaints.
     *
     * @param  int $nID
     * @param  string $view
     * @return boolean
     */
    protected function printComplaintListingInit($nID, $view = 'list')
    {
        if (!isset($GLOBALS["SL"]->x["isHomePage"])) {
            $GLOBALS["SL"]->x["isHomePage"] = false;
        }
        if ($GLOBALS["SL"]->REQ->has('update')) {
            $this->updateNewPrivacy();
        }
        $this->v["sView"] = $view;
        if ($GLOBALS["SL"]->REQ->has('sView')) {
            $this->v["sView"] = $view = $GLOBALS["SL"]->REQ->sView;
        } // elseif ...
        if ($GLOBALS["SL"]->x["isPublicList"]) {
            $this->v["sView"] = 'lrg';
        }
        $this->v["complaints"] 
            = $this->v["complaintsPreviews"] 
            = $this->v["complaintsPreviewsIDs"] 
            = $this->v["complaintsPreviewsUser"]
            = $this->v["complaintsPreviewsPriv"] 
            = $this->v["comInfo"] 
            = $this->v["lastNodes"] 
            = $this->v["ajaxRefreshs"] 
            = [];
        $this->v["filtersDesc"] = '';
        $this->v["firstComplaint"] = [ 0, 0 ];
        $this->initSearcher();
        $this->searcher->getSearchFilts();
        $this->v["listPrintFilters"] = $this->printComplaintsFilters($nID, $view);
        return true;
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
        /* $cacheKey = 'complaintListingResults' 
            . $this->searcher->searchFiltsURL() 
            . $GLOBALS["SL"]->getCacheSffxAdds();
        $cache = '';
        if ($this->v["sView"] == 'lrg') {
            $cache = $GLOBALS["SL"]->chkCache($cacheKey, 'ajax', 1);
            if ($cache != '') {
                return $cache;
            }
        } */
        $complaints = $this->runComplaintListQueries($nID);
        $this->printComplaintFiltsDesc($nID);
        if ($complaints && sizeof($complaints) > 0) {
            foreach ($complaints as $i => $com) {
                $this->printComplaintListingResultsAdd($com);
            }
            if (isset($this->searcher->v["sortDir"])
                && $this->searcher->v["sortDir"] == 'desc') {
                krsort($this->v["complaints"]);
            } else {
                ksort($this->v["complaints"]);
            }
            $first = true;
            foreach ($this->v["complaints"] as $com) {
                if ($first) {
                    $first = false;
                    $pubID = 0;
                    if (isset($com->com_public_id)) {
                        $pubID = intVal($com->com_public_id);
                    }
                    $this->v["firstComplaint"] = [
                        $pubID, 
                        intVal($com->com_id)
                    ];
                }
            }
        }
        $this->v["limit"] = 0;
        if ($GLOBALS["SL"]->REQ->has('limit')) {
            $this->v["limit"] = intVal($GLOBALS["SL"]->REQ->get('limit'));
        }
        if ($this->v["sView"] == 'lrg') {
            return $this->printComplaintListingResultsLrg($nID);
            //$GLOBALS["SL"]->putCache($cacheKey, $content, 'ajax', 1);
        }
        return '<!-- -->';
    }
    
    /**
     * Print the actual search results for large complaint previews.
     *
     * @param  int $nID
     * @return string
     */
    protected function printComplaintListingResultsLrg($nID)
    {
        $this->printComplaintListingResultsPreviews();
        $this->printComplaintFiltDescPrev();
//echo '<textarea style="width: 100%; height: 300px;">' . $this->v["complaintsPreviews"][0] . '</textarea><h3>Priv: ' . $this->v["complaintsPreviewsPriv"][0] . '</h3>'; exit;
//echo '<pre>Priv: '; print_r($this->v["complaintsPreviewsPriv"]); echo '</pre>'; exit;
        $this->v["isStaffSort"] = $this->isStaffOrAdmin();
        return view(
            'vendor.openpolice.nodes.1418-admin-complaints-listing-previews', 
            $this->v
        )->render();
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
                    $cacheName = 'complaint' . $com->com_id . '-preview-';
                    if ($this->isStaffOrAdmin()) {
                        $cacheName .= 'sensitive';
                    } else {
                        $cacheName .= 'public';
                    }
                    if (!$GLOBALS["SL"]->REQ->has('refresh')) {
                        $ret = $GLOBALS["SL"]->chkCache(
                            $cacheName, 
                            'search-rec', 
                            1, 
                            $com->com_id
                        );
                    }
                    if ($ret == '') {
                        $this->loadAllSessData('complaints', $com->com_id);
                        $ret = $this->printPreviewReport();
                        $GLOBALS["SL"]->putCache(
                            $cacheName, 
                            $ret,
                            'search-rec', 
                            1, 
                            $com->com_id
                        );
                        //$this->printPreviewReportCustom($isAdmin);
                    }
                    $this->v["complaintsPreviews"][] = '<div id="reportPreview' 
                        . $com->com_id . '" class="reportPreview">' . $ret . '</div>';
                    $this->v["complaintsPreviewsIDs"][]  = $com->com_id;
                    $this->v["complaintsPreviewsUser"][] = intVal($com->com_user_id);
                    $this->v["complaintsPreviewsPriv"][] = $this->canPrintFullReportByRecordSpecs($com);
                }
            }
        }
        return true;
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
            if ($isOwner || $this->isStaffOrAdmin()) {
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
            if ($isOwner || $this->isStaffOrAdmin()) {
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
     * Print search results across multiple data sets.
     *
     * @param  int $nID
     * @return string
     */
    protected function printSearchResults($nID)
    {
        $this->initSearcher();
        $this->searcher->getSearchFilts();
        $GLOBALS["SL"]->addHshoo("#departments");
        $GLOBALS["SL"]->addHshoo("#complaints");
        return view(
            'vendor.openpolice.nodes.1221-search-results-multi-data-sets', 
            [
                "nID"         => $nID,
                "dashView"    => ($nID == 1221),
                "isStaff"     => $this->isStaffOrAdmin(),
                "searcher"    => $this->searcher
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