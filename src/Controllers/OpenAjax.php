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
    public function ajaxPoliceDeptSearch(Request $request)
    {
        if (trim($request->get('policeDept')) == '') {
            return '<i>Please type part of the department\'s name to find it.</i>';
        }
        $depts = $deptIDs = [];
        // Prioritize by Incident City first, also by Department size (# of officers)
        $reqState = $reqLike = '';
        if ($request->has('policeState')) {
            $reqState = trim(strtolower($request->get('policeState')));
        }
        if (in_array($request->policeDept, ['washington dc', 'washington d.c.'])) {
            $request->policeDept = 'washington';
        }
        $reqLike = '%' . $request->policeDept . '%';
        if (!in_array($reqState, ['', 'US'])) {
            $deptsRes = OPDepartments::where('dept_name', 'LIKE', $reqLike)
                ->where('dept_address_state', $reqState)
                ->orderBy('dept_jurisdiction_population', 'desc')
                ->orderBy('dept_tot_officers', 'desc')
                ->orderBy('dept_name', 'asc')
                ->get();
            $this->addDeptToResults($deptIDs, $depts, $deptsRes);
            $deptsRes = OPDepartments::where('dept_address_city', 'LIKE', $reqLike)
                ->where('dept_address_state', $reqState)
                ->orderBy('dept_jurisdiction_population', 'desc')
                ->orderBy('dept_tot_officers', 'desc')
                ->orderBy('dept_name', 'asc')
                ->get();
            $this->addDeptToResults($deptIDs, $depts, $deptsRes);
            $deptsRes = OPDepartments::where('dept_address', 'LIKE', $reqLike)
                ->where('dept_address_state', $reqState)
                ->orderBy('dept_jurisdiction_population', 'desc')
                ->orderBy('dept_tot_officers', 'desc')
                ->orderBy('dept_name', 'asc')
                ->get();
            $this->addDeptToResults($deptIDs, $depts, $deptsRes);
            $zips = $counties = [];
            $cityZips = SLZips::where('zip_city', 'LIKE', $reqLike)
                ->where('zip_state', 'LIKE', $reqState)
                ->get();
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
                        ->where('dept_address_state', $reqState)
                        ->orderBy('dept_jurisdiction_population', 'desc')
                        ->orderBy('dept_tot_officers', 'desc')
                        ->orderBy('dept_name', 'asc')
                        ->get();
                    $this->addDeptToResults($deptIDs, $depts, $deptsMore);
                    $deptsMore = OPDepartments::where('dept_address_county', 'LIKE', '%' . $c . '%')
                        ->where('dept_address_state', $reqState)
                        ->orderBy('dept_jurisdiction_population', 'desc')
                        ->orderBy('dept_tot_officers', 'desc')
                        ->orderBy('dept_name', 'asc')
                        ->get();
                    $this->addDeptToResults($deptIDs, $depts, $deptsMore);
                }
            }
        }
        $deptsFed = OPDepartments::where('dept_type', 366)
            ->where('dept_name', 'LIKE', $reqLike)
            ->orderBy('dept_jurisdiction_population', 'desc')
            ->orderBy('dept_tot_officers', 'desc')
            ->orderBy('dept_name', 'asc')
            ->get();
        $this->addDeptToResults($deptIDs, $depts, $deptsFed);
        $GLOBALS["SL"]->loadStates();
        echo view(
            'vendor.openpolice.ajax.search-police-dept', 
            [
                "depts"            => $depts, 
                "search"           => $request->get('policeDept'), 
                "stateName"        => $GLOBALS["SL"]->states->getState($reqState), 
                "newDeptStateDrop" => $GLOBALS["SL"]->states->stateDrop($reqState, true)
            ]
        )->render();
        exit;
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