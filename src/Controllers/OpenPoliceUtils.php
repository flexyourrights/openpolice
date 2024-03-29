<?php
/**
  * OpenPoliceUtils is the bottom-level class extending Survloop
  * that performs smaller data translation, lookup functions.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.0.12
  */
namespace FlexYourRights\OpenPolice\Controllers;

use Auth;
use App\Models\User;
use App\Models\OPComplaints;
use App\Models\OPCompliments;
use App\Models\OPDepartments;
use App\Models\OPIncidents;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksComplimentDept;
use App\Models\OPPersonContact;
use App\Models\OPzVolunUserInfo;
use FlexYourRights\OpenPolice\Controllers\OpenPoliceVars;

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
        return ($this->sessData->dataSets["complaints"][0]->com_award_medallion
            == 'Silver');
    }

    protected function isGold()
    {
        if (!isset($this->sessData->dataSets["complaints"])) {
            return false;
        }
        return ($this->sessData->dataSets["complaints"][0]->com_award_medallion
            == 'Gold');
    }

    protected function isPublicOfficerName()
    {
        if ($this->treeID == 1) {
            if (!isset($this->sessData->dataSets["officers"])
                || sizeof($this->sessData->dataSets["officers"]) == 0) {
                return true;
            }
            return $this->sessData->dataFieldIsInt(
                'complaints',
                'com_publish_officer_name'
            );
        } elseif ($this->treeID == 5) {
            if (!isset($this->sessData->dataSets["officers"])
                || sizeof($this->sessData->dataSets["officers"]) == 0) {
                return true;
            }
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
        return $this->isPublicComplainantName()
            && $this->isPublicOfficerName();
    }

    public function isTypeComplaint($coreRec)
    {
        $def = $GLOBALS["SL"]->def->getID(
            'Complaint Type',
            'Police Complaint'
        );
        return (isset($coreRec->com_type)
            && intVal($coreRec->com_type) == $def);
    }

    public function isTypeUnverified($coreRec)
    {
        $def = $GLOBALS["SL"]->def->getID(
            'Complaint Type',
            'Unverified'
        );
        return (isset($coreRec->com_type)
            && intVal($coreRec->com_type) == $def);
    }

    public function isPublished($coreTbl, $coreID, $coreRec = NULL)
    {
        if ($coreTbl == 'complaints') {
            if (!$coreRec
                || !isset($coreRec->com_status)
                || !isset($coreRec->com_type)) {
                $coreRec = OPComplaints::find($coreID);
            }
            if ($coreRec
                && isset($coreRec->com_status)
                && $this->isTypeComplaint($coreRec)) {
                $pubDefs = $this->getPublishedStatusList($coreTbl);
                return in_array($coreRec->com_status, $pubDefs);
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
        $ret = 'Started '
            . $GLOBALS["SL"]->printTimeZoneShift($coreRecord[1]->created_at);
        if ($this->treeID == 1) {
            $incident = OPIncidents::find($coreRecord[1]->com_incident_id);
            if ($incident && isset($incident->inc_address_city)) {
                $ret .= $incident->inc_address_city . ', Incident Date: '
                    . date('n/j/y', strtotime($incident->inc_time_start));
            }
            $ret .= ' (Complaint #' . $coreRecord[1]->com_id . ')';
        } elseif ($this->treeID == 5) {
            $incident = OPIncidents::find($coreRecord[1]->compli_incident_id);
            if ($incident && isset($incident->inc_address_city)) {
                $ret .= $incident->inc_address_city . ', Incident Date: '
                    . date('n/j/y', strtotime($incident->inc_time_start));
            }
            $ret .= ' (Compliment #' . $coreRecord[1]->compli_id . ')';
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
            $def = $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete');
            if ($coreRec->com_status == $def) {
                return true;
            }
        } elseif ($this->treeID == 5) {
            $def = $GLOBALS["SL"]->def->getID('Compliment Status', 'Incomplete');
            if ($coreRec->compli_status == $def) {
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
            $typeDef = $GLOBALS["SL"]->def->getID(
                'Complaint Type',
                'Police Complaint'
            );
            $list = OPComplaints::where('com_type', $typeDef)
                ->whereIn('com_status', $this->getPublishedStatusList($coreTbl))
                ->select('com_id', 'com_public_id')
                ->orderBy('created_at', 'desc')
                ->get();
            if ($list && $list->isNotEmpty()) {
                foreach ($list as $l) {
                    $this->allPublicCoreIDs[] = $l->com_id;
                }
            }
        } elseif ($coreTbl == 'compliments') {
            $list = OPCompliments::whereIn('compli_status',
                    $this->getPublishedStatusList($coreTbl))
                //->where('compli_type', $typeDef)
                ->select('compli_id', 'compli_public_id')
                ->orderBy('created_at', 'desc')
                ->get();
            if ($list && $list->isNotEmpty()) {
                foreach ($list as $l) {
                    $this->allPublicCoreIDs[] = $l->compli_id;
                }
            }
        } elseif ($coreTbl == 'departments') {
            $list = null;
            if ($GLOBALS["SL"]->REQ->has('state')
                && trim($GLOBALS["SL"]->REQ->get('state')) != '') {
                $list = OPDepartments::whereNotNull('dept_name')
                    ->where('dept_name', 'NOT LIKE', '')
                    ->where('dept_address_state',
                        trim($GLOBALS["SL"]->REQ->get('state')))
                    ->select('dept_id')
                    ->orderBy('dept_name', 'asc')
                    ->get();
            } else {
                $list = OPDepartments::whereNotNull('dept_name')
                    ->where('dept_name', 'NOT LIKE', '')
                    ->select('dept_id')
                    ->orderBy('dept_address_state', 'asc')
                    ->orderBy('dept_name', 'asc')
                    ->get();
            }
            if ($list && $list->isNotEmpty()) {
                foreach ($list as $l) {
                    $this->allPublicCoreIDs[] = $l->dept_id;
                }
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
                $GLOBALS["SL"]->def->getID($set, 'Wants Attorney'),
                $GLOBALS["SL"]->def->getID($set, 'Pending Attorney'),
                $GLOBALS["SL"]->def->getID($set, 'Has Attorney')
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
        } elseif ($com
            && isset($com->com_publish_officer_name)
            && intVal($com->com_publish_officer_name) == 1) {
            $printOff = true;
        }
        return $printOff;
    }

    protected function canPrintComplainantName($com = null)
    {
        return ($com
            && isset($com->com_publish_user_name)
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

    protected function canPrintFullReport($view = '')
    {
        if (!isset($this->sessData->dataSets["complaints"])) {
            return false;
        }
        if ($view != 'public'
            && ((isset($this->v["isOwner"]) && $this->v["isOwner"])
                || $this->isStaffOrAdmin())) {
            return true;
        }
        $com = $this->sessData->dataSets["complaints"][0];
        return $this->canPrintFullReportByRecordSpecs($com);
    }

    protected function canPrintFullReportOrPrivs()
    {
        return ((isset($this->v["isOwner"]) && $this->v["isOwner"])
            || $this->isStaffOrAdmin()
            || $this->canPrintFullReport());
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
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Wants Attorney'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Has Attorney'),
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
            } elseif ($this->isStaffOrAdmin()) {
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
                $this->loadDeptStuff($deptID, $this->coreID);
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

    public function userFormalName($uID)
    {
        $userInfo = OPzVolunUserInfo::where('user_info_user_id', $uID)
            ->first();
        if ($userInfo && isset($userInfo->user_info_person_contact_id)) {
            $personContact = OPPersonContact::find($userInfo->user_info_person_contact_id);
            if ($personContact
                && (isset($personContact->prsn_name_first)
                    || isset($personContact->prsn_name_last))) {
                return trim($personContact->prsn_name_first
                    . ' ' . $personContact->prsn_name_last);
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

    protected function printComplaintStatus($defID, $type = 0)
    {
        return $GLOBALS["CUST"]->printComplaintStatus($defID, $type);
    }

    protected function loadReportUploadTypes()
    {
        $this->v["reportUploadTypes"] = [
            [
                'sensitive',
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
                'Filed with Investigative Agency'
            ],[
                'lnk_com_over_received',
                'Received by Investigative Agency'
            ],[
                'lnk_com_over_still_no_response',
                'Still No Response from Investigative Agency'
            ],[
                'lnk_com_over_investigated',
                'Investigated by Investigative Agency'
            ],[
                'lnk_com_over_report_date',
                'Investigative Agency Report Uploaded'
            ],[
                'lnk_com_over_declined',
                'Investigative Agency Declined To Investigate'
            ]
        ];
        return $this->v["oversightDateLookups"];
    }

}
