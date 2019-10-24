<?php
/**
  * OpenAjax is a mid-level class which handles custom requests
  * via Ajax/jQuery patterns.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
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
            return '<i>Please type part of the '
                . 'department\'s name to find it.</i>';
        }
        $depts = $deptIDs = [];
        // Prioritize by Incident City first, also by Department size (# of officers)
        $reqState = (($request->has('policeState')) ? trim($request->get('policeState')) : '');
        if (in_array(strtolower($request->policeDept), ['washington dc', 'washington d.c.'])) {
            $request->policeDept = 'Washington';
        }
        if (!in_array($reqState, ['', 'US'])) {
            $deptsRes = OPDepartments::where('DeptName', 
                    'LIKE', '%' . $request->policeDept . '%')
                ->where('DeptAddressState', $reqState)
                ->orderBy('DeptJurisdictionPopulation', 'desc')
                ->orderBy('DeptTotOfficers', 'desc')
                ->orderBy('DeptName', 'asc')
                ->get();
            $this->addDeptToResults($deptIDs, $depts, $deptsRes);
            $deptsRes = OPDepartments::where('DeptAddressCity', 
                    'LIKE', '%' . $request->policeDept . '%')
                ->where('DeptAddressState', $reqState)
                ->orderBy('DeptJurisdictionPopulation', 'desc')
                ->orderBy('DeptTotOfficers', 'desc')
                ->orderBy('DeptName', 'asc')
                ->get();
            $this->addDeptToResults($deptIDs, $depts, $deptsRes);
            $deptsRes = OPDepartments::where('DeptAddress', 
                    'LIKE', '%' . $request->policeDept . '%')
                ->where('DeptAddressState', $reqState)
                ->orderBy('DeptJurisdictionPopulation', 'desc')
                ->orderBy('DeptTotOfficers', 'desc')
                ->orderBy('DeptName', 'asc')
                ->get();
            $this->addDeptToResults($deptIDs, $depts, $deptsRes);
            $zips = $counties = [];
            $cityZips = SLZips::where('ZipCity', 
                    'LIKE', '%' . $request->policeDept . '%')
                ->where('ZipState', 'LIKE', $reqState)
                ->get();
            if ($cityZips->isNotEmpty()) {
                foreach ($cityZips as $z) {
                    $zips[] = $z->ZipZip;
                    $counties[] = $z->ZipCounty;
                }
                $deptsMore = OPDepartments::whereIn('DeptAddressZip', $zips)
                    ->orderBy('DeptName', 'asc')
                    ->get();
                $this->addDeptToResults($deptIDs, $depts, $deptsMore);
                foreach ($counties as $c) {
                    $deptsMore = OPDepartments::where('DeptName', 
                            'LIKE', '%' . $c . '%')
                        ->where('DeptAddressState', $reqState)
                        ->orderBy('DeptJurisdictionPopulation', 'desc')
                        ->orderBy('DeptTotOfficers', 'desc')
                        ->orderBy('DeptName', 'asc')
                        ->get();
                    $this->addDeptToResults($deptIDs, $depts, $deptsMore);
                    $deptsMore = OPDepartments::where('DeptAddressCounty', 
                            'LIKE', '%' . $c . '%')
                        ->where('DeptAddressState', $reqState)
                        ->orderBy('DeptJurisdictionPopulation', 'desc')
                        ->orderBy('DeptTotOfficers', 'desc')
                        ->orderBy('DeptName', 'asc')
                        ->get();
                    $this->addDeptToResults($deptIDs, $depts, $deptsMore);
                }
            }
        }
        $deptsFed = OPDepartments::where('DeptName', 
            'LIKE', '%' . $request->policeDept . '%')
            ->where('DeptType', 366)
            ->orderBy('DeptJurisdictionPopulation', 'desc')
            ->orderBy('DeptTotOfficers', 'desc')
            ->orderBy('DeptName', 'asc')
            ->get();
        $this->addDeptToResults($deptIDs, $depts, $deptsFed);
        $GLOBALS["SL"]->loadStates();
        echo view('vendor.openpolice.ajax.search-police-dept', [
            "depts" => $depts, 
            "search" => $request->get('policeDept'), 
            "stateName" => $GLOBALS["SL"]->states
                ->getState($request->get('policeState')), 
            "newDeptStateDrop" => $GLOBALS["SL"]->states
                ->stateDrop($request->get('policeState'), true)
        ])->render();
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
                if (!in_array($d->DeptID, $deptIDs)) {
                    $deptIDs[] = $d->DeptID;
                    $depts[] = $d;
                }
            }
        }
        return true;
    }
    
}