<?php
/**
  * OpenAjax is a mid-level class which handles custom requests
  * via Ajax/jQuery patterns.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OPComplaints;
use App\Models\OPDepartments;
use App\Models\SLSearchRecDump;
use App\Models\SLZips;
use OpenPolice\Controllers\OpenComplaintSaves;

class OpenAjax extends OpenComplaintSaves
{
    /**
     * Check for ajax requests customized beyond 
     * SurvLoop's default behavior.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  string $over
     * @return boolean
     */
    public function runAjaxChecksCustom(Request $request, $over = '')
    {
        if ($request->has('policeDept')) {
            echo $this->ajaxPoliceDeptSearch($request);
            exit;
        }
        return false;
    }
    
    /**
     * Check for ajax requests customized beyond 
     * SurvLoop's default behavior, called via /ajax/{type}.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  string $type
     * @return boolean
     */
    public function ajaxChecksCustom(Request $request, $type = '')
    {
        if ($type == 'dept-kml-desc') {
            return $this->ajaxDeptKmlDesc($request);
        } elseif ($type == 'save-default-state') {
            return $this->saveVolunLocationForm($request);
        } elseif ($type == 'dept-search') { // public
            return $this->ajaxPoliceDeptSearch($request, 'public');
        } elseif ($type == 'home-complaint-previews') {
            $this->ajaxLoadHomeComplaintPreviews($request);
        } elseif ($type == 'search-complaint-previews') {
            $this->ajaxLoadSearchComplaintPreviews($request);
        }
        return '';
    }

    /**
     * Print home page complaints previews.
     *
     * @param  Illuminate\Http\Request  $request
     * @return string
     */
    public function ajaxLoadHomeComplaintPreviews(Request $request)
    {
        $GLOBALS["SL"]->x["isHomePage"] = true;
        $GLOBALS["SL"]->x["isPublicList"] = true;
        $GLOBALS["SL"]->x["reqPics"] = true;
        $GLOBALS["SL"]->pageView = 'public';
        return $this->printComplaintListing(2685, 'lrg');
    }

    /**
     * Print search complaints previews.
     *
     * @param  Illuminate\Http\Request  $request
     * @return string
     */
    public function ajaxLoadSearchComplaintPreviews(Request $request)
    {
        $GLOBALS["SL"]->x["isPublicList"] = true;
        $GLOBALS["SL"]->pageView = 'public';
        return $this->printComplaintListing(2685, 'lrg');
    }
    
    /**
     * Run the ajax request to search police departments 
     * and return clickable results.
     *
     * @param  Illuminate\Http\Request  $request
     * @return string
     */
    public function ajaxPoliceDeptSearch(Request $request, $view = 'survey')
    {
        if ($view == 'survey' && trim($request->get('policeDept')) == '') {
            return '<i>Please type part of the department\'s name to find it.</i>';
        }
        $GLOBALS["SL"]->loadStates();
        $loadUrl = '/ajax/dept-search';
        // Prioritize by Incident City first, also by Department size (# of officers)
        $this->chkDeptStateFlt($request);
        $searchStr = $this->chkDeptSearchFlt($request);
        $this->getDeptStateFltNames();

        list($sortLab, $sortDir) = $this->chkDeptSorts($this->v["reqState"]);
        $loadUrl .= $this->v["reqLike"] . '&states=' . implode(',', $this->v["reqState"]) 
            . '&sortLab=' . $sortLab . '&sortDir=' . $sortDir;
        $ret = $GLOBALS["SL"]->chkCache($loadUrl, 'search', 1);
        if (trim($ret) == '' || $request->has('refresh')) {
            $this->ajaxRunDeptSearch($this->v["reqLike"]);
            if ($sortLab != 'match') {
                $this->applyDeptSorts($this->v["depts"], $sortLab, $sortDir);
            }
            if ($view == 'survey') {
                $drop = $GLOBALS["SL"]->states->stateDrop($this->v["reqState"][0], true);
                $ret = view(
                    'vendor.openpolice.ajax.search-police-dept', 
                    [
                        "depts"            => $this->v["depts"], 
                        "search"           => $searchStr, 
                        "stateName"        => $this->v["stateNames"], 
                        "newDeptStateDrop" => $drop
                    ]
                )->render();
            } else {
                $ret = view(
                    'vendor.openpolice.ajax.department-previews', 
                    [
                        "depts"      => $this->v["depts"],
                        "deptSearch" => $searchStr,
                        "stateName"  => $this->v["stateNames"],
                        "sortLab"    => $sortLab,
                        "sortDir"    => $sortDir
                    ]
                )->render();
            }
            $GLOBALS["SL"]->putCache($loadUrl, $ret, 'search', 1);
        }
        echo $ret;
        exit;
    }
    
    /**
     * Run the ranked search for police departments.
     *
     * @param  string  $str
     * @return boolean
     */
    protected function ajaxRunDeptSearch($str)
    {
        if (!isset($this->v["deptIDs"])) {
            $this->v["deptIDs"] 
                = $this->v["depts"] 
                = [];
        }
        if (!isset($this->v["reqState"])) {
            $this->v["reqState"] = [];
        }
        $str = $this->loadDeptSearchFlt($str);
        if (sizeof($this->v["reqState"]) > 0
            && (sizeof($this->v["reqState"]) > 1 || $this->v["reqState"][0] != 'US')) {
            $this->ajaxRunDeptSearchStates($str);
        } elseif (!in_array($str, ['', '%%']) && sizeof($this->v["reqState"]) == 0) {
            $this->ajaxRunDeptSearchBasic($str);
        }
        if (sizeof($this->v["reqState"]) > 0 && in_array('US', $this->v["reqState"])) {
            $this->ajaxRunDeptSearchFederal($str);
        }
        return true;
    }
    
    /**
     * Run the ranked search for police departments within certain states.
     *
     * @param  string  $str
     * @param  boolean  $states
     * @return array
     */
    private function ajaxRunDeptSearchBasic($str, $states = false)
    {
        if (sizeof($this->v["deptSearchWords"]) == 0) {
            return false;
        }
        $evalStates = "";
        if ($states) {
            $evalStates = "->whereIn('dept_address_state', \$this->v['reqState'])";
        }
        foreach ($this->v["deptSearchWords"] as $word) {
            $wrd = '%' . $word . '%';
            $eval = "\$deptsRes = App\\Models\\OPDepartments::where('dept_name', 'LIKE', \$wrd)
                " . $evalStates . "
                ->orderBy('dept_jurisdiction_population', 'desc')
                ->orderBy('dept_tot_officers', 'desc')
                ->orderBy('dept_name', 'asc')
                ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                    'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                    'dept_address_city', 'dept_address_state')
                ->get();";
            eval($eval);
            $this->addDeptToResults($deptsRes);
        }
        foreach ($this->v["deptSearchWords"] as $word) {
            $wrd = '%' . $word . '%';
            $eval = "\$deptsRes = App\\Models\\OPDepartments::where('dept_address_city', 'LIKE', \$wrd)
                " . $evalStates . "
                ->orderBy('dept_jurisdiction_population', 'desc')
                ->orderBy('dept_tot_officers', 'desc')
                ->orderBy('dept_name', 'asc')
                ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                    'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                    'dept_address_city', 'dept_address_state')
                ->get();";
            eval($eval);
            $this->addDeptToResults($deptsRes);
        }
        foreach ($this->v["deptSearchWords"] as $word) {
            $wrd = '%' . $word . '%';
            $eval = "\$deptsRes = App\\Models\\OPDepartments::where('dept_address_county', 'LIKE', \$wrd)
                " . $evalStates . "
                ->orderBy('dept_jurisdiction_population', 'desc')
                ->orderBy('dept_tot_officers', 'desc')
                ->orderBy('dept_name', 'asc')
                ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                    'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                    'dept_address_city', 'dept_address_state')
                ->get();";
            eval($eval);
            $this->addDeptToResults($deptsRes);
        }
        foreach ($this->v["deptSearchWords"] as $word) {
            $wrd = '%' . $word . '%';
            $eval = "\$deptsRes = App\\Models\\OPDepartments::where('dept_address', 'LIKE', \$wrd)
                " . $evalStates . "
                ->orderBy('dept_jurisdiction_population', 'desc')
                ->orderBy('dept_tot_officers', 'desc')
                ->orderBy('dept_name', 'asc')
                ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                    'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                    'dept_address_city', 'dept_address_state')
                ->get();";
            eval($eval);
            $this->addDeptToResults($deptsRes);
        }
        return true;
    }
    
    /**
     * Run the ranked search for police departments within certain states.
     *
     * @param  string  $str
     * @return array
     */
    private function ajaxRunDeptSearchStates($str)
    {
        if (in_array($str, ['', '%%'])) {
            $deptsRes = OPDepartments::whereIn('dept_address_state', $this->v["reqState"])
                ->orderBy('dept_jurisdiction_population', 'desc')
                ->orderBy('dept_tot_officers', 'desc')
                ->orderBy('dept_name', 'asc')
                ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                    'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                    'dept_address_city', 'dept_address_state')
                ->get();
            $this->addDeptToResults($deptsRes);
        } else {
            $this->ajaxRunDeptSearchBasic($str, true);
            $zips = $counties = [];
            $cityZips = SLZips::where('zip_city', 'LIKE', $str)
                ->whereIn('zip_state', $this->v["reqState"])
                ->get();
            /* if ($cityZips->isEmpty()) {
                $cityZips = SLZips::where('zip_city', 'LIKE', $str)
                    ->get();
            } */
            if ($cityZips->isNotEmpty()) {
                foreach ($cityZips as $z) {
                    $zips[] = $z->zip_zip;
                    $counties[] = $z->zip_county;
                }
                $deptsMore = OPDepartments::whereIn('dept_address_zip', $zips)
                    ->orderBy('dept_name', 'asc')
                    ->get();
                $this->addDeptToResults($deptsMore);
                foreach ($counties as $c) {
                    $deptsMore = OPDepartments::where('dept_name', 'LIKE', '%' . $c . '%')
                        ->whereIn('dept_address_state', $this->v["reqState"])
                        ->orderBy('dept_jurisdiction_population', 'desc')
                        ->orderBy('dept_tot_officers', 'desc')
                        ->orderBy('dept_name', 'asc')
                        ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                            'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                            'dept_address_city', 'dept_address_state')
                        ->get();
                    $this->addDeptToResults($deptsMore);
                    $deptsMore = OPDepartments::where('dept_address_county', 'LIKE', '%' . $c . '%')
                        ->whereIn('dept_address_state', $this->v["reqState"])
                        ->orderBy('dept_jurisdiction_population', 'desc')
                        ->orderBy('dept_tot_officers', 'desc')
                        ->orderBy('dept_name', 'asc')
                        ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                            'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                            'dept_address_city', 'dept_address_state')
                        ->get();
                    $this->addDeptToResults($deptsMore);
                }
            }
        }
        return true;
    }
    
    /**
     * Run the ranked search for police departments within certain federal jurisdictions.
     *
     * @param  string  $str
     * @return array
     */
    private function ajaxRunDeptSearchFederal($str)
    {
        $fedDef = $GLOBALS["SL"]->def->getID(
            'Department Types',
            'Federal Law Enforcement'
        );
        if (!in_array($str, ['', '%%'])) {
            if (sizeof($this->v["deptSearchWords"]) > 0) {
                foreach ($this->v["deptSearchWords"] as $word) {
                    $wrd = '%' . $word . '%';
                    $deptsFed = OPDepartments::where('dept_type', $fedDef)
                        ->where('dept_name', 'LIKE', $wrd)
                        ->orderBy('dept_jurisdiction_population', 'desc')
                        ->orderBy('dept_tot_officers', 'desc')
                        ->orderBy('dept_name', 'asc')
                        ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                            'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                            'dept_address_city', 'dept_address_state')
                        ->get();
                    $this->addDeptToResults($deptsFed);
                }
            }
        } else {
            $deptsFed = OPDepartments::where('dept_type', $fedDef)
                ->orderBy('dept_jurisdiction_population', 'desc')
                ->orderBy('dept_tot_officers', 'desc')
                ->orderBy('dept_name', 'asc')
                ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                    'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                    'dept_address_city', 'dept_address_state')
                ->get();
            $this->addDeptToResults($deptsFed);
        }
        return true;
    }

    /**
     * Check for filtering by text search for department listings.
     *
     * @return array
     */
    protected function chkDeptSearchFlt($request)
    {
        $this->v["reqLike"] = $str = $loadUrl = '';
        if ($request->has('policeDept') && trim($request->policeDept)) {
            $str = trim($request->policeDept);
            $loadUrl .= '?survey=1';
        } elseif ($request->has('deptSearch') && trim($request->deptSearch) != '') {
            $str = trim($request->deptSearch);
        } elseif ($request->has('s') && trim($request->get('s')) != '') {
            $str = trim($request->get('s'));
        }
        return $this->loadDeptSearchFlt($str);
    }

    /**
     * Check for filtering by text search for department listings.
     *
     * @return array
     */
    protected function loadDeptSearchFlt($str)
    {
        $str = $this->chkDeptSearchTweak($str);
        $this->v["deptSearchWords"] = $tmp = [];
        $searchRaw = $GLOBALS["SL"]->mexplode(' ', $str);
        if (sizeof($searchRaw) > 0) {
            foreach ($searchRaw as $word) {
                $abbr = strtoupper($word);
                $state = $GLOBALS["SL"]->getState($abbr);
                if ($state != '') {
                    $this->chkDeptSearchAddState($abbr);
                } else {
                    $abbr = $GLOBALS["SL"]->states->getStateAbrr($word);
                    if ($abbr != '') {
                        $this->chkDeptSearchAddState($abbr);
                    } else {
                        if (!in_array($word, $this->v["deptSearchWords"])) {
                            $tmp[] = $word;
                        }
                    }
                }
            }
        }
        if (sizeof($tmp) > 0) {
            if ($str == implode(' ', $tmp)) {
                $this->v["deptSearchWords"][] = $str;
            } else {
                $str = implode(' ', $tmp);
            }
            foreach ($tmp as $word) {
                if (!in_array($word, $this->v["deptSearchWords"])) {
                    $this->v["deptSearchWords"][] = $word;
                }
            }
        }
        $this->v["reqLike"] = '%' . strtolower($str) . '%';
        return $str;
    }

    /**
     * Tweak certain searches to improve results.
     *
     * @return string
     */
    protected function chkDeptSearchTweak($str)
    {
        if (in_array(strtolower($str), ['washington dc', 'washington d.c.'])) {
            $this->chkDeptSearchAddState('DC');
            return 'washington';
        }
        return $str;
    }

    /**
     * Customize search results from one data sets.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  array  $tblInfo
     * @return string
     */
    protected function printDataSetResultsAjaxCustom(Request $request, $tblInfo)
    {
        $limit = 10;
        if ($request->has('limit') && intVal($request->get('limit')) > 0) {
            $limit = intVal($request->get('limit'));
        }
        $str = '';
        if ($request->has('s')) {
            $str = trim($request->get('s'));
        }
        if ($tblInfo["name"] == 'Police Departments') {
            return $this->printDataSetResultsAjaxDepts($request, $limit);
        }
        return $this->printDataSetResultsAjaxComplaints($request, $limit);
    }

    /**
     * Customize multi-set search results from departments.
     *
     * @param  Illuminate\Http\Request  $request
     * @return string
     */
    protected function printDataSetResultsAjaxDepts(Request $request, $limit)
    {
        $str = $this->chkDeptSearchFlt($request);
        $this->ajaxRunDeptSearch($str);
        return view(
            'vendor.openpolice.ajax.department-previews-table', 
            [
                "limit" => $limit,
                "depts" => $this->v["depts"]
            ]
        )->render();
    }

    /**
     * Convert part of a test search into a state filter.
     *
     * @return boolean
     */
    protected function chkDeptSearchAddState($abbr)
    {
        if (!isset($this->v["reqState"])) {
            $this->v["reqState"] = [];
        }
        if (!in_array($abbr, $this->v["reqState"])) {
            $this->v["reqState"][] = $abbr;
        }
        if (isset($this->searcher) 
            && isset($this->searcher->searchFilts)
            && isset($this->searcher->searchFilts["states"])
            && !in_array($abbr, $this->searcher->searchFilts["states"])) {
            $this->searcher->searchFilts["states"][] = $abbr;
        }
        return true;
    }

    /**
     * Check for filtering by state for department listings.
     *
     * @return array
     */
    protected function chkDeptStateFlt($request)
    {
        if (!isset($this->v["reqState"])) {
            $this->v["reqState"] = [];
        }
        if ($request->has('policeState')) {
            $this->v["reqState"] = [
                trim(strtoupper($request->get('policeState')))
            ];
        } elseif ($request->has('states')) {
            $states = strtoupper($request->get('states'));
            $this->v["reqState"] = $GLOBALS["SL"]->mexplode(',', $states);
        } elseif ($request->has('sFilt') && strpos($request->sFilt, '__states_') !== false) {
            $filts = $GLOBALS["SL"]->mexplode('__', $GLOBALS["SL"]->REQ->get('sFilt'));
            if (sizeof($filts) > 0) {
                foreach ($filts as $flt) {
                    $filtParts = $GLOBALS["SL"]->mexplode('_', $flt);
                    if (sizeof($filtParts) == 2 
                        && $filtParts[0] == 'states') {
                        $this->v["reqState"] = $GLOBALS["SL"]->mexplode(',', $filtParts[1]);
                    }
                }
            }
        }
        return $this->v["reqState"];
    }   

    /**
     * Get the written list of state filters.
     *
     * @return string
     */
    protected function getDeptStateFltNames()
    {
        $this->v["stateNames"] = '';
        if (sizeof($this->v["reqState"]) > 0) {
            foreach ($this->v["reqState"] as $i => $s) {
                $this->v["stateNames"] .= (($i > 0) ? ', ' : '');
                if ($s == 'US') {
                    $this->v["stateNames"] .= 'Federal Law Enforcement';
                } else {
                    $this->v["stateNames"] .= $GLOBALS["SL"]->getState($s);
                }
            }
        }
        return $this->v["stateNames"];
    }   

    /**
     * Check for sorting specific to department listings.
     *
     * @return array
     */
    protected function chkDeptSorts()
    {
        $sortLab = 'match';
        $sortDir = 'asc';
        if (sizeof($this->v["reqState"]) > 0
            && (!isset($this->v["reqLike"]) 
                || trim($this->v["reqLike"]) == '')) {
            $sortLab = 'name';
        }
        if ($GLOBALS["SL"]->REQ->has('sDeptSort') 
            && trim($GLOBALS["SL"]->REQ->get('sDeptSort')) != '') {
            $sortLab = trim($GLOBALS["SL"]->REQ->get('sDeptSort'));
        }
        if ($GLOBALS["SL"]->REQ->has('sDeptSortDir') 
            && trim($GLOBALS["SL"]->REQ->get('sDeptSortDir')) != '') {
            $sortDir = trim($GLOBALS["SL"]->REQ->get('sDeptSortDir'));
        } elseif ($sortLab == 'score') {
            $sortDir = 'desc';
        }
        return [ $sortLab, $sortDir ];
    }

    /**
     * Check for sorting specific to department listings.
     *
     * @return array
     */
    protected function applyDeptSorts(&$depts, $sortLab, $sortDir)
    {
        if ($sortLab == 'name') {
            if ($sortDir == 'desc') {
                usort($depts, function($a, $b) {
                    return (strcasecmp($a->dept_name, $b->dept_name) <= 0);
                });
            } else {
                usort($depts, function($a, $b) {
                    return (strcasecmp($a->dept_name, $b->dept_name) > 0);
                });
            }
//echo 'by Name, applyDeptSorts(<pre>'; print_r($depts); echo '</pre>';
        } elseif ($sortLab == 'city') {
            if ($sortDir == 'desc') {
                usort($depts, function($a, $b) {
                    return (strcasecmp(
                        $a->dept_address_state . $a->dept_address_city, 
                        $b->dept_address_state . $b->dept_address_city
                    ) <= 0);
                });
            } else {
                usort($depts, function($a, $b) {
                    return (strcasecmp(
                        $a->dept_address_state . $a->dept_address_city, 
                        $b->dept_address_state . $b->dept_address_city
                    ) > 0);
                });
            }
        } elseif ($sortLab == 'score') {
            if ($sortDir == 'desc') {
                usort($depts, function($a, $b) {
                    return ($a->dept_score_openness <= $b->dept_score_openness);
                });
            } else {
                usort($depts, function($a, $b) {
                    return ($a->dept_score_openness > $b->dept_score_openness);
                });
            }
        }
        return $depts;
    }
    
    /**
     * Pull and print the department description to appear when
     * clicking it inside the Google map.
     *
     * @param  Illuminate\Http\Request  $request
     * @return string
     */
    public function ajaxDeptKmlDesc(Request $request)
    {
        if ($request->has('deptID') && intVal($request->deptID) > 0) {
            $deptID = intVal($request->deptID);
            $this->loadDeptStuff($deptID);
            return $this->v["deptScores"]->printMapScoreDesc($deptID);
        }
        return '';
    }
    
    /**
     * Add a new set of departments ($deptsIn) into the larger
     * sets of results.
     *
     * @param  array $deptsIn
     * @return boolean
     */
    protected function addDeptToResults($deptsIn)
    {
        if ($deptsIn->isNotEmpty()) {
            foreach ($deptsIn as $d) {
                if (!in_array($d->dept_id, $this->v["deptIDs"])) {
                    $this->v["deptIDs"][] = $d->dept_id;
                    $this->v["depts"][]   = $d;
                }
            }
        }
        return true;
    }

    /**
     * Customize multi-set search results from complaints.
     *
     * @param  Illuminate\Http\Request  $request
     * @return string
     */
    protected function printDataSetResultsAjaxComplaints(Request $request, $limit)
    {
        $str = '';
        if ($request->has('s')) {
            $str = trim($request->get('s'));
        }
        $this->ajaxRunComplaintSearch($str);
        $this->prepDataSetPreviewComplaints();
        return view(
            'vendor.openpolice.nodes.1221-search-results-multi-data-sets-complaints', 
            [
                "limit"      => $limit,
                "isStaff"    => $this->isStaffOrAdmin(),
                "complaints" => $this->v["complaints"]
            ]
        )->render();
    }
    
    /**
     * Load baseline data fields needed to preview complaints 
     * for multi-set search results.
     *
     * @param  string  $str
     * @return boolean
     */
    private function prepDataSetPreviewComplaints()
    {
        if (sizeof($this->v["comIDs"]) > 0) {
            foreach ($this->v["comIDs"] as $i => $comID) {
                $this->v["complaints"][$i] = new ComplaintPreview($comID);
            }
        }
        return true;
    }
    
    /**
     * Run the ranked search for complaints.
     *
     * @param  string  $str
     * @return boolean
     */
    protected function ajaxRunComplaintSearch($str)
    {
        if (!isset($this->v["comIDs"])) {
            $this->v["comIDs"] 
                = $this->v["complaints"] 
                = $this->v["allComIDs"] 
                = $this->v["allIncIDs"] 
                = [];
        }
        $this->v["comSearchTxt"] = $GLOBALS["SL"]->parseSearchWords($str);
        $this->ajaxRunComplaintSearchLoadAll();
        if (sizeof($this->v["comSearchTxt"]) > 0) {
            $this->ajaxRunComplaintSearchAddBasic();
            foreach ($this->v["comSearchTxt"] as $str) {
                $GLOBALS["strLike"] = '%' . $str . '%';
                $comMatches = OPComplaints::whereIn('com_id', $this->v["allComIDs"])
                    ->where('com_summary', 'LIKE', $GLOBALS["strLike"])
                    ->select('com_id')
                    ->get();
                $this->addComplaintToResults($comMatches, 'com_id');
            }

        }
        return true;
    }
    
    /**
     * Run the ranked search for complaints.
     *
     * @param  string  $str
     * @return boolean
     */
    protected function ajaxRunComplaintSearchAddBasic()
    {
        foreach ($this->v["comSearchTxt"] as $str) {
            $GLOBALS["strLike"] = '%' . $str . '%';
            $civMatches = DB::table('op_civilians')
                ->join('op_person_contact', 'op_person_contact.prsn_id',
                    '=', 'op_civilians.civ_person_id')
                ->where('op_civilians.civ_is_creator', 'LIKE', 'Y')
                ->whereIn('op_civilians.civ_complaint_id', $this->v["allComIDs"])
                ->where(function ($query) {
                    return $query->where('op_person_contact.prsn_name_first', 'LIKE', $GLOBALS["strLike"])
                        ->orWhere('op_person_contact.prsn_name_last', 'LIKE', $GLOBALS["strLike"])
                        ->orWhere('op_person_contact.prsn_nickname', 'LIKE', $GLOBALS["strLike"]);
                })
                ->select('op_civilians.civ_complaint_id')
                ->get();
            $this->addComplaintToResults($civMatches, 'civ_complaint_id');
        }
        foreach ($this->v["comSearchTxt"] as $str) {
            $GLOBALS["strLike"] = '%' . $str . '%';
            $incMatches = DB::table('op_incidents')
                ->join('op_complaints', 'op_complaints.com_incident_id',
                    '=', 'op_incidents.inc_id')
                ->whereIn('op_complaints.com_id', $this->v["allComIDs"])
                ->where(function ($query) {
                    return $query->where('op_incidents.inc_address_city', 'LIKE', $GLOBALS["strLike"])
                        ->orWhere('op_incidents.inc_address', 'LIKE', $GLOBALS["strLike"])
                        ->orWhere('op_incidents.inc_landmarks', 'LIKE', $GLOBALS["strLike"]);
                })
                ->select('op_complaints.com_id')
                ->get();
            $this->addComplaintToResults($incMatches, 'com_id');
        }
        foreach ($this->v["comSearchTxt"] as $str) {
            $GLOBALS["strLike"] = '%' . $str . '%';
            $civMatches = DB::table('op_civilians')
                ->join('op_person_contact', 'op_person_contact.prsn_id',
                    '=', 'op_civilians.civ_person_id')
                ->where('op_civilians.civ_is_creator', 'NOT LIKE', 'Y')
                ->whereIn('op_civilians.civ_complaint_id', $this->v["allComIDs"])
                ->where(function ($query) {
                    return $query->where('op_person_contact.prsn_name_first', 'LIKE', $GLOBALS["strLike"])
                        ->orWhere('op_person_contact.prsn_name_last', 'LIKE', $GLOBALS["strLike"])
                        ->orWhere('op_person_contact.prsn_nickname', 'LIKE', $GLOBALS["strLike"]);
                })
                ->select('op_civilians.civ_complaint_id')
                ->get();
            $this->addComplaintToResults($civMatches, 'civ_complaint_id');
        }
        foreach ($this->v["comSearchTxt"] as $str) {
            $GLOBALS["strLike"] = '%' . $str . '%';
            $offMatches = DB::table('op_officers')
                ->join('op_person_contact', 'op_person_contact.prsn_id',
                    '=', 'op_officers.off_person_id')
                ->whereIn('op_officers.off_complaint_id', $this->v["allComIDs"])
                ->where(function ($query) {
                    return $query->where('op_person_contact.prsn_name_first', 'LIKE', $GLOBALS["strLike"])
                        ->orWhere('op_person_contact.prsn_name_last', 'LIKE', $GLOBALS["strLike"])
                        ->orWhere('op_person_contact.prsn_nickname', 'LIKE', $GLOBALS["strLike"]);
                })
                ->select('op_officers.off_complaint_id')
                ->get();
            $this->addComplaintToResults($offMatches, 'off_complaint_id');
        }
        if ($this->isStaffOrAdmin()) {
            foreach ($this->v["comSearchTxt"] as $str) {
                $GLOBALS["strLike"] = '%' . $str . '%';
                $dumpMatches = SLSearchRecDump::where('sch_rec_dmp_tree_id', 1)
                    ->whereIn('sch_rec_dmp_rec_id', $this->v["allComIDs"])
                    ->where('sch_rec_dmp_rec_dump', 'LIKE', $GLOBALS["strLike"])
                    ->orderBy('sch_rec_dmp_rec_id', 'desc')
                    ->select('sch_rec_dmp_rec_id')
                    ->get();
                $this->addComplaintToResults($dumpMatches, 'sch_rec_dmp_rec_id');
            }
        }
        return true;
    }
    
    /**
     * Load the list of all complaint IDs in the searching pool.
     *
     * @return boolean
     */
    protected function ajaxRunComplaintSearchLoadAll()
    {
        $chk = null;
        if ($this->isStaffOrAdmin()) {
            $chk = OPComplaints::whereNotNull('com_summary')
                ->where('com_summary', 'NOT LIKE', '')
                ->orderBy('com_id', 'desc')
                ->select('com_id')
                ->get();
        } else {
            $chk = OPComplaints::whereNotNull('com_summary')
                ->where('com_summary', 'NOT LIKE', '')
                ->whereIn('com_status', $this->getPublishedStatusList('complaints'))
                ->orderBy('com_id', 'desc')
                ->select('com_id')
                ->get();
        }
        $this->v["allComIDs"] = $GLOBALS["SL"]->resultsToArrIds($chk, 'com_id');
        return $chk;
    }
    
    /**
     * Add a new set of complaints into the larger sets of results, 
     * based on the requested data field.
     *
     * @param  array $complaintsIn
     * @param  string $fld
     * @return boolean
     */
    protected function addComplaintToResults($complaintsIn, $fld = '')
    {
        if ($complaintsIn->isNotEmpty()) {
            foreach ($complaintsIn as $complaint) {
                if (isset($complaint->{ $fld })
                    && !in_array($complaint->{ $fld }, $this->v["comIDs"])) {
                    $this->v["comIDs"][] = $complaint->{ $fld };
                }
            }
        }
        return true;
    }

    
}

class ComplaintPreview
{
    public $comID       = 0;
    public $complaint   = null;
    public $complainant = null;
    public $officers    = null;
    public $depts       = null;
    private $isStaff    = false;

    public function __construct($comID)
    {
        $this->isStaff = (Auth::user() && Auth::user()->hasRole('administrator|staff'));
        $this->comID = $comID;
        $this->complaint = DB::table('op_incidents')
            ->join('op_complaints', 'op_complaints.com_incident_id',
                '=', 'op_incidents.inc_id')
            ->where('op_complaints.com_id', $comID)
            ->select('op_complaints.com_id', 
                'op_complaints.com_public_id', 
                'op_complaints.com_status', 
                'op_complaints.com_record_submitted',
                'op_complaints.com_publish_user_name', 
                'op_complaints.com_publish_officer_name', 
                'op_incidents.inc_address_city', 
                'op_incidents.inc_address_state')
            ->first();
        OPComplaints::find($comID);
        if ($this->complaint && isset($this->complaint->com_id)) {
            $this->complainant = DB::table('op_civilians')
                ->join('op_person_contact', 'op_person_contact.prsn_id',
                    '=', 'op_civilians.civ_person_id')
                ->where('op_civilians.civ_complaint_id', $comID)
                ->where('op_civilians.civ_is_creator', 'Y')
                ->select('op_person_contact.prsn_name_first', 
                    'op_person_contact.prsn_name_last',
                    'op_person_contact.prsn_nickname')
                ->first();
            $this->officers = DB::table('op_officers')
                ->join('op_person_contact', 'op_person_contact.prsn_id',
                    '=', 'op_officers.off_person_id')
                ->where('op_officers.off_complaint_id', $comID)
                ->orderBy('op_officers.off_id', 'asc')
                ->select('op_person_contact.prsn_name_first', 
                    'op_person_contact.prsn_name_last',
                    'op_person_contact.prsn_nickname')
                ->get();
            $this->depts = DB::table('op_departments')
                ->join('op_links_complaint_dept', 'op_links_complaint_dept.lnk_com_dept_dept_id',
                    '=', 'op_departments.dept_id')
                ->where('op_links_complaint_dept.lnk_com_dept_complaint_id', $comID)
                ->orderBy('op_departments.dept_name', 'asc')
                ->select('op_departments.dept_id', 
                    'op_departments.dept_name', 
                    'op_departments.dept_slug')
                ->get();
        }
    }


    protected function setTreePageFadeInDelay()
    {
        /* if (in_array($this->treeID, [35, 34])) {
            return 3500;
        } */
        return 500;
    }

}