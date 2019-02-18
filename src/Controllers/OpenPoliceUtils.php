<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use App\Models\User;
use App\Models\OPComplaints;
use App\Models\OPCompliments;
use App\Models\OPIncidents;
use App\Models\OPEventSequence;
use App\Models\OPStops;
use App\Models\OPSearches;
use App\Models\OPArrests;
use App\Models\OPForce;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksOfficerEvents;
use App\Models\OPLinksCivilianEvents;
use App\Models\OPPersonContact;
use App\Models\OPzVolunUserInfo;
use SurvLoop\Controllers\TreeSurvForm;

class OpenPoliceUtils extends TreeSurvForm
{
    public $classExtension     = 'OpenPoliceUtils';
    public $treeID             = 1;
    
    protected $allCivs         = [];
    protected $allegations     = [];
    public $worstAllegations   = [ // Allegations in descending order of severity, Definition IDs
        [126, 'Sexual Assault',                 'AlleSilSexualAssault'],
        [115, 'Unreasonable Force',             'AlleSilForceUnreason'],
        [116, 'Wrongful Arrest',                'AlleSilArrestWrongful'], 
        [480, 'Sexual Harassment',              'AlleSilSexualHarass'], 
        [120, 'Wrongful Property Seizure',      'AlleSilPropertyWrongful'],
        [496, 'Wrongful Property Damage',       'AlleSilPropertyDamage'],
        [125, 'Intimidating Display of Weapon', 'AlleSilIntimidatingWeapon'], 
        [119, 'Wrongful Search',                'AlleSilSearchWrongful'],
        [118, 'Wrongful Entry',                 'AlleSilWrongfulEntry'],
        [117, 'Wrongful Detention',             'AlleSilStopWrongful'], 
        [121, 'Bias-Based Policing',            'AlleSilBias'],
        [122, 'Excessive Arrest Charges',       'AlleSilArrestRetaliatory'],
        [124, 'Excessive Citation',             'AlleSilCitationExcessive'],
        [128, 'Conduct Unbecoming an Officer',  'AlleSilUnbecoming'],
        [129, 'Discourtesy',                    'AlleSilDiscourteous'],
        [127, 'Neglect of Duty',                'AlleSilNeglectDuty'], 
        [127, 'Policy or Procedure Violation',  'AlleSilProcedure'], 
        [131, 'Miranda Rights',                 'AlleSilArrestMiranda'],
        [130, 'Officer Refused To Provide ID',  'AlleSilOfficerRefuseID']
        ];
    public $eventTypes         = [ 'Stops', 'Searches', 'Force', 'Arrests' ];
    public $eveTypIDs          = [ 252 => 'Stops', 253 => 'Searches', 254 => 'Force', 255 => 'Arrests' ];
    protected $eventTypeLabel  = [
        'Stops'    => 'Stop/Questioning',
        'Searches' => 'Search/Seizure',
        'Force'    => 'Use of Force',
        'Arrests'  => 'Arrest'
        ];
    protected $eventGoldAllegs = [
        'Stops'    => [117, 118],
        'Searches' => [119, 120, 496],
        'Force'    => [115],
        'Arrests'  => [116, 122]
        ];
    protected $cmplntUpNodes   = [280, 324, 317, 413, 371];
    protected $eventTypeLookup = []; // $eveSeqID => 'Event Type'
    protected $eventCivLookup  = []; // array('Event Type' => array($civID, $civID, $civID))
    
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
        return ($overRow && isset($overRow->OverEmail) && trim($overRow->OverEmail) != '' 
            && isset($overRow->OverWaySubEmail) && intVal($overRow->OverWaySubEmail) == 1
            && isset($overRow->OverOfficialFormNotReq) && intVal($overRow->OverOfficialFormNotReq) == 1);
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
            if (!$coreRec) {
                $coreRec = OPComplaints::find($coreID);
            }
            if ($coreRec && isset($coreRec->ComStatus)) {
                return (in_array($coreRec->ComStatus, $this->getPublishedStatusList($coreTbl)));
            }
            return false;
        }
        return false;
    }
    
    protected function moreThan1Victim()
    { 
        if (!isset($this->sessData->loopItemIDs['Victims'])) {
            return false;
        }
        return (sizeof($this->sessData->loopItemIDs['Victims']) > 1); 
    }
    
    protected function moreThan1Officer() 
    { 
        if (!isset($this->sessData->loopItemIDs['Officers'])) {
            return false;
        }
        return (sizeof($this->sessData->loopItemIDs["Officers"]) > 1); 
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
            $ret .= (($cnt == 1) ? 'An Unfinished Complaint' : 'Unfinished Complaints');
        } elseif ($this->treeID == 5) {
            $ret .= (($cnt == 1) ? 'An Unfinished Compliment' : 'Unfinished Compliments');
        }
        return $ret . '</h3>';
    }
    
    public function multiRecordCheckRowTitle($coreRecord)
    {
        $ret = '';
        if ($this->treeID == 1) {
            if (isset($coreRecord[1]->ComSummary) && trim($coreRecord[1]->ComSummary) != '') {
                $ret .= $GLOBALS["SL"]->wordLimitDotDotDot($coreRecord[1]->ComSummary, 10);
            } else {
                $ret .= '(empty)';
            }
        } elseif ($this->treeID == 5) {
            if (isset($coreRecord[1]->CompliSummary) && trim($coreRecord[1]->CompliSummary) != '') {
                $ret .= $GLOBALS["SL"]->wordLimitDotDotDot($coreRecord[1]->CompliSummary, 10);
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
        return 'Are you sure you want to delete this complaint? Deleting it CANNOT be undone.';
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
        if (!isset($coreRec->ComSubmissionProgress) || intVal($coreRec->ComSubmissionProgress) <= 0) {
            return true;
        }
        if (!isset($coreRec->ComSummary) || trim($coreRec->ComSummary) == '') {
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
            if ($coreRec->ComStatus == $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) {
                return true;
            }
        } elseif ($this->treeID == 5) {
            if ($coreRec->ComStatus == $GLOBALS["SL"]->def->getID('Compliment Status', 'Incomplete')) {
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
            $list = OPComplaints::whereIn('ComStatus', $this->getPublishedStatusList($coreTbl))
                //->where('ComType', $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Police Complaint'))
                ->select('ComID', 'ComPublicID')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($coreTbl == 'Compliments') {
            $list = OPCompliments::whereIn('CompliStatus', $this->getPublishedStatusList($coreTbl))
                //->where('CompliType', $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Police Complaint'))
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
        if (!isset($coreTbl)) {
            $coreTbl = $GLOBALS["SL"]->coreTbl;
        }
        if ($coreTbl == 'Complaints') {
            return [
                $GLOBALS["SL"]->def->getID('Complaint Status',  'Submitted to Oversight'), 
                $GLOBALS["SL"]->def->getID('Complaint Status',  'Received by Oversight'), 
                $GLOBALS["SL"]->def->getID('Complaint Status',  'Declined To Investigate (Closed)'), 
                $GLOBALS["SL"]->def->getID('Complaint Status',  'Investigated (Closed)')
            ];
        } elseif ($coreTbl == 'Compliments') {
            return [
                $GLOBALS["SL"]->def->getID('Compliment Status', 'Reviewed'), 
                $GLOBALS["SL"]->def->getID('Compliment Status', 'Submitted to Oversight'), 
                $GLOBALS["SL"]->def->getID('Compliment Status', 'Received by Oversight')
            ];
        }
        return [];
    }
    
    protected function getUnPublishedStatusList($coreTbl = '')
    {
        if (!isset($coreTbl)) {
            $coreTbl = $GLOBALS["SL"]->coreTbl;
        }
        if ($coreTbl == 'Complaints') {
            return [
                $GLOBALS["SL"]->def->getID('Complaint Status',  'Hold'), 
                $GLOBALS["SL"]->def->getID('Complaint Status',  'New'), 
                $GLOBALS["SL"]->def->getID('Complaint Status',  'Pending Attorney'), 
                $GLOBALS["SL"]->def->getID('Complaint Status',  'Attorney\'d'), 
                $GLOBALS["SL"]->def->getID('Complaint Status',  'Reviewed'), 
                $GLOBALS["SL"]->def->getID('Complaint Status',  'OK to Submit to Oversight'), 
                $GLOBALS["SL"]->def->getID('Complaint Status',  'Closed')
            ];
        } elseif ($coreTbl == 'Compliments') {
            return [
                $GLOBALS["SL"]->def->getID('Compliment Status', 'Hold'), 
                $GLOBALS["SL"]->def->getID('Compliment Status', 'New'),
                $GLOBALS["SL"]->def->getID('Compliment Status', 'Closed')
            ];
        }
        return [];
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
                $GLOBALS["SL"]->x["pageView"] = 'public';
            }
        }
        if (isset($this->v["user"]) && isset($this->v["user"]->id)) {
            if (isset($this->sessData->dataSets["Civilians"]) 
                && $this->v["uID"] == $this->sessData->dataSets["Civilians"][0]->CivUserID) {
                //$this->v["isOwner"] = true;
                if (isset($GLOBALS["fullAccess"]) && $GLOBALS["fullAccess"]) $GLOBALS["SL"]->x["pageView"] = 'full';
            } elseif ($this->v["user"]->hasRole('administrator|staff')) {
                $GLOBALS["SL"]->x["pageView"] = 'full';
            } elseif ($this->v["user"]->hasRole('partner') && $this->v["user"]->hasRole('oversight')) {
                $overRow = OPOversight::where('OverEmail', $this->v["user"]->email)->first();
                if ($overRow && isset($overRow->OverDeptID)) {
                    $lnkChk = OPLinksComplaintDept::where('LnkComDeptComplaintID', $this->coreID)
                        ->where('LnkComDeptDeptID', $overRow->OverDeptID)
                        ->first();
                    if ($lnkChk && isset($lnkChk->LnkComDeptID)) {
                        $GLOBALS["SL"]->x["pageView"] = 'full';
                    }
                }
            }
        }
        return true;
    }
    
    protected function loadYourContact()
    {
        if (Auth::user() && isset(Auth::user()->id) && Auth::user()->id > 0) {
            $this->v["yourContact"] = OPPersonContact::where('PrsnUserID', Auth::user()->id)
                ->first();
            if (!$this->v["yourContact"] || !isset($this->v["yourContact"]->PrsnID)) {
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
        if ($deptID > 0 && isset($GLOBALS["SL"]->x["depts"]) && sizeof($GLOBALS["SL"]->x["depts"]) > 0 
            && isset($GLOBALS["SL"]->x["depts"][$deptID]) && $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]) {
            return $GLOBALS["SL"]->x["depts"][$deptID]["overUser"];
        }
        return [];
    }
    
    // if oversight agency's email address doesn't have a User record, create one to link with tokens
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
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->password = $this->genTokenStr('pass', 100);
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->save();
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->assignRole('oversight');
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->assignRole('partner');
                    $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]->assignRole('volunteer');
                }
            }
        }
        return true;
    }
    
    protected function chkOverUserHasCore()
    {
        if ($this->v["uID"] > 0 && $this->v["user"]->hasRole('oversight')) {
            if (isset($this->sessData->dataSets["Oversight"]) && sizeof($this->sessData->dataSets["Oversight"]) > 0) {
                foreach ($this->sessData->dataSets["Oversight"] as $i => $o) {
                    if (isset($o->OverEmail) && $o->OverEmail == $this->v["user"]->email) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    
    
    
/*****************************************************************************
// START Processes Which Handle Allegations
*****************************************************************************/
    
    public function simpleAllegationList()
    {
        if (empty($this->allegations) && isset($this->sessData->dataSets["AllegSilver"]) 
            && isset($this->sessData->dataSets["AllegSilver"][0])) {
            foreach ($this->worstAllegations as $i => $alleg) {
                $allegInfo = [$alleg[1], '', -3, [], []]; // Alleg Name, Alleg Why, Alleg ID, Civs, Offs
                switch ($alleg[1]) {
                    case 'Sexual Assault':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilSexualAssault', $alleg[1], $alleg[0]);
                        break;
                    case 'Unreasonable Force':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilForceUnreason', $alleg[1], $alleg[0]);
                        break;
                    case 'Wrongful Arrest':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilArrestWrongful', $alleg[1], $alleg[0]);
                        break;
                    case 'Wrongful Property Seizure':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilPropertyWrongful', $alleg[1], $alleg[0]);
                        break;
                    case 'Intimidating Display of Weapon':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilIntimidatingWeapon', $alleg[1], $alleg[0]);
                        break;
                    case 'Wrongful Search':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilSearchWrongful', $alleg[1], $alleg[0]);
                        break;
                    case 'Wrongful Entry':
                        if (isset($this->sessData->dataSets["Stops"]) 
                            && sizeof($this->sessData->dataSets["Stops"]) > 0) {
                            foreach ($this->sessData->dataSets["Stops"] as $stop) {
                                if (isset($stop->StopAllegWrongfulEntry) && $stop->StopAllegWrongfulEntry == 'Y') {
                                    $allegRec = $this->getAllegGoldRec($alleg[1], $alleg[0]);
                                    $allegInfo[1] .= ', ' . $this->getAllegDesc($alleg[1], $alleg[0], $allegRec);
                                }
                            }
                        }
                        break;
                    case 'Wrongful Detention':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilStopWrongful', $alleg[1], $alleg[0]);
                        break;
                    case 'Bias-Based Policing':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilBias', $alleg[1], $alleg[0]);
                        break;
                    case 'Excessive Arrest Charges':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilArrestRetaliatory', $alleg[1], $alleg[0]);
                        break;
                    case 'Conduct Unbecoming an Officer':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilUnbecoming', $alleg[1], $alleg[0]);
                        break;
                    case 'Discourtesy':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilDiscourteous', $alleg[1], $alleg[0]);
                        break;
                    case 'Neglect of Duty':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilNeglectDuty', $alleg[1], $alleg[0]);
                        break;
                    case 'Policy or Procedure Violation':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilProcedure', $alleg[1], $alleg[0]);
                        break;
                    case 'Excessive Citation':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilCitationExcessive', $alleg[1], $alleg[0]);
                        break;
                    case 'Miranda Rights':
                        if (isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilArrestMiranda)
                            && $this->sessData->dataSets["AllegSilver"][0]->AlleSilArrestMiranda == 'Y') {
                            $allegInfo[1] .= ' ';
                        }
                        break;
                    case 'Officer Refused To Provide ID':
                        if (isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilOfficerRefuseID)
                            && $this->sessData->dataSets["AllegSilver"][0]->AlleSilOfficerRefuseID == 'Y') {
                            $allegInfo[1] .= ' ';
                        }
                        break;
                }
                if ($allegInfo[1] != '') {
                    $this->allegations[] = $allegInfo;
                }
            }
        }
        return $this->allegations;
    }
    
    public function commaAllegationList($ulist = false)
    {
        $ret = '';
        $this->simpleAllegationList();
        if (sizeof($this->allegations) > 0) {
            foreach ($this->allegations as $i => $alleg) {
                if ($ulist) {
                    $ret .= '<li>' . $alleg[0] . '</li>';
                } else {
                    $ret .= (($i > 0) ? ', ' : '') . $alleg[0];
                }
            }
        }
        return $ret;
    }
    
    public function commaTopThreeAllegationList()
    {
        $ret = '';
        $this->simpleAllegationList();
        if (sizeof($this->allegations) > 0) {
            if (sizeof($this->allegations) == 1) {
                return $this->allegations[0][0];
            }
            if (sizeof($this->allegations) == 2) {
                return $this->allegations[0][0] . ' and ' . $this->allegations[1][0];
            }
            return $this->allegations[0][0] . ', ' . $this->allegations[1][0] . ', and ' . $this->allegations[2][0];
        }
        return $ret;
    }
    
    protected function commaComplimentList()
    {
        $types = [];
        if (isset($this->sessData->dataSets["OffCompliments"]) 
            && sizeof($this->sessData->dataSets["OffCompliments"]) > 0) {
            foreach ($this->sessData->dataSets["OffCompliments"] as $off) {
                if (isset($off->OffCompValor) && trim($off->OffCompValor) == 'Y') {
                    $types[] = 'Valor';
                }
                if (isset($off->OffCompLifesaving) && trim($off->OffCompLifesaving) == 'Y') {
                    $types[] = 'Lifesaving';
                }
                if (isset($off->OffCompDeescalation) && trim($off->OffCompDeescalation) == 'Y') {
                    $types[] = 'De-escalation';
                }
                if (isset($off->OffCompProfessionalism) && trim($off->OffCompProfessionalism) == 'Y') {
                    $types[] = 'Professionalism';
                }
                if (isset($off->OffCompFairness) && trim($off->OffCompFairness) == 'Y') {
                    $types[] = 'Fairness';
                }
                if (isset($off->OffCompConstitutional) && trim($off->OffCompConstitutional) == 'Y') {
                    $types[] = 'Constitutional';
                }
                if (isset($off->OffCompCompassion) && trim($off->OffCompCompassion) == 'Y') {
                    $types[] = 'Compassion';
                }
                if (isset($off->OffCompCommunity) && trim($off->OffCompCommunity) == 'Y') {
                    $types[] = 'Community';
                }
            }
        }
        $ret = '';
        if (in_array('Valor', $types)) {
            $ret .= ', Valor';
        }
        if (in_array('Lifesaving', $types)) {
            $ret .= ', Lifesaving';
        }
        if (in_array('De-escalation', $types)) {
            $ret .= ', De-escalation';
        }
        if (in_array('Professionalism', $types)) {
            $ret .= ', Professionalism';
        }
        if (in_array('Fairness', $types)) {
            $ret .= ', Fairness';
        }
        if (in_array('Constitutional', $types)) {
            $ret .= ', Constitutional Policing';
        }
        if (in_array('Compassion', $types)) {
            $ret .= ', Compassion';
        }
        if (in_array('Community', $types)) {
            $ret .= ', Community Service';
        }
        if (trim($ret) != '') {
            $ret = trim(substr($ret, 1));
        }
        return $ret;
    }
    
    protected function checkAllegIntimidWeapon($alleg)
    {
        $defA = $GLOBALS["SL"]->def->getID('Allegation Type', 'Intimidating Display of Weapon');
        $defB = $GLOBALS["SL"]->def->getID('Intimidating Displays Of Weapon', 'N/A');
        $defC = $GLOBALS["SL"]->def->getID('Intimidating Displays Of Weapon', 'Don\'t Know');
        return ($alleg->AlleType != $defA || !in_array($alleg->AlleIntimidatingWeapon, [$defB, $defC]));
    }
    
    protected function getAllegID($allegName)
    {
        if (sizeof($this->worstAllegations) > 0) {
            foreach ($this->worstAllegations as $a) {
                if ($a[1] == $allegName) {
                    return $a[0];
                }
            }
        }
        return -3;
    }
    
    protected function getAllegSilvRec($allegName, $allegID = -3)
    {
        if ($allegID <= 0) {
            $allegID = $this->getAllegID($allegName);
        }
        if (isset($this->sessData->dataSets["Allegations"]) && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
            foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                if ($alleg->AlleType == $allegID 
                    && (!isset($alleg->AlleEventSequenceID) || intVal($alleg->AlleEventSequenceID) <= 0)) {
                    return $alleg;
                }
            }
        }
        return [];
    }
    
    protected function getAllegGoldRec($allegName, $allegID = -3)
    {
        if ($allegID <= 0) {
            $allegID = $this->getAllegID($allegName);
        }
        if (isset($this->sessData->dataSets["Allegations"]) && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
            foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                if ($alleg->AlleType == $allegID 
                    && isset($alleg->AlleEventSequenceID) && intVal($alleg->AlleEventSequenceID) > 0) {
                    return $alleg;
                }
            }
        }
        return [];
    }
    
    protected function getAllegDesc($allegName, $allegID = -3, $allegRec = [])
    {
        if (!$allegRec || !isset($allegRec->AlleDescription)) {
            $allegRec = $this->getAllegSilvRec($allegName, $allegID);
        }
        if ($allegRec && isset($allegRec->AlleDescription)) {
            return trim($allegRec->AlleDescription);
        }
        return '';
    }
    
    protected function chkSilvAlleg($fldName, $allegName, $allegID = -3)
    {
        if (isset($this->sessData->dataSets["AllegSilver"][0]->{ $fldName })
            && trim($this->sessData->dataSets["AllegSilver"][0]->{ $fldName }) == 'Y') {
            return ', ' . $this->getAllegDesc($allegName, $allegID);
        }
        return '';
    }    
    
    
    protected function basicAllegationList($showWhy = false, $isAnon = false)
    {
        $ret = '';
        if (isset($this->sessData->dataSets["Allegations"]) && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
            $printedOfficers = false;
            $allegOffs = [];
            // if there's only one Officer on the Complaint, then it is associated with all Allegations
            if (!$isAnon && isset($this->sessData->dataSets["Officers"]) 
                && sizeof($this->sessData->dataSets["Officers"]) == 1) {
                /*
                $ret .= '<div class="pL5 pB10 f16">Officer '
                    . $this->getOfficerNameFromID($this->sessData->dataSets["Officers"][0]->OffID) . '</div>';
                */
                $printedOfficers = true;
            } else { // Load Officer names for each Allegation
                foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                    if ($this->checkAllegIntimidWeapon($alleg)) {
                        $allegOffs[$alleg->AlleID] = '';
                        $offs = $this->getLinkedToEvent('Officer', $alleg->AlleID);
                        if (sizeof($offs) > 0) {
                            foreach ($offs as $off) {
                                $allegOffs[$alleg->AlleID] .= ', ' . $this->getOfficerNameFromID($off);
                            }
                        }
                        if (trim($allegOffs[$alleg->AlleID]) != '') {
                            $allegOffs[$alleg->AlleID] = substr($allegOffs[$alleg->AlleID], 1); 
                            // 'Officer'.((sizeof($offs) > 1) ? 's' : '').
                        }
                    }
                }
                // now let's check if all allegations are against the same officers, so we only print them once
                $allOfficersSame = true; $prevAllegOff = '*START*';
                foreach ($allegOffs as $allegOff) {
                    if ($prevAllegOff == '*START*') {
                    
                    } elseif ($prevAllegOff != $allegOff) {
                        $allOfficersSame = false;
                    }
                    $prevAllegOff = $allegOff;
                }
                if (!$isAnon && $allOfficersSame) { // all the same, so print once at the top
                    $ret .= '<div class="pL5 pB10 f18">' 
                        . $allegOffs[$this->sessData->dataSets["Allegations"][0]->AlleID] . '</div>';
                    $printedOfficers = true;
                }
            }
            foreach ($this->worstAllegations as $allegType) { // printing Allegations in order of severity...
                foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                    if ($alleg->AlleType == $GLOBALS["SL"]->def->getID('Allegation Type', $allegType)) {
                        if ($this->checkAllegIntimidWeapon($alleg)) {
                            $ret .= '<div class="f18">' . $allegType;
                            if (!$isAnon && !$printedOfficers && isset($allegOffs[$alleg->AlleID])) {
                                $ret .= ' <span class="f16 mL20 gry6">' . $allegOffs[$alleg->AlleID] . '</span>';
                            }
                            $ret .= '</div>' 
                            . (($showWhy) ? '<div class="gry9 f14 mTn10 pL20">' . $alleg->AlleDescription . '</div>' : '')
                            . '<div class="p5"></div>';
                        }
                    }
                }
            }
        } else {
            $ret = '<i>No allegations found.</i>';
        }
        return $ret;
    }
    
/*****************************************************************************
// END Processes Which Handle Allegations
*****************************************************************************/



    
/*****************************************************************************
// START Processes Which Handle The Event Sequence
*****************************************************************************/

    // get Incident Event Type from Node location in the Gold process
    protected function getEveSeqTypeFromNode($nID)
    {
        $eveSeqLoop = array('Stops' => 149, 'Searches' => 150, 'Force' => 151, 'Arrests' => 152);
        foreach ($eveSeqLoop as $eventType => $nodeRoot) {
            if ($this->allNodes[$nID]->checkBranch($this->allNodes[$nodeRoot]->nodeTierPath)) {
                return $eventType;
            }
        }
        return '';
    }
    
    protected function getEveSeqOrd($eveSeqID)
    {
        if (isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) { 
            foreach ($this->sessData->dataSets["EventSequence"] as $i => $eveSeq) {
                if ($eveSeq->EveID == $eveSeqID) {
                    return $eveSeq->EveOrder;
                }
            }
        }
        return 0;
    }
    
    protected function getLastEveSeqOrd()
    {
        $newOrd = 0;
        if (isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            $ind = sizeof($this->sessData->dataSets["EventSequence"])-1;
            $newOrd = $this->sessData->dataSets["EventSequence"][$ind]->EveOrder;
        }
        return $newOrd;
    }
    
    protected function checkHasEventSeq($nID)
    {
        //if (sizeof($this->eventCivLookup) > 0) return $this->eventCivLookup;
        $this->eventCivLookup = [
            'Stops'        => [], 
            'Searches'     => [], 
            'Force'        => [], 
            'Force Animal' => [], 
            'Arrests'      => [], 
            'Citations'    => [], 
            'Warnings'     => [], 
            'No Punish'    => []
            ];
        $loopRows = $this->sessData->getLoopRows('Victims');
        foreach ($loopRows as $i => $civ) {
            if ($this->getCivEveSeqIdByType($civ->CivID, 'Stops') > 0) {
                $this->eventCivLookup["Stops"][] = $civ->CivID;
            }
            if ($this->getCivEveSeqIdByType($civ->CivID, 'Searches') > 0) {
                $this->eventCivLookup["Searches"][] = $civ->CivID;
            }
            if ($this->getCivEveSeqIdByType($civ->CivID, 'Force') > 0) {
                $this->eventCivLookup["Force"][] = $civ->CivID;
            }
            if ($this->getCivEveSeqIdByType($civ->CivID, 'Arrests') > 0) {
                $this->eventCivLookup["Arrests"][] = $civ->CivID;
            } elseif (($civ->CivGivenCitation == 'N' || trim($civ->CivGivenCitation) == '') 
                && ($civ->CivGivenWarning == 'N' || trim($civ->CivGivenWarning) == '')) {
                $this->eventCivLookup["No Punish"][] = $civ->CivID;
            }
            if ($civ->CivGivenCitation == 'Y') {
                $this->eventCivLookup["Citations"][] = $civ->CivID;
            }
            if ($civ->CivGivenWarning == 'Y') {
                $this->eventCivLookup["Warnings"][] = $civ->CivID;
            }
        }
        if (isset($this->sessData->dataSets["Force"]) && sizeof($this->sessData->dataSets["Force"]) > 0) {
            foreach ($this->sessData->dataSets["Force"] as $forceRow) {
                if ($forceRow->ForAgainstAnimal == 'Y') {
                    $this->eventCivLookup["Force Animal"][] = $forceRow->ForID;
                }
            }
        }
        return true;
    }
    
    protected function addNewEveSeq($eventType, $forceType = -3)
    {
        $newEveSeq = new OPEventSequence;
        $newEveSeq->EveComplaintID = $this->coreID;
        $newEveSeq->EveType = $eventType;
        $newEveSeq->EveOrder = (1+$this->getLastEveSeqOrd());
        $newEveSeq->save();
        eval("\$newEvent = new App\\Models\\" . $GLOBALS["SL"]->tblModels[$eventType] . ";");
        $newEvent->{ $GLOBALS["SL"]->tblAbbr[$eventType].'EventSequenceID' } = $newEveSeq->getKey();
        if ($eventType == 'Force' && $forceType > 0) {
            $newEvent->ForType = $forceType;
        }
        $newEvent->save();
        $this->sessData->dataSets["EventSequence"][] = $newEveSeq;
        $this->sessData->dataSets[$eventType][] = $newEvent;
        return $newEvent;
    }
    
    protected function getCivEventID($nID, $eveType, $civID)
    {
        $civLnk = DB::table('OP_LinksCivilianEvents')
            ->join('OP_EventSequence', 'OP_EventSequence.EveID', '=', 'OP_LinksCivilianEvents.LnkCivEveEveID')
            ->where('OP_EventSequence.EveType', $eveType)
            ->where('OP_LinksCivilianEvents.LnkCivEveCivID', $civID)
            ->select('OP_EventSequence.*')
            ->first();
        if ($civLnk && isset($civLnk->EveID)) {
            return $civLnk->EveID;
        }
        return -3;
    }
    
    protected function getForceEveID($forceType, $animal = false)
    {
        if (isset($this->sessData->dataSets["Force"]) && sizeof($this->sessData->dataSets["Force"]) > 0) {
            foreach ($this->sessData->dataSets["Force"] as $force) {
                if (isset($force->ForType) && $force->ForType == $forceType) {
                    if ($animal) {
                        if (isset($force->ForAgainstAnimal) && trim($force->ForAgainstAnimal) == 'Y') {
                            return $force->ForEventSequenceID;
                        }
                    } elseif (!isset($force->ForAgainstAnimal) || trim($force->ForAgainstAnimal) != 'Y') {
                        return $force->ForEventSequenceID;
                    }
                }
            }
        }
        return -3;
    }
    
    protected function getCivAnimalForces()
    {
        return DB::table('OP_EventSequence')
            ->join('OP_Force', 'OP_Force.ForEventSequenceID', '=', 'OP_EventSequence.EveID')
            ->where('OP_EventSequence.EveComplaintID', $this->coreID)
            ->where('OP_EventSequence.EveType', 'Force')
            ->where('OP_Force.ForAgainstAnimal', 'Y')
            ->select('OP_Force.*')
            ->get();
    }
    
    protected function createCivAnimalForces()
    {
        $eve = new OPEventSequence;
        $eve->EveComplaintID = $this->coreID;
        $eve->EveType = 'Force';
        $eve->save();
        $frc = new OPForce;
        $frc->ForEventSequenceID = $eve->getKey();
        $frc->ForAgainstAnimal = 'Y';
        $frc->save();
        if (!isset($this->sessData->dataSets["Force"])) {
            $this->sessData->dataSets["Force"] = [];
        }
        $this->sessData->dataSets["Force"][] = $frc;
        if (!isset($this->sessData->dataSets["EventSequence"])) {
            $this->sessData->dataSets["EventSequence"] = [];
        }
        $this->sessData->dataSets["EventSequence"][] = $eve;
        return $eve;
    }
    
    protected function delCivEvent($nID, $eveType, $civID)
    {
        return $this->deleteEventByID($this->getCivEventID($nID, $eveType, $civID));
    }
    
    protected function deleteEventByID($eveSeqID)
    {
        if ($eveSeqID > 0) {
            $chk = OPEventSequence::find($eveSeqID);
            if ($chk && isset($chk->EveID)) {
                OPEventSequence::find($eveSeqID)->delete();
                OPStops::where('StopEventSequenceID', $eveSeqID)->delete();
                OPSearches::where('SrchEventSequenceID', $eveSeqID)->delete();
                OPArrests::where('ArstEventSequenceID', $eveSeqID)->delete();
                OPForce::where('ForEventSequenceID', $eveSeqID)->delete();
                OPLinksCivilianEvents::where('LnkCivEveEveID', $eveSeqID)->delete();
                OPLinksOfficerEvents::where('LnkOffEveEveID', $eveSeqID)->delete();
            }
        }
        return true;
    }
    
/*****************************************************************************
// END Processes Which Handle The Event Sequence
*****************************************************************************/



    
/*****************************************************************************
// START Processes Which Handle People/Officer Linkages
*****************************************************************************/
    
    protected function getEventSequence($eveSeqID = -3)
    {
        $eveSeqs = [];
        if (isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            foreach ($this->sessData->dataSets["EventSequence"] as $eveSeq) {
                if ($eveSeqID <= 0 || $eveSeqID == $eveSeq->EveID) {
                    $eveSeqs[] = [ 
                        "EveID"     => $eveSeq->EveID, 
                        "EveOrder"  => $eveSeq->EveOrder, 
                        "EveType"   => $eveSeq->EveType, 
                        "Civilians" => $this->getLinkedToEvent('Civilian', $eveSeq->EveID), 
                        "Officers"  => $this->getLinkedToEvent('Officer', $eveSeq->EveID), 
                        "Event"     => $this->sessData->getChildRow('EventSequence', $eveSeq->EveID, $eveSeq->EveType)
                        ];
                }
            }
        }
        return $eveSeqs;
    }
    
    protected function getEventLabel($eveSeqID = -3)
    {
        if ($eveSeqID > 0) {
            $eveSeq = $this->getEventSequence($eveSeqID);
            return $this->printEventSequenceLine($eveSeq);
        }
        return '';
    }
    
    protected function printEventSequenceLine($eveSeq, $info = '')
    {
        if (!isset($eveSeq["EveType"]) && is_array($eveSeq) && sizeof($eveSeq) > 0) {
            $eveSeq = $eveSeq[0];
        }
        if (!is_array($eveSeq) || !isset($eveSeq["EveType"])) {
            return '';
        }
        $ret = '<span class="slBlueDark">';
        if ($eveSeq["EveType"] == 'Force' && isset($eveSeq["Event"]->ForType) && trim($eveSeq["Event"]->ForType) != ''){
            if ($eveSeq["Event"]->ForType == $GLOBALS["SL"]->def->getID('Force Type', 'Other')) {
                $ret .= $eveSeq["Event"]->ForTypeOther . ' Force ';
            } else {
                $ret .= $GLOBALS["SL"]->def->getVal('Force Type', $eveSeq["Event"]->ForType) . ' Force ';
            }
        } elseif (isset($this->eventTypeLabel[$eveSeq["EveType"]])) {
            $ret .= $this->eventTypeLabel[$eveSeq["EveType"]] . ' ';
        }
        if ($eveSeq["EveType"] == 'Force' && $eveSeq["Event"]->ForAgainstAnimal == 'Y') {
            $ret .= '<span class="blk">on</span> ' . $eveSeq["Event"]->ForAnimalDesc;
        } else {
            $civNames = $offNames = '';
            if ($this->moreThan1Victim() && in_array($info, array('', 'Civilians'))) { 
                foreach ($eveSeq["Civilians"] as $civ) {
                    $civNames .= ', ' . $this->getCivilianNameFromID($civ);
                }
                if (trim($civNames) != '') {
                    $ret .= '<span class="blk">' . (($eveSeq["EveType"] == 'Force') ? 'on ' : 'of ')
                        . '</span>' . substr($civNames, 1);
                }
            }
            if ($this->moreThan1Officer() && in_array($info, array('', 'Officers'))) { 
                foreach ($eveSeq["Officers"] as $off) {
                    $offNames .= ', ' . $this->getOfficerNameFromID($off);
                }
                if (trim($offNames) != '') $ret .= ' <span class="blk">by</span> ' . substr($offNames, 1);
            }
        }
        $ret .= '</span>';
        return $ret;
    }

    protected function getLinkedToEvent($Ptype = 'Officer', $eveSeqID = -3)
    {
        $retArr = [];
        $abbr = (($Ptype == 'Officer') ? 'Off' : 'Civ');
        if ($eveSeqID > 0 && isset($this->sessData->dataSets["Links" . $Ptype . "Events"]) 
            && sizeof($this->sessData->dataSets["Links" . $Ptype . "Events"]) > 0) {
            foreach ($this->sessData->dataSets["Links" . $Ptype . "Events"] as $PELnk) {
                if ($PELnk->{ 'Lnk' . $abbr . 'EveEveID' } == $eveSeqID) {
                    $retArr[] = $PELnk->{ 'Lnk' . $abbr . 'Eve' . $abbr . 'ID' };
                }
            }
        }
        return $retArr;
    }
    
    protected function getCivEveSeqIdByType($civID, $eventType)
    {
        if ($eventType != '' && isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            foreach ($this->sessData->dataSets["EventSequence"] as $eveSeq) {
                if ($eveSeq->EveType == $eventType
                    && in_array($civID, $this->getLinkedToEvent('Civilian', $eveSeq->EveID))) {
                    return $eveSeq->EveID;
                }
            }
        }
        return -3;
    }
    
    protected function getEveSeqRowType($eveSeqID = -3)
    {
        $eveSeq = $this->sessData->getRowById('EventSequence', $eveSeqID);
        if ($eveSeq) {
            return $eveSeq->EveType;
        }
        return '';
    }
    
    protected function chkCivHasForce($civID = -3)
    {
        if ($civID > 0 && isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            foreach ($this->sessData->dataSets["EventSequence"] as $eve) {
                if ($eve->EveType == 'Force') {
                    $chk = OPLinksCivilianEvents::where('LnkCivEveEveID', $eve->EveID)
                        ->where('LnkCivEveCivID', $civID)
                        ->first();
                    if ($chk && isset($chk->LnkCivEveID)) {
                        return 1;
                    }
                }
            }
        }
        return 0;
    }
    
    protected function isEventAnimalForce($eveSeqID = -3, $force = [])
    {
        if (empty($force)) {
            $eveSeq = $this->sessData->getRowById('EventSequence', $eveSeqID);
            if ($eveSeq && $eveSeq->EveType == 'Force') {
                $force = $this->sessData->getChildRow('EventSequence', $eveSeq->EveID, $eveSeq->EveType);
            }
        }
        if ($force && isset($force->ForAgainstAnimal) && $force->ForAgainstAnimal == 'Y') {
            return $force->ForAnimalDesc;
        }
        return '';
    }
    
/*****************************************************************************
// END Processes Which Handle People/Officer Linkages
*****************************************************************************/




/*****************************************************************************
// START Processes Which Manage Lists of People
*****************************************************************************/

    protected function getPersonLabel($type = 'Civilians', $id = -3, $row = [])
    {
        $name = '';
        $civ2 = [];
        $civ2 = $this->sessData->getChildRow($type, $id, 'PersonContact');
        if ($civ2 && trim($civ2->PrsnNickname) != '') {
            $name = $civ2->PrsnNickname;
        } elseif ($civ2 && (trim($civ2->PrsnNameFirst) != '' || trim($civ2->PrsnNameLast) != '')) {
            $name = $civ2->PrsnNameFirst . ' ' . $civ2->PrsnNameLast . ' ' . $name;
        } else {
            if ($type == 'Officers' && isset($row->OffBadgeNumber) && intVal($row->OffBadgeNumber) > 0) {
                $name = 'Badge #' . $row->OffBadgeNumber . ' ' . $name;
            }
        }
        return trim($name);
    }

    protected function youLower($civName = '')
    {
        return str_replace('You', 'you', $civName);
    }
    
    // converts Civilians row into identifying name used in most of the complaint process
    protected function getCivName($loop, $civ = [], $itemInd = -3)
    {
        $name = '';
        if (!isset($civ->CivID)) {
            if (isset($civ->InjSubjectID)) {
                $civ = $this->sessData->getRowById('Civilians', $civ->InjSubjectID);
            } elseif (isset($civ->InjCareSubjectID)) {
                $civ = $this->sessData->getRowById('Civilians', $civ->InjCareSubjectID);
            }
        }
        if ($civ->CivIsCreator == 'Y' && (($loop == 'Victims' && $civ->CivRole == 'Victim') 
            || ($loop == 'Witnesses' && $civ->CivRole == 'Witness')) ) {
            if ($this->isReport) {
                if (isset($civ->CivPersonID) && intVal($civ->CivPersonID) > 0) {
                    $contact = $this->sessData->getChildRow('Civilians', $civ->CivPersonID, 'PersonContact');
                    $name = $contact->PrsnNameFirst . ' ' . $contact->PrsnNameLast;
                }
                if (trim($name) == '') {
                    $name = 'Complainant';
                }
            } else {
                $name = 'You ' . $name;
            }
        } else {
            $name = $this->getPersonLabel('Civilians', $civ->CivID, $civ);
        }
        $name = trim($name);
        if ($name != '' && $name != 'You') {
            $name .= ' (' . $civ->CivRole . ' #' . (1+$this->sessData->getLoopIndFromID($loop, $civ->CivID)) . ')';
        }
        if ($name == '') {
            
        }
        return trim($name);
    }
    
    public function getCivilianNameFromID($civID)
    {
        if ($this->sessData->dataSets["Civilians"][0]->CivID == $civID) {
            $role = '';
            if ($this->sessData->dataSets["Civilians"][0]->CivRole == 'Victim') {
                $role = 'Victims';
            } elseif ($this->sessData->dataSets["Civilians"][0]->CivRole == 'Witness') {
                $role = 'Witnesses';
            }
            return $this->getCivName($role, $this->sessData->dataSets["Civilians"][0], 1);
        }
        $civInd = $this->sessData->getLoopIndFromID('Victims', $civID);
        if ($civInd >= 0) {
            $name = $this->getCivName('Victims', $this->sessData->getRowById('Civilians', $civID), (1+$civInd));
            if ($name == '') {
                $name = 'Victim #' . (1+$civInd);
            }
            return $name;
        }
        $civInd = $this->sessData->getLoopIndFromID('Witnesses', $civID);
        if ($civInd >= 0) {
            $name = $this->getCivName('Witnesses', $this->sessData->getRowById('Civilians', $civID), (1+$civInd));
            if ($name == '') {
                $name = 'Witness #' . (1+$civInd);
            }
            return $name;
        }
        return '';
    }
    
    protected function getCivNamesFromEvent($eveID)
    {
        $ret = '';
        $lnks = OPLinksCivilianEvents::where('LnkCivEveEveID', $eveID)
            ->get();
        if ($lnks->isNotEmpty()) {
            $civs = [];
            foreach ($lnks as $lnk) {
                if (!in_array($lnk->LnkCivEveCivID, $civs)) {
                    $civs[] = $lnk->LnkCivEveCivID;
                }
            }
            foreach ($civs as $i => $civID) {
                $ret .= (($i > 0) ? ', ' . ((sizeof($civs) > 2 && $i == (sizeof($civs)-1)) ? 'and ' : '') : '')
                    . $this->getPersonLabel('Civilians', $civID);
            }
        }
        return $ret;
    }
    
    // converts Officer row into identifying name used in most of the complaint process
    protected function getOfficerName($officer = [], $itemIndex = -3)
    {
        $name = $this->getPersonLabel('Officers', $officer->OffID, $officer);
        if (trim($name) == '') {
            $name = 'Officer #' . (1+$itemIndex);
        } else {
            $name .= ' (Officer #' . (1+$itemIndex) . ')';
        }
        return trim($name);
    }
    
    protected function getOfficerNameFromID($offID)
    {
        $offInd = $this->sessData->getLoopIndFromID('Officers', $offID);
        if ($offInd >= 0) {
            return $this->getOfficerName($this->sessData->getRowById('Officers', $offID), (1+$offInd));
        }
        return '';
    }
    
    protected function getFirstVictimCivInd()
    {
        $civInd = -3;
        if (sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if (isset($civ->CivRole) && trim($civ->CivRole) == 'Victim' && $civInd < 0) {
                    $civInd = $i;
                }
            }
        }
        return $civInd;
    }
    
    protected function chkDeptLinks($newDeptID)
    {
        $deptChk = OPLinksComplaintDept::where('LnkComDeptComplaintID', $this->coreID)
            ->where('LnkComDeptDeptID', $newDeptID)
            ->get();
        if ($deptChk->isEmpty()) {
            $newDeptLnk = new OPLinksComplaintDept;
            $newDeptLnk->LnkComDeptComplaintID = $this->coreID;
            $newDeptLnk->LnkComDeptDeptID = $newDeptID;
            $newDeptLnk->save();
        }
        $this->getOverUpdateRow($this->coreID, $newDeptID);
        $this->sessData->refreshDataSets();
        $this->runLoopConditions();
        return true;
    }
    
    protected function getDeptName($dept = [], $itemIndex = -3)
    {
        $name = ''; //(($itemIndex > 0) ? '<span class="fPerc66 gry9">(#'.$itemIndex.')</span>' : '');
        if (isset($dept->DeptName) && trim($dept->DeptName) != '') {
            $name = $dept->DeptName . ' ' . $name;
        }
        return trim($name);
    }
    
    protected function getDeptNameByID($deptID)
    {
        $dept = $this->sessData->getRowById('Departments', $deptID);
        if ($dept) {
            return $this->getDeptName($dept);
        }
        return '';
    }
    
    protected function civRow2Set($civ)
    {
        if (!$civ || !isset($civ->CivIsCreator)) {
            return '';
        }
        return (($civ->CivIsCreator == 'Y') ? '' : (($civ->CivRole == 'Victim') ? 'Victims' : 'Witnesses') );
    }
    
    protected function getCivilianList($loop = 'Victims')
    {
        if ($loop == 'Victims' || $loop == 'Witness') {
            return $this->sessData->loopItemIDs[$loop];
        }
        $civs = [];
        if (isset($this->sessData->dataSets["Civilians"]) && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $civ) {
                $civs[] = $civ->CivID;
            }
        }
        return $civs;
    }
    
    protected function loadPartnerTypes()
    {
        if (!isset($this->v["prtnTypes"])) {
            $this->v["prtnTypes"] = [
                [
                    "abbr" => 'org',
                    "sing" => 'Organization',
                    "plur" => 'Organizations', 
                    "defID" => $GLOBALS["SL"]->def->getID('Partner Types', 'Organization')
                ], [
                    "abbr" => 'attorney',
                    "sing" => 'Attorney',
                    "plur" => 'Attorneys', 
                    "defID" => $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney')
                ]
            ];
        }
        return true;
    }
    
    protected function loadPrtnAbbr($defID)
    {
        if (!isset($this->v["prtnTypes"])) {
            $this->loadPartnerTypes();
        }
        foreach ($this->v["prtnTypes"] as $p) {
            if ($p["defID"] == $defID) {
                return $p["abbr"];
            }
        }
        return '';
    }
    
    protected function loadPrtnDefID($abbr)
    {
        if (!isset($this->v["prtnTypes"])) {
            $this->loadPartnerTypes();
        }
        foreach ($this->v["prtnTypes"] as $p) {
            if ($p["abbr"] == $abbr) {
                return $p["defID"];
            }
        }
        return '';
    }
    
/*****************************************************************************
// END Processes Which Manage Lists of People
*****************************************************************************/





    






    
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
                    $GLOBALS["SL"]->def->getID('Oversight Agency Types', 'Civilian Oversight'))
                ->get();
            if ($ovrs->isNotEmpty()) {
                foreach ($ovrs as $ovr) {
                    $ia = OPOversight::where('OverDeptID', $ovr->OverDeptID)
                        ->where('OverType', 
                            $GLOBALS["SL"]->def->getID('Oversight Agency Types', 'Internal Affairs'))
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
            if ($personContact && (isset($personContact->PrsnNameFirst) || isset($personContact->PrsnNameLast))) {
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
                if (!in_array($dept->DeptAddressCity, $this->v["searchSuggest"]) && $dept->DeptAddressCounty) {
                    $this->v["searchSuggest"][] = json_encode($dept->DeptAddressCity);
                }
            }
        }
        $deptCounties = OPDepartments::select('DeptAddressCounty')
            ->distinct()
            ->get();
        if ($deptCounties->isNotEmpty()) {
            foreach ($deptCounties as $dept) {
                if (!in_array($dept->DeptAddressCounty, $this->v["searchSuggest"]) && $dept->DeptAddressCounty) {
                    $this->v["searchSuggest"][] = json_encode($dept->DeptAddressCounty);
                }
            }
        }
        return true;
    }
    
}
