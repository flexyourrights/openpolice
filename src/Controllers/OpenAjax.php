<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use SurvLoop\Models\User;
use OpenPolice\Models\OPDepartments;
use SurvLoop\Models\SLZips;
use OpenPolice\Controllers\OpenComplaintSaves;

class OpenAjax extends OpenComplaintSaves
{
    public function runAjaxChecksCustom(Request $request, $over = '')
    {
        if ($request->has('email') && $request->has('password')) {
            echo $this->ajaxEmailPass($request);
            exit;
        } elseif ($request->has('policeDept')) {
            echo $this->ajaxPoliceDeptSearch($request);
            exit;
        }
        return false;
    }
    
    public function ajaxChecksCustom(Request $request, $type = '')
    {
        if ($type == 'dept-kml-desc') {
            return $this->ajaxDeptKmlDesc($request);
        }
        return '';
    }
    
    public function ajaxEmailPass(Request $request)
    {
        print_r($request);
        $chk = User::where('email', $request->email)->get();
        if ($chk->isNotEmpty()) echo 'found';
        echo 'not found';
        exit;
    }
    
    public function ajaxPoliceDeptSearch(Request $request)
    {
        if (trim($request->get('policeDept')) == '') {
            return '<i>Please type part of the department\'s name to find it.</i>';
        }
        $depts = $deptIDs = [];
        // Prioritize by Incident City first, also by Department size (# of officers)
        $reqState = (($request->has('policeState')) ? trim($request->get('policeState')) : '');
        if (in_array(strtolower($request->policeDept), ['washington dc', 'washington d.c.'])) {
            $request->policeDept = 'Washington';
        }
        if (!in_array($reqState, ['', 'US'])) {
            $deptsRes = OPDepartments::where('DeptName', 'LIKE', '%' . $request->policeDept . '%')
                ->where('DeptAddressState', $reqState)
                ->orderBy('DeptJurisdictionPopulation', 'desc')
                ->orderBy('DeptTotOfficers', 'desc')
                ->orderBy('DeptName', 'asc')
                ->get();
            list($deptIDs, $depts) = $this->addDeptToResults($deptIDs, $depts, $deptsRes);
            $deptsRes = OPDepartments::where('DeptAddressCity', 'LIKE', '%' . $request->policeDept . '%')
                ->where('DeptAddressState', $reqState)
                ->orderBy('DeptJurisdictionPopulation', 'desc')
                ->orderBy('DeptTotOfficers', 'desc')
                ->orderBy('DeptName', 'asc')
                ->get();
            list($deptIDs, $depts) = $this->addDeptToResults($deptIDs, $depts, $deptsRes);
            $deptsRes = OPDepartments::where('DeptAddress', 'LIKE', '%' . $request->policeDept . '%')
                ->where('DeptAddressState', $reqState)
                ->orderBy('DeptJurisdictionPopulation', 'desc')
                ->orderBy('DeptTotOfficers', 'desc')
                ->orderBy('DeptName', 'asc')
                ->get();
            list($deptIDs, $depts) = $this->addDeptToResults($deptIDs, $depts, $deptsRes);
            $zips = $counties = [];
            $cityZips = SLZips::where('ZipCity', 'LIKE', '%' . $request->policeDept . '%')
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
                list($deptIDs, $depts) = $this->addDeptToResults($deptIDs, $depts, $deptsMore);
                foreach ($counties as $c) {
                    $deptsMore = OPDepartments::where('DeptName', 'LIKE', '%' . $c . '%')
                        ->where('DeptAddressState', $reqState)
                        ->orderBy('DeptJurisdictionPopulation', 'desc')
                        ->orderBy('DeptTotOfficers', 'desc')
                        ->orderBy('DeptName', 'asc')
                        ->get();
                    list($deptIDs, $depts) = $this->addDeptToResults($deptIDs, $depts, $deptsMore);
                    $deptsMore = OPDepartments::where('DeptAddressCounty', 'LIKE', '%' . $c . '%')
                        ->where('DeptAddressState', $reqState)
                        ->orderBy('DeptJurisdictionPopulation', 'desc')
                        ->orderBy('DeptTotOfficers', 'desc')
                        ->orderBy('DeptName', 'asc')
                        ->get();
                    list($deptIDs, $depts) = $this->addDeptToResults($deptIDs, $depts, $deptsMore);
                }
            }
        }
        $deptsFed = OPDepartments::where('DeptName', 'LIKE', '%' . $request->policeDept . '%')
            ->where('DeptType', 366)
            ->orderBy('DeptJurisdictionPopulation', 'desc')
            ->orderBy('DeptTotOfficers', 'desc')
            ->orderBy('DeptName', 'asc')
            ->get();
        list($deptIDs, $depts) = $this->addDeptToResults($deptIDs, $depts, $deptsFed);
        $GLOBALS["SL"]->loadStates();
        echo view('vendor.openpolice.ajax.search-police-dept', [
            "depts"            => $depts, 
            "search"           => $request->get('policeDept'), 
            "stateName"        => $GLOBALS["SL"]->states->getState($request->get('policeState')), 
            "newDeptStateDrop" => $GLOBALS["SL"]->states->stateDrop($request->get('policeState'), true)
        ])->render();
        exit;
    }
    
    public function ajaxDeptKmlDesc(Request $request)
    {
        if ($request->has('deptID') && intVal($request->deptID) > 0) {
            $deptID = intVal($request->deptID);
            $this->loadDeptStuff($deptID);
            return $this->v["deptScores"]->printMapScoreDesc($deptID);
        }
        return '';
    }
    
    protected function addDeptToResults($deptIDs, $depts, $deptsIn)
    {
        if ($deptsIn->isNotEmpty()) {
            foreach ($deptsIn as $d) {
                if (!in_array($d->DeptID, $deptIDs)) {
                    $deptIDs[] = $d->DeptID;
                    $depts[] = $d;
                }
            }
        }
        return [$deptIDs, $depts];
    }
    
}