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
use App\Models\OPDepartments;
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
        } elseif ($type == 'saveDefaultState') {
            return $this->saveVolunLocationForm($request);
        } elseif ($type == 'dept-search') { // public
            return $this->ajaxPoliceDeptSearch($request, 'public');
        }
        return '';
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
        $reqLikeOrig = $this->chkDeptSearchFlt($request);
        $this->getDeptStateFltNames();

        list($sortLab, $sortDir) = $this->chkDeptSorts($this->v["reqState"]);
        $depts = $deptIDs = [];
        $loadUrl .= $this->v["reqLike"] . '&states=' . implode(',', $this->v["reqState"]) 
            . '&sortLab=' . $sortLab . '&sortDir=' . $sortDir;
        $ret = $GLOBALS["SL"]->chkCache($loadUrl, 'search', 1);
        if (trim($ret) == '' || $request->has('refresh')) {
            $fedDef = $GLOBALS["SL"]->def->getID(
                'Department Types',
                'Federal Law Enforcement'
            );
            if (sizeof($this->v["reqState"]) > 0
                && (sizeof($this->v["reqState"]) > 1 || $this->v["reqState"][0] != 'US')) {
                if (in_array($this->v["reqLike"], ['', '%%'])) {
                    $deptsRes = OPDepartments::whereIn('dept_address_state', $this->v["reqState"])
                        ->orderBy('dept_jurisdiction_population', 'desc')
                        ->orderBy('dept_tot_officers', 'desc')
                        ->orderBy('dept_name', 'asc')
                        ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                            'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                            'dept_address_city', 'dept_address_state')
                        ->get();
                    $this->addDeptToResults($deptIDs, $depts, $deptsRes);
                } else {
                    $deptsRes = OPDepartments::where('dept_name', 'LIKE', $this->v["reqLike"])
                        ->whereIn('dept_address_state', $this->v["reqState"])
                        ->orderBy('dept_jurisdiction_population', 'desc')
                        ->orderBy('dept_tot_officers', 'desc')
                        ->orderBy('dept_name', 'asc')
                        ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                            'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                            'dept_address_city', 'dept_address_state')
                        ->get();
                    $this->addDeptToResults($deptIDs, $depts, $deptsRes);
                    $deptsRes = OPDepartments::where('dept_address_city', 'LIKE', $this->v["reqLike"])
                        ->whereIn('dept_address_state', $this->v["reqState"])
                        ->orderBy('dept_jurisdiction_population', 'desc')
                        ->orderBy('dept_tot_officers', 'desc')
                        ->orderBy('dept_name', 'asc')
                        ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                            'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                            'dept_address_city', 'dept_address_state')
                        ->get();
                    $this->addDeptToResults($deptIDs, $depts, $deptsRes);
                    $deptsRes = OPDepartments::where('dept_address', 'LIKE', $this->v["reqLike"])
                        ->whereIn('dept_address_state', $this->v["reqState"])
                        ->orderBy('dept_jurisdiction_population', 'desc')
                        ->orderBy('dept_tot_officers', 'desc')
                        ->orderBy('dept_name', 'asc')
                        ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                            'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                            'dept_address_city', 'dept_address_state')
                        ->get();
                    $this->addDeptToResults($deptIDs, $depts, $deptsRes);
                    $zips = $counties = [];
                    $cityZips = SLZips::where('zip_city', 'LIKE', $this->v["reqLike"])
                        ->whereIn('zip_state', $this->v["reqState"])
                        ->get();
                    /* if ($cityZips->isEmpty()) {
                        $cityZips = SLZips::where('zip_city', 'LIKE', $this->v["reqLike"])
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
                        $this->addDeptToResults($deptIDs, $depts, $deptsMore);
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
                            $this->addDeptToResults($deptIDs, $depts, $deptsMore);
                            $deptsMore = OPDepartments::where('dept_address_county', 'LIKE', '%' . $c . '%')
                                ->whereIn('dept_address_state', $this->v["reqState"])
                                ->orderBy('dept_jurisdiction_population', 'desc')
                                ->orderBy('dept_tot_officers', 'desc')
                                ->orderBy('dept_name', 'asc')
                                ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                                    'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                                    'dept_address_city', 'dept_address_state')
                                ->get();
                            $this->addDeptToResults($deptIDs, $depts, $deptsMore);
                        }
                    }
                }
            } elseif (!in_array($this->v["reqLike"], ['', '%%'])
                && sizeof($this->v["reqState"]) == 0) {
                $deptsRes = OPDepartments::where('dept_name', 'LIKE', $this->v["reqLike"])
                    ->orWhere('dept_address', 'LIKE', $this->v["reqLike"])
                    ->orWhere('dept_address_city', 'LIKE', $this->v["reqLike"])
                    ->orWhere('dept_address_county', 'LIKE', $this->v["reqLike"])
                    ->orderBy('dept_jurisdiction_population', 'desc')
                    ->orderBy('dept_tot_officers', 'desc')
                    ->orderBy('dept_name', 'asc')
                    ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                        'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                        'dept_address_city', 'dept_address_state')
                    ->get();
                $this->addDeptToResults($deptIDs, $depts, $deptsRes);
            }
            if (sizeof($this->v["reqState"]) > 0 
                && in_array('US', $this->v["reqState"])) {
                if (!in_array($this->v["reqLike"], ['', '%%'])) {
                    $deptsFed = OPDepartments::where('dept_type', $fedDef)
                        ->where('dept_name', 'LIKE', $this->v["reqLike"])
                        ->orderBy('dept_jurisdiction_population', 'desc')
                        ->orderBy('dept_tot_officers', 'desc')
                        ->orderBy('dept_name', 'asc')
                        ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                            'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                            'dept_address_city', 'dept_address_state')
                        ->get();
                    $this->addDeptToResults($deptIDs, $depts, $deptsFed);
                } else {
                    $deptsFed = OPDepartments::where('dept_type', $fedDef)
                        ->orderBy('dept_jurisdiction_population', 'desc')
                        ->orderBy('dept_tot_officers', 'desc')
                        ->orderBy('dept_name', 'asc')
                        ->select('dept_address_state', 'dept_address_county', 'dept_name', 
                            'dept_id', 'dept_slug', 'dept_score_openness', 'dept_verified',
                            'dept_address_city', 'dept_address_state')
                        ->get();
                    $this->addDeptToResults($deptIDs, $depts, $deptsFed);
                }
            }
            if ($sortLab != 'match') {
                $this->applyDeptSorts($depts, $sortLab, $sortDir);
            }
            if ($view == 'survey') {
                $drop = $GLOBALS["SL"]->states->stateDrop($this->v["reqState"][0], true);
                $ret = view(
                    'vendor.openpolice.ajax.search-police-dept', 
                    [
                        "depts"            => $depts, 
                        "search"           => $reqLikeOrig, 
                        "stateName"        => $this->v["stateNames"], 
                        "newDeptStateDrop" => $drop
                    ]
                )->render();
            } else {
                $ret = view(
                    'vendor.openpolice.ajax.department-previews', 
                    [
                        "depts"      => $depts,
                        "deptSearch" => $reqLikeOrig,
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
     * Check for filtering by text search for department listings.
     *
     * @return array
     */
    protected function chkDeptSearchFlt($request)
    {
//echo '<pre>'; print_r($this->v["reqState"]); echo '</pre>';
        $this->v["reqLike"] = $reqLikeOrig = '';
        $loadUrl = '';
        if ($request->has('policeDept')) {
            $reqLikeOrig = trim($request->policeDept);
            $loadUrl .= '?survey=1';
        } elseif ($request->has('deptSearch')) {
            $reqLikeOrig = trim($request->deptSearch);
        }
        if (in_array($request->policeDept, ['washington dc', 'washington d.c.'])) {
            $reqLikeOrig = 'washington';
        }
        $searchWords = [];
        $searchRaw = $GLOBALS["SL"]->mexplode(' ', $reqLikeOrig);
        if (sizeof($searchRaw) > 0) {
            foreach ($searchRaw as $word) {
                $abbr = strtoupper($word);
                $state = $GLOBALS["SL"]->getState($abbr);
//echo 'adding ' . $abbr . ', state: ' . $state;
                if ($state != '') {
                    $this->chkDeptSearchAddState($abbr);
                } else {
                    $abbr = $GLOBALS["SL"]->states->getStateAbrr($word);
                    if ($abbr != '') {
                        $this->chkDeptSearchAddState($abbr);
                    } else {
                        $searchWords[] = $word;
                    }
                }
            }
        }
        $reqLikeOrig = implode(' ', $searchWords);
        $this->v["reqLike"] = '%' . strtolower($reqLikeOrig) . '%';
//echo '<pre>'; print_r($this->v["reqState"]); echo '</pre> reqLikeOrig: ' . $reqLikeOrig . '<br />';
//exit;
        return $reqLikeOrig;
    }

    /**
     * Convert part of a test search into a state filter.
     *
     * @return boolean
     */
    protected function chkDeptSearchAddState($abbr)
    {
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
        $this->v["reqState"] = [];
        if ($request->has('policeState')) {
            $this->v["reqState"] = [
                trim(strtoupper($request->get('policeState')))
            ];
        } elseif ($request->has('states')) {
            $this->v["reqState"] = $GLOBALS["SL"]->mexplode(
                ',', 
                strtoupper($request->get('states'))
            );
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
     * @param  array &$deptIDs
     * @param  array &$depts
     * @param  array $deptsIn
     * @return boolean
     */
    protected function addDeptToResults(&$deptIDs, &$depts, $deptsIn)
    {
        if ($deptsIn->isNotEmpty()) {
            foreach ($deptsIn as $d) {
                if (!in_array($d->dept_id, $deptIDs)) {
                    $deptIDs[] = $d->dept_id;
                    $depts[] = $d;
                }
            }
        }
        return true;
    }
    
}