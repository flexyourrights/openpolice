<?php
/**
  * OpenPoliceUtils is the bottom-level class extending SurvLoop
  * that performs smaller data translation, lookup functions.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
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
        if (!isset($this->sessData->dataSets["complaints"])) {
            return ($this->treeID == 5);
        }
        $com = $this->sessData->dataSets["complaints"][0];
        return (isset($com->com_is_compliment)
            && intVal($com->com_is_compliment) == 1);
    }
    
    protected function isSilver()
    { 
        if (!isset($this->sessData->dataSets["complaints"])) {
            return false;
        }
        return ($this->sessData->dataSets["complaints"][0]
            ->com_award_medallion == 'Silver'); 
    }
    
    protected function isGold()
    {
        if (!isset($this->sessData->dataSets["complaints"])) {
            return false;
        }
        return ($this->sessData->dataSets["complaints"][0]
            ->com_award_medallion == 'Gold');
    }

    protected function isPublicOfficerName()
    {
        if ($this->treeID == 1) {
            return $this->sessData->dataFieldIsInt(
                'complaints', 
                'com_publish_officer_name'
            );
        } elseif ($this->treeID == 5) {
            return $this->sessData->dataFieldIsInt(
                'compliments', 
                'compli_publish_officer_name'
            );
        }
        return false;
    }

    protected function isPublicComplainantName()
    {
        if ($this->treeID == 1) {
            return $this->sessData->dataFieldIsInt(
                'complaints', 
                'com_publish_user_name'
            );
        } elseif ($this->treeID == 5) {
            return $this->sessData->dataFieldIsInt(
                'compliments', 
                'compli_publish_user_name'
            );
        }
        return false;
    }

    protected function isPublic()
    {
        return $this->isPublicComplainantName() && $this->isPublicOfficerName();
    }
    
    public function isPublished($coreTbl, $coreID, $coreRec = NULL)
    {
        if ($coreTbl == 'complaints') {
            if (!$coreRec || !isset($coreRec->com_status)) {
                $coreRec = OPComplaints::find($coreID);
            }
            if ($coreRec && isset($coreRec->com_status)) {
                return in_array(
                    $coreRec->com_status, 
                    $this->getPublishedStatusList($coreTbl)
                );
            }
            return false;
        }
        return false;
    }
    
    protected function isAnonyLogin()
    {
        if ($this->treeID == 1) {
            return $this->sessData->dataFieldIsInt(
                'complaints', 
                'com_anon'
            );
        } elseif ($this->treeID == 5) {
            return $this->sessData->dataFieldIsInt(
                'compliments', 
                'compli_anon'
            );
        }
        return false;
    }
    
    public function multiRecordCheckIntro($cnt = 1)
    {
        $ret = '<h3 class="slBlueDark">' 
            . $this->v["user"]->name . ', You Have ';
        if ($this->treeID == 1) {
            $ret .= (($cnt == 1) 
                ? 'An Unfinished Complaint' 
                : 'Unfinished Complaints');
        } elseif ($this->treeID == 5) {
            $ret .= (($cnt == 1) 
                ? 'An Unfinished Compliment' 
                : 'Unfinished Compliments');
        }
        return $ret . '</h3>';
    }
    
    public function multiRecordCheckRowTitle($coreRecord)
    {
        $ret = '';
        if ($this->treeID == 1) {
            if (isset($coreRecord[1]->com_summary) 
                && trim($coreRecord[1]->com_summary) != '') {
                $ret .= $GLOBALS["SL"]->wordLimitDotDotDot(
                    $coreRecord[1]->com_summary, 
                    10
                );
            } else {
                $ret .= '(empty)';
            }
        } elseif ($this->treeID == 5) {
            if (isset($coreRecord[1]->compli_summary) 
                && trim($coreRecord[1]->compli_summary) != '') {
                $ret .= $GLOBALS["SL"]->wordLimitDotDotDot(
                    $coreRecord[1]->compli_summary, 
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
        $ret = 'Started ' . date('n/j/y, g:ia', strtotime($coreRecord[1]->created_at));
        if ($this->treeID == 1) {
            $incident = OPIncidents::find($coreRecord[1]->com_incident_id);
            if ($incident && isset($incident->inc_address_city)) {
                $ret .= $incident->inc_address_city . ', Incident Date: ' 
                    . date('n/j/y', strtotime($incident->inc_time_start));
            }
            $ret .= ' (Complaint #' . $coreRecord[1]->getKey() . ')';
        } elseif ($this->treeID == 5) {
            $incident = OPIncidents::find($coreRecord[1]->compli_incident_id);
            if ($incident && isset($incident->inc_address_city)) {
                $ret .= $incident->inc_address_city . ', Incident Date: ' 
                    . date('n/j/y', strtotime($incident->inc_time_start));
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
        if (!isset($coreRec->com_submission_progress) 
            || intVal($coreRec->com_submission_progress) <= 0) {
            return true;
        }
        if (!isset($coreRec->com_summary) 
            || trim($coreRec->com_summary) == '') {
            return true;
        }
        return false;
    }
    
    protected function recordIsEditable($coreTbl, $coreID, $coreRec = NULL)
    {
        if (!$coreRec && $coreID > 0) {
            $coreRec = OPComplaints::find($coreID);
        }
        if (!isset($coreRec->com_status)) {
            return true;
        }
        if (!$coreRec) {
            return false;
        }
        if ($this->treeID == 1) {
            if ($coreRec->com_status == $GLOBALS["SL"]->def
                ->getID('Complaint Status', 'Incomplete')) {
                return true;
            }
        } elseif ($this->treeID == 5) {
            if ($coreRec->compli_status == $GLOBALS["SL"]->def
                ->getID('Compliment Status', 'Incomplete')) {
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
        $list = null;
        if ($coreTbl == 'complaints') {
            $list = OPComplaints::whereIn('com_status', 
                    $this->getPublishedStatusList($coreTbl))
                //->where('com_type', $GLOBALS["SL"]->def->getID('Complaint Type', 'Police Complaint'))
                ->select('com_id', 'com_public_id')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($coreTbl == 'compliments') {
            $list = OPCompliments::whereIn('compli_status', 
                    $this->getPublishedStatusList($coreTbl))
                //->where('compli_type', $GLOBALS["SL"]->def->getID('Complaint Type', 'Police Complaint'))
                ->select('compli_id') //, 'compli_public_id')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        if ($list && $list->isNotEmpty()) {
            foreach ($list as $l) {
                $this->allPublicCoreIDs[] = $l->com_public_id;
            }
        }
        //echo '<pre>'; print_r($this->allPublicCoreIDs); echo '</pre>';
        return $this->allPublicCoreIDs;
    }
    
    protected function getPublishedStatusList($coreTbl = '')
    {
        if (!isset($coreTbl) || trim($coreTbl) == '') {
            $coreTbl = $GLOBALS["SL"]->coreTbl;
        }
        if ($coreTbl == 'complaints') {
            $set = 'Complaint Status';
            return [
                $GLOBALS["SL"]->def->getID($set, 'OK to Submit to Oversight'), 
                $GLOBALS["SL"]->def->getID($set, 'Submitted to Oversight'), 
                $GLOBALS["SL"]->def->getID($set, 'Received by Oversight'), 
                $GLOBALS["SL"]->def->getID($set, 'Declined To Investigate (Closed)'), 
                $GLOBALS["SL"]->def->getID($set, 'Investigated (Closed)')
            ];
        } elseif ($coreTbl == 'compliments') {
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
        if ($coreTbl == 'complaints') {
            $set = 'Complaint Status';
            return [
                $GLOBALS["SL"]->def->getID($set, 'Incomplete'), 
                $GLOBALS["SL"]->def->getID($set, 'New'), 
                $GLOBALS["SL"]->def->getID($set, 'Hold'), 
                $GLOBALS["SL"]->def->getID($set, 'Reviewed'), 
                $GLOBALS["SL"]->def->getID($set, 'Needs More Work'), 
                $GLOBALS["SL"]->def->getID($set, 'Pending Attorney'), 
                $GLOBALS["SL"]->def->getID($set, 'Attorney\'d')
            ];
        } elseif ($coreTbl == 'compliments') {
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
        if (isset($this->sessData->dataSets["complaints"])
            && isset($this->sessData->dataSets["complaints"][0]->com_status)) {
            $status = $this->sessData->dataSets["complaints"][0]->com_status;
            if (in_array($status, $this->getPublishedStatusList('complaints'))) {
                return 1;
            }
        }
        return 0;
    }
    
    protected function canPrintOfficersName($com = null)
    {
        $printOff = false;
        if (!isset($this->sessData->dataSets["officers"])
            || sizeof($this->sessData->dataSets["officers"]) == 0) {
            $printOff = true;
        } elseif (isset($com->com_publish_officer_name)
            && intVal($com->com_publish_officer_name) == 1) {
            $printOff = true;
        }
        return $printOff;
    }
    
    protected function canPrintComplainantName($com = null)
    {
        return (isset($com->com_publish_user_name)
            && intVal($com->com_publish_user_name) == 1);
    }
    
    protected function canPrintFullReportByRecSettings($com = null)
    {
        if ( (!$com || $com === null)
            && isset($this->sessData->dataSets["complaints"]) 
            && isset($this->sessData->dataSets["complaints"][0]) ) {
            $com = $this->sessData->dataSets["complaints"][0];
        }
        return ($this->canPrintComplainantName($com)
            && $this->canPrintOfficersName($com));
    }
    
    protected function corePublishStatuses($com = null)
    {
        return in_array($com->com_status, [200, 201, 203, 204]);
    }
    
    protected function canPrintFullReportByRecordSpecs($com = null)
    {
        if ( (!$com || $com === null)
            && isset($this->sessData->dataSets["complaints"]) 
            && isset($this->sessData->dataSets["complaints"][0]) ) {
            $com = $this->sessData->dataSets["complaints"][0];
        }
        return ($this->corePublishStatuses($com)
            && $this->canPrintFullReportByRecSettings($com));
    }
    
    protected function canPrintFullReport()
    {
        if (!isset($this->sessData->dataSets["complaints"])) {
            return false;
        }
        if ((isset($this->v["isAdmin"]) && $this->v["isAdmin"])
            || (isset($this->v["isOwner"]) && $this->v["isOwner"])) {
            return true;
        }
        $com = $this->sessData->dataSets["complaints"][0];
        return $this->canPrintFullReportByRecordSpecs($com);
    }
    
    public function tblsInPackage()
    {
        if ($this->dbID == 1) {
            return ['departments', 'oversight'];
        }
        return [];
    }
    
    protected function maxUserView()
    {
        if ($this->isPublic()
            || in_array($this->sessData->dataSets["complaints"][0]->com_status, [
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Reviewed'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight')
            ])) {
            $GLOBALS["SL"]->pageView = 'public';
        }
        if (isset($this->v["user"]) && isset($this->v["user"]->id)) {
            if (isset($this->sessData->dataSets["civilians"]) 
                && $this->v["uID"] == $this->sessData->dataSets["civilians"][0]->civ_user_id) {
                //$this->v["isOwner"] = true;
                if (isset($GLOBALS["SL"]->x["fullAccess"]) && $GLOBALS["SL"]->x["fullAccess"]) {
                    $GLOBALS["SL"]->pageView = 'full';
                }
            } elseif (isset($this->sessData->dataSets["complaints"]) 
                && $this->v["uID"] == $this->sessData->dataSets["complaints"][0]->com_user_id) {
                if (isset($GLOBALS["SL"]->x["fullAccess"]) && $GLOBALS["SL"]->x["fullAccess"]) {
                    $GLOBALS["SL"]->pageView = 'full';
                }
            } elseif ($this->v["user"]->hasRole('administrator|staff')) {
                $GLOBALS["SL"]->pageView = 'full';
            } elseif ($this->v["user"]->hasRole('partner') 
                && $this->v["user"]->hasRole('oversight')) {
                $overRow = OPOversight::where('over_email', $this->v["user"]->email)
                    ->first();
                if ($overRow && isset($overRow->over_dept_id)) {
                    $lnkChk = OPLinksComplaintDept::where('lnk_com_dept_complaint_id', 
                            $this->coreID)
                        ->where('lnk_com_dept_dept_id', $overRow->over_dept_id)
                        ->first();
                    if ($lnkChk && isset($lnkChk->lnk_com_dept_id)) {
                        $GLOBALS["SL"]->pageView = 'full';
                    }
                }
            }
        }
        return true;
    }
    
    protected function loadYourContact()
    {
        if (Auth::user() && isset(Auth::user()->id) && Auth::user()->id > 0) {
            $uID = Auth::user()->id;
            $this->v["yourContact"] = OPPersonContact::where('prsn_user_id', $uID)
                ->first();
            if (!$this->v["yourContact"] || !isset($this->v["yourContact"]->prsn_id)) {
                $this->v["yourContact"] = new OPPersonContact;
                $this->v["yourContact"]->prsn_user_id = Auth::user()->id;
                $this->v["yourContact"]->save();
            } elseif (!isset($this->v["yourContact"]->prsn_user_id)) {
                $this->v["yourContact"]->prsn_user_id = Auth::user()->id;
                $this->v["yourContact"]->save();
            }
        }
        return true;
    }
    
    public function loadDeptStuff($deptID = -3)
    {
        $GLOBALS["SL"]->loadStates();
        if (!isset($this->v["deptScores"])) {
            $this->v["deptScores"] = new DepartmentScores;
            if ($deptID <= 0) {
                $this->initSearcher();
                $this->searcher->getSearchFilts();
                $this->v["deptScores"]->loadAllDepts($this->searcher->searchFilts);
            }
        }
        if ($deptID > 0) {
            $this->v["deptScores"]->loadDeptStuff($deptID);
        }
        return true;
    }
    
    protected function getDeptUser($deptID = -3)
    {
        $this->chkDeptUsers();
        if ($deptID > 0 
            && isset($GLOBALS["SL"]->x["depts"]) 
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
        if (isset($this->sessData->dataSets["links_complaint_dept"]) 
            && sizeof($this->sessData->dataSets["links_complaint_dept"]) > 0) {
            foreach ($this->sessData->dataSets["links_complaint_dept"] as $deptLnk) {
                $deptID = $deptLnk->lnk_com_dept_dept_id;
                $this->loadDeptStuff($deptID);
                $wchOvr = $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"];
                if (isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && (!isset($GLOBALS["SL"]->x["depts"][$deptID]["overUser"]) 
                    || !isset($GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->email)) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID][$wchOvr])
                    && isset($GLOBALS["SL"]->x["depts"][$deptID][$wchOvr]->over_email)) {
                    $overRow = $GLOBALS["SL"]->x["depts"][$deptID][$wchOvr];
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"] = new User;
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->email = $overRow->over_email;
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->name = $overRow->over_agnc_name;
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->password = $this->genTokenStr('pass', 100);
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->save();
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->assignRole('oversight');
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->assignRole('partner');
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->assignRole('volunteer');

                    $GLOBALS["SL"]->x["depts"][$deptID][$wchOvr]->update([
                        'over_user_id' => $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->id
                    ]);
                }
            }
        }
        return true;
    }
    
    protected function chkOverUserHasCore()
    {
//echo '??? chkOverUserHasCore(' . $this->v["uID"] . ', <pre>'; print_r($this->sessData->dataSets["oversight"]); echo '</pre>'; exit;

        if ($this->v["uID"] > 0 && $this->v["user"]->hasRole('oversight')) {
            if (isset($this->sessData->dataSets["oversight"]) 
                && sizeof($this->sessData->dataSets["oversight"]) > 0) {
                foreach ($this->sessData->dataSets["oversight"] as $i => $o) {
                    if (isset($o->over_email) 
                        && $o->over_email == $this->v["user"]->email) {
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
                'over_website', 'over_facebook', 'over_twitter', 'over_youtube', 'over_email', 
                'over_homepage_complaint_link', 'over_web_complaint_info', 'over_complaint_pdf', 
                'over_complaint_web_form', 'over_submit_deadline', 'over_way_sub_email', 
                'over_way_sub_verbal_phone', 'over_way_sub_paper_mail', 'over_way_sub_paper_in_person',
                'over_official_form_not_req', 'over_official_anon', 'over_way_sub_notary'
                ];
            $civDef = $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Civilian Oversight');
            $iaDef = $GLOBALS["SL"]->def->getID('Investigative Agency Types', 'Internal Affairs');
            $ovrs = OPOversight::where('over_type', $civDef)
                ->get();
            if ($ovrs->isNotEmpty()) {
                foreach ($ovrs as $ovr) {
                    $ia = OPOversight::where('over_dept_id', $ovr->over_dept_id)
                        ->where('over_type', $iaDef)
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
        $userInfo = OPzVolunUserInfo::where('user_info_user_id', $uID)
            ->first();
        if ($userInfo && isset($userInfo->user_info_person_contact_id)) {
            $personContact = OPPersonContact::find($userInfo->user_info_person_contact_id);
            if ($personContact 
                && (isset($personContact->prsn_name_first) || isset($personContact->prsn_name_last))) {
                return trim($personContact->prsn_name_first . ' ' . $personContact->prsn_name_last);
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
        $deptCitys = OPDepartments::select('dept_address_city')
            ->distinct()
            ->get();
        if ($deptCitys->isNotEmpty()) {
            foreach ($deptCitys as $dept) {
                if (!in_array($dept->dept_address_city, $this->v["searchSuggest"]) 
                    && $dept->dept_address_county) {
                    $this->v["searchSuggest"][] = json_encode($dept->dept_address_city);
                }
            }
        }
        $deptCounties = OPDepartments::select('dept_address_county')
            ->distinct()
            ->get();
        if ($deptCounties->isNotEmpty()) {
            foreach ($deptCounties as $dept) {
                if (!in_array($dept->dept_address_county, $this->v["searchSuggest"]) 
                    && $dept->dept_address_county) {
                    $this->v["searchSuggest"][] = json_encode($dept->dept_address_county);
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
            ],[
                'declined',
                'Investigation Declined'
            ]
        ];
        return $this->v["reportUploadTypes"];
    }

    protected function loadOversightDateLookups()
    {
        $this->v["oversightDateLookups"] = [
            [
                'lnk_com_over_submitted',       
                'Submitted to Investigative Agency'
            ],[
                'lnk_com_over_received',        
                'Received by Investigative Agency'
            ],[
                'lnk_com_over_still_no_response', 
                'Still No Response from Agency'
            ],[
                'lnk_com_over_investigated',
                'Investigated by Investigative Agency'
            ],[
                'lnk_com_over_report_date',
                'Investigative Agency Report Uploaded'
            ]
        ];
        return $this->v["oversightDateLookups"];
    }
    
}
