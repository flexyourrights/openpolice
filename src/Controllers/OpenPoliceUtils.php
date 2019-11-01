<?php
/**
  * OpenPoliceUtils is the bottom-level class extending SurvLoop
  * that performs smaller data translation, lookup functions.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use Auth;
use App\Models\User;
use App\Models\OPComplaints;
use App\Models\OPCompliments;
use App\Models\OPIncidents;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksComplimentDept;
use App\Models\OPPersonContact;
use App\Models\OPzVolunUserInfo;
use OpenPolice\Controllers\OpenPoliceVars;

class OpenPoliceUtils extends OpenPoliceVars
{

    public function comStatus($defVal)
    {
        return $GLOBALS["SL"]->def->getID('Complaint Status', $defVal);
    }
    
    protected function isCompliment()
    { 
        if (!isset($this->sessData->dataSets["Complaints"])) {
            return ($this->treeID == 5);
        }
        return (isset($this->sessData->dataSets["Complaints"][0]->ComIsCompliment)
            && intVal($this->sessData->dataSets["Complaints"][0]->ComIsCompliment) == 1);
    }
    
    public function isOverCompatible($overRow)
    {
        return ($overRow && isset($overRow->OverEmail) 
            && trim($overRow->OverEmail) != '' 
            && isset($overRow->OverWaySubEmail) 
            && intVal($overRow->OverWaySubEmail) == 1
            && isset($overRow->OverOfficialFormNotReq) 
            && intVal($overRow->OverOfficialFormNotReq) == 1);
    }
    
    protected function isSilver()
    { 
        if (!isset($this->sessData->dataSets["Complaints"])) {
            return false;
        }
        return ($this->sessData->dataSets["Complaints"][0]->ComAwardMedallion == 'Silver'); 
    }
    
    protected function isGold()
    {
        if (!isset($this->sessData->dataSets["Complaints"])) {
            return false;
        }
        return ($this->sessData->dataSets["Complaints"][0]->ComAwardMedallion == 'Gold');
    }
    
    protected function isPublic()
    {
        if (!isset($this->sessData->dataSets["Complaints"])) {
            return false;
        }
        return ($this->sessData->dataSets["Complaints"][0]->ComPrivacy 
            == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly'));
    }
    
    public function isPublished($coreTbl, $coreID, $coreRec = NULL)
    {
        if ($coreTbl == 'Complaints') {
            if (!$coreRec || !isset($coreRec->ComStatus)) {
                $coreRec = OPComplaints::find($coreID);
            }
            if ($coreRec && isset($coreRec->ComStatus)) {
                return (in_array($coreRec->ComStatus, $this->getPublishedStatusList($coreTbl)));
            }
            return false;
        }
        return false;
    }
    
    protected function isAnonyLogin()
    {
        if ($this->treeID == 1) {
            return (isset($this->sessData->dataSets["Complaints"]) 
                && (in_array($this->sessData->dataSets["Complaints"][0]->ComUnresolvedCharges, ['Y', '?'])
                || intVal($this->sessData->dataSets["Complaints"][0]->ComPrivacy) 
                    == intVal($GLOBALS["SL"]->def->getID('Privacy Types', 'Completely Anonymous'))));
        } elseif ($this->treeID == 5) {
            return (isset($this->sessData->dataSets["Compliments"]) 
                && intVal($this->sessData->dataSets["Compliments"][0]->CompliPrivacy)
                    == intVal($GLOBALS["SL"]->def->getID('Privacy Types', 'Completely Anonymous')));
        }
    }
    
    public function multiRecordCheckIntro($cnt = 1)
    {
        $ret = '<h3 class="slBlueDark">' . $this->v["user"]->name . ', You Have ';
        if ($this->treeID == 1) {
            $ret .= (($cnt == 1) ? 'An Unfinished Complaint'
                : 'Unfinished Complaints');
        } elseif ($this->treeID == 5) {
            $ret .= (($cnt == 1) ? 'An Unfinished Compliment'
                : 'Unfinished Compliments');
        }
        return $ret . '</h3>';
    }
    
    public function multiRecordCheckRowTitle($coreRecord)
    {
        $ret = '';
        if ($this->treeID == 1) {
            if (isset($coreRecord[1]->ComSummary) 
                && trim($coreRecord[1]->ComSummary) != '') {
                $ret .= $GLOBALS["SL"]->wordLimitDotDotDot(
                    $coreRecord[1]->ComSummary, 
                    10
                );
            } else {
                $ret .= '(empty)';
            }
        } elseif ($this->treeID == 5) {
            if (isset($coreRecord[1]->CompliSummary) 
                && trim($coreRecord[1]->CompliSummary) != '') {
                $ret .= $GLOBALS["SL"]->wordLimitDotDotDot(
                    $coreRecord[1]->CompliSummary, 
                    10
                );
            } else {
                $ret .= '(empty)';
            }
        }
        return trim($ret);
    }
    
    public function multiRecordCheckRowSummary($coreRecord)
    {
        $ret = 'Started ' . date('n/j/y, g:ia', 
            strtotime($coreRecord[1]->created_at));
        if ($this->treeID == 1) {
            $incident = OPIncidents::find($coreRecord[1]->ComIncidentID);
            if ($incident && isset($incident->IncAddressCity)) {
                $ret .= $incident->IncAddressCity . ', Incident Date: ' 
                    . date('n/j/y', strtotime($incident->IncTimeStart));
            }
            $ret .= ' (Complaint #' . $coreRecord[1]->getKey() . ')';
        } elseif ($this->treeID == 5) {
            $incident = OPIncidents::find($coreRecord[1]->CompliIncidentID);
            if ($incident && isset($incident->IncAddressCity)) {
                $ret .= $incident->IncAddressCity . ', Incident Date: ' 
                    . date('n/j/y', strtotime($incident->IncTimeStart));
            }
            $ret .= ' (Compliment #' . $coreRecord[1]->getKey() . ')';
        }
        return $ret;
    }
    
    public function multiRecordCheckDelWarn()
    {
        return 'Are you sure you want to delete this complaint? '
            . 'Deleting it CANNOT be undone.';
    }
    
    public function chkCoreRecEmpty($coreID = -3, $coreRec = NULL)
    {
        if ($coreID <= 0) {
            $coreID = $this->coreID;
        }
        if (!$coreRec && $coreID > 0) {
            $coreRec = OPComplaints::find($coreID);
        }
        if (!$coreRec) {
            return false;
        }
        if (!isset($coreRec->ComSubmissionProgress) 
            || intVal($coreRec->ComSubmissionProgress) <= 0) {
            return true;
        }
        if (!isset($coreRec->ComSummary) 
            || trim($coreRec->ComSummary) == '') {
            return true;
        }
        return false;
    }
    
    protected function recordIsEditable($coreTbl, $coreID, $coreRec = NULL)
    {
        if (!$coreRec && $coreID > 0) {
            $coreRec = OPComplaints::find($coreID);
        }
        if (!isset($coreRec->ComStatus)) {
            return true;
        }
        if (!$coreRec) {
            return false;
        }
        if ($this->treeID == 1) {
            if ($coreRec->ComStatus == $GLOBALS["SL"]
                ->def->getID('Complaint Status', 'Incomplete')) {
                return true;
            }
        } elseif ($this->treeID == 5) {
            if ($coreRec->ComStatus == $GLOBALS["SL"]
                ->def->getID('Compliment Status', 'Incomplete')) {
                return true;
            }
        }
        return false;
    }
    
    public function getAllPublicCoreIDs($coreTbl = '')
    {
        
        if (trim($coreTbl) == '') {
            $coreTbl = $GLOBALS["SL"]->coreTbl;
        }
        $this->allPublicCoreIDs = $list = [];
        if ($coreTbl == 'Complaints') {
            $list = OPComplaints::whereIn('ComStatus', 
                    $this->getPublishedStatusList($coreTbl))
                //->where('ComType', $GLOBALS["SL"]->def->getID('Complaint Type', 'Police Complaint'))
                ->select('ComID', 'ComPublicID')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($coreTbl == 'Compliments') {
            $list = OPCompliments::whereIn('CompliStatus', 
                    $this->getPublishedStatusList($coreTbl))
                //->where('CompliType', $GLOBALS["SL"]->def->getID('Complaint Type', 'Police Complaint'))
                ->select('CompliID') //, 'CompliPublicID')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        if ($list->isNotEmpty()) {
            foreach ($list as $l) {
                $this->allPublicCoreIDs[] = $l->ComPublicID;
            }
        }
        //echo '<pre>'; print_r($this->allPublicCoreIDs); echo '</pre>';
        return $this->allPublicCoreIDs;
    }
    
    protected function getPublishedStatusList($coreTbl = '')
    {
        if (!isset($coreTbl) 
            || trim($coreTbl) == '') {
            $coreTbl = $GLOBALS["SL"]->coreTbl;
        }
        if ($coreTbl == 'Complaints') {
            $set = 'Complaint Status';
            return [
                $GLOBALS["SL"]->def->getID($set, 'OK to Submit to Oversight'), 
                $GLOBALS["SL"]->def->getID($set, 'Submitted to Oversight'), 
                $GLOBALS["SL"]->def->getID($set, 'Received by Oversight'), 
                $GLOBALS["SL"]->def->getID($set, 'Declined To Investigate (Closed)'), 
                $GLOBALS["SL"]->def->getID($set, 'Investigated (Closed)')
            ];
        } elseif ($coreTbl == 'Compliments') {
            $set = 'Compliment Status';
            return [
                $GLOBALS["SL"]->def->getID($set, 'Reviewed'), 
                $GLOBALS["SL"]->def->getID($set, 'Submitted to Oversight'), 
                $GLOBALS["SL"]->def->getID($set, 'Received by Oversight')
            ];
        }
        return [];
    }
    
    protected function getUnPublishedStatusList($coreTbl = '')
    {
        if (!isset($coreTbl) || trim($coreTbl) == '') {
            $coreTbl = $GLOBALS["SL"]->coreTbl;
        }
        if ($coreTbl == 'Complaints') {
            $set = 'Complaint Status';
            return [
                $GLOBALS["SL"]->def->getID($set, 'Hold'), 
                $GLOBALS["SL"]->def->getID($set, 'New'), 
                $GLOBALS["SL"]->def->getID($set, 'Pending Attorney'), 
                $GLOBALS["SL"]->def->getID($set, 'Attorney\'d'), 
                $GLOBALS["SL"]->def->getID($set, 'Reviewed')
            ];
        } elseif ($coreTbl == 'Compliments') {
            $set = 'Compliment Status';
            return [
                $GLOBALS["SL"]->def->getID($set, 'Hold'), 
                $GLOBALS["SL"]->def->getID($set, 'New')
            ];
        }
        return [];
    }
    
    protected function complaintHasPublishedStatus()
    {
        if (isset($this->sessData->dataSets["Complaints"])
            && isset($this->sessData->dataSets["Complaints"][0]->ComStatus)) {
            $status = $this->sessData->dataSets["Complaints"][0]->ComStatus;
            if (in_array($status, $this->getPublishedStatusList('Complaints'))) {
                return 1;
            }
        }
        return 0;
    }
    
    protected function canPrintFullReport()
    {
        if (!isset($this->sessData->dataSets["Complaints"])) {
            return false;
        }
        if ((isset($this->v["isAdmin"]) 
                && $this->v["isAdmin"])
            || (isset($this->v["isOwner"]) 
                && $this->v["isOwner"])) {
            return true;
        }
        $com = $this->sessData->dataSets["Complaints"][0];
        return ($com->ComPrivacy == $GLOBALS["SL"]->def
                ->getID('Privacy Types', 'Submit Publicly')
            && in_array($com->ComStatus, [200, 201, 203, 204]));
    }
    
    public function tblsInPackage()
    {
        if ($this->dbID == 1) {
            return ['Departments', 'Oversight'];
        }
        return [];
    }
    
    protected function maxUserView()
    {
        if (isset($this->sessData->dataSets["Complaints"])) {
            if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy 
                    != $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')
                || in_array($this->sessData->dataSets["Complaints"][0]->ComStatus, [
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'Reviewed'),
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney'),
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d'),
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight')
                ])) {
                $GLOBALS["SL"]->pageView = 'public';
            }
        }
        if (isset($this->v["user"]) && isset($this->v["user"]->id)) {
            if (isset($this->sessData->dataSets["Civilians"]) 
                && $this->v["uID"] == $this->sessData->dataSets["Civilians"][0]->CivUserID) {
                //$this->v["isOwner"] = true;
                if (isset($GLOBALS["fullAccess"]) && $GLOBALS["fullAccess"]) {
                    $GLOBALS["SL"]->pageView = 'full';
                }
            } elseif ($this->v["user"]->hasRole('administrator|staff')) {
                $GLOBALS["SL"]->pageView = 'full';
            } elseif ($this->v["user"]->hasRole('partner') 
                && $this->v["user"]->hasRole('oversight')) {
                $overRow = OPOversight::where('OverEmail', $this->v["user"]->email)
                    ->first();
                if ($overRow && isset($overRow->OverDeptID)) {
                    $lnkChk = OPLinksComplaintDept::where('LnkComDeptComplaintID', $this->coreID)
                        ->where('LnkComDeptDeptID', $overRow->OverDeptID)
                        ->first();
                    if ($lnkChk && isset($lnkChk->LnkComDeptID)) {
                        $GLOBALS["SL"]->pageView = 'full';
                    }
                }
            }
        }
        return true;
    }
    
    protected function loadYourContact()
    {
        if (Auth::user() && isset(Auth::user()->id) 
            && Auth::user()->id > 0) {
            $uID = Auth::user()->id;
            $this->v["yourContact"] = OPPersonContact::where('PrsnUserID', $uID)
                ->first();
            if (!$this->v["yourContact"] 
                || !isset($this->v["yourContact"]->PrsnID)) {
                $this->v["yourContact"] = new OPPersonContact;
                $this->v["yourContact"]->PrsnUserID = Auth::user()->id;
                $this->v["yourContact"]->save();
            }
        }
        return true;
    }
    
    public function loadDeptStuff($deptID = -3)
    {
        if (!isset($this->v["deptScores"])) {
            $this->v["deptScores"] = new DepartmentScores;
        }
        $this->v["deptScores"]->loadDeptStuff($deptID);
        return true;
    }
    
    protected function getDeptUser($deptID = -3)
    {
        $this->chkDeptUsers();
        if ($deptID > 0 && isset($GLOBALS["SL"]->x["depts"]) 
            && sizeof($GLOBALS["SL"]->x["depts"]) > 0 
            && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
            && $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]) {
            return $GLOBALS["SL"]->x["depts"][$deptID]["overUser"];
        }
        return [];
    }
    
    // if investigative agency's email address doesn't have a User record, 
    // create one to link with tokens
    protected function chkDeptUsers()
    {
        if (isset($this->sessData->dataSets["LinksComplaintDept"]) 
            && sizeof($this->sessData->dataSets["LinksComplaintDept"]) > 0) {
            foreach ($this->sessData->dataSets["LinksComplaintDept"] as $deptLnk) {
                $deptID = $deptLnk->LnkComDeptDeptID;
                $this->loadDeptStuff($deptID);
                $wchOvr = $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"];
                if (isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && (!isset($GLOBALS["SL"]->x["depts"][$deptID]["overUser"]) 
                    || !isset($GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->email)) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID][$wchOvr])
                    && isset($GLOBALS["SL"]->x["depts"][$deptID][$wchOvr]->OverEmail)) {
                    $overRow = $GLOBALS["SL"]->x["depts"][$deptID][$wchOvr];
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"] = new User;
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->email = $overRow->OverEmail;
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->name = $overRow->OverAgncName;
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->password 
                        = $this->genTokenStr('pass', 100);
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->save();
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->assignRole('oversight');
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->assignRole('partner');
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->assignRole('volunteer');

                    $GLOBALS["SL"]->x["depts"][$deptID][$wchOvr]->update([
                        'OverUserID' => $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->id
                    ]);
                }
            }
        }
        return true;
    }
    
    protected function chkOverUserHasCore()
    {
        if ($this->v["uID"] > 0 && $this->v["user"]->hasRole('oversight')) {
            if (isset($this->sessData->dataSets["Oversight"]) 
                && sizeof($this->sessData->dataSets["Oversight"]) > 0) {
                foreach ($this->sessData->dataSets["Oversight"] as $i => $o) {
                    if (isset($o->OverEmail) && $o->OverEmail == $this->v["user"]->email) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    
    protected function sysUpdatesCust($apply = false)
    {
        $msgs = '';
        // Template for adding more updates (for now)...
        $updateID = [ 'OPC-2018-02-08', 'Complaint submission data moved to default IA record' ];
        if (!$this->addSysUpdate($updateID) && $apply) {
            $msgs .= '<b>' . $updateID[0] . ':</b> ' . $updateID[1] . '<br />';
            $flds = [
                'OverWebsite', 'OverFacebook', 'OverTwitter', 'OverYouTube', 'OverEmail', 'OverHomepageComplaintLink',
                'OverWebComplaintInfo', 'OverComplaintPDF', 'OverComplaintWebForm', 'OverSubmitDeadline',
                'OverWaySubEmail', 'OverWaySubVerbalPhone', 'OverWaySubPaperMail', 'OverWaySubPaperInPerson',
                'OverOfficialFormNotReq', 'OverOfficialAnon', 'OverWaySubNotary'
                ];
            $ovrs = OPOversight::where('OverType', 
                    $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Civilian Oversight'))
                ->get();
            if ($ovrs->isNotEmpty()) {
                foreach ($ovrs as $ovr) {
                    $ia = OPOversight::where('OverDeptID', $ovr->OverDeptID)
                        ->where('OverType', 
                            $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Internal Affairs'))
                        ->first();
                    if ($ia) {
                        foreach ($flds as $fld) {
                            if ((!isset($ia->{ $fld }) || trim($ia->{ $fld }) == '') 
                                && isset($ovr->{ $fld }) && trim($ovr->{ $fld }) != '') {
                                $ia->{ $fld } = $ovr->{ $fld };
                            }
                        }
                        $ia->save();
                    }
                }
            }
        } // end update 'OPC-2018-02-08'
        
        return $msgs;
    }
    
    public function userFormalName($uID)
    {
        $userInfo = OPzVolunUserInfo::where('UserInfoUserID', $uID)->first();
        if ($userInfo && isset($userInfo->UserInfoPersonContactID)) {
            $personContact = OPPersonContact::find($userInfo->UserInfoPersonContactID);
            if ($personContact && (isset($personContact->PrsnNameFirst) 
                || isset($personContact->PrsnNameLast))) {
                return trim($personContact->PrsnNameFirst . ' ' . $personContact->PrsnNameLast);
            }
        }
        $usr = User::find($uID);
        if ($usr && isset($usr->name)) {
            return $usr->name;
        }
        return '';
    }
    
    protected function loadSearchSuggestions()
    {
        $this->v["searchSuggest"] = [];
        $deptCitys = OPDepartments::select('DeptAddressCity')
            ->distinct()
            ->get();
        if ($deptCitys->isNotEmpty()) {
            foreach ($deptCitys as $dept) {
                if (!in_array($dept->DeptAddressCity, $this->v["searchSuggest"]) 
                    && $dept->DeptAddressCounty) {
                    $this->v["searchSuggest"][] = json_encode($dept->DeptAddressCity);
                }
            }
        }
        $deptCounties = OPDepartments::select('DeptAddressCounty')
            ->distinct()
            ->get();
        if ($deptCounties->isNotEmpty()) {
            foreach ($deptCounties as $dept) {
                if (!in_array($dept->DeptAddressCounty, $this->v["searchSuggest"]) 
                    && $dept->DeptAddressCounty) {
                    $this->v["searchSuggest"][] = json_encode($dept->DeptAddressCounty);
                }
            }
        }
        return true;
    }
    
    protected function printComplaintStatus($defID)
    {
        switch ($defID) {
            case 194: return 'Incomplete';
            case 196: return 'New';
            case 195: return 'Hold';
            case 197: return 'Reviewed';
            case 627: return 'Needs More Work';
            case 198: return 'Pending Attorney';
            case 199: return 'Attorney\'d';
            case 202: return 'OK to Submit to Investigative Agency';
            case 200: return 'Submitted to Investigative Agency';
            case 201: return 'Received by Investigative Agency';
            case 203: return 'Declined To Investigate (Closed)';
            case 204: return 'Investigated (Closed)';
            case 205: return 'Closed';
        }
        return $GLOBALS["SL"]->def->getVal('Complaint Status', $defID);
    }

    protected function loadReportUploadTypes()
    {
        $this->v["reportUploadTypes"] = [
            [
                'full',
                'Full Sensitive Report'
            ],[
                'public',
                'Public Report'
            ],[
                'findings',
                'Investigative Findings Report'
            ]
        ];
        return $this->v["reportUploadTypes"];
    }

    protected function loadOversightDateLookups()
    {
        $this->v["oversightDateLookups"] = [
            [
                'LnkComOverSubmitted',       
                'Submitted to Investigative Agency'
            ],[
                'LnkComOverReceived',        
                'Received by Investigative Agency'
            ],[
                'LnkComOverStillNoResponse', 
                'Still No Response from Agency'
            ],[
                'LnkComOverInvestigated',
                'Investigated by Investigative Agency'
            ],[
                'LnkComOverReportDate',
                'Investigative Agency Report Uploaded'
            ]
        ];
        return $this->v["oversightDateLookups"];
    }
    
}
