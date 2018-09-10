<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Aws\Ses\SesClient;

use App\Models\User;
use App\Models\SLZips;
use App\Models\SLEmailed;
use App\Models\SLDefinitions;
use App\Models\SLNode;
use App\Models\SLNodeSavesPage;
use App\Models\SLEmails;

use App\Models\OPComplaints;
use App\Models\OPCompliments;
use App\Models\OPIncidents;
use App\Models\OPEventSequence;
use App\Models\OPAllegSilver;
use App\Models\OPAllegations;
use App\Models\OPStops;
use App\Models\OPSearches;
use App\Models\OPArrests;
use App\Models\OPForce;
use App\Models\OPInjuries;
use App\Models\OPCivilians;
use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksOfficerEvents;
use App\Models\OPLinksCivilianEvents;
use App\Models\OPPersonContact;
use App\Models\OPPhysicalDesc;
use App\Models\OPLinksComplaintOversight;

use App\Models\OPzComplaintReviews;
use App\Models\OPZeditDepartments;
use App\Models\OPZeditOversight;
use App\Models\OPzVolunTmp;
use App\Models\OPzVolunUserInfo;
use App\Models\OPzVolunStatDays;

use OpenPolice\Controllers\OpenPoliceAdmin;
use OpenPolice\Controllers\OpenPoliceReport;
use OpenPolice\Controllers\VolunteerLeaderboard;
    use OpenPolice\Controllers\VolunteerController;
use OpenPolice\Controllers\DepartmentScores;

use SurvLoop\Controllers\SurvFormTree;
use SurvLoop\Controllers\SurvLoopStat;

class OpenPolice extends SurvFormTree
{
    
    public $classExtension     = 'OpenPolice';
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
    
    protected function isCompliment()
    { 
        if (!isset($this->sessData->dataSets["Complaints"])) return ($this->treeID == 5);
        return (isset($this->sessData->dataSets["Complaints"][0]->ComIsCompliment)
            && intVal($this->sessData->dataSets["Complaints"][0]->ComIsCompliment) == 1);
    }
    
    protected function isSilver()
    { 
        if (!isset($this->sessData->dataSets["Complaints"])) return false;
        return ($this->sessData->dataSets["Complaints"][0]->ComAwardMedallion == 'Silver'); 
    }
    
    protected function isGold()
    {
        if (!isset($this->sessData->dataSets["Complaints"])) return false;
        return ($this->sessData->dataSets["Complaints"][0]->ComAwardMedallion == 'Gold');
    }
    
    protected function isPublic()
    {
        if (!isset($this->sessData->dataSets["Complaints"])) return false;
        return ($this->sessData->dataSets["Complaints"][0]->ComPrivacy 
            == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly'));
    }
    
    public function isPublished($coreTbl, $coreID, $coreRec = NULL)
    {
//echo '<br /><br /><br />isPublished(' . $coreTbl . ', ' . $coreID . ', <pre>'; print_r($coreRec); echo '</pre>';
        if ($coreTbl == 'Complaints') {
            if (!$coreRec) $coreRec = OPComplaints::find($coreID);
            if ($coreRec && isset($coreRec->ComStatus)) {
                return (in_array($coreRec->ComStatus, $this->getPublishedStatusList($coreTbl)));
            }
            return false;
        }
        return false;
    }
    
    protected function moreThan1Victim()
    { 
        if (!isset($this->sessData->loopItemIDs['Victims'])) return false;
        return (sizeof($this->sessData->loopItemIDs['Victims']) > 1); 
    }
    
    protected function moreThan1Officer() 
    { 
        if (!isset($this->sessData->loopItemIDs['Officers'])) return false;
        return (sizeof($this->sessData->loopItemIDs["Officers"]) > 1); 
    }
    
    public function chkCoreRecEmpty($coreID = -3, $coreRec = NULL)
    {
        if ($coreID <= 0) $coreID = $this->coreID;
        if (!$coreRec && $coreID > 0) $coreRec = OPComplaints::find($coreID);
        if (!$coreRec) return false;
        if (!isset($coreRec->ComSubmissionProgress) || intVal($coreRec->ComSubmissionProgress) <= 0) return true;
        if (!isset($coreRec->ComSummary) || trim($coreRec->ComSummary) == '') return true;
        return false;
    }
    
    protected function recordIsEditable($coreTbl, $coreID, $coreRec = NULL)
    {
        if (!$coreRec && $coreID > 0) $coreRec = OPComplaints::find($coreID);
        if (!isset($coreRec->ComStatus)) return true;
        if (!$coreRec) return false;
        if ($this->treeID == 1) {
            if ($coreRec->ComStatus == $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) return true;
        } elseif ($this->treeID == 5) {
            if ($coreRec->ComStatus == $GLOBALS["SL"]->def->getID('Compliment Status', 'Incomplete')) return true;
        }
        return false;
    }
    
    public function getAllPublicCoreIDs($coreTbl = '')
    {
        
        if (trim($coreTbl) == '') $coreTbl = $GLOBALS["SL"]->coreTbl;
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
            foreach ($list as $l) $this->allPublicCoreIDs[] = $l->ComPublicID;
        }
        //echo '<pre>'; print_r($this->allPublicCoreIDs); echo '</pre>';
        return $this->allPublicCoreIDs;
    }
    
    protected function getPublishedStatusList($coreTbl = '')
    {
        if (!isset($coreTbl)) $coreTbl = $GLOBALS["SL"]->coreTbl;
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
        if (!isset($coreTbl)) $coreTbl = $GLOBALS["SL"]->coreTbl;
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
    
    // Initializing a bunch of things which are not [yet] automatically determined by the software
    protected function initExtra(Request $request)
    {
        // Establishing Main Navigation Organization, with Node ID# and Section Titles
        $this->loadYourContact();
        $this->majorSections = [];
        if (!isset($GLOBALS["SL"]->treeID)) return true;
        if ($GLOBALS["SL"]->treeID == 1) {
            $this->majorSections[] = array(1,      'Your Story',        'active');
            $this->majorSections[] = array(4,      'Who\'s Involved',   'active');
            $this->majorSections[] = array(5,      'Allegations',       'active');
            $this->majorSections[] = array(6,      'Go Gold',           'disabled');
            $this->majorSections[] = array(419,    'Finish',            'active');
            
            $this->minorSections = array( [], [], [], [], [] );
            $this->minorSections[0][] = array(157, 'Start Your Story');
            $this->minorSections[0][] = array(437, 'Privacy Options');
            $this->minorSections[0][] = array(158, 'When & Where');
            $this->minorSections[0][] = array(707, 'The Scene');
            
            $this->minorSections[1][] = array(139, 'About You');
            $this->minorSections[1][] = array(140, 'Victims');
            $this->minorSections[1][] = array(141, 'Witnesses');
            $this->minorSections[1][] = array(144, 'Police Departments');
            $this->minorSections[1][] = array(142, 'Officers');
            
            $this->minorSections[2][] = array(198, 'Stops');
            $this->minorSections[2][] = array(199, 'Searches');
            $this->minorSections[2][] = array(200, 'Force');
            $this->minorSections[2][] = array(201, 'Arrests');
            $this->minorSections[2][] = array(202, 'Citations');
            $this->minorSections[2][] = array(154, 'Other');
            
            $this->minorSections[3][] = array(483, 'GO GOLD!');
            $this->minorSections[3][] = array(148, 'Event Details');
            $this->minorSections[3][] = array(153, 'Citations');
            $this->minorSections[3][] = array(410, 'Injuries');
            
            $this->minorSections[4][] = array(420, 'Review Narrative');
            $this->minorSections[4][] = array(431, 'Sharing Options');
            $this->minorSections[4][] = array(156, 'Submit Complaint');
        } elseif ($GLOBALS["SL"]->treeID == 5) {
            $this->majorSections[] = array(752,    'Your Story',        'active');
            $this->majorSections[] = array(761,    'Who\'s Involved',   'active');
            $this->majorSections[] = array(763,    'Compliments',       'active');
            $this->majorSections[] = array(764,    'Finish',            'active');
            
            $this->minorSections = array( [], [], [], [], [] );
            $this->minorSections[0][] = array(753, 'Start Your Story');
            $this->minorSections[0][] = array(867, 'Privacy Options');
            $this->minorSections[0][] = array(877, 'When & Where');
            $this->minorSections[0][] = array(887, 'The Scene');
            
            $this->minorSections[1][] = array(762, 'About You');
            $this->minorSections[1][] = array(765, 'Police Departments');
            $this->minorSections[1][] = array(766, 'Officers');
            
            $this->minorSections[2][] = array(945, 'Compliment Officers');
            
            $this->minorSections[3][] = array(957, 'Review Narrative');
            $this->minorSections[3][] = array(961, 'Feedback');
            $this->minorSections[3][] = array(964, 'Submit Complaint');
        } elseif ($GLOBALS["SL"]->treeID == 36) {
            if ($request->has('d') && trim($request->get('d')) != '') {
                $chk = OPDepartments::where('DeptSlug', 'LIKE', trim($request->get('d')))
                    ->first();
                if ($chk && isset($chk->DeptID)) {
                    $this->loadAllSessData($GLOBALS["SL"]->coreTbl, $chk->DeptID);
                }
            }
        }
        return true;
    }
    
    protected function tblsInPackage()
    {
        if ($this->dbID == 1) return ['Departments', 'Oversight'];
        return [];
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
        
    // Initializing a bunch of things which are not [yet] automatically determined by the software
    protected function loadExtra()
    {
        if ($this->treeID == 1 && $this->isGold()) $this->majorSections[3][2] = 'active';
        if ($this->treeID == 1 || $GLOBALS["SL"]->getReportTreeID() == 1) {
            if ($this->v["user"] && intVal($this->v["user"]->id) > 0 && isset($this->sessData->dataSets["Civilians"]) 
                && isset($this->sessData->dataSets["Civilians"][0])
                && (!isset($this->sessData->dataSets["Civilians"][0]->CivUserID) 
                    || intVal($this->sessData->dataSets["Civilians"][0]->CivUserID) <= 0)) {
                $this->sessData->dataSets["Civilians"][0]->update([
                    'CivUserID' => $this->v["user"]->id
                ]);
            }
            $this->chkPersonRecs();
            if (isset($this->sessData->dataSets["Departments"]) && 
                sizeof($this->sessData->dataSets["Departments"]) > 0) {
                foreach ($this->sessData->dataSets["Departments"] as $i => $d) {
                    $this->chkDeptLinks($d->DeptID);
                }
            }
            if (isset($this->sessData->dataSets["Complaints"]) 
                && !isset($this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted) 
                && $this->sessData->dataSets["Complaints"][0]->ComStatus 
                    != $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) {
                $this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted 
                    = $this->sessData->dataSets["Complaints"][0]->created_at;
                $chk = DB::table('SL_NodeSavesPage')
                    ->join('SL_Sess', 'SL_Sess.SessID', '=', 'SL_NodeSavesPage.PageSaveSession')
                    ->where('SL_Sess.SessTree', 1)
                    ->where('SL_Sess.SessCoreID', $this->coreID)
                    ->select('SL_NodeSavesPage.created_at')
                    ->orderBy('SL_NodeSavesPage.created_at', 'desc')
                    ->first();
                if ($chk && isset($chk->created_at)) {
                    $this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted = $chk->created_at;
                }
                $this->sessData->dataSets["Complaints"][0]->save();
            }
        }
        if ($this->treeID == 5 || $GLOBALS["SL"]->getReportTreeID() == 5) {
            if (isset($this->sessData->dataSets["Complaints"])
                && (!isset($this->sessData->dataSets["Complaints"][0]->ComIsCompliment)
                    || intVal($this->sessData->dataSets["Complaints"][0]->ComIsCompliment) != 1)) {
                $this->sessData->dataSets["Complaints"][0]->ComIsCompliment = 1;
                $this->sessData->dataSets["Complaints"][0]->save();
            }
        }
        if (session()->has('opcDeptID') && intVal(session()->get('opcDeptID')) > 0) {
            if ($this->treeID == 1) {
                if (isset($this->sessData->dataSets["Complaints"])
                    && intVal($this->sessData->dataSets["Complaints"][0]->ComSubmissionProgress) > 0) {
                    if (!isset($this->sessData->dataSets["LinksComplaintDept"])) {
                        $this->sessData->dataSets["LinksComplaintDept"] = [];
                    }
                    if (empty($this->sessData->dataSets["LinksComplaintDept"])) {
                        $newDept = new OPLinksComplaintDept;
                        $newDept->LnkComDeptComplaintID = $this->coreID;
                        $newDept->LnkComDeptDeptID      = intVal(session()->get('opcDeptID'));
                        $newDept->save();
                        session()->forget('opcDeptID');
                    }
                }
            } elseif ($this->treeID == 5) {
                if (isset($this->sessData->dataSets["Compliments"])
                    && intVal($this->sessData->dataSets["Compliments"][0]->ComSubmissionProgress) > 0) {
                    if (!isset($this->sessData->dataSets["LinksComplimentDept"])) {
                        $this->sessData->dataSets["LinksComplimentDept"] = [];
                    }
                    if (empty($this->sessData->dataSets["LinksComplimentDept"])) {
                        $newDept = new OPLinksComplimentDept;
                        $newDept->LnkCompliDeptComplimentID = $this->coreID;
                        $newDept->LnkCompliDeptDeptID       = intVal(session()->get('opcDeptID'));
                        $newDept->save();
                        session()->forget('opcDeptID');
                    }
                }
            }
        }
        $this->v["isPublic"] = $this->isPublic();
        return true;
    }
    
    protected function chkPersonRecs()
    {
        // This should've been automated via the data table subset option
        // but for now, I'm replacing that complication with this check...
        $found = false;
        foreach ([ ['Civilians', 'Civ'], ['Officers', 'Off'] ] as $type) {
            if (isset($this->sessData->dataSets[$type[0]]) && sizeof($this->sessData->dataSets[$type[0]]) > 0) {
                foreach ($this->sessData->dataSets[$type[0]] as $i => $civ) {
                    if (!isset($civ->{ $type[1] . 'PersonID' }) || intVal($civ->{ $type[1] . 'PersonID' }) <= 0) {
                        $new = new OPPersonContact;
                        $new->save();
                        $this->sessData->dataSets[$type[0]][$i]->update([
                            $type[1] . 'PersonID' => $new->getKey() ]);
                        $found = true;
                    }
                    if (!isset($civ->{ $type[1] . 'PhysDescID' }) 
                        || intVal($civ->{ $type[1] . 'PhysDescID' }) <= 0) {
                        $new = new OPPhysicalDesc;
                        $new->save();
                        $this->sessData->dataSets[$type[0]][$i]->update([
                            $type[1] . 'PhysDescID' => $new->getKey() ]);
                        $found = true;
                    }
                }
            }
        }
        if ($found) $this->sessData->refreshDataSets();
        // // // //
        return true;
    }
    
    protected function initBlnkAllegsSilv()
    {
        if (!isset($this->sessData->dataSets["AllegSilver"])) {
            $this->sessData->dataSets["AllegSilver"] = new OPAllegSilver;
            $this->sessData->dataSets["AllegSilver"]->AlleSilComplaintID = $this->coreID;
            $this->sessData->dataSets["AllegSilver"]->save();
        }
        foreach ($this->worstAllegations as $alle) {
            $found = false;
            if (isset($this->sessData->dataSets["Allegations"]) 
                && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
                foreach ($this->sessData->dataSets["Allegations"] as $i => $alleRow) {
                    if ($alle[0] == $alleRow->AlleType) $found = true;
                }
            }
            if (!$found) {
                $new = new OPAllegations;
                $new->AlleComplaintID = $this->coreID;
                $new->AlleType = $alle[0];
                $new->save();
            }
        }
        return true;
    }
    
    protected function initBlnkAllegsGold()
    {
        if (isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            foreach ($this->sessData->dataSets["EventSequence"] as $i => $eveSeq) {
                foreach ($this->eventGoldAllegs[$eveSeq->EveType] as $alleID) {
                    $found = false;
                    if (isset($this->sessData->dataSets["Allegations"]) 
                        && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
                        foreach ($this->sessData->dataSets["Allegations"] as $i => $alleRow) {
                            if ($alleRow->AlleEventSequenceID == $eveSeq->EveID 
                                && $alleID == $alleRow->AlleType) $found = true;
                        }
                    }
                    if (!$found) {
                        $new = new OPAllegations;
                        $new->AlleComplaintID = $this->coreID;
                        $new->AlleEventSequenceID = $eveSeq->EveID;
                        $new->AlleType = $alleID;
                        $new->save();
                    }
                }
            }
        }
        
        return true;
    }
    
    protected function afterCreateNewDataLoopItem($tbl = '', $itemID = -3)
    {
        if (in_array($tbl, ['Civilians', 'Officers']) && $itemID > 0) $this->chkPersonRecs();
        return true;
    }
    
    protected function overrideMinorSection($nID = -3, $majorSectInd = -3)
    {
        if (in_array($nID, [483, 484, 485])) return 148;
        return -1;
    }
    
    protected function getTableRecLabelCustom($tbl, $rec = [], $ind = -3)
    {
        if ($tbl == 'Vehicles' && isset($rec->VehicTransportation)) {
            return $GLOBALS["SL"]->def->getValById($rec->VehicTransportation)
                . ((isset($rec->VehicVehicleMake))  ? ' ' . $rec->VehicVehicleMake : '')
                . ((isset($rec->VehicVehicleModel)) ? ' ' . $rec->VehicVehicleModel : '')
                . ((isset($rec->VehicVehicleDesc))  ? ' ' . $rec->VehicVehicleDesc : '');
        }
        return '';
    }
    
    // CUSTOM={OnlyIfNoAllegationsOtherThan:WrongStop,Miranda,PoliceRefuseID]
    protected function checkNodeConditionsCustom($nID, $condition = '')
    {
        if ($condition == '#VehicleStop') { // could be replaced by OR functionality
            if (isset($this->sessData->dataSets["Scenes"]) && sizeof($this->sessData->dataSets["Scenes"]) > 0) {
                if (isset($this->sessData->dataSets["Scenes"][0]->ScnIsVehicle) 
                    && trim($this->sessData->dataSets["Scenes"][0]->ScnIsVehicle) == 'Y') {
                    return 1;
                }
                if (isset($this->sessData->dataSets["Scenes"][0]->ScnIsVehicleAccident) 
                    && trim($this->sessData->dataSets["Scenes"][0]->ScnIsVehicleAccident) == 'Y') {
                    return 1;
                }
            }
            return 0;
        } elseif ($condition == '#LawyerInvolved') {
            if ((isset($this->sessData->dataSets["Complaints"][0]->ComAttorneyHas) 
                && in_array(trim($this->sessData->dataSets["Complaints"][0]->ComAttorneyHas), ['Y', '?']))
                || (isset($this->sessData->dataSets["Complaints"][0]->ComAttorneyWant) 
                && in_array(trim($this->sessData->dataSets["Complaints"][0]->ComAttorneyWant), ['Y', '?']))) {
                return 1;
            }
            if ((isset($this->sessData->dataSets["Complaints"][0]->ComAnyoneCharged) 
                && in_array(trim($this->sessData->dataSets["Complaints"][0]->ComAnyoneCharged), ['Y', '?']))
                && (!isset($this->sessData->dataSets["Complaints"][0]->ComAllChargesResolved) 
                    || trim($this->sessData->dataSets["Complaints"][0]->ComAllChargesResolved) != 'Y')) {
                return 1;
            }
            return 0;
        } elseif ($condition == '#NoSexualAllegation') {
            $noSexAlleg = true;
            if (isset($this->sessData->dataSets["Allegations"]) 
                && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
                foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                    if (in_array($alleg->AlleType, [
                        $GLOBALS["SL"]->def->getID('Allegation Type', 'Sexual Assault'), 
                        $GLOBALS["SL"]->def->getID('Allegation Type', 'Sexual Harassment')
                        ])) {
                        $noSexAlleg = false;
                    }
                }
            }
            return ($noSexAlleg) ? 1 : 0;
        } elseif ($condition == '#IncidentHasAddress') {
            if (isset($this->sessData->dataSets["Incidents"]) 
                && isset($this->sessData->dataSets["Incidents"][0]->IncAddress)
                && trim($this->sessData->dataSets["Incidents"][0]->IncAddress) != '') {
                return 1;
            } else {
                return 0;
            }
        } elseif ($condition == '#HasArrestOrForce') {
            if ($this->sessData->dataHas('Arrests') || $this->sessData->dataHas('Force')) return 1;
            else return 0;
        } elseif (in_array($condition, [
            '#PreviousEnteredStops', 
            '#PreviousEnteredSearches', 
            '#PreviousEnteredForce', 
            '#PreviousEnteredArrests'
            ])) {
            return (sizeof($this->getPreviousEveSeqsOfType($GLOBALS["SL"]->closestLoop["itemID"])) > 0) ? 1 : 0;
        } elseif ($condition == '#HasForceHuman') {
            if (!$this->sessData->dataHas('Force') || empty($this->sessData->dataSets["Force"])) return 0;
            $foundHuman = false;
            foreach ($this->sessData->dataSets["Force"] as $force) {
                if (trim($force->ForAgainstAnimal) != 'Y') $foundHuman = true;
            }
            return ($foundHuman) ? 1 : 0;
        } elseif ($condition == '#CivHasForce') {
            if (isset($GLOBALS["SL"]->closestLoop["itemID"]) && intVal($GLOBALS["SL"]->closestLoop["itemID"]) > 0
                && isset($this->sessData->dataSets["EventSequence"]) 
                && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
                foreach ($this->sessData->dataSets["EventSequence"] as $eve) {
                    if ($eve->EveType == 'Force') {
                        $chk = OPLinksCivilianEvents::where('LnkCivEveEveID', $eve->EveID)
                            ->where('LnkCivEveCivID', $GLOBALS["SL"]->closestLoop["itemID"])
                            ->first();
                        if ($chk && isset($chk->LnkCivEveID)) return 1;
                    }
                }
            }
            return 0;
        } elseif ($condition == '#MedicalCareNotYou') {
            if (isset($GLOBALS["SL"]->closestLoop["itemID"])) {
                $careRec = $this->sessData->getRowById('InjuryCare', $GLOBALS["SL"]->closestLoop["itemID"]);
                if ($careRec && isset($careRec->InjCareSubjectID)) {
                    $civ = $this->sessData->getRowById('Civilians', $careRec->InjCareSubjectID);
                    if ($civ && isset($civ->CivIsCreator) && trim($civ->CivIsCreator) == 'Y') return 0;
                }
            }
        } elseif ($condition == '#Property') {
            $search = $this->sessData->getChildRow('EventSequence', $GLOBALS["SL"]->closestLoop["itemID"], 'Searches');
            if ((isset($search->SrchSeized) && trim($search->SrchSeized) == 'Y')
                || (isset($search->SrchDamage) && trim($search->SrchDamage) == 'Y')) {
                return 1;
            } else {
                return 0;
            }
        } elseif ($condition == '#EmailConfirmSentToday') {
            if (isset($this->v["user"]) && isset($this->v["user"]->id)) {
                $cutoff = date("Y-m-d H:i:s", 
                    mktime(date("H"), date("i"), date("s"), date("n"), date("j")-1, date("Y")));
                $chk = SLEmailed::where('EmailedEmailID', 1)
                    ->where('EmailedFromUser', $this->v["user"]->id)
                    ->where('created_at', '>', $cutoff)
                    ->get();
                 if ($chk->isNotEmpty()) {
                     return 1;                
                 }
            }
            return 0;
        } elseif ($condition == '#HasUploads') {
            $uploads = $this->getUploadsMultNodes($this->cmplntUpNodes, $this->v["isAdmin"], $this->v["isOwner"]);
            if ($uploads && sizeof($uploads) > 0) return 1;
            return 0;
        } elseif ($condition == '#PrintAnonOnly') {
            if (isset($GLOBALS["SL"]->x["pageView"]) && in_array($GLOBALS["SL"]->x["pageView"], ['public', 'pdf'])
                && isset($this->sessData->dataSets["Complaints"][0]->ComStatus)
                && in_array(trim($this->sessData->dataSets["Complaints"][0]->ComStatus), 
                    $this->getUnPublishedStatusList())) {
                return 1;
            }
            if (isset($GLOBALS["SL"]->x["pageView"]) && in_array($GLOBALS["SL"]->x["pageView"], ['public', 'pdf'])
                && isset($this->sessData->dataSets["Complaints"][0]->ComPrivacy)
                && $this->sessData->dataSets["Complaints"][0]->ComPrivacy 
                    != $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')) {
                return 1;
            }
            return 0;
        } elseif ($condition == '#PrintSensitiveReport') {
            if (isset($GLOBALS["SL"]->x["pageView"]) && in_array($GLOBALS["SL"]->x["pageView"], ['full', 'full-pdf'])) {
                return 1;
            }
            return 0;
        } elseif ($condition == '#IsOversightAgency') {
            return (($this->v["uID"] > 0 && $this->v["user"]->hasRole('oversight')) ? 1 : 0);
        }
        return -1; 
    }
    
    protected function loadSessionDataSavesExceptions($nID)
    { 
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
    
    /* public function findUserCoreID()
    {
        $this->coreIncompletes = [];
        if (isset($this->v["user"]) && isset($this->v["user"]->id)) {
            $incompletes = DB::table('OP_Civilians')
                ->join('OP_Complaints', 'OP_Civilians.CivComplaintID', '=', 'OP_Complaints.ComID')
                ->where('OP_Civilians.CivUserID', $this->v["user"]->id)
                ->where('OP_Civilians.CivIsCreator', 'Y')
                ->where('OP_Complaints.ComStatus', $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete'))
                ->whereNotNull('OP_Complaints.ComSummary')
                ->where('OP_Complaints.ComSummary', 'NOT LIKE', '')
                ->select('OP_Complaints.*') //, 'OP_Civilians.CivID', 'OP_Civilians.CivRole'
                ->orderBy('OP_Complaints.created_at', 'desc')
                ->get();
            if ($incompletes->isNotEmpty()) {
                foreach ($incompletes as $i => $com) $this->coreIncompletes[] = [$com->ComID, $com];
                return $this->coreIncompletes[0][0];
            }
        }
        return -3;
    } */
    
    public function multiRecordCheckIntro($cnt = 1)
    {
        $ret = '<a id="hidivBtnUnfinished' . $this->currNode() . '" class="btn btn-lg btn-secondary w100 hidivBtn" '
            . 'href="javascript:;">' . $this->v["user"]->name . ', You Have ';
        if ($this->treeID == 1) {
            $ret .= (($cnt == 1) ? 'An Unfinished Complaint' : 'Unfinished Complaints');
        } elseif ($this->treeID == 5) {
            $ret .= (($cnt == 1) ? 'An Unfinished Compliment' : 'Unfinished Compliments');
        }
        return $ret . '</a>';
    }
    
    public function multiRecordCheckRowTitle($coreRecord)
    {
        $ret = '';
        if ($this->treeID == 1) {
            if (isset($coreRecord[1]->ComSummary) && trim($coreRecord[1]->ComSummary) != '') {
                $ret .= $this->wordLimitDotDotDot($coreRecord[1]->ComSummary, 10);
            } else {
                $ret .= '(empty)';
            }
        } elseif ($this->treeID == 5) {
            if (isset($coreRecord[1]->CompliSummary) && trim($coreRecord[1]->CompliSummary) != '') {
                $ret .= $this->wordLimitDotDotDot($coreRecord[1]->CompliSummary, 10);
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
    
    
/*****************************************************************************
// START Processes Which Override Basic Node Printing and $_POST Processing
*****************************************************************************/
    
    protected function tweakPageViewPerms()
    {
        if (isset($this->sessData->dataSets["Complaints"]) 
            && $this->isPublished('Complaints', $this->coreID, $this->sessData->dataSets["Complaints"][0])
            && isset($this->sessData->dataSets["Complaints"][0]->ComPrivacy)
            && $this->sessData->dataSets["Complaints"][0]->ComPrivacy 
                == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')) {
            if ($GLOBALS["SL"]->x["dataPerms"] == 'public') {
                $GLOBALS["SL"]->x["dataPerms"] = 'private';
            }
        }
        return true;
    }
    
    protected function rawOrderPercentTweak($nID, $rawPerc, $found = -3)
    { 
        if ($this->isGold() && !$this->isCompliment()) return $rawPerc;
        return round(100*($found/(sizeof($this->nodesRawOrder)-200)));
    }
    
    protected function runPageExtra($nID = -3)
    {
        if ($nID == 148) {
            $this->initBlnkAllegsGold();
        } elseif ($nID == 1362) { // Loading Complaint Report: Check for oversight permissions
            if (!isset($GLOBALS["SL"]->x["pageView"])) $this->maxUserView(); // shouldn't be needed?
            if ($this->chkOverUserHasCore()) $GLOBALS["SL"]->x["dataPerms"] = 'sensitive';
        }
        return true;
    }

    protected function customNodePrint($nID = -3, $tmpSubTier = [], $nIDtxt = '', $nSffx = '', $currVisib = 1)
    {
        $ret = '';
        if ($nID == 1095) {
            $ret .= '<div class="nPrompt">Incident Address: ' . $this->sessData->dataSets["Incidents"][0]->IncAddress 
                . ', ' . ((isset($this->sessData->dataSets["Incidents"][0]->IncAddress2)
                    && trim($this->sessData->dataSets["Incidents"][0]->IncAddress2) != '') 
                    ? $this->sessData->dataSets["Incidents"][0]->IncAddress2 . ', ' : '')
                . $this->sessData->dataSets["Incidents"][0]->IncAddressCity . ', '
                . $this->sessData->dataSets["Incidents"][0]->IncAddressState . ' '
                . ((isset($this->sessData->dataSets["Incidents"][0]->IncAddressZip)) 
                    ? $this->sessData->dataSets["Incidents"][0]->IncAddressZip : '') . '</div>';
        } elseif ($nID == 1436) {
            $ret .= $this->printVehicleMatcher();
        } elseif (in_array($nID, [145, 920])) {
            $this->nextBtnOverride = 'Find & Select A Department';
            $searchSuggest = [];
            $deptCitys = OPDepartments::select('DeptAddressCity')
                ->distinct()
                ->where('DeptAddressState', $this->sessData->dataSets["Incidents"][0]->IncAddressState)
                ->get();
            if ($deptCitys->isNotEmpty()) {
                foreach ($deptCitys as $dept) {
                    if (!in_array($dept->DeptAddressCity, $searchSuggest)) {
                        $searchSuggest[] = json_encode($dept->DeptAddressCity);
                    }
                }
            }
            $deptCounties = OPDepartments::select('DeptAddressCounty')
                ->distinct()
                ->where('DeptAddressState', $this->sessData->dataSets["Incidents"][0]->IncAddressState)
                ->get();
            if ($deptCounties->isNotEmpty()) {
                foreach ($deptCounties as $dept) {
                    if (!in_array($dept->DeptAddressCounty, $searchSuggest)) {
                        $searchSuggest[] = json_encode($dept->DeptAddressCounty);
                    }
                }
            }
            $deptFed = OPDepartments::select('DeptName')->where('DeptType', 366)
                ->get();
            if ($deptFed->isNotEmpty()) {
                foreach ($deptFed as $dept) {
                    if (!in_array($dept->DeptName, $searchSuggest)) {
                        $searchSuggest[] = json_encode($dept->DeptName);
                    }
                }
            }
            $GLOBALS["SL"]->pageAJAX .= view('vendor.openpolice.nodes.145-ajax-dept-search', [
                "nID"           => $nID,
                "searchSuggest" => $searchSuggest
            ])->render();
            $GLOBALS["SL"]->loadStates();
            $ret .= view('vendor.openpolice.nodes.145-dept-search', [ 
                "nID"                => $nID,
                "IncAddressCity"     => $this->sessData->dataSets["Incidents"][0]->IncAddressCity, 
                "stateDropstateDrop" => $GLOBALS["SL"]->states->stateDrop(
                    $this->sessData->dataSets["Incidents"][0]->IncAddressState, true) 
            ])->render();
        } elseif ($nID == 203) {
            $this->initBlnkAllegsSilv();
        } elseif (in_array($nID, [270, 973])) {
            $url = '';
            if ($nID == 270) {
                $this->sessData->currSessData($nID, 'Complaints', 'ComStatus', 'update', 
                    $GLOBALS["SL"]->def->getID('Complaint Status', 'New'));
                $this->sessData->currSessData($nID, 'Complaints', 'ComRecordSubmitted', 'update', 
                    date("Y-m-d H:i:s"));
                $this->sessData->currSessData($nID, 'Complaints', 'ComAllegList', 'update', 
                    $this->commaAllegationList());
                $this->sessData->dataSets["Complaints"][0]->update([
                    'ComPublicID' => $GLOBALS["SL"]->genNewCorePubID() ]);
                $url = '/complaint/read-' . $this->sessData->dataSets["Complaints"][0]->ComPublicID;
            } else {
                $this->sessData->currSessData($nID, 'Compliments', 'CompliStatus', 'update', 
                    $GLOBALS["SL"]->def->getID('Compliment Status', 'New'));
                $this->sessData->currSessData($nID, 'Compliments', 'CompliRecordSubmitted', 'update', 
                    date("Y-m-d H:i:s"));
                //$this->sessData->currSessData($nID, 'Compliments', 'CompliAllegList', 'update', 
                //    $this->commaAllegationList());
                $this->sessData->dataSets["Compliments"][0]->update([ 
                    'CompliPublicID' => $GLOBALS["SL"]->genNewCorePubID() ]);
                $url = '/compliment-read/' . $this->sessData->dataSets["Compliments"][0]->ComPublicID;
            }
            /* if ($nID == 270 && trim($this->sessData->dataSets["Complaints"][0]->ComSlug) != '') {
                $url = '/report' . $this->sessData->dataSets["Complaints"][0]->ComSlug;
            } elseif ($nID == 973 && trim($this->sessData->dataSets["Compliments"][0]->CompliSlug) != '') {
                $url = '/report' . $this->sessData->dataSets["Compliments"][0]->CompliSlug;
            } */
            $ret .= '<br /><br /><center><h1>All Done!<br />Taking you to <a href="' . $url . '">your finished '
                . (($nID == 270) ? 'complaint' : 'compliment') 
                . '</a> ...</h1>' . $GLOBALS["SL"]->sysOpts["spinner-code"] 
                . '</center><script type="text/javascript"> setTimeout("window.location=\'' 
                . $url . '\'", 1500); </script><style> #nodeSubBtns, #sessMgmt, #dontWorry { display: none; } </style>';
            $this->restartSess($GLOBALS["SL"]->REQ);
            
            
        // Custom Nodes on site pages
        
        
        // Home Page
        } elseif ($nID == 1876) {
            $ret .= '<div class="relDiv w100">'
                . '<div id="photoCred" class="absDiv f8 wht opac80" style="top: -25px; right: 15px;">'
                . '<a href="https://www.flickr.com/photos/fibonacciblue/26146967075/in/dateposted/" target="_blank" '
                . 'class="fnt">Photo by Fibonacci Blue</a>, <a href="'
                . 'https://creativecommons.org/licenses/by/2.0/" target="_blank" class="fnt">CC BY 2.0</a>'
                . '</div></div>';
                
                
        // FAQ
        } elseif ($nID == 1884) {
            $GLOBALS["SL"]->addBodyParams('onscroll="if (typeof bodyOnScroll === \'function\') bodyOnScroll();"');
                
        // Public Departments Accessibility Overview
        } elseif ($nID == 1858) {
            if (!isset($this->v["deptScores"])) $this->v["deptScores"] = new DepartmentScores;
            $ret .= $this->v["deptScores"]->printTotsBars();
        } elseif ($nID == 1816) {
            $GLOBALS["SL"]->addBodyParams('onscroll="if (typeof bodyOnScroll === \'function\') bodyOnScroll();"');
            if (!isset($this->v["deptScores"])) $this->v["deptScores"] = new DepartmentScores;
            $ret .= $this->v["deptScores"]->printTotsBars();
            /*
            $statGrades = new SurvLoopStat;
            $statGrades->addFilt('grade', 'Grade', 
                ['A', 'B', 'C', 'D', 'F'],
                ['A', 'B', 'C', 'D', 'F']); // a
            $statGrades->addDataType('cnt', 'Departments', ' Departments'); // a
            $statGrades->loadMap();
            if ($this->v["deptScores"]->scoreDepts->isNotEmpty()) {
                foreach ($this->v["deptScores"]->scoreDepts as $i => $dept) {
                    $grade = trim($GLOBALS["SL"]->calcGrade($dept->DeptScoreOpenness));
                    if (strlen($grade) > 1) $grade = substr($grade, 0, 1);
                    $statGrades->resetRecFilt();
                    $statGrades->addRecFilt('grade', $grade, $dept->DeptID);
                    $statGrades->addRecDat('cnt', 1, $dept->DeptID);
                }
            }
            $statGrades->calcStats();
            $blue1 = $GLOBALS["SL"]->printColorFadeHex(0.6, '#FFFFFF', '#2B3493');
            $blue2 = $GLOBALS["SL"]->printColorFadeHex(0.3, '#FFFFFF', '#2B3493');
            $red2 = $GLOBALS["SL"]->printColorFadeHex(0.3, '#FFFFFF', '#EC2327');
            $colors = [ '#2B3493', $blue1, $blue2, $red2, '#EC2327' ];
            $ret = $statGrades->piePercCntCore('grade', 0.2, $colors);
            */
        } elseif ($nID == 1863) {
            if (!isset($this->v["deptScores"])) {
                $this->v["deptScores"] = new DepartmentScores;
                $this->v["deptScores"]->loadAllDepts();
            }
            $GLOBALS["SL"]->loadStates();
            $cnt = 0;
            $limit = 10;
            foreach ($this->v["deptScores"]->scoreDepts as $i => $dept) {
                if ($cnt < $limit && (!isset($dept->DeptAddressLat) || intVal($dept->DeptAddressLat) == 0)) {
                    list($lat, $lng) = $GLOBALS["SL"]->states->getLatLng($GLOBALS["SL"]->printRowAddy($dept, 'Dept'));
                    $this->v["deptScores"]->scoreDepts[$i]->update([
                        'DeptAddressLat' => $lat,
                        'DeptAddressLng' => $lng
                        ]);
                    $cnt++;
                }
            }
            $ret = $GLOBALS["SL"]->states->embedMap();
        } elseif (in_array($nID, [859, 1454])) {
            $GLOBALS["SL"]->addHshoo('/departments-accessibility#gradeDesc');
            if (!isset($this->v["deptScores"])) {
                $this->v["deptScores"] = new DepartmentScores;
                $this->v["deptScores"]->loadAllDepts();
            }
            $ret .= view('vendor.openpolice.nodes.859-depts-overview-public', [
                "nID"        => $nID,
                "deptScores" => $this->v["deptScores"]
                ])->render();
        } elseif (in_array($nID, [1456])) { // public oversight overview
            /*
            $oversights = DB::table('OP_Oversight')
                ->where('OP_Oversight.OverType', 302)
                ->leftJoin('OP_Departments', 'OP_Departments.DeptID', '=', 'OP_Oversight.OverDeptID')
                ->orderBy('OP_Oversight.OverAddressState', 'asc')
                ->orderBy('OP_Oversight.OverAddressCounty', 'asc')
                ->orderBy('OP_Departments.DeptName', 'asc')
                ->get();
            $ret .= view('vendor.openpolice.nodes.1456-oversight-overview-public', [
                "nID"        => $nID,
                "oversights" => $oversights
                ])->render();
            */
        } elseif ($nID == 1099) {
            if (!isset($this->v["deptID"]) || intVal($this->v["deptID"]) <= 0) {
                if ($GLOBALS["SL"]->REQ->has('d') && intVal($GLOBALS["SL"]->REQ->get('d')) > 0) {
                    $this->v["deptID"] = $GLOBALS["SL"]->REQ->get('d');
                } else {
                    $this->v["deptID"] = -3;
                }
            }
            $this->loadDeptStuff($this->v["deptID"]);
            if ($this->v["uID"] > 0 && $this->v["user"]->hasRole('administrator|databaser|staff|partner|volunteer')) {
                $GLOBALS["SL"]->addTopNavItem('pencil', 
                    '/dashboard/start-' . $this->v["deptID"] . '/volunteers-research-departments');
            }
            $previews = '<div id="n' . $nID . 'ajaxLoad" class="w100">' . $GLOBALS["SL"]->sysOpts["spinner-code"] 
                . '</div>';
            $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID . 'ajaxLoad").load("/record-prevs/1?d=' . $this->v["deptID"] 
                . '&limit=20");' . "\n";
            
            /*

            if (trim($previews) == '') {
                $previews = '<p><i>No complaints have been submitted for this deparment.</i></p>';
            }
            */
            $ret .= view('vendor.openpolice.dept-page', [
                "d"        => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]],
                "previews" => $previews
                ])->render();
                
        // User Profile Nodes
        } elseif ($nID == 1437) {
            $chk = OPComplaints::where('ComUserID', $this->v["uID"])
                ->whereIn('ComStatus', $this->getUnPublishedStatusList())
                ->orderBy('created_at', 'desc')
                ->get();
            if ($chk->isNotEmpty()) {
                $ret .= '<h2>Your Complaints Waiting For Review</h2><div id="n' . $nID 
                    . 'ajaxLoad" class="w100">' . $GLOBALS["SL"]->sysOpts["spinner-code"] . '</div>';
                $loadURL = '/record-prevs/1?ids=';
                foreach ($chk as $i => $rec) $loadURL .= (($i > 0) ? ',' : '') . $rec->ComPublicID;
                $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID . 'ajaxLoad").load("' . $loadURL . '");' . "\n";
            } else {
                $ret .= '<div class="p10"></div>';
            }
        } elseif ($nID == 1779) {
            $this->setUserOversightFilt();
            if (!isset($this->v["fltQry"])) $this->v["fltQry"] = '';
            $this->v["fltQry"] .= " c.`ComStatus` IN ("
                . $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight') . ", "
                . $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight') . ", "
                . $GLOBALS["SL"]->def->getID('Complaint Status', 'Received by Oversight') . ", "
                . $GLOBALS["SL"]->def->getID('Complaint Status', 'Declined To Investigate (Closed)') . ", "
                . $GLOBALS["SL"]->def->getID('Complaint Status', 'Investigated (Closed)') . ") AND ";
            $this->v["statusSkips"] = [
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'New'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Hold'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Reviewed'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Closed')
                ];
            return $this->printComplaintListing();
            
        // Complaint Report Nodes
        } elseif (in_array($nID, [1374, 1729])) {
            return $this->reportAllegsWhy($nID);
        } elseif ($nID == 1373) {
            return $this->reportStory($nID);
        } elseif (in_array($nID, [1466, 1728])) {
            return $this->chkGetReportDept($this->sessData->getLatestDataBranchID());
        } elseif (in_array($nID, [1382, 1734])) {
            return $this->getReportDept($this->sessData->getLatestDataBranchID());
        } elseif (in_array($nID, [1690, 1747])) {
            return $this->getReportByLine();
        } elseif (in_array($nID, [1687, 1731])) {
            return $this->getReportWhenLine();
        } elseif (in_array($nID, [1688, 1732])) {
            return $this->getReportWhereLine($nID);
        } elseif (in_array($nID, [1467, 1733])) {
            return ['Privacy Setting', $this->getReportPrivacy($nID)];
        } elseif ($nID == 1468) {
            return $this->getCivReportNameHeader($nID);
        } elseif ($nID == 1476) {
            return $this->getOffReportNameHeader($nID);
        } elseif ($nID == 1795) {
            $uploads = $this->getUploadsMultNodes($this->cmplntUpNodes, $this->v["isAdmin"], $this->v["isOwner"]);
            return '<h3 class="mT0 slBlueDark">' . (($uploads && sizeof($uploads) > 1) ? 'Uploads' : 'Upload') . '</h3>'
                . view('vendor.survloop.inc-report-uploads', [ "uploads" => $uploads ])->render();
        } elseif ($nID == 1478) {
            return [ $this->getCivSnstvFldsNotPrinted($this->sessData->getLatestDataBranchID()) ];
        } elseif ($nID == 1511) {
            return $this->reportCivAddy($nID);
        } elseif ($nID == 1519) {
            return [ $this->getOffSnstvFldsNotPrinted($this->sessData->getLatestDataBranchID()) ];
        } elseif ($nID == 1566) {
            return $this->getOffProfan();
        } elseif ($nID == 1567) {
            return $this->getCivProfan();
        } elseif ($nID == 1574) {
            return $this->reportEventTitle($this->sessData->getLatestDataBranchID());
        } elseif ($nID == 1710) {
            return $this->printReportShare();
        } elseif ($nID == 1707) {
            return $this->printGlossary();
        } elseif ($nID == 1708) {
            return $this->printFlexArts();
        } elseif ($nID == 1753) {
            return $this->printFlexVids();
        } elseif ($nID == 1712) {
            return $this->printComplaintAdmin();
        } elseif ($nID == 1713) {
            return $this->printComplaintOversight();
        } elseif ($nID == 1714) {
            return $this->printComplaintOwner();
        } elseif ($nID == 1780) {
            return $this->printMfaInstruct();
            
        // Staff Area Nodes
        } elseif ($nID == 1418) {
            return $this->printComplaintListing();
        } elseif ($nID == 1420) {
            return $this->printComplaintListing('incomplete');
        
            
        // Volunteer Area Nodes
        } elseif ($nID == 1211) {
            return $this->printVolunPriorityList();
        } elseif ($nID == 1755) {
            return $this->printVolunAllList();
        } elseif ($nID == 1217) {
            return $this->printVolunLocationForm();
        } elseif ($nID == 1225) {
            return $this->printDeptEditHeader();
        } elseif ($nID == 1261) {
            return view('vendor.openpolice.nodes.1261-volun-dept-edit-wiki-stats', $this->v)->render();
        } elseif ($nID == 1809) {
            return view('vendor.openpolice.nodes.1809-volun-dept-edit-how-investigate', $this->v)->render();
        } elseif ($nID == 1227) {
            return view('vendor.openpolice.nodes.1227-volun-dept-edit-search-complaint', $this->v)->render();
        } elseif (in_array($nID, [1228, 1229])) {
            return $this->printDeptEditContact($nID);
        } elseif ($nID == 1231) {
            return view('vendor.openpolice.volun.volun-dept-edit-history', $this->v)->render();
        } elseif ($nID == 1338) {
            return $GLOBALS["SL"]->getBlurbAndSwap('Volunteer Checklist');
        } elseif ($nID == 1340) {
            return $this->redirAfterDeptEdit();
        } elseif ($nID == 1344) {
            return $this->redirNextDeptEdit();
        } elseif ($nID == 1346) {
            return $this->volunStars();
        } elseif ($nID == 1351) {
            return $this->volunDepts();
        } elseif ($nID == 1349) {
            $this->v["isDash"] = true;
            return $this->volunStatsDailyGraph();
        } elseif ($nID == 1342) {
            return $this->printDashSessGraph();
        } elseif ($nID == 1361) {
            return $this->printDashTopStats();
        }
        return $ret;
    }
    
    // This is more for special behavior which is repeating for multiple nodes, compared to one-time specialization 
    // of customNodePrint(). Also in contrast to customNodePrint(), printSpecial() runs within the standard 
    // node print process (not replacing it) and does not require printing of the <form> or prompt text.
    protected function printSpecial($nID = -3, $promptNotesSpecial = '', $currNodeSessionData = '')
    { 
        $ret = '';
        if ($promptNotesSpecial == '[[MergeVictimsEventSequence]]') {
            
            $eventType = $this->getEveSeqTypeFromNode($nID);
            $typeLabel = (($eventType == 'Stops') ? 'stop' 
                : (($eventType == 'Searches') ? 'search' 
                    : (($eventType == 'Force') ? 'force' : 'arrest')));
            $ret .= '<div class="nPrompt">Was this ' . $typeLabel 
                . ' the same as another victim\'s ' . $typeLabel . '?</div>
            <div class="nFld pB20 mB20">' . "\n";
                $prevEvents = $this->getPreviousEveSeqsOfType($GLOBALS["SL"]->closestLoop["itemID"]);
                if (sizeof($prevEvents) > 0) {
                    foreach ($prevEvents as $i => $prevEveID) {
                        $eveSeq = $this->getEventSequence($prevEveID);
                        $ret .= '<div class="nFld pT20" style="font-size: 125%;"><label for="eventMerge' . $i . '">
                            <input type="radio" name="eventMerge" id="eventMerge' . $i . '" value="' . $prevEveID . '">
                            <span class="mL5">' . $this->printEventSequenceLine($eveSeq) . '</span>
                        </label></div>' . "\n";
                    }
                }
                $ret .= '<div class="nFld pT20 pB20" style="font-size: 125%;"><label for="eventMergeNo">
                    <input type="radio" name="eventMerge" id="eventMergeNo" value="-3"> <span class="mL5">No</span>
                </label></div>
            </div>';
            
        } elseif ($promptNotesSpecial == '[[VictimsWithoutArrests]]') {
            
            $ret .= $this->formCivSetPossibilities('!Arrests', 'Charges');
            
        }
        return $ret;
    }
    
    protected function customNodePrintButton($nID = -3, $promptNotes = '')
    { 
        if (in_array($nID, [270, 973])) return '<!-- no buttons, all done! -->';
        return '';
    }
    
    protected function postNodePublicCustom($nID = -3, $tmpSubTier = [])
    {
        if (empty($tmpSubTier)) $tmpSubTier = $this->loadNodeSubTier($nID);
        list($tbl, $fld) = $this->allNodes[$nID]->getTblFld();
        if (isset($this->sessData->dataSets["Complaints"])) {
            $this->sessData->dataSets["Complaints"][0]->update([ "updated_at" => date("Y-m-d H:i:s") ]);
        }
        if ($nID == 439) { // Unresolved criminal charges decision
            if ($GLOBALS["SL"]->REQ->has('n439fld')) {
                $defID = $GLOBALS["SL"]->def->getID('Unresolved Charges Actions', 'Full complaint to print or save');
                if ($GLOBALS["SL"]->REQ->input('n439fld') == $defID) {
                    $defID = $GLOBALS["SL"]->def->getID('Privacy Types', 'Anonymized');
                    if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == $defID) {
                        $this->sessData->dataSets["Complaints"][0]->update([
                            "ComPrivacy" => $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')
                        ]);
                    }
                } else {
                    $defID = $GLOBALS["SL"]->def->getID('Unresolved Charges Actions', 'Anonymous complaint data only');
                    if ($GLOBALS["SL"]->REQ->input('n439fld') == $defID) {
                        $this->sessData->dataSets["Complaints"][0]->update([
                            "ComPrivacy" => $GLOBALS["SL"]->def->getID('Privacy Types', 'Anonymized')
                        ]);
                    }
                }
            }
            return false;
        } elseif (in_array($nID, [16, 17])) {
            $time = $this->postFormTimeStr($nID);
            $date = date("Y-m-d", strtotime($GLOBALS["SL"]->REQ->n15fld));
            if ($time === null) $date .= ' 00:00:00';
            else $date .= ' ' . $time;
            $this->sessData->currSessData($nID, $tbl, $fld, 'update', $date);
            return true;
        } elseif ($nID == 47) { // Complainant Recorded Incident?
            $this->sessData->dataSets["Civilians"][0]->CivCameraRecord = $GLOBALS["SL"]->REQ->input('n47fld');
            $this->sessData->dataSets["Civilians"][0]->save();
            return true;
            
        } elseif (in_array($nID, [1408, 1409, 1410, 1411, 1412])) {
            if ($GLOBALS["SL"]->REQ->has('n1405fld') && $GLOBALS["SL"]->REQ->n1405fld == 'Y') return true;
        } elseif (in_array($nID, [91, 92, 93, 94, 95])) {
            if ($GLOBALS["SL"]->REQ->has('n576fld') && $GLOBALS["SL"]->REQ->n576fld == 'Y') return true;
        } elseif (in_array($nID, [131, 132, 133, 134, 135])) {
            if ($GLOBALS["SL"]->REQ->has('n571fld') && $GLOBALS["SL"]->REQ->n571fld == 'Y') return true;
        } elseif (in_array($nID, [189, 615, 190, 191, 192])) {
            if ($GLOBALS["SL"]->REQ->has('n584fld') && $GLOBALS["SL"]->REQ->n584fld == 'Y') return true;
            
            
        } elseif (in_array($nID, [145, 920])) { // Searched & Found Police Department
            return $this->saveNewDept($nID);
        } elseif ($nID == 671) { // Officers Used Profanity?
            foreach ($this->sessData->dataSets["Officers"] as $i => $off) {
                if (isset($GLOBALS["SL"]->REQ->n671fld) && in_array($off->getKey(), $GLOBALS["SL"]->REQ->n671fld)) {
                    $this->sessData->dataSets["Officers"][$i]->OffUsedProfanity = 'Y';
                }
                else $this->sessData->dataSets["Officers"][$i]->OffUsedProfanity = '';
                $this->sessData->dataSets["Officers"][$i]->save();
            }
        } elseif ($nID == 674) { // Officer Used Profanity?
            if (isset($GLOBALS["SL"]->REQ->n674fld)) {
                $this->sessData->dataSets["Officers"][0]->OffUsedProfanity = trim($GLOBALS["SL"]->REQ->n674fld);
            }
            else $this->sessData->dataSets["Officers"][0]->OffUsedProfanity = '';
            $this->sessData->dataSets["Officers"][0]->save();
        } elseif ($nID == 670) { // Victims Used Profanity?
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if (isset($GLOBALS["SL"]->REQ->n670fld) && in_array($civ->getKey(), $GLOBALS["SL"]->REQ->n670fld)) {
                    $this->sessData->dataSets["Civilians"][$i]->CivUsedProfanity = 'Y';
                } else {
                    $this->sessData->dataSets["Civilians"][$i]->CivUsedProfanity = '';
                }
                $this->sessData->dataSets["Civilians"][$i]->save();
            }
        } elseif ($nID == 676) { // Victim Used Profanity?
            $civInd = $this->getFirstVictimCivInd();
            if ($civInd >= 0) {
                if (isset($GLOBALS["SL"]->REQ->n676fld)) {
                    $this->sessData->dataSets["Civilians"][$civInd]->CivUsedProfanity 
                        = trim($GLOBALS["SL"]->REQ->n676fld);
                }
                else $this->sessData->dataSets["Civilians"][$civInd]->CivUsedProfanity = '';
                $this->sessData->dataSets["Civilians"][$civInd]->save();
            }
        } elseif ($nID == 146) { // Going Gold transitions, if needed...
            if ($GLOBALS["SL"]->REQ->has('n146fld') && $GLOBALS["SL"]->REQ->n146fld == 'Gold' 
                && sizeof($this->sessData->loopItemIDs["Victims"]) == 1) {
                $this->checkHasEventSeq($nID);
                if (sizeof($this->eventCivLookup["Stops"]) == 0 
                    && isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilStopYN)
                    && in_array($this->sessData->dataSets["AllegSilver"][0]->AlleSilStopYN, ['Y', '?'])) {
                    foreach ($this->sessData->loopItemIDs["Victims"] as $civ) $this->addNewEveSeq('Stops', $civ);
                }
                if (sizeof($this->eventCivLookup["Searches"]) == 0 
                    && ( (isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilSearchYN)
                    && in_array($this->sessData->dataSets["AllegSilver"][0]->AlleSilSearchYN, ['Y', '?']))
                    || (isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilPropertyYN)
                    && in_array($this->sessData->dataSets["AllegSilver"][0]->AlleSilPropertyYN, ['Y', '?'])) )) {
                    foreach ($this->sessData->loopItemIDs["Victims"] as $civ) $this->addNewEveSeq('Searches', $civ);
                }
                if (sizeof($this->eventCivLookup["Force"]) == 0 
                    && isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilForceYN)
                    && in_array($this->sessData->dataSets["AllegSilver"][0]->AlleSilForceYN, ['Y', '?'])) {
                    foreach ($this->sessData->loopItemIDs["Victims"] as $civ) $this->addNewEveSeq('Force', $civ);
                }
                if (sizeof($this->eventCivLookup["Arrests"]) == 0 
                    && isset($this->sessData->dataSets["AllegSilver"][0]->AlleSilArrestYN)
                    && in_array($this->sessData->dataSets["AllegSilver"][0]->AlleSilArrestYN, ['Y', '?'])) {
                    foreach ($this->sessData->loopItemIDs["Victims"] as $civ) $this->addNewEveSeq('Arrests', $civ);
                }
            }
            return false;
        } elseif (in_array($nID, [732, 736, 733])) { // Gold Stops & Searches, Multiple Victims
            $this->initGoldEventMult($nID, ((in_array($nID, [732, 736])) ? 'Stops' : 'Searches'));
        } elseif (in_array($nID, [738, 737, 739])) { // Gold Stops & Searches, Only One Victims
            $this->initGoldEventSingle($nID, ((in_array($nID, [738, 737])) ? 'Stops' : 'Searches'));
        } elseif ($nID == 740) { // Use of Force on Victims
            $GLOBALS["SL"]->def->loadDefs('Force Type');
            $loopRows = $this->sessData->getLoopRows('Victims');
            foreach ($loopRows as $i => $civ) {
                $nIDtxt = 'n' . $nID . 'cyc' . $i . 'fld';
                $nIDtxt2 = 'n742cyc' . $i . 'fld';
                if ($GLOBALS["SL"]->REQ->has($nIDtxt) && trim($GLOBALS["SL"]->REQ->get($nIDtxt)) == 'Y'
                    && $GLOBALS["SL"]->REQ->has($nIDtxt2) && is_array($GLOBALS["SL"]->REQ->get($nIDtxt2))
                    && sizeof($GLOBALS["SL"]->REQ->get($nIDtxt2)) > 0) {
                    foreach ($GLOBALS["SL"]->REQ->get($nIDtxt2) as $forceType) {
                        if ($this->getCivForceEventID($nID, $civ->CivID, $forceType) <= 0) {
                            $this->addNewEveSeq('Force', $civ->CivID, $forceType);
                        }
                    }
                }
                foreach ($GLOBALS["SL"]->defValues["Force Type"] as $i => $def) {
                    if ($GLOBALS["SL"]->REQ->get($nIDtxt) == 'N' || !$GLOBALS["SL"]->REQ->has($nIDtxt2) 
                        || !in_array($def->DefID, $GLOBALS["SL"]->REQ->input($nIDtxt2))) {
                        $this->deleteEventByID($nID, $this->getCivForceEventID($nID, $civ->CivID, $def->DefID));
                    }
                }
            }
        } elseif ($nID == 742) { // Use of Force on Victims: Sub-Types processed by 740
            return true;
        } elseif ($nID == 743) { // Use of Force against Animal: Yes/No
            if (!$GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') 
                || $GLOBALS["SL"]->REQ->get('n' . $nID . 'fld') == 'N') {
                $animalsForce = $this->getCivAnimalForces();
                if ($animalsForce && sizeof($animalsForce) > 0) {
                    foreach ($animalsForce as $force) $this->deleteEventByID($nID, $force->ForEventSequenceID);
                }
            }
        } elseif ($nID == 744) { // Use of Force against Animal: Sub-types
            if ($GLOBALS["SL"]->REQ->has('n743fld') && $GLOBALS["SL"]->REQ->get('n743fld') == 'Y' 
                && $GLOBALS["SL"]->REQ->has('n744fld') && is_array($GLOBALS["SL"]->REQ->n744fld) 
                && sizeof($GLOBALS["SL"]->REQ->n744fld) > 0) {
                $animalDesc = (($GLOBALS["SL"]->REQ->has('n746fld')) ? trim($GLOBALS["SL"]->REQ->n746fld) : '');
                $animalsForce = $this->getCivAnimalForces();
                foreach ($GLOBALS["SL"]->REQ->n744fld as $forceType) {
                    $foundType = false;
                    if ($animalsForce && sizeof($animalsForce) > 0) {
                        foreach ($animalsForce as $force) {
                            if ($force->ForType == $forceType) $foundType = true;
                        }
                    }
                    if (!$foundType) {
                        $newForce = $this->addNewEveSeq('Force', -3, $forceType);
                        $newForce->ForAgainstAnimal = 'Y';
                        $newForce->ForAnimalDesc = $animalDesc;
                        $newForce->save();
                    }
                }
            }
        } elseif ($nID == 741) { // Arrests, Citations, Warnings
            $this->checkHasEventSeq($nID);
            $loopRows = $this->sessData->getLoopRows('Victims');
            foreach ($loopRows as $i => $civ) {
                $nIDtxt = 'n' . $nID . 'cyc' . $i . 'fld';
                if ($GLOBALS["SL"]->REQ->has($nIDtxt) && trim($GLOBALS["SL"]->REQ->input($nIDtxt)) != '') {
                    if ($GLOBALS["SL"]->REQ->input($nIDtxt) == 'Arrests') {
                        if (!in_array($civ->CivID, $this->eventCivLookup['Arrests'])) {
                            $this->addNewEveSeq('Arrests', $civ->CivID);
                        }
                        $loopRows[$i]->CivGivenCitation = 'N';
                        $loopRows[$i]->CivGivenWarning = 'N';
                    } elseif ($GLOBALS["SL"]->REQ->input($nIDtxt) == 'Citations') {
                        $loopRows[$i]->CivGivenCitation = 'Y';
                        $loopRows[$i]->CivGivenWarning = 'N';
                        $this->delCivEvent($nID, 'Arrests', $civ->CivID);
                    } elseif ($GLOBALS["SL"]->REQ->input($nIDtxt) == 'Warnings') {
                        $loopRows[$i]->CivGivenCitation = 'N';
                        $loopRows[$i]->CivGivenWarning = 'Y';
                        $this->delCivEvent($nID, 'Arrests', $civ->CivID);
                    } elseif ($GLOBALS["SL"]->REQ->input($nIDtxt) == 'None') {
                        $loopRows[$i]->CivGivenCitation = 'N';
                        $loopRows[$i]->CivGivenWarning = 'N';
                        $this->delCivEvent($nID, 'Arrests', $civ->CivID);
                    }
                    $loopRows[$i]->save();
                }
            }
            
        } elseif ($this->allNodes[$nID]->nodeRow->NodePromptNotes == '[[MergeVictimsEventSequence]]') {
            return $this->processEventMerge($nID);
        } elseif ($nID == 316) {
            return $this->processHandcuffInjury($nID);
        } elseif ($nID == 1285) {
            /*
            OPOversight::where('OverDeptID', $this->sessData->dataSets["Departments"][0]->DeptID)
                ->where('OverType', $this->overWhichDefID('IA'))
                ->update([
                    'OverWaySubEmail' => (in_array('Email', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0)
                ]);
            */
            if ($GLOBALS["SL"]->REQ->has('n1285fld') && is_array($GLOBALS["SL"]->REQ->n1285fld)
                && is_array($GLOBALS["SL"]->REQ->n1285fld) && sizeof($GLOBALS["SL"]->REQ->n1285fld) > 0) {
                $this->sessData->currSessData($nID, 'Oversight', 'OverWaySubEmail', 'update', 
                    ((in_array('Email', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0));
                $this->sessData->currSessData($nID, 'Oversight', 'OverWaySubVerbalPhone', 'update', 
                    ((in_array('VerbalPhone', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0));
                $this->sessData->currSessData($nID, 'Oversight', 'OverWaySubPaperMail', 'update', 
                    ((in_array('PaperMail', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0));
                $this->sessData->currSessData($nID, 'Oversight', 'OverWaySubPaperInPerson', 'update', 
                    ((in_array('PaperInPerson', $GLOBALS["SL"]->REQ->n1285fld) ) ? 1 : 0));
            }
        } elseif ($nID == 1287) {
            if ($GLOBALS["SL"]->REQ->has('n1287fld') && is_array($GLOBALS["SL"]->REQ->n1287fld)
                && sizeof($GLOBALS["SL"]->REQ->n1287fld) > 0) {
                $this->sessData->currSessData($nID, 'Oversight', 'OverOfficialFormNotReq', 'update', 
                    ((in_array('OfficialFormNotReq', $GLOBALS["SL"]->REQ->n1287fld) ) ? 1 : 0));
                $this->sessData->currSessData($nID, 'Oversight', 'OverOfficialAnon', 'update', 
                    ((in_array('OfficialAnon', $GLOBALS["SL"]->REQ->n1287fld) ) ? 1 : 0));
                $this->sessData->currSessData($nID, 'Oversight', 'OverWaySubNotary', 'update', 
                    ((in_array('Notary', $GLOBALS["SL"]->REQ->n1287fld) ) ? 1 : 0));
                $this->sessData->currSessData($nID, 'Oversight', 'OverSubmitDeadline', 'update', 
                    ((in_array('TimeLimit', $GLOBALS["SL"]->REQ->n1287fld) ) 
                        ? (($GLOBALS["SL"]->REQ->has('n1288fld')) ? $GLOBALS["SL"]->REQ->n1288fld : 0) : 0));
            }
        } elseif ($nID == 1329) {
            if ($GLOBALS["SL"]->REQ->get('step') != 'next') return true;
            if ($GLOBALS["SL"]->REQ->has('n1329fld') && is_array($GLOBALS["SL"]->REQ->n1329fld)
                && sizeof($GLOBALS["SL"]->REQ->n1329fld) > 0) {
                if (in_array('IACall', $GLOBALS["SL"]->REQ->n1329fld)) {
                    $this->sessData->currSessData($nID, 'Departments', 'DeptVerified', 'update', date("Y-m-d H:i:s"));
                }
            }
            $this->sessData->createTblExtendFlds('Departments', $this->coreID, 'Zedit_Departments', [
                'ZedDeptDeptID'   => $this->coreID,
                'ZedDeptUserID'   => $this->v["uID"],
                'ZedDeptDuration' => (time()-intVal($GLOBALS["SL"]->REQ->formLoaded)),
                'ZedDeptDeptID'   => $this->coreID,
                ]);
            $over = $this->getOverRow('IA');
            $this->sessData->createTblExtendFlds('Oversight', $over->getKey(), 'Zedit_Oversight', [
                'ZedOverZedDeptID'      => $this->sessData->dataSets['Zedit_Departments'][0]->getKey(),
                'ZedOverOnlineResearch' => (($GLOBALS["SL"]->REQ->has('n1329fld') 
                    && in_array('Online', $GLOBALS["SL"]->REQ->n1329fld)) ? 0 : 1),
                'ZedOverMadeDeptCall'   => (($GLOBALS["SL"]->REQ->has('n1329fld') 
                    && in_array('DeptCall', $GLOBALS["SL"]->REQ->n1329fld)) ? 0 : 1),
                'ZedOverMadeIACall'     => (($GLOBALS["SL"]->REQ->has('n1329fld') 
                    && in_array('IACall', $GLOBALS["SL"]->REQ->n1329fld)) ? 0 : 1),
                'ZedOverNotes'          => (($GLOBALS["SL"]->REQ->has('n1334fld')) ? $GLOBALS["SL"]->REQ->n1334fld : '')
                ]);
            $over = $this->getOverRow('Civ');
            if ($over && isset($over->OverID)) {
                $this->sessData->createTblExtendFlds('Oversight', $over->getKey(), 'Zedit_Oversight', [
                    'ZedOverZedDeptID'      => $this->sessData->dataSets['Zedit_Departments'][0]->getKey()
                    ]);
            }
        } elseif (in_array($nID, [976, 1090, 1175])) {
            if ($GLOBALS["SL"]->REQ->get('step') != 'next') {
                return true;
            } elseif ($nID == 976) {
                $this->sessData->dataSets['Complaints'][0]->ComStatus 
                    = $GLOBALS["SL"]->def->getID('Complaint Status', 'New');
                $this->sessData->dataSets['Complaints'][0]->save();
            }
        }
        return false; // false to continue standard post processing
    }
    
    // returns an array of overrides for ($currNodeSessionData, ???... 
    protected function printNodeSessDataOverride($nID = -3, $tmpSubTier = [], $currNodeSessionData = '')
    {
        if (empty($this->sessData->dataSets)) return [];
        if ($nID == 28) { // Complainant's Role
            return [trim($this->sessData->dataSets["Civilians"][0]->CivRole)];
        } elseif ($nID == 47) { // Complainant Recorded Incident?
            return [trim($this->sessData->dataSets["Civilians"][0]->CivCameraRecord)];
        } elseif ($nID == 19) { // Would you like to provide the GPS location?
            if (intVal($this->sessData->dataSets["Incidents"][0]->IncAddressLat) != 0 
                || intVal($this->sessData->dataSets["Incidents"][0]->IncAddressLng) != 0) {
                return ['Yes'];
            } else {
                return [];
            }
        } elseif ($nID == 39) {
            if ($currNodeSessionData == '') {
                $user = Auth::user();
                if ($user && isset($user->email)) return [$user->email];
                return [''];
            }
        } elseif ($nID == 671) { // Officers Used Profanity?
            $currVals = [];
            foreach ($this->sessData->dataSets["Officers"] as $i => $off) {
                if (isset($off->OffUsedProfanity) && $off->OffUsedProfanity == 'Y') $currVals[] = $off->getKey();
            }
            return [';' . implode(';', $currVals) . ';'];
        } elseif ($nID == 674) { // Officer Used Profanity?
            return trim($this->sessData->dataSets["Officers"][0]->OffUsedProfanity);
        } elseif ($nID == 670) { // Victims Used Profanity?
            $currVals = [];
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if ($civ->CivUsedProfanity == 'Y') $currVals[] = $civ->getKey();
            }
            return [';' . implode(';', $currVals) . ';'];
        } elseif ($nID == 676) { // Victim Used Profanity?
            $civInd = $this->getFirstVictimCivInd();
            if ($civInd >= 0) {
                return trim($this->sessData->dataSets["Civilians"][$civInd]->CivUsedProfanity);
            }
        } elseif (in_array($nID, [732, 736, 733])) { // Gold Stops & Searches, Multiple Victims
            if (!isset($this->v["firstTimeGoGoldDeets"])) {
                $chk = SLNodeSavesPage::where('PageSaveSession', $this->coreID)
                    ->where('PageSaveNode', 484)
                    ->first();
                $this->v["firstTimeGoGoldDeets"] = (!$chk || !isset($chk->PageSaveID));
            }
            $ret = [];
            $eveType = (in_array($nID, [732, 736])) ? 'Stops' : 'Searches';
            if (sizeof($this->sessData->loopItemIDs["Victims"]) > 0) {
                foreach ($this->sessData->loopItemIDs["Victims"] as $civ) {
                    if ($this->getCivEventID($nID, $eveType, $civ) > 0) $ret[] = $civ;
                }
            }
            return $ret;
        } elseif (in_array($nID, [738, 737, 739])) { // Gold Stops & Searches, Only One Victims
            $eveType = (in_array($nID, [738, 737])) ? 'Stops' : 'Searches';
            if ($this->getCivEventID($nID, $eveType, $this->sessData->loopItemIDs["Victims"][0]) > 0) {
                return ['Y'];
            }
            return ['N'];
        } elseif ($nID == 740) { // Use of Force on Victims
            $ret = [];
            $this->checkHasEventSeq($nID);
            foreach ($this->sessData->loopItemIDs["Victims"] as $i => $civ) {
                if (in_array($civ, $this->eventCivLookup['Force'])) {
                    $ret[] = 'cyc' . $i . 'Y';
                } elseif (!isset($this->v["firstTimeGoGoldDeets"]) || !$this->v["firstTimeGoGoldDeets"]
                    || !in_array($civ, $this->eventCivLookup['Force'])) {
                    $ret[] = 'cyc' . $i . 'N';
                }
            }
            if (empty($ret)) $ret = ['N'];
            return $ret;
        } elseif ($nID == 742) { // Use of Force on Victims: Sub-Types
            $ret = [];
            $GLOBALS["SL"]->def->loadDefs('Force Type');
            foreach ($this->sessData->loopItemIDs["Victims"] as $i => $civ) {
                foreach ($GLOBALS["SL"]->defValues["Force Type"] as $j => $def) {
                    if ($this->getCivForceEventID($nID, $civ, $def->DefID) > 0) {
                        $ret[] = 'cyc' . $i . $def->DefID;
                    }
                }
            }
            return $ret;
        } elseif ($nID == 743) { // Use of Force against Animal: Yes/No
            $animalsForce = $this->getCivAnimalForces();
            if ($animalsForce && sizeof($animalsForce) > 0) return ['Y'];
            elseif (!isset($this->v["firstTimeGoGoldDeets"]) || !$this->v["firstTimeGoGoldDeets"]) return ['N'];
        } elseif ($nID == 746) { // Use of Force against Animal: Description
            $animalsForce = $this->getCivAnimalForces();
            if ($animalsForce->isNotEmpty() && isset($animalsForce[0]->ForAnimalDesc)) {
                return [$animalsForce[0]->ForAnimalDesc];
            }
        } elseif ($nID == 744) { // Use of Force against Animal: Sub-types
            $ret = [];
            $animalsForce = $this->getCivAnimalForces();
            if ($animalsForce && sizeof($animalsForce) > 0) {
                foreach ($animalsForce as $force) $ret[] = $force->ForType;
            }
            return $ret;
        } elseif ($nID == 741) { // Arrests, Citations, Warnings
            $ret = [];
            $this->checkHasEventSeq($nID);
            foreach ($this->sessData->loopItemIDs["Victims"] as $i => $civ) {
                if (in_array($civ, $this->eventCivLookup['Arrests'])) $ret[] = 'cyc' . $i . 'Arrests';
                elseif (in_array($civ, $this->eventCivLookup['Citations'])) $ret[] = 'cyc' . $i . 'Citations';
                elseif (in_array($civ, $this->eventCivLookup['Warnings'])) $ret[] = 'cyc' . $i . 'Warnings';
                else $ret[] = 'cyc' . $i . 'None';
            }
            return $ret;
        } elseif (in_array($nID, [401, 334, 409, 356, 384])) { // Gold Allegations: Pre-Load "Why" From Silver
            if (trim($currNodeSessionData) == '') {
                $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Wrongful Detention'); // 401
                switch ($nID) {
                    case 334: $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Wrongful Search'); break;
                    case 409: $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Wrongful Property Seizure'); break;
                    case 356: $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Unreasonable Force'); break;
                    case 384: $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Wrongful Arrest'); break;
                }
                if (isset($this->sessData->dataSets["Allegations"]) 
                    && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
                    foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                        if (isset($alleg->AlleType) && $alleg->AlleType == $defID && isset($alleg->AlleDescription)
                            && (!isset($alleg->AlleEventSequenceID) || intVal($alleg->AlleEventSequenceID) == 0)) {
                            return [$alleg->AlleDescription];
                        }
                    }
                }
            }
        } elseif ($nID == 269) { // Confirm Submission, Complaint Completed!
            return [(($this->sessData->dataSets["Complaints"][0]->ComStatus 
                != $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) ? 'Y' : '')];
        
        // Volunteer Research Departments
        } elseif ($nID == 1285) {
            $this->getOverRow('IA');
            $currNodeSessionData = [];
            if (isset($this->v["overRowIA"]->OverWaySubEmail) && intVal($this->v["overRowIA"]->OverWaySubEmail) > 0) {
                $currNodeSessionData[] = 'Email';
            }
            if (isset($this->v["overRowIA"]->OverWaySubVerbalPhone) 
                && intVal($this->v["overRowIA"]->OverWaySubVerbalPhone) > 0) {
                $currNodeSessionData[] = 'VerbalPhone';
            }
            if (isset($this->v["overRowIA"]->OverWaySubPaperMail) 
                && intVal($this->v["overRowIA"]->OverWaySubPaperMail) > 0) {
                $currNodeSessionData[] = 'PaperMail';
            }
            if (isset($this->v["overRowIA"]->OverWaySubPaperInPerson) 
                && intVal($this->v["overRowIA"]->OverWaySubPaperInPerson) > 0) {
                $currNodeSessionData[] = 'PaperInPerson';
            }
            return $currNodeSessionData;
        } elseif ($nID == 1287) {
            $currNodeSessionData = [];
            if (isset($this->v["overRowIA"]->OverOfficialFormNotReq) 
                && intVal($this->v["overRowIA"]->OverOfficialFormNotReq) > 0) {
                $currNodeSessionData[] = 'OfficialFormNotReq';
            }
            if (isset($this->v["overRowIA"]->OverOfficialAnon) 
                && intVal($this->v["overRowIA"]->OverOfficialAnon) > 0) {
                $currNodeSessionData[] = 'OfficialAnon';
            }
            if (isset($this->v["overRowIA"]->OverWaySubNotary) 
                && intVal($this->v["overRowIA"]->OverWaySubNotary) > 0) {
                $currNodeSessionData[] = 'Notary';
            }
            if (isset($this->v["overRowIA"]->OverSubmitDeadline) 
                && intVal($this->v["overRowIA"]->OverSubmitDeadline) > 0) {
                $currNodeSessionData[] = 'TimeLimit';
            }
            return $currNodeSessionData;
        }
        return [];
    }
    
    protected function customLabels($nIDtxt = '', $str = '')
    {
        if (in_array($this->treeID, [1, 5])) {
            $event = [];
            if ($GLOBALS["SL"]->closestLoop["loop"] == 'Events') {
                $event = $this->getEventSequence($GLOBALS["SL"]->closestLoop["itemID"]);
            }
            if (isset($event[0]) && isset($event[0]["EveID"])) {
                if (strpos($str, '[LoopItemLabel]') !== false) {
                    $civName = $this->isEventAnimalForce($event[0]["EveID"], $event[0]["Event"]);
                    if (trim($civName) == '' && isset($event[0]["Civilians"])) {
                        $civName = $this->getCivilianNameFromID($event[0]["Civilians"][0]);
                    }
                    $str = str_replace('[LoopItemLabel]', 
                        '<span class="slBlueDark"><b>' . $civName . '</b></span>', $str);
                }
                if (strpos($str, '[ForceType]') !== false) {
                    $forceDesc = $GLOBALS["SL"]->def->getVal('Force Type', $event[0]["Event"]->ForType);
                    if ($forceDesc == 'Other') $forceDesc = $event[0]["Event"]->ForTypeOther;
                    $str = str_replace('[ForceType]', '<span class="slBlueDark"><b>'
                        . $forceDesc . '</b></span>', $str);
                }
            }
            if (strpos($str, '[InjuryType]') !== false) {
                $inj = $this->sessData->getRowById('Injuries', $GLOBALS["SL"]->closestLoop["itemID"]);
                if (sizeof($inj) > 0) {
                    $str = str_replace('[InjuryType]', '<span class="slBlueDark"><b>'
                        . $GLOBALS["SL"]->def->getVal('Injury Types', $inj->InjType) . '</b></span>', $str);
                    $str = $this->cleanLabel(str_replace('[LoopItemLabel]', '<span class="slBlueDark"><b>'
                        . $this->getCivilianNameFromID($inj->InjSubjectID) . '</b></span>', $str));
                }
            }
            if (strpos($str, '[[List of Allegations]]') !== false) {
                $str = str_replace('[[List of Allegations]]', 
                    $this->commaAllegationList(), $str);
            }
            if (strpos($str, '[[List of Events and Allegations]]') !== false) {
                $str = str_replace('[[List of Events and Allegations]]', 
                    $this->basicAllegationList(true), $str);
            }
            if (strpos($str, '[[List of Compliments]]') !== false) {
                $str = str_replace('[[List of Compliments]]', 
                    $this->commaComplimentList(), $str);
            }
            if (isset($this->sessData->dataSets["Civilians"])) {
                $complainantVic = (isset($this->sessData->dataSets["Civilians"][0])
                    && $this->sessData->dataSets["Civilians"][0]->CivRole == 'Victim');
                $multipleVic = (sizeof($this->sessData->getLoopRows('Victims')) > 1);
                if (in_array($nIDtxt, ['209', '212', '852', '248', '222', '227', '234', '243'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody', 'you', $str);
                    } elseif ($complainantVic && $multipleVic) {
                        $str = str_replace('anybody', 'you or anybody else', $str);
                    }
                } elseif (in_array($nIDtxt, ['204'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody', 'you', $str);
                    } elseif ($complainantVic && $multipleVic) {
                        $str = str_replace('anybody', 'you (or anybody else)', $str);
                    }
                } elseif (in_array($nIDtxt, ['205', '213'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody was', 'you were', $str);
                    } elseif ($complainantVic && $multipleVic) {
                        $str = str_replace('anybody was', 'anybody was', $str);
                    }
                } elseif (in_array($nIDtxt, ['591'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody', 'you', $str);
                    }
                } elseif (in_array($nIDtxt, ['228'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody was', 'you were', $str);
                    }
                }
            }
        }
        return $str;
    }
    
    protected function getLoopItemLabelCustom($loop, $itemRow = null, $itemInd = -3)
    {
        //if ($itemIndex < 0) return '';
        if (!$itemRow) return '';
        if (in_array($loop, ['Victims', 'Witnesses'])) {
            return $this->getCivName($loop, $itemRow, $itemInd);
        } elseif ($loop == 'Civilians') {
            if (isset($itemRow->CivID)) return $this->getCivilianNameFromID($itemRow->CivID);
        } elseif (in_array($loop, ['Officers', 'Excellent Officers'])) {
            return $this->getOfficerName($itemRow, $itemInd);
        } elseif ($loop == 'Departments') {
            return $this->getDeptName($itemRow, $itemInd);
        } elseif ($loop == 'Events') {
            if (isset($itemRow->EveID)) return $this->getEventLabel($itemRow->EveID);
        } elseif ($loop == 'Injuries') {
            if (isset($itemRow->InjSubjectID) && isset($itemRow->InjType)) {
                return $this->getCivilianNameFromID($itemRow->InjSubjectID) . ': ' 
                    .  $GLOBALS["SL"]->def->getVal('Injury Types', $itemRow->InjType);
            }
        } elseif ($loop == 'Medical Care') {
            if (isset($itemRow->InjCareSubjectID)) return $this->getCivilianNameFromID($itemRow->InjCareSubjectID);
        } elseif ($loop == 'Citations') { // why isn't this working?!
            if (isset($itemRow->StopEventSequenceID) && intVal($itemRow->StopEventSequenceID) > 0) {
                $eveID = $itemRow->StopEventSequenceID;
                $EveSeq = $this->getEventSequence($eveID);
                if (sizeof($EveSeq[0]["Civilians"]) == 1) {
                    return $this->getCivilianNameFromID($EveSeq[0]["Civilians"][0]);
                }
                $civList = '';
                foreach ($EveSeq[0]["Civilians"] as $civID) $civList .= ', ' . $this->getCivilianNameFromID($civID);
                return substr($civList, 1);
            }
        }
        return '';
    }
    
    protected function printSetLoopNavRowCustom($nID, $loopItem, $setIndex) 
    {
        if (in_array($nID, [143, 917]) && $loopItem) { // $tbl == 'Departments'
            return view('vendor.openpolice.nodes.143-dept-loop-custom-row', [
                "loopItem" => $this->sessData->getChildRow('LinksComplaintDept', $loopItem->getKey(), 'Departments'), 
                "setIndex" => $setIndex, 
                "itemID"   => $loopItem->getKey()
            ])->render();
        }
        return '';
    }
    
    protected function printVehicleMatcher()
    {
        $civNames = $offNames = [];
        if (isset($this->sessData->dataSets["Civilians"]) && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $civ) {
                $civNames[$civ->CivID] = $this->getCivilianNameFromID($civ->CivID);
            }
        }
        if (isset($this->sessData->dataSets["Officers"]) && sizeof($this->sessData->dataSets["Officers"]) > 0) {
            foreach ($this->sessData->dataSets["Officers"] as $off) {
                $offNames[$off->OffID] = $this->getOfficerNameFromID($off->OffID);
            }
        }
        return view('vendor.openpolice.nodes.1436-vehicle-match', [
            "vehicles" => $this->sessData->dataSets["Vehicles"],
            "linksCiv" => ((isset($this->sessData->dataSets["LinksCivilianVehicles"]))
                ? $this->sessData->dataSets["LinksCivilianVehicles"] : []),
            "linksOff" => ((isset($this->sessData->dataSets["LinksOfficerVehicles"]))
                ? $this->sessData->dataSets["LinksOfficerVehicles"] : []),
            "civs"     => $this->sessData->dataSets["Civilians"],
            "offs"     => $this->sessData->dataSets["Officers"],
            "civNames" => $civNames,
            "offNames" => $offNames
        ])->render();
    }
    
    public function printPreviewReport($isAdmin = false)
    {
        if (!isset($this->sessData->dataSets["Complaints"]) || !isset($this->sessData->dataSets["Incidents"])) {
            return '';
        }
        $storyPrev = $this->wordLimitDotDotDot($this->sessData->dataSets["Complaints"][0]->ComSummary, 100);
        $comDate = date('F Y', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeStart));
        if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 304 || $this->v["isAdmin"]) {
            $comDate = date('n/j/Y', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeStart));
        }
        $where = $this->getReportWhereLine();
        $deptList = '';
        $depts = ((isset($this->sessData->dataSets["Departments"])) ? $this->sessData->dataSets["Departments"] : null);
        if ($depts && sizeof($depts) > 0) {
            foreach ($depts as $i => $d) {
                if (isset($d->DeptName)) {
                    $deptList .= ((trim($deptList) != '') ? ', ' : '') 
                        . str_replace('Department', 'Dept', $d->DeptName);
                }
            }
        }
        return view('vendor.openpolice.complaint-report-preview', [
            "storyPrev"   => $storyPrev,
            "complaint"   => $this->sessData->dataSets["Complaints"][0], 
            "incident"    => $this->sessData->dataSets["Incidents"][0], 
            "comDate"     => $comDate, 
            "comWhere"    => ((isset($where[1])) ? $where[1] : ''),
            "allegations" => $this->commaAllegationList(),
            "featureImg"  => '',
            "deptList"    => $deptList
        ])->render();
    }
    
    protected function printValCustom($nID, $val) {
        if (in_array($nID, [1486, 1528])) {
            return $GLOBALS["SL"]->printHeight(intVal($val));
        }
        return $val;
    }
    
    protected function processSearchFilt($key, $val)
    {
        if ($key == 'd') {
            $deptComs = $both = [];
            $chk = OPLinksComplaintDept::where('LnkComDeptDeptID', $val)
                ->get();
            if ($chk->isNotEmpty()) {
                foreach ($chk as $com) $deptComs[] = $com->LnkComDeptComplaintID;
                $chk = OPComplaints::whereIn('ComID', $deptComs)
                    ->get();
                $deptComs = [];
                if ($chk->isNotEmpty()) {
                    foreach ($chk as $com) {
                        if (in_array($com->ComPublicID, $this->allPublicFiltIDs)) {
                            $both[] = $com->ComPublicID;
                        }
                    }
                }
            }
            $this->allPublicFiltIDs = $both;
        }
        return true;
    }
    
    protected function processTokenAccessRedirExtra()
    {
        return '<style> #blockWrap1758, #blockWrap1780 { display: none; } </style>';
    }
    
    protected function loadUpDeetPrivacy($upRow = NULL)
    {
        if ($upRow && isset($upRow->UpPrivacy)) {
            if ($upRow->UpTreeID == 1) {
                if ($GLOBALS["SL"]->x["dataPerms"] == 'public') return 'Block';
                if ($GLOBALS["SL"]->x["pageView"] == 'public') return 'Public';
                if ($upRow->UpPrivacy == 'Private') {
                    if (in_array($GLOBALS["SL"]->x["pageView"], 'sensitive', 'internal')) {
                        return 'Public';
                    } else {
                        return 'Block';
                    }
                }
            }
            return $upRow->UpPrivacy;
        }
        return 'Block';
    }
    

    
    
/*****************************************************************************
// END Processes Which Override Default Behaviors of SetNav LOOPS
*****************************************************************************/
    
    












    
    
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
                if ($allegInfo[1] != '') $this->allegations[] = $allegInfo;
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
            if (sizeof($this->allegations) == 1) return $this->allegations[0][0];
            if (sizeof($this->allegations) == 2) return $this->allegations[0][0] . ' and ' . $this->allegations[1][0];
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
        if (in_array('Valor', $types))           $ret .= ', Valor';
        if (in_array('Lifesaving', $types))      $ret .= ', Lifesaving';
        if (in_array('De-escalation', $types))   $ret .= ', De-escalation';
        if (in_array('Professionalism', $types)) $ret .= ', Professionalism';
        if (in_array('Fairness', $types))        $ret .= ', Fairness';
        if (in_array('Constitutional', $types))  $ret .= ', Constitutional Policing';
        if (in_array('Compassion', $types))      $ret .= ', Compassion';
        if (in_array('Community', $types))       $ret .= ', Community Service';
        if (trim($ret) != '') $ret = trim(substr($ret, 1));
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
                if ($a[1] == $allegName) return $a[0];
            }
        }
        return -3;
    }
    
    protected function getAllegSilvRec($allegName, $allegID = -3)
    {
        if ($allegID <= 0) $allegID = $this->getAllegID($allegName);
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
        if ($allegID <= 0) $allegID = $this->getAllegID($allegName);
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
        if (!$allegRec || !isset($allegRec->AlleDescription)) $allegRec = $this->getAllegSilvRec($allegName, $allegID);
        if ($allegRec && isset($allegRec->AlleDescription)) return trim($allegRec->AlleDescription);
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
                if ($eveSeq->EveID == $eveSeqID) return $eveSeq->EveOrder;
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
        
    protected function getPreviousEveSeqsOfType($eveSeqID)
    {
        $eventType = $this->getEveSeqRowType($eveSeqID);
        $eventRow = $this->sessData->getChildRow('EventSequence', $eveSeqID, $eventType);
        if ($this->isEventAnimalForce($eveSeqID, $eventRow)) return [];
        $prevEvents = [];
        $eveSeqInd = $this->sessData->getLoopIndFromID('Events', $eveSeqID);
        for ($i=($eveSeqInd-1); $i>=0; $i--) {
            if ($eventType == 'Force') {
                $forceEvent = $this->sessData->getChildRow('EventSequence', 
                    $this->sessData->dataSets["EventSequence"][$i]->EveID, 'Force');
                if ($this->getEveSeqRowType($this->sessData->dataSets["EventSequence"][$i]->EveID) == 'Force' 
                    && $eventRow->ForType == $forceEvent->ForType 
                    && $eventRow->ForTypeOther == $forceEvent->ForTypeOther) {
                    $prevEvents[] = $this->sessData->dataSets["EventSequence"][$i]->EveID;
                }
            } elseif ($eventType == $this->getEveSeqRowType($this->sessData->dataSets["EventSequence"][$i]->EveID)) {
                $prevEvents[] = $this->sessData->dataSets["EventSequence"][$i]->EveID;
            }
        }
        return $prevEvents;
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
            if ($civ->CivGivenCitation == 'Y') $this->eventCivLookup["Citations"][] = $civ->CivID;
            if ($civ->CivGivenWarning == 'Y') $this->eventCivLookup["Warnings"][] = $civ->CivID;
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
    
    protected function addNewEveSeq($eventType, $civID = -3, $forceType = -3)
    {
        if ($eventType != 'Force') {
            $civLnk = DB::table('OP_LinksCivilianEvents')
                ->join('OP_EventSequence', 'OP_EventSequence.EveID', '=', 'OP_LinksCivilianEvents.LnkCivEveEveID')
                ->where('OP_EventSequence.EveType', $eventType)
                ->where('OP_LinksCivilianEvents.LnkCivEveCivID', $civID)
                ->select('OP_EventSequence.*')
                ->first();
            if ($civLnk && isset($civLnk->EveID)) return $civLnk;
        }
        $newEveSeq = new OPEventSequence;
        $newEveSeq->EveComplaintID = $this->coreID;
        $newEveSeq->EveType = $eventType;
        $newEveSeq->EveOrder = (1+$this->getLastEveSeqOrd());
        $newEveSeq->save();
        eval("\$newEvent = new App\\Models\\" . $GLOBALS["SL"]->tblModels[$eventType] . ";");
        $newEvent->{ $GLOBALS["SL"]->tblAbbr[$eventType].'EventSequenceID' } = $newEveSeq->getKey();
        if ($eventType == 'Force' && $forceType > 0) $newEvent->ForType = $forceType;
        $newEvent->save();
        if ($civID > 0) {
            $civLnk = new OPLinksCivilianEvents;
            $civLnk->LnkCivEveEveID = $newEveSeq->getKey();
            $civLnk->LnkCivEveCivID = $civID;
            $civLnk->save();
        }
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
        if ($civLnk && isset($civLnk->EveID)) return $civLnk->EveID;
        return -3;
    }
    
    protected function getCivForceEventID($nID, $civID, $forceType)
    {
        $civLnk = DB::table('OP_LinksCivilianEvents')
            ->join('OP_EventSequence', 'OP_EventSequence.EveID', '=', 'OP_LinksCivilianEvents.LnkCivEveEveID')
            ->join('OP_Force', 'OP_Force.ForEventSequenceID', '=', 'OP_EventSequence.EveID')
            ->where('OP_EventSequence.EveType', 'Force')
            ->where('OP_LinksCivilianEvents.LnkCivEveCivID', $civID)
            ->where('OP_Force.ForType', $forceType)
            ->select('OP_EventSequence.*')
            ->first();
        if ($civLnk && isset($civLnk->EveID)) return $civLnk->EveID;
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
        if (!isset($this->sessData->dataSets["Force"])) $this->sessData->dataSets["Force"] = [];
        $this->sessData->dataSets["Force"][] = $frc;
        if (!isset($this->sessData->dataSets["EventSequence"])) $this->sessData->dataSets["EventSequence"] = [];
        $this->sessData->dataSets["EventSequence"][] = $eve;
        return $eve;
    }
    
    protected function delCivEvent($nID, $eveType, $civID)
    {
        return $this->deleteEventByID($nID, $this->getCivEventID($nID, $eveType, $civID));
    }
    
    protected function deleteEventByID($nID, $eveSeqID)
    {
        if ($eveSeqID > 0) {
            OPEventSequence::find($eveSeqID)->delete();
            OPStops::where('StopEventSequenceID', $eveSeqID)->delete();
            OPSearches::where('SrchEventSequenceID', $eveSeqID)->delete();
            OPArrests::where('ArstEventSequenceID', $eveSeqID)->delete();
            OPForce::where('ForEventSequenceID', $eveSeqID)->delete();
            OPLinksCivilianEvents::where('LnkCivEveEveID', $eveSeqID)->delete();
            OPLinksOfficerEvents::where('LnkOffEveEveID', $eveSeqID)->delete();
        }
        return true;
    }
    
    public function initGoldEventMult($nID, $eveType)
    {
        $this->checkHasEventSeq($nID);
        if (sizeof($this->eventCivLookup[$eveType]) > 0) {
            foreach ($this->eventCivLookup[$eveType] as $civ) {
                if (!in_array($civ, $GLOBALS["SL"]->REQ->input('n' . $nID . 'fld'))) {
                    $this->delCivEvent($nID, $eveType, $civ);
                }
            }
        }
        if ($GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') && is_array($GLOBALS["SL"]->REQ->input('n' . $nID . 'fld'))
            && sizeof($GLOBALS["SL"]->REQ->input('n' . $nID . 'fld')) > 0) {
            foreach ($GLOBALS["SL"]->REQ->input('n' . $nID . 'fld') as $civ) {
                if (!in_array($civ, $this->eventCivLookup[$eveType])) {
                    $this->addNewEveSeq($eveType, $civ);
                }
            }
        }
        return true;
    }
    
    public function initGoldEventSingle($nID, $eveType)
    {
        $this->checkHasEventSeq($nID);
        $civ = $this->sessData->loopItemIDs["Victims"][0];
        if ($GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') && is_array($GLOBALS["SL"]->REQ->input('n' . $nID . 'fld')) 
            && sizeof($GLOBALS["SL"]->REQ->input('n' . $nID . 'fld')) > 0
            && trim($GLOBALS["SL"]->REQ->input('n' . $nID . 'fld')[0]) == 'Y') {
            if (!in_array($civ, $this->eventCivLookup[$eveType])) {
                $this->addNewEveSeq($eveType, $civ);
            }
        } else {
            $this->delCivEvent($nID, $eveType, $civ);
        }
        return true;
    }
    
    protected function processEventMerge($nID)
    {
        /* needs update!
        $eventType = $this->getEveSeqTypeFromNode($nID);
        if ($GLOBALS["SL"]->REQ->has('eventMerge') && intVal($GLOBALS["SL"]->REQ->eventMerge) > 0) {
            $civs = $this->getLinkedToEvent('Civilian', $GLOBALS["SL"]->closestLoop["itemID"]);
            if (sizeof($civs) > 0) { 
                foreach ($civs as $civID) {
                    $this->savePeopleEventLink($this->saveDataNewRow('PeopleEventLinks', '+'), -3, $civID, -3, 
                        intVal($GLOBALS["SL"]->REQ->eventMerge));
                }
            }
            $this->deleteEventPeopleLinks($nID, $GLOBALS["SL"]->closestLoop["itemID"]);
            $this->sessData->deleteDataItem($nID, $eventType, $this->subsetChildRow(true));
            $this->sessData->deleteDataItem($nID, 'EventSequence', $GLOBALS["SL"]->closestLoop["itemID"]);
            $GLOBALS["SL"]->closestLoop["itemID"] = -3;
            $this->sessInfo->save();
            $GLOBALS["SL"]->REQ->jumpTo = 148;
            //echo '<script type="text/javascript"> setTimeout("window.location=\'/\'", 5); </script>';
            //exit;
        }
        */
        return true;
    }
    
    protected function processHandcuffInjury($nID)
    {
        $handcuffDefID = $GLOBALS["SL"]->def->getID('Injury Types', 'Handcuff Injury');
        $stopRow = $this->getEventSequence($GLOBALS["SL"]->closestLoop["itemID"]);
        if ($GLOBALS["SL"]->REQ->has('n316fld') && trim($GLOBALS["SL"]->REQ->n316fld) == 'Y') {
            if (intVal($stopRow[0]["Event"]->StopSubjectHandcuffInjury) <= 0) {
                $newInj = new OPInjuries;
                $newInj->InjType = $handcuffDefID;
                $newInj->InjSubjectID = ((isset($stopRow[0]["Civilians"][0])) ? $stopRow[0]["Civilians"][0] : -3);
                $newInj->save();
                $this->sessData->dataSets["Injuries"]["Handcuff"][] = $newInj;
                OPStops::find($stopRow[0]["Event"]->StopID)
                    ->update(array('StopSubjectHandcuffInjury' => $newInj->InjID));
            }
        } elseif (intVal($stopRow[0]["Event"]->StopSubjectHandcuffInjury) > 0) {
            OPStops::find($stopRow[0]["Event"]->StopID)->update(array('StopSubjectHandcuffInjury' => NULL));
            $this->sessData->deleteDataItem($nID, 'Injuries', $stopRow[0]["Event"]->StopSubjectHandcuffInjury);
        }
        return false;
    }
    
/*****************************************************************************
// END Processes Which Handle The Event Sequence
*****************************************************************************/
    



/*****************************************************************************
// START Processes Which Print Chunks of The Report
*****************************************************************************/

    protected function reportAllegsWhyDeets($nID = -3)
    {
        $deets = [];
        if (isset($this->sessData->dataSets["AllegSilver"]) && $this->sessData->dataSets["AllegSilver"][0]
            && isset($this->sessData->dataSets["Allegations"]) && sizeof($this->sessData->dataSets["Allegations"]) > 0){
            foreach ($this->worstAllegations as $i => $alleg) {
                if (isset($this->sessData->dataSets["AllegSilver"][0]->{ $alleg[2] })
                    && trim($this->sessData->dataSets["AllegSilver"][0]->{ $alleg[2] }) == 'Y') {
                    $alle = '';
                    if ($GLOBALS["SL"]->x["dataPerms"] != 'public') {
                        $foundWhy = false;
                        $alle .= '<b class="fPerc125">' . $alleg[1] . '</b><br />';
                        foreach ($this->sessData->dataSets["Allegations"] as $j => $all) {
                            if (!$foundWhy && $all && isset($all->AlleType) && $all->AlleType == $alleg[0]
                                && trim($all->AlleDescription) != '') {
                                $alle .= $all->AlleDescription . '<br />';
                                $foundWhy = true;
                            }
                        }
                    } else {
                        $alle .= '<b class="fPerc125">' . $alleg[1] . '</b>';
                    }
                    $deets[] = [$alle];
                }
            }
        }
        return $deets;
    }

    protected function reportAllegsWhy($nID = -3)
    {
        return $this->printReportDeetsBlock($this->reportAllegsWhyDeets($nID), 
            'Allegations<div class="pT5 f12 slGrey">Including comments from the complainant</span> ');
    }

    protected function reportCivAddy($nID)
    {
        if ($nID > 0 && isset($this->allNodes[$nID]) && $this->checkFldDataPerms($this->allNodes[$nID]->getFldRow()) 
            && $this->checkViewDataPerms($this->allNodes[$nID]->getFldRow())) {
            $addy = $GLOBALS["SL"]->printRowAddy($this->sessData->getLatestDataBranchRow(), 'Prsn');
            if (trim($addy) != '') return [ 'Address', $addy ];
        }
        return [];
    }

    protected function reportStory($nID)
    {
        $ret = '';
        if ($nID > 0 && isset($this->allNodes[$nID]) && $this->checkFldDataPerms($this->allNodes[$nID]->getFldRow()) 
            && $this->checkViewDataPerms($this->allNodes[$nID]->getFldRow())) {
            if (!in_array($GLOBALS["SL"]->x["pageView"], ['pdf', 'full-pdf'])) {
                $previewMax = 1800;
                if (strlen($this->sessData->dataSets["Complaints"][0]->ComSummary) > $previewMax) {
                    $brkPos = strpos($this->sessData->dataSets["Complaints"][0]->ComSummary, ' ', $previewMax);
                    if ($brkPos > 0) {
                        $ret = '<div id="hidivStoryLess" class="disBlo">' . str_replace("\n", '<br />', 
                            substr($this->sessData->dataSets["Complaints"][0]->ComSummary, 0, $brkPos+1)) . ' ...<br />'
                            . '<a id="hidivBtnStoryMore" class="btn btn-primary mT20" href="javascript:;">Read More</a>'
                            . '</div>'
                            . '<div id="hidivStoryMore" class="disNon">' . str_replace("\n", '<br />', 
                            $this->sessData->dataSets["Complaints"][0]->ComSummary, $brkPos) 
                            . '<br /><a id="hidivBtnStryLessBtn" class="btn btn-primary mT20" href="javascript:;">'
                            . 'Read Less</a></div>';
                        $GLOBALS["SL"]->pageAJAX .= '$(document).on("click", "#hidivBtnStoryMore", function() { '
                            . 'document.getElementById("hidivStoryMore").style.display="block"; setTimeout(function() { '
                            . 'document.getElementById("hidivStoryLess").style.display="none"; }, 5); });'
                            . '$(document).on("click", "#hidivBtnStryLessBtn", function() { '
                            . 'document.getElementById("hidivStoryLess").style.display="block"; setTimeout(function() { '
                            . 'document.getElementById("hidivStoryMore").style.display="none"; }, 5); });';
                    }
                }
            }
            if (trim($ret) == '') {
                $ret = str_replace("\n", '<br />', $this->sessData->dataSets["Complaints"][0]->ComSummary);
            }
        }
        return '<h3 class="slBlueDark mT0">Story</h3><p>' . $ret . '</p>';
        
    }
    
    protected function queuePeopleSubsets($id, $type = 'Civilians')
    {
        $prsn = $this->sessData->getChildRow($type, $id, 'PersonContact');
        $phys = $this->sessData->getChildRow($type, $id, 'PhysicalDesc');
        return [$prsn, $phys];
    }
    
    protected function chkGetReportDept($overLnkID)
    {
        if (!isset($this->v["reportDepts"])) $this->v["reportDepts"] = [];
        $overLnk = $this->sessData->getRowById('LinksComplaintOversight', $overLnkID);
        if ($overLnk && isset($overLnk->LnkComOverDeptID) && intVal($overLnk->LnkComOverDeptID) > 0
            && !in_array($overLnk->LnkComOverDeptID, $this->v["reportDepts"])) {
            $this->v["reportDepts"][] = $overLnk->LnkComOverDeptID;
            return '';
        }
        return '<!-- skipping overLnk #' . $overLnkID . ' -->';
    }
    
    protected function getReportDept($deptID)
    {
        $dept = $this->sessData->getRowById('Departments', $deptID);
        if ($dept && isset($dept->DeptName)) {
            return '<h3 class="mT0 mB5"><a href="/dept/' . $dept->DeptSlug . '" class="slBlueDark">' 
                 . $dept->DeptName . '</a></h3><div class="mB10">Complaint #' 
                . $this->sessData->dataSets["Complaints"][0]->ComPublicID . ': <b>' 
                . $GLOBALS["SL"]->def->getVal('Complaint Status', 
                    $this->sessData->dataSets["Complaints"][0]->ComStatus) 
                . '</b></div>';
        }
        $this->v["reportDepts"][] = $deptID;
        return '';
    }
    
    protected function getReportByLine()
    {
        $ret = '';
        if ($this->sessData->dataSets['Complaints'][0]->ComPrivacy 
            == $GLOBALS["SL"]->def->getID('Privacy Types', 'Completely Anonymous')) {
            $ret = 'Anonymous';
        } elseif (isset($this->sessData->dataSets["Civilians"]) 
            && isset($this->sessData->dataSets["Civilians"][0]->CivID) 
            && ($GLOBALS["SL"]->x["pageView"] == 'full'
            || ($this->isPublished('Complaints', $this->coreID, $this->sessData->dataSets["Complaints"][0])
                && $this->sessData->dataSets['Complaints'][0]->ComPrivacy 
                == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')))) {
            $ret = $this->getCivReportName($this->sessData->dataSets["Civilians"][0]->CivID);
        }
        if (trim($ret) != '') return ['Submitted By', $ret];
        return [];
    }
    
    protected function getReportWhenLine()
    {
        $date = '';
        if ($this->v["isOwner"] || $this->v["isAdmin"] || ($GLOBALS["SL"]->x["pageView"] != 'public' 
            && $this->sessData->dataSets['Complaints'][0]->ComPrivacy
            == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly'))) {
            $date = date('n/j/Y', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeStart));
            if ($this->sessData->dataSets["Incidents"][0]->IncTimeStart !== null) {
                $date .= ' at ' . date('g:ia', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeStart));
            }
            if ($this->sessData->dataSets["Incidents"][0]->IncTimeEnd !== null) {
                $date .= ' until ' . date('g:ia', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeEnd));
            }
        } else {
            $date = date('F Y', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeStart));
        }
        return ['When', $date];
    }
    
    protected function chkPrintWhereLine($nID = -3)
    {
        $show = false;
        if ($nID > 0 && isset($this->allNodes[$nID]) && $this->checkFldDataPerms($this->allNodes[$nID]->getFldRow()) 
            && $this->checkViewDataPerms($this->allNodes[$nID]->getFldRow())) {
            if ($GLOBALS["SL"]->x["pageView"] == 'full') {
                $show = true;
            } elseif (isset($this->sessData->dataSets["Incidents"][0]->IncPublic) 
                && $this->sessData->dataSets["Incidents"][0]->IncPublic == 'Y'
                && $this->isPublished('Complaints', $this->coreID, $this->sessData->dataSets["Complaints"][0])) {
                $show = true;
            }
        }
        return $show;
    }
    
    protected function getReportWhereLine($nID = -3)
    {
        $addy = $GLOBALS["SL"]->printRowAddy($this->sessData->dataSets["Incidents"][0], 'Inc');
        if ($this->chkPrintWhereLine($nID) && trim($addy) != '') {
            return ['Where', $addy];
        }
        if (isset($this->sessData->dataSets["Incidents"][0]->IncAddressState)) {
            $c = '';
            $state = $this->sessData->dataSets["Incidents"][0]->IncAddressState;
            if (isset($this->sessData->dataSets["Incidents"][0]->IncAddressZip)) {
                $c = $GLOBALS["SL"]->getZipProperty($this->sessData->dataSets["Incidents"][0]->IncAddressZip, 'County');
            } elseif (isset($this->sessData->dataSets["Incidents"][0]->IncAddressCity)) {
                $c = $GLOBALS["SL"]->getCityCounty($this->sessData->dataSets["Incidents"][0]->IncAddressZip, $state);
            }
            if (trim($c) != '') return ['Where', $GLOBALS["SL"]->allCapsToUp1stChars($c) . ' County, ' . $state];
        }
        return [];
    }
    
    protected function getReportPrivacy($nID)
    {
        switch ($this->sessData->dataSets["Complaints"][0]->ComPrivacy) {
            case $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly'): 
                return 'Full Transparency';
            case $GLOBALS["SL"]->def->getID('Privacy Types', 'Names Visible to Police but not Public'): 
                return 'No Names Public';
            case $GLOBALS["SL"]->def->getID('Privacy Types', 'Completely Anonymous'): 
                return 'Anonymous';
        }
        return '';
    }
    
    protected function getCivReportNameHeader($nID)
    {
        return '<h3 class="slBlueDark" style="margin: 0px 0px 18px 0px;">' 
            . $this->getCivReportName($this->sessData->getLatestDataBranchID()) . '</h3>';
    }
    
    protected function getCivReportName($civID, $ind = 0, $type = 'Subject', $prsn = NULL)
    {
        if (!isset($this->v["civNames"])) $this->v["civNames"] = [];
        if (!isset($this->v["civNames"][$civID]) || $this->v["civNames"][$civID] == '') {
            if (!$prsn) list($prsn, $phys) = $this->queuePeopleSubsets($civID);
            $name = '';
            if ($GLOBALS["SL"]->x["pageView"] != 'public') {
                if ( $civID == $this->sessData->dataSets["Civilians"][0]->CivID 
                    && (trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) == ''
                    || $this->sessData->dataSets["Complaints"][0]->ComPrivacy == 306) ) {
                    $name = $this->getNameTopAnon();
                } elseif (trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) != '' 
                    && ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 304 
                    || $GLOBALS["SL"]->x["pageView"] == 'full')) {
                    if (trim($prsn->PrsnNickname) != '') {
                        $name = trim($prsn->PrsnNickname);
                    } else {
                        $name = '<span style="color: #2b3493;" title="This complainant wanted to publicly provide their'
                            . ' name.">' . $prsn->PrsnNameFirst . ' ' . $prsn->PrsnNameLast 
                            . '</span>'; // ' . $prsn->PrsnNameMiddle . ' 
                    }
                }
            }
            if ($type == 'Subject' && $this->sessData->dataSets["Civilians"][0]->CivID != $civID
                && $this->sessData->dataSets["Civilians"][0]["CivRole"] == 'Victim') $ind++;
            if ($type == 'Witness' && $this->sessData->dataSets["Civilians"][0]->CivID != $civID
                && $this->sessData->dataSets["Civilians"][0]["CivRole"] == 'Witness') $ind++;
            $this->v["civNames"][$civID] = $type . ' #' . (1+$ind) . ((trim($name) != '') ? ': ' . $name : '');
        }
        return $this->v["civNames"][$civID];
    }
    
    protected function getOffReportNameHeader($nID)
    {
        list($itemInd, $itemID) = $this->sessData->currSessDataPosBranchOnly('Officers');
        return '<h3 class="slBlueDark" style="margin: 0px 0px 18px 0px;">' 
            . $this->getOffReportName($this->sessData->getRowById('Officers', $this->sessData->getLatestDataBranchID()),
                $itemInd)
            . '</h3>';
    }
    
    protected function getOffReportName($off, $ind = 0, $prsn = NULL)
    {
        if (!isset($this->v["offNames"])) $this->v["offNames"] = [];
        if ($off && isset($off->OffID)) {
            if (sizeof($this->v["offNames"]) == 0 || !isset($this->v["offNames"][$off->OffID]) 
                || trim($this->v["offNames"][$off->OffID]) == '') {
                if (!$prsn) list($prsn, $phys) = $this->queuePeopleSubsets($off->OffID, 'Officers');
                $name = ' ';
                if ($GLOBALS["SL"]->x["pageView"] != 'public') {
                    if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 304 
                        || $GLOBALS["SL"]->x["pageView"] == 'full') {
                        if (trim($prsn->PrsnNickname) != '') {
                            $name = trim($prsn->PrsnNickname);
                        } else {
                            $name = trim($prsn->PrsnNameFirst . ' ' . $prsn->PrsnNameMiddle . ' ' . $prsn->PrsnNameLast);
                            if (trim($name) == '' && trim($off->OffBadgeNumber) != '' 
                                && trim($off->OffBadgeNumber) != '0') {
                                $name = 'Badge #' . $off->OffBadgeNumber;
                            }
                        }
                    }
                }
                $this->v["offNames"][$off->OffID] = 'Officer #' . (1+$ind) . ((trim($name) != '') ? ': ' . $name : '');
            }
            return $this->v["offNames"][$off->OffID];
        }
        return '';
    }
    
    protected function getCivSnstvFldsNotPrinted($civID)
    {
        $info = '';
        $prsn = $this->sessData->getChildRow('Civilians', $civID, 'PersonContact');
        if ((((isset($prsn->PrsnNameFirst) && trim($prsn->PrsnNameFirst) != '') 
            || (isset($prsn->PrsnNameLast) && $prsn->PrsnNameLast != '')) ? ', Name' : '')
            && $this->sessData->dataSets["Complaints"][0]->ComPrivacy != 304) {
            $info .= ', Name';
        }
        if (isset($prsn->PrsnAddress) && trim($prsn->PrsnAddress) != '')   $info .= ', Address';
        if (isset($prsn->PrsnPhoneHome) && trim($prsn->PrsnPhoneHome) != '') $info .= ', Phone Number'; 
        if (isset($prsn->PrsnEmail) && trim($prsn->PrsnEmail) != '')     $info .= ', Email'; 
        if (isset($prsn->PrsnFacebook) && trim($prsn->PrsnFacebook) != '')  $info .= ', Facebook';
        if (isset($prsn->PrsnBirthday) && trim($prsn->PrsnBirthday) != '' && trim($prsn->PrsnBirthday) != '0000-00-00' 
            && trim($prsn->PrsnBirthday) != '1970-01-01') {
            $info .= ', Birthday';
        }
        if (($civID != $this->sessData->dataSets["Civilians"][0]->CivID 
            || $this->sessData->dataSets["Complaints"][0]->ComPrivacy != 306) && trim($info) != '') {
            return '<i class="slGrey">Not public: ' . substr($info, 1) . '</i>';
        }
        return '';
    }
    
    protected function getOffSnstvFldsNotPrinted($offID)
    {
        $off = $this->sessData->getRowById('Officers', $offID);
        $prsn = $this->sessData->getChildRow('Officers', $offID, 'PersonContact');
        $info = (((isset($prsn->PrsnNameFirst) && trim($prsn->PrsnNameFirst) != '') 
            || (isset($prsn->PrsnNameLast) && $prsn->PrsnNameLast != '')) ? ', Name' : '')
            . ((isset($off->OffBadgeNumber) && intVal($off->OffBadgeNumber) > 0) ? ', Badge Number' : '');
        if (trim($info) != '') return '<i class="slGrey">Not public: ' . substr($info, 1) . '</i>';
        return '';
    }
    
    protected function getOffProfan()
    {
        $cnt = 0;
        $profanity = '';
        if (isset($this->sessData->dataSets["Officers"]) && sizeof($this->sessData->dataSets["Officers"]) > 0) {
            foreach ($this->sessData->dataSets["Officers"] as $i => $off) {
                if ($off->OffUsedProfanity == 'Y') {
                    $cnt++;
                    $profanity .= ', ' . $this->getOffReportName($off);
                }
            }
        }
        if (trim($profanity) != '') {
            return ['Officer' . (($cnt > 1) ? 's' : '') . ' used profanity?', substr($profanity, 1)];
        }
        return [];
    }
    
    protected function getCivProfan()
    {
        $cnt = 0;
        $profanity = '';
        if (isset($this->sessData->dataSets["Civilians"]) && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if ($civ->CivUsedProfanity == 'Y') {
                    $cnt++;
                    $profanity .= ', ' . $this->getCivReportName($civ->getKey());
                }
            }
        }
        if (trim($profanity) != '') {
            return ['Civilian' . (($cnt > 1) ? 's' : '') . ' used profanity?', substr($profanity, 1)];
        }
        return [];
    }
    
    protected function reportEventTitle($eveID)
    {
        $h3 = '<h3 class="slBlueDark mT0 mB20">';
        switch ($this->getEveSeqRowType($eveID)) {
            case 'Stops':    return $h3 . 'Stop</h3>';
            case 'Searches': return $h3 . 'Search</h3>';
            case 'Force':    return $h3 . 'Use of Force</h3>';
            case 'Arrests':  return $h3 . 'Arrest</h3>';
        }
        return '';
    }
    
    protected function printReportShare()
    {
        return view('vendor.openpolice.nodes.1710-report-inc-share', [
            "pubID"     => $this->sessData->dataSets["Complaints"][0]->ComPublicID,
            "emojiTags" => $this->printEmojiTags(),
            "published" => $this->isPublished('Complaints', $this->coreID, $this->sessData->dataSets["Complaints"][0]),
            "viewPrfx"  => (($GLOBALS["SL"]->x["pageView"] == 'full') ? 'full-' : '')
            ])->render();
    }
    
    protected function fillGlossary()
    {
        $this->v["glossaryList"] = [];
        $prvLnk = '<a href="/complaint-privacy-options" target="_blank">Privacy Setting</a>: ';
        if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy 
            == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')) {
            $this->v["glossaryList"][] = ['<b>Full Transparency</b>', 
                $prvLnk . 'User opts to publish the names of civilians and police officers on this website.'];
        } elseif ($this->sessData->dataSets["Complaints"][0]->ComPrivacy 
            == $GLOBALS["SL"]->def->getID('Privacy Types', 'Names Visible to Police but not Public')) {
            $this->v["glossaryList"][] = ['<b>No Names Public</b>', 
                $prvLnk . 'User doesn\'t want to publish any names on this website. 
                This includes police officers\' names and badge numbers too.'];
        } elseif ($this->sessData->dataSets["Complaints"][0]->ComPrivacy 
            == $GLOBALS["SL"]->def->getID('Privacy Types', 'Completely Anonymous')) {
            $this->v["glossaryList"][] = ['<b>Anonymous</b> ', 
                $prvLnk . 'User needs complaint to be completely anonymous, even though it will be harder to '
                    . 'investigate. No names will be published on this website. Neither OPC staff nor investigators '
                    . 'will be able to contact them. Any details that could be used for personal identification '
                    . 'may be deleted from the database.'];
        }
        if ($this->sessData->dataSets["Complaints"][0]->ComAwardMedallion == 'Gold') {
            $this->v["glossaryList"][] = ['<b>Gold-Level Complaint</b>', 
                '<a href="/frequently-asked-questions#what-is-gold-star">Optional</a>: This user opted '
                    . 'to share more complete details about their police experience than a Basic Complaint.'];
        }
        $this->simpleAllegationList();
        if (sizeof($this->allegations) > 0) {
            foreach ($this->allegations as $i => $a) {
                $this->v["glossaryList"][] = [
                    '<b>' . $a[0] . '</b>', 
                    '<a href="/allegations" target="_blank">Allegation</a>: ' 
                        . $GLOBALS["SL"]->def->getDesc('Allegation Type', $a[0])
                ];
            }
        }
        return true;
    }
    
    protected function printFlexArts()
    {
        $this->loadRelatedArticles();
        return view('vendor.openpolice.nodes.1708-report-flex-articles', [
            "allUrls" => $this->v["allUrls"] ])->render();
    }
    
    protected function printFlexVids()
    {
        $this->loadRelatedArticles();
        return view('vendor.openpolice.nodes.1753-report-flex-videos', [
            "allUrls" => $this->v["allUrls"] ])->render();
    }
    
    protected function printComplaintOversight()
    {
        $overRow = OPOversight::where('OverEmail', $this->v["user"]->email)
            ->first();
        if ($this->chkOverUserHasCore()) {
            if ($GLOBALS["SL"]->REQ->has('overUpdate') && intVal($GLOBALS["SL"]->REQ->get('overUpdate')) == 1
                && $overRow && isset($overRow->OverDeptID)) {
                $overUpdateRow = $this->getOverUpdateRow($this->coreID, $overRow->OverID);
                $newReview = new OPzComplaintReviews;
                $newReview->ComRevComplaint = $this->coreID;
                $newReview->ComRevUser      = $this->v["user"]->id;
                $newReview->ComRevDate      = date("Y-m-d H:i:s");
                $newReview->ComRevType      = 'Oversight';
                $newReview->ComRevNote      = (($GLOBALS["SL"]->REQ->has('overNote')) 
                    ? trim($GLOBALS["SL"]->REQ->overNote) : '');
                if ($GLOBALS["SL"]->REQ->has('overStatus')) { 
                    if ($GLOBALS["SL"]->REQ->overStatus == 'Received by Oversight') {
                        $this->logOverUpDate($this->coreID, $overRow->OverID, 'Received', $overUpdateRow);
                    } elseif ($GLOBALS["SL"]->REQ->overStatus == 'Investigated (Closed)') {
                        $this->logOverUpDate($this->coreID, $overRow->OverID, 'Investigated', $overUpdateRow);
                    }
                    $statusID = $GLOBALS["SL"]->def->getID('Complaint Status', trim($GLOBALS["SL"]->REQ->overStatus));
                    $this->sessData->dataSets["Complaints"][0]->update([ "ComStatus" => $statusID ]);
                    $newReview->ComRevStatus = $GLOBALS["SL"]->REQ->overStatus;
                }
                $newReview->save();
            } elseif ($GLOBALS["SL"]->REQ->has('upResult') && intVal($GLOBALS["SL"]->REQ->get('upResult')) == 1) {
                
            }
        }
        return view('vendor.openpolice.nodes.1711-report-inc-oversight-tools', [
            "user"        => $this->v["user"],
            "complaint"   => $this->sessData->dataSets["Complaints"][0],
            "overRow"     => $overRow
            ])->render();
    }
    
    protected function printComplaintOwner()
    {
        if ($this->v["isOwner"] && $GLOBALS["SL"]->REQ->has('ownerUpdate') 
            && intVal($GLOBALS["SL"]->REQ->get('ownerUpdate')) == 1 && isset($this->sessData->dataSets["Oversight"])) {
            $overID = $this->sessData->dataSets["Oversight"][0]->OverID;
            if (isset($this->sessData->dataSets["Oversight"][1]) 
                && $this->sessData->dataSets["Oversight"][1]->OverType == 303) {
                $overID = $this->sessData->dataSets["Oversight"][1]->OverID;
            }
            $overUpdateRow = $this->getOverUpdateRow($this->coreID, $overID);
        
            $newReview = new OPzComplaintReviews;
            $newReview->ComRevComplaint  = $this->coreID;
            $newReview->ComRevUser       = $this->v["user"]->id;
            $newReview->ComRevDate       = date("Y-m-d H:i:s");
            $newReview->ComRevType       = 'Owner';
            $newReview->ComRevNote       = (($GLOBALS["SL"]->REQ->has('overNote')) 
                ? trim($GLOBALS["SL"]->REQ->overNote) : '');
            $newReview->ComRevStatus     = $GLOBALS["SL"]->REQ->overStatus;
            $newReview->save();
            if ($GLOBALS["SL"]->REQ->has('overStatus')) {
                if (trim($GLOBALS["SL"]->REQ->overStatus) == 'Received by Oversight') {
                    $this->logOverUpDate($this->coreID, $overID, 'Received', $overUpdateRow);
                    if ($this->sessData->dataSets["Complaints"][0]->ComStatus 
                        == $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight')) {
                        $this->sessData->dataSets["Complaints"][0]->update([ 
                            "ComStatus" => $GLOBALS["SL"]->def->getID('Complaint Status', 
                                'Submitted to Oversight') ]);
                    }
                } else {
                    if ($GLOBALS["SL"]->REQ->overStatus == 'Investigated (Closed)') {
                        $this->logOverUpDate($this->coreID, $overID, 'Investigated', $overUpdateRow);
                    } elseif (in_array($GLOBALS["SL"]->REQ->overStatus, [
                        'Submitted to Oversight', 'OK to Submit to Oversight'])) {
                        if (isset($overUpdateRow->LnkComOverReceived) && $overUpdateRow->LnkComOverReceived != '') {
                            $overUpdateRow->LnkComOverReceived = NULL;
                            $overUpdateRow->save();
                        }
                    }
                    $this->sessData->dataSets["Complaints"][0]->update([ 
                        "ComStatus" => $GLOBALS["SL"]->def->getID('Complaint Status', 
                            $GLOBALS["SL"]->REQ->overStatus) ]);
                }
            }
        }
        return view('vendor.openpolice.nodes.1714-report-inc-owner-tools', [
            "user"        => $this->v["user"],
            "complaint"   => $this->sessData->dataSets["Complaints"][0],
            "depts"       => ((isset($this->sessData->dataSets["Departments"])) 
                ? $this->sessData->dataSets["Departments"] : []),
            "oversigts"   => ((isset($this->sessData->dataSets["Oversight"]))
                ? $this->sessData->dataSets["Oversight"] : []),
            "overUpdates" => ((isset($this->sessData->dataSets["LinksComplaintOversight"]))
                ? $this->sessData->dataSets["LinksComplaintOversight"] : []),
            "overList"    => $this->oversightList(),
            ])->render();
    }
    
    protected function printMfaInstruct()
    {
        if (isset($this->v["tokenUser"]) && $this->v["tokenUser"]) {
            return view('vendor.openpolice.nodes.1780-mfa-instructions', [
                "user" => $this->v["tokenUser"],
                "mfa"  => $this->processTokenAccess(false)
                ])->render();
        }
        return '';
    }
    
    
    
/*****************************************************************************
// END Processes Which Print Chunks of The Report
*****************************************************************************/




    
    
/*****************************************************************************
// START Processes Which Handle People/Officer Linkages
*****************************************************************************/

    protected function getLinkedToEvent($Ptype = 'Officer', $eveSeqID = -3)
    {
        $retArr = [];
        if ($eveSeqID > 0) {
            if ($Ptype == 'Civilian') {
                if (isset($this->sessData->dataSets["LinksCivilianEvents"]) 
                    && sizeof($this->sessData->dataSets["LinksCivilianEvents"]) > 0) {
                    foreach ($this->sessData->dataSets["LinksCivilianEvents"] as $PELnk) {
                        if ($PELnk->LnkCivEveEveID == $eveSeqID) $retArr[] = $PELnk->LnkCivEveCivID;
                    }
                }
            } elseif ($Ptype == 'Officer') {
                if (isset($this->sessData->dataSets["LinksOfficerEvents"]) 
                    && sizeof($this->sessData->dataSets["LinksOfficerEvents"]) > 0) {
                    foreach ($this->sessData->dataSets["LinksOfficerEvents"] as $PELnk) {
                        if ($PELnk->LnkOffEveEveID == $eveSeqID) $retArr[] = $PELnk->LnkOffEveOffID;
                    }
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
                if ($eveSeq->EveType == $eventType) {
                    if (in_array($civID, $this->getLinkedToEvent('Civilian', $eveSeq->EveID))) {
                        return $eveSeq->EveID;
                    }
                }
            }
        }
        return -3;
    }
    
    protected function getEveSeqRowType($eveSeqID = -3)
    {
        $eveSeq = $this->sessData->getRowById('EventSequence', $eveSeqID);
        if ($eveSeq) return $eveSeq->EveType;
        return '';
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
    
    protected function getEventSequence($eveSeqID = -3)
    {
        $eveSeqs = [];
        $allEvents = $this->sessData->getLoopRows('Events');
        if (sizeof($allEvents) > 0) {
            foreach ($allEvents as $eveSeq) {
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
        if (!isset($eveSeq["EveType"]) && is_array($eveSeq) && sizeof($eveSeq) > 0) $eveSeq = $eveSeq[0];
        if (!is_array($eveSeq) || !isset($eveSeq["EveType"])) return '';
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
    
/*****************************************************************************
// END Processes Which Handle People/Officer Linkages
*****************************************************************************/




/*****************************************************************************
// START Processes Which Manage Lists of People
*****************************************************************************/

    protected function getGenderLabel($physDesc)
    {
        if ($physDesc->PhysGender == 'F') {
            return 'Female';
        } elseif ($physDesc->PhysGender == 'M') {
            return 'Male';
        } elseif ($physDesc->PhysGender == 'O') {
            if (isset($physDesc->PhysGenderOther) && trim($physDesc->PhysGenderOther) != '') {
                return $physDesc->PhysGenderOther;
            }
        }
        return '';
    }

    protected function shortRaceName($raceDefID)
    {
        $race = $GLOBALS["SL"]->def->getVal('Races', $raceDefID);
        if (in_array($race, ['Other', 'Decline or Unknown'])) return '';
        return str_replace('Asian American',         'Asian', 
            str_replace('Black or African American', 'Black', 
            str_replace('Hispanic or Latino',        'Hispanic', 
            $race)));
    }
    
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
            } else {
                $civ2 = $this->sessData->getChildRow($type, $id, 'PhysicalDesc');
                if ($civ2) {
                    if (trim($civ2->PhysGender) != '') $name = $this->getGenderLabel($civ2) . ' ' . $name;
                    if (intVal($civ2->PhysRace) > 0) $name = $this->shortRaceName($civ2->PhysRace) . ' ' . $name;
                }
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
        if ($civ->CivIsCreator == 'Y' && (($loop == 'Victims' && $civ->CivRole == 'Victim') 
            || ($loop == 'Witnesses' && $civ->CivRole == 'Witness')) ) {
            if ($this->isReport) {
                if (isset($civ->CivPersonID) && intVal($civ->CivPersonID) > 0) {
                    $contact = $this->sessData->getChildRow('Civilians', $civ->CivPersonID, 'PersonContact');
                    $name = $contact->PrsnNameFirst . ' ' . $contact->PrsnNameLast;
                }
                if (trim($name) == '') $name = 'Complainant';
            } else {
                $name = 'You ' . $name;
            }
        }
        else $name = $this->getPersonLabel('Civilians', $civ->CivID, $civ);
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
            return $this->getCivName('Victims', $this->sessData->getRowById('Civilians', $civID), (1+$civInd));
        }
        $civInd = $this->sessData->getLoopIndFromID('Witnesses', $civID);
        if ($civInd >= 0) {
            return $this->getCivName('Witnesses', $this->sessData->getRowById('Civilians', $civID), (1+$civInd));
        }
        return '';
    }
    
    // converts Officer row into identifying name used in most of the complaint process
    protected function getOfficerName($officer = [], $itemIndex = -3)
    {
        $name = $this->getPersonLabel('Officers', $officer->OffID, $officer);
        if (trim($name) == '') $name = 'Officer #' . (1+$itemIndex);
        else $name .= ' (Officer #' . (1+$itemIndex) . ')';
        return trim($name);
    }
    
    protected function getOfficerNameFromID($offID)
    {
        $offInd = $this->sessData->getLoopIndFromID('Officers', $offID);
        if ($offInd >= 0) return $this->getOfficerName($this->sessData->getRowById('Officers', $offID), (1+$offInd));
        return '';
    }
    
    protected function getFirstVictimCivInd()
    {
        $civInd = -3;
        if (sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if (isset($civ->CivRole) && trim($civ->CivRole) == 'Victim' && $civInd < 0) $civInd = $i;
            }
        }
        return $civInd;
    }
    
    protected function saveNewDept($nID)
    {
        $newDeptID = -3;
        if (intVal($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld')) > 0) {
            $newDeptID = intVal($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'));
            $this->sessData->logDataSave($nID, 'NEW', -3, 'ComplaintDeptLinks', 
                $GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'));
        } elseif ($GLOBALS["SL"]->REQ->has('newDeptName') && trim($GLOBALS["SL"]->REQ->newDeptName) != '') {
            $newDept = $this->newDeptAdd($GLOBALS["SL"]->REQ->newDeptName, 
                $GLOBALS["SL"]->REQ->newDeptAddressState);
            $newDeptID = $newDept->DeptID;
            $logTxt = 'ComplaintDeptLinks - !New Department Added!';
            $this->sessData->logDataSave($nID, 'NEW', -3, $logTxt, $newDeptID);
        }
        if ($newDeptID > 0) $this->chkDeptLinks($newDeptID);
        return true;   
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
        if (isset($dept->DeptName) && trim($dept->DeptName) != '') $name = $dept->DeptName . ' ' . $name;
        return trim($name);
    }
    
    protected function getDeptNameByID($deptID)
    {
        $dept = $this->sessData->getRowById('Departments', $deptID);
        if ($dept) return $this->getDeptName($dept);
        return '';
    }
    
    protected function civRow2Set($civ)
    {
        if (!$civ || !isset($civ->CivIsCreator)) return '';
        return (($civ->CivIsCreator == 'Y') ? '' : (($civ->CivRole == 'Victim') ? 'Victims' : 'Witnesses') );
    }
    
    protected function getCivilianList($loop = 'Victims')
    {
        if ($loop == 'Victims' || $loop == 'Witness') return $this->sessData->loopItemIDs[$loop];
        $civs = [];
        if (isset($this->sessData->dataSets["Civilians"]) && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $civ) $civs[] = $civ->CivID;
        }
        return $civs;
    }

    // This function provides an "opts" set of Civilian IDs related to Table 1 which are to be used as candidates for 
    // Table 2. It also provides an "active" set of Civilian IDs which already have records in Table 2, with one field 
    // value optionally associated.
    // Usages: ('Force', 'Injuries', 'InjType'), ('Injuries', 'InjuryCare'), ('!Arrests', 'Charges')
    protected function getCivSetPossibilities($tbl1, $tbl2, $activeFld = 'ID')
    {
        $possible = array("opts" => [], "active" => []);
        $notInTbl1 = false;
        if (substr($tbl1, 0, 1) == '!') {
            $notInTbl1 = true;
            $tbl1 = str_replace('!', '', $tbl1);
        }
        if (sizeof($this->sessData->dataSets[$tbl1]) > 0) {
            foreach ($this->sessData->dataSets[$tbl1] as $tblRow1) {
                $eveSeqID  = $tblRow1->{$GLOBALS["SL"]->tblAbbr[$tbl1]."EventSequenceID"};
                $eveSubjID = '';
                if (isset($tblRow1->{$GLOBALS["SL"]->tblAbbr[$tbl1]."SubjectID"})) {
                    $eveSubjID = $tblRow1->{$GLOBALS["SL"]->tblAbbr[$tbl1]."SubjectID"};
                }
                if (intVal($eveSeqID) > 0) {
                    $tmpArr = $this->getLinkedToEvent('Civilian', $eveSeqID);
                    if (sizeof($tmpArr) > 0) {
                        foreach ($tmpArr as $civID) {
                            if (!in_array($civID, $possible["opts"])) $possible["opts"][] = $civID;
                        }
                    }
                } elseif (intVal($eveSubjID) > 0 && !in_array($eveSubjID, $possible["opts"])) {
                    $possible["opts"][] = $eveSubjID;
                }
            }
        }
        if ($notInTbl1) {
            $tmpArr = $possible["opts"]; $possible["opts"] = []; 
            foreach ($this->sessData->loopItemIDs["Victims"] as $civ) {
                if (!in_array($civ, $tmpArr)) $possible["opts"][] = $civ;
            }
        }
        if (isset($this->sessData->dataSets[$tbl2]) && sizeof($this->sessData->dataSets[$tbl2]) > 0) {
            foreach ($this->sessData->dataSets[$tbl2] as $tblRow2) {
                $eveSubjID = $tblRow2->{$GLOBALS["SL"]->tblAbbr[$tbl2]."SubjectID"};
                if (!isset($possible["active"][$eveSubjID])) $possible["active"][$eveSubjID] = [];
                $possible["active"][$eveSubjID][] = $tblRow2->{$GLOBALS["SL"]->tblAbbr[$tbl2] . $activeFld};
            }
        }
        return $possible;
    }
    
    // Takes in the same Tables of getCivSetPossibilities and provides the checkbox fields controlling active options.
    // Usage: ('Injuries', 'InjuryCare'), ('!Arrests', 'Charges')
    protected function formCivSetPossibilities($tbl1, $tbl2, $possible = [])
    {
        if (empty($possible)) $possible = $this->getCivSetPossibilities($tbl1, $tbl2);
        $ret = '<div class="nFld pB20">';
        if (sizeof($possible["opts"]) > 0) { 
            foreach ($possible["opts"] as $i => $civID) {
                $ret .= '<div class="nFld" style="font-size: 130%;"><nobr><input type="checkbox" autocomplete="off" 
                value="' . $civID . '" name="' . $tbl2 . '[]" id="' . $tbl2 . $civID . '" ' 
                . ((isset($possible["active"][$civID])) ? 'CHECKED' : '') . ' > 
                <label for="' . $tbl2 . $civID . '">' . $this->getCivilianNameFromID($civID) . '</label></nobr></div>';
            }
        }
        return $ret . '</div>';
    }
    
    // Takes in the same Tables of getCivSetPossibilities and the checkbox field controlling active options.
    // It deletes records which have since been deselected, and creates new ones. 
    // Same usage as formCivSetPossibilities().
    protected function postCivSetPossibilities($tbl1, $tbl2, $activeFld = 'ID')
    {
        $possible = $this->getCivSetPossibilities($tbl1, $tbl2, $activeFld);
        if (sizeof($possible["active"]) > 0) { 
            foreach ($possible["active"] as $civID => $activeFld) {
                if (!$GLOBALS["SL"]->REQ->has($tbl2) || !in_array($civID, $GLOBALS["SL"]->REQ->input($tbl2))) {
                    $complaintIDLnk = ($tbl2 != 'InjuryCare') 
                        ? "->where('".$GLOBALS["SL"]->tblAbbr[$tbl2]."ComplaintID', ".$this->coreID.")" : "";
                    eval("App\\Models\\" . $GLOBALS["SL"]->tblModels[$tbl2] . "::where('"
                        . $GLOBALS["SL"]->tblAbbr[$tbl2] . "SubjectID', " . $civID . ")" 
                        . $complaintIDLnk . "->delete();");
                }
            }
        }
        if ($GLOBALS["SL"]->REQ->has($tbl2) && is_array($GLOBALS["SL"]->REQ->input($tbl2)) 
            && sizeof($GLOBALS["SL"]->REQ->input($tbl2)) > 0) { 
            foreach ($GLOBALS["SL"]->REQ->input($tbl2) as $civID) {
                if (!isset($possible["active"][$civID])) {
                    $injRow = $this->sessData->newDataRecord($tbl2, $activeFld, $civID);
                }
            }
        }
        return true;
    }
    
/*****************************************************************************
// END Processes Which Manage Lists of People
*****************************************************************************/


    
    public function runAjaxChecks(Request $request, $over = '')
    {
        if ($request->has('email') && $request->has('password')) {
            
            print_r($request);
            $chk = User::where('email', $request->email)->get();
            if ($chk->isNotEmpty()) echo 'found';
            echo 'not found';
            exit;
            
        } elseif ($request->has('policeDept')) {
            
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
                if ($deptsRes->isNotEmpty()) {
                    foreach ($deptsRes as $d) {
                        if (!in_array($d->DeptID, $deptIDs)) {
                            $deptIDs[] = $d->DeptID;
                            $depts[] = $d;
                        }
                    }
                }
                $deptsRes = OPDepartments::where('DeptAddressCity', 'LIKE', '%' . $request->policeDept . '%')
                    ->where('DeptAddressState', $reqState)
                    ->orderBy('DeptJurisdictionPopulation', 'desc')
                    ->orderBy('DeptTotOfficers', 'desc')
                    ->orderBy('DeptName', 'asc')
                    ->get();
                if ($deptsRes->isNotEmpty()) {
                    foreach ($deptsRes as $d) $depts[] = $d;
                }
                $deptsRes = OPDepartments::where('DeptAddress', 'LIKE', '%' . $request->policeDept . '%')
                    ->where('DeptAddressState', $reqState)
                    ->orderBy('DeptJurisdictionPopulation', 'desc')
                    ->orderBy('DeptTotOfficers', 'desc')
                    ->orderBy('DeptName', 'asc')
                    ->get();
                if ($deptsRes->isNotEmpty()) {
                    foreach ($deptsRes as $d) $depts[] = $d;
                }
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
                    if ($deptsMore->isNotEmpty()) {
                        foreach ($deptsMore as $d) $depts[] = $d;
                    }
                    foreach ($counties as $c) {
                        $deptsMore = OPDepartments::where('DeptName', 'LIKE', '%' . $c . '%')
                            ->where('DeptAddressState', $reqState)
                            ->orderBy('DeptJurisdictionPopulation', 'desc')
                            ->orderBy('DeptTotOfficers', 'desc')
                            ->orderBy('DeptName', 'asc')
                            ->get();
                        if ($deptsMore->isNotEmpty()) {
                            foreach ($deptsMore as $d) $depts[] = $d;
                        }
                        $deptsMore = OPDepartments::where('DeptAddressCounty', 'LIKE', '%' . $c . '%')
                            ->where('DeptAddressState', $reqState)
                            ->orderBy('DeptJurisdictionPopulation', 'desc')
                            ->orderBy('DeptTotOfficers', 'desc')
                            ->orderBy('DeptName', 'asc')
                            ->get();
                        if ($deptsMore->isNotEmpty()) {
                            foreach ($deptsMore as $d) $depts[] = $d;
                        }
                    }
                }
            }
            $deptsFed = OPDepartments::where('DeptName', 'LIKE', '%' . $request->policeDept . '%')
                ->where('DeptType', 366)
                ->orderBy('DeptJurisdictionPopulation', 'desc')
                ->orderBy('DeptTotOfficers', 'desc')
                ->orderBy('DeptName', 'asc')
                ->get();
            if ($deptsFed->isNotEmpty()) {
                foreach ($deptsFed as $d) $depts[] = $d;
            }
            $GLOBALS["SL"]->loadStates();
            echo view('vendor.openpolice.ajax.search-police-dept', [
                "depts"            => $depts, 
                "search"           => $request->get('policeDept'), 
                "stateName"        => $GLOBALS["SL"]->states->getState($request->get('policeState')), 
                "newDeptStateDrop" => $GLOBALS["SL"]->states->stateDrop($request->get('policeState'), true)
            ])->render();
            return '';
            
        }
        exit;
    }
    
    public function allegationsList(Request $request)
    {
        $this->v["content"] = view('vendor.openpolice.allegations')->render();
        return view('vendor.survloop.master', $this->v)->render();
    }
    
    protected function oversightList()
    {
        $ret = '';
        if (isset($this->sessData->dataSets["Oversight"]) && sizeof($this->sessData->dataSets["Oversight"]) > 0) {
            $cnt = 0;
            foreach ($this->sessData->dataSets["Oversight"] as $i => $o) {
                if (isset($o->OverAgncName) && trim($o->OverAgncName) != '') {
                    $ret .= (($cnt > 0) ? ' and ' : '') . $o->OverAgncName;
                    $cnt++;
                }
            }
        }
        return $ret;
    }
    
    public function sortableStart($nID)
    {
        return '<div class="gry6 mB10">Start of Incident</div>';
    }
    
    public function sortableEnd($nID)
    {
        return '<div class="gry6 mT10">End of Incident</div>';
    }
    
    protected function uploadWarning($nID)
    {
        return '<div class="red mB10">WARNING: If documents show sensitive personal information, set this to "private." 
            This includes addresses, phone numbers, emails, or social security numbers.</div>';
    }
    
    
    public function isOverCompatible($overRow)
    {
        return ($overRow && isset($overRow->OverEmail) && trim($overRow->OverEmail) != '' 
            && isset($overRow->OverWaySubEmail) && intVal($overRow->OverWaySubEmail) == 1
            && isset($overRow->OverOfficialFormNotReq) && intVal($overRow->OverOfficialFormNotReq) == 1);
    }
    
    public function loadDeptStuff($deptID = -3)
    {
        if (!isset($this->v["deptScores"])) $this->v["deptScores"] = new DepartmentScores;
        if ($deptID > 0 && !isset($GLOBALS["SL"]->x["depts"][$deptID])) {
            $GLOBALS["SL"]->x["depts"][$deptID] = [ "id" => $deptID ];
            $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"] = OPDepartments::find($deptID);
            $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"] = OPOversight::where('OverDeptID', $deptID)
                ->where('OverType', $GLOBALS["SL"]->def->getID('Oversight Agency Types', 'Internal Affairs'))
                ->first();
            $GLOBALS["SL"]->x["depts"][$deptID]["civRow"] = OPOversight::where('OverDeptID', $deptID)
                ->where('OverType', $GLOBALS["SL"]->def->getID('Oversight Agency Types', 'Civilian Oversight'))
                ->first();
            if (!isset($GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]) 
                || !$GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]) {
                $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"] = new OPOversight;
                $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverDeptID = $deptID;
                if ($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"] 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName)) {
                    $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverType
                        = $GLOBALS["SL"]->def->getID('Oversight Agency Types', 'Internal Affairs');
                    $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAgncName
                        = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName;
                    $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddress
                        = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddress;
                    $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddress2
                        = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddress2;
                    $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddressCity
                        = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressCity;
                    $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddressState
                        = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressState;
                    $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddressZip
                        = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressZip;
                    $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverPhoneWork
                        = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptPhoneWork;
                }
                $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->save();
            }
            if (!isset($GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAgncName) 
                || trim($GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAgncName) == '') {
                $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAgncName
                    = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName;
                $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->save();
            }
            if ($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"] 
                && isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddress)) {
                $GLOBALS["SL"]->x["depts"][$deptID]["deptAddy"] 
                    = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddress . ', ';
                if (isset($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddress2) 
                    && trim($GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddress2) != '') {
                    $GLOBALS["SL"]->x["depts"][$deptID]["deptAddy"] 
                        .= $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddress2 . ', ';
                }
                $GLOBALS["SL"]->x["depts"][$deptID]["deptAddy"] 
                    .= $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressCity . ', ' 
                    . $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressState . ' ' 
                    . $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressZip;
                $GLOBALS["SL"]->x["depts"][$deptID]["iaAddy"] = '';
                if (isset($GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddress) 
                    && trim($GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddress) != '') {
                    $GLOBALS["SL"]->x["depts"][$deptID]["iaAddy"] 
                        = $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddress . ', ';
                    if (isset($GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddress2) 
                        && trim($GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddress2) != '') {
                        $GLOBALS["SL"]->x["depts"][$deptID]["iaAddy"] 
                            .= $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddress2 . ', ';
                    }
                    $GLOBALS["SL"]->x["depts"][$deptID]["iaAddy"] 
                        .= $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddressCity . ', ' 
                        . $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddressState . ' ' 
                        . $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]->OverAddressZip;
                }
                $GLOBALS["SL"]->x["depts"][$deptID]["civAddy"]  = '';
                if (isset($GLOBALS["SL"]->x["depts"][$deptID]["civRow"]->OverAddress) 
                    && trim($GLOBALS["SL"]->x["depts"][$deptID]["civRow"]->OverAddress) != '') {
                    $GLOBALS["SL"]->x["depts"][$deptID]["civAddy"] 
                        = $GLOBALS["SL"]->x["depts"][$deptID]["civRow"]->OverAddress . ', ';
                    if (isset($GLOBALS["SL"]->x["depts"][$deptID]["civRow"]->OverAddress2) 
                        && trim($GLOBALS["SL"]->x["depts"][$deptID]["civRow"]->OverAddress2) != '') {
                        $GLOBALS["SL"]->x["depts"][$deptID]["civAddy"] 
                            .= $GLOBALS["SL"]->x["depts"][$deptID]["civRow"]->OverAddress2 . ', ';
                    }
                    $GLOBALS["SL"]->x["depts"][$deptID]["civAddy"] 
                        .= $GLOBALS["SL"]->x["depts"][$deptID]["civRow"]->OverAddressCity . ', ' 
                        . $GLOBALS["SL"]->x["depts"][$deptID]["civRow"]->OverAddressState . ' ' 
                        . $GLOBALS["SL"]->x["depts"][$deptID]["civRow"]->OverAddressZip;
                }
            }
            
            $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"] = $which = '';
            if (isset($GLOBALS["SL"]->x["depts"][$deptID]["civRow"]) 
                && isset($GLOBALS["SL"]->x["depts"][$deptID]["civRow"]->OverAgncName)) {
                $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"] = $which = "civRow";
            } else {
                $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"] = $which = "iaRow";
            }
            $GLOBALS["SL"]->x["depts"][$deptID]["overUser"] = [];
            if (isset($GLOBALS["SL"]->x["depts"][$deptID][$which])
                && isset($GLOBALS["SL"]->x["depts"][$deptID][$which]->OverEmail)) {
                $email = $GLOBALS["SL"]->x["depts"][$deptID][$which]->OverEmail;
                $GLOBALS["SL"]->x["depts"][$deptID]["overUser"] = User::where('email', $email)->first();
            }
            
            if (isset($GLOBALS["SL"]->x["depts"]) && isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                && isset($GLOBALS["SL"]->x["depts"][$deptID]["iaRow"])) {
                $GLOBALS["SL"]->x["depts"][$deptID]["score"] = [];
                foreach ($this->v["deptScores"]->vals as $type => $specs) {
                    $GLOBALS["SL"]->x["depts"][$deptID]["score"][] = [
                        $specs->score,
                        $specs->label,
                        ($this->v["deptScores"]->checkRecFld($specs, $GLOBALS["SL"]->x["depts"][$deptID]["iaRow"]) != 0)
                        ];
                }
            }
        }
        return true;
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
    
    protected function getDeptUser($deptID = -3)
    {
        $this->chkDeptUsers();
        if ($deptID > 0 && isset($GLOBALS["SL"]->x["depts"]) && sizeof($GLOBALS["SL"]->x["depts"]) > 0 
            && isset($GLOBALS["SL"]->x["depts"][$deptID]) && $GLOBALS["SL"]->x["depts"][$deptID]["overUser"]) {
            return $GLOBALS["SL"]->x["depts"][$deptID]["overUser"];
        }
        return [];
    }
    
    public function emailRecordSwap($emaTxt)
    {
        $deptID = -3;
        if (isset($this->sessData->dataSets["LinksComplaintDept"]) 
            && sizeof($this->sessData->dataSets["LinksComplaintDept"]) > 0) {
            foreach ($this->sessData->dataSets["LinksComplaintDept"] as $deptLnk) {
                $this->loadDeptStuff($deptLnk->LnkComDeptDeptID);
                $deptID = $deptLnk->LnkComDeptDeptID;
            }
        }
        $emaTxt = $this->sendEmailBlurbs($emaTxt, $deptID);
        return $emaTxt;
    }
    
    public function sendEmailBlurbsCustom($emailBody, $deptID = -3)
    {
        if (!isset($GLOBALS["SL"]->x["depts"]) || empty($GLOBALS["SL"]->x["depts"])) {
            if ($deptID > 0) {
                $this->loadDeptStuff($deptID);
            } elseif (isset($this->sessData->dataSets["LinksComplaintDept"]) 
                && sizeof($this->sessData->dataSets["LinksComplaintDept"]) > 0) {
                foreach ($this->sessData->dataSets["LinksComplaintDept"] as $i => $deptLnk) {
                    $this->loadDeptStuff($deptLnk->LnkComDeptDeptID);
                    if ($i == 0) $deptID = $deptLnk->LnkComDeptDeptID;
                }
            }
        } else {
            if ($deptID <= 0) {
                foreach ($GLOBALS["SL"]->x["depts"] as $dID => $stuff) {
                    if ($deptID <= 0) $deptID = $dID;
                }
            }
            if (!isset($GLOBALS["SL"]->x["depts"][$deptID])) {
                $this->loadDeptStuff($deptID);
            }
        }
        if (strpos($emailBody, '[{ Complaint Oversight Agency }]') !== false) {
            if (isset($GLOBALS["SL"]->x["depts"][$deptID])) {
                $wchOvr = $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"];
                if (isset($GLOBALS["SL"]->x["depts"][$deptID][$wchOvr])) {
                    $overName = trim($GLOBALS["SL"]->x["depts"][$deptID][$wchOvr]->OverAgncName);
                    if ($overName == '') $overName = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName;
                    $forDept = (($overName != $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName) 
                        ? ' (for the ' . $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName . ')' 
                        : (($wchOvr == 'iaRow') ? ' Internal Affairs' : ''));
                    $splits = $GLOBALS["SL"]->mexplode('[{ Complaint Oversight Agency }]', $emailBody);
                    $emailBody = $splits[0] . $overName . $forDept;
                    if ($wchOvr == 'iaRow') $overName = 'Internal Affairs';
                    for ($i = 1; $i < sizeof($splits); $i++) $emailBody .= (($i > 1) ? $overName : '') . $splits[$i];
                }
            }
        }
        
        $dynamos = [
            '[{ Complaint ID }]', 
            '[{ Complaint URL }]', 
            '[{ Complaint URL Link }]', 
            '[{ Complaint URL PDF }]', 
            '[{ Complaint URL PDF Link }]', 
            '[{ Complaint URL JSON }]', 
            '[{ Complaint URL JSON Link }]', 
            '[{ Complainant Name }]', 
            '[{ Confirmation URL }]', 
            '[{ Days From Now: 7, mm/dd/yyyy }]', 
            '[{ Complaint Number of Weeks Old }]', 
            '[{ Analyst Name }]', 
            '[{ Analyst Email }]', 
            '[{ Complaint Department Submission Ways }]', 
            '[{ Complaint Police Department }]', 
            '[{ Complaint Police Department URL }]', 
            '[{ Complaint Police Department URL Link }]', 
            '[{ Dear Primary Oversight Agency }]', 
            '[{ Complaint Investigability Score & Description }]', 
            '[{ Complaint Allegation List }]', 
            '[{ Complaint Allegation List Lower Bold }]', 
            '[{ Complaint Top Three Allegation List Lower Bold }]', 
            '[{ Complaint Worst Allegation }]', 
            '[{ Oversight Complaint Token URL Link }]', 
            '[{ Oversight Complaint Secure MFA }]',
            '[{ Complaint Department Complaint PDF }]', 
            '[{ Complaint Department Complaint Web }]', 
            '[{ Complaint Officers Reference }]', 
            '[{ Flex Article Suggestions Based On Responses }]'
        ];
        
        foreach ($dynamos as $dy) {
            if (strpos($emailBody, $dy) !== false) {
                $swap = $dy;
                $dyCore = str_replace('[{ ', '', str_replace(' }]', '', $dy));
                switch ($dy) {
                    case '[{ Complaint ID }]': 
                        $swap = $this->corePublicID;
                        break;
                    case '[{ Complaint URL }]':
                        $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' 
                            . $this->corePublicID);
                        break;
                    case '[{ Complaint URL Link }]':
                        $swap = $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $this->corePublicID;
                        break;
                    case '[{ Complaint URL PDF }]':
                        $swap = '<a href="' . $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' 
                            . $this->corePublicID . '/pdf" target="_blank">Download full complaint as a PDF</a>';
                        break;
                    case '[{ Complaint URL PDF Link }]':
                        $swap = $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $this->corePublicID . '/pdf';
                        break;
                    case '[{ Complaint URL XML }]':
                    case '[{ Complaint URL JSON }]':
                        $swap = '<a href="' . $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' 
                            . $this->corePublicID . '/xml" target="_blank">Download full complaint as a XML</a>';
                        break;
                    case '[{ Complaint URL XML Link }]':
                    case '[{ Complaint URL JSON Link }]':
                        $swap = $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $this->corePublicID . '/xml';
                        break;
                    case '[{ Complaint URL XML }]':
                        $swap = '<a href="' . $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' 
                            . $this->corePublicID . '/xml" target="_blank">Download full complaint as an OPC Data File '
                            . '(XML)</a>';
                        break;
                    case '[{ Complaint URL XML Link }]':
                        $swap = $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $this->corePublicID . '/xml';
                        break;
                    case '[{ Complainant Name }]':
                        if (isset($this->sessData->dataSets["Civilians"][0]->CivPersonID) 
                            && intVal($this->sessData->dataSets["Civilians"][0]->CivPersonID) > 0) {
                            $contact = $this->sessData->getRowById('PersonContact', 
                                $this->sessData->dataSets["Civilians"][0]->CivPersonID);
                            if ($contact && isset($contact->PrsnNameFirst)) {
                                $swap = $contact->PrsnNameFirst;
                            }
                        }
                        break;
                    case '[{ Days From Now: 7, mm/dd/yyyy }]':
                        $swap = date('n/j/y', mktime(0, 0, 0, date("n"), (7+date("j")), date("Y")));
                        break;
                    case '[{ Complaint Number of Weeks Old }]':
                        $dayCount = date_diff(mktime(), strtotime(
                            $this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted
                            ))->format('%a');
                        $swap = floor($dayCount/7);
                        break;
                    case '[{ Analyst Name }]':
                        $swap = $this->userFormalName($this->v["user"]->id);
                        break;
                    case '[{ Analyst Email }]':
                        $swap = $this->v["user"]->email;
                        break;
                    case '[{ Complaint Police Department }]':
                        $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName;
                        break;
                    case '[{ Complaint Police Department URL }]':
                        $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . '/dept/' 
                            . $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptSlug);
                        break;
                    case '[{ Complaint Police Department URL Link }]':
                        $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptName;
                        break;
                    case '[{ Dear Primary Oversight Agency }]':
                        $swap = 'To Whom It May Concern,';
                        break;
                    case '[{ Complaint Officers Reference }]':
                        if (empty($this->sessData->dataSets["Officers"])) $swap = 'no officers';
                        elseif (sizeof($this->sessData->dataSets["Officers"]) == 1) $swap = 'one officer';
                        elseif (sizeof($this->sessData->dataSets["Officers"]) < 10) {
                            switch (sizeof($this->sessData->dataSets["Officers"])) {
                                case 2: $swap = 'two'; break;
                                case 3: $swap = 'three'; break;
                            }
                            $swap .= ' officers';
                        } else {
                            $swap = $f->format(sizeof($this->sessData->dataSets["Officers"])) . ' officers';
                        }
                        break;
                    case '[{ Complaint Officers Count }]':
                        $swap = sizeof($this->sessData->dataSets["Officers"]);
                        break;
                    case '[{ Complaint Allegation List }]':
                        $swap = $this->commaAllegationList();
                        break;
                    case '[{ Complaint Allegation List Lower Bold }]':
                        $swap = '<b>' . strtolower($this->commaAllegationList()) . '</b>';
                        break;
                    case '[{ Complaint Top Three Allegation List Lower Bold }]':
                        $swap = '<b>' . strtolower($this->commaTopThreeAllegationList()) . '</b>';
                        break;
                    case '[{ Complaint Worst Allegation }]':
                        $this->simpleAllegationList();
                        if (sizeof($this->allegations) > 0) $swap = $this->allegations[0][0];
                        break;
                    case '[{ Oversight Complaint Token URL Link }]':
                        $deptUser = $this->getDeptUser($deptID);
                        if (!isset($deptUser->id)) {
                            $swap = '#';
                        } else {
                            $token = $this->createToken('Sensitive', $this->treeID, $this->coreID, $deptUser->id);
                            $swap = $GLOBALS["SL"]->sysOpts["app-url"] . '/complaint/read-' . $this->corePublicID 
                                . '/full/t-' . $token;
                        }
                        $swap = '<a href="' . $swap . '" target="_blank">' . $swap . '</a>';
                        break;
                    case '[{ Oversight Complaint Secure MFA }]':
                        $deptUser = $this->getDeptUser($deptID);
                        if (!isset($deptUser->id)) {
                            $swap = '<span style="color: red;">* DEPARTMENT IS NOT OPC-COMPLIANT *</span>';
                        } else {
                            $swap = $this->createToken('MFA', $this->treeID, $this->coreID, $deptUser->id);
                        }
                        break;
                    case '[{ Complaint Department Complaint PDF }]':
                        $which = $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"];
                        if (isset($GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintPDF) 
                            && trim($GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintPDF) != '') {
                            $swap = '';
                            if (sizeof($GLOBALS["SL"]->x["depts"]) > 1) {
                                $swap .= $GLOBALS["SL"]->x["depts"][$deptID][$which]->OverAgncName;
                            }
                            $swap .= ': ' 
                                . $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintPDF);
                        }
                        if (sizeof($GLOBALS["SL"]->x["depts"]) > 1) {
                            for ($i = 1; $i < sizeof($GLOBALS["SL"]->x["depts"]); $i++) {
                                if (trim($swap) != '') $swap .= '<br />';
                                $which = $GLOBALS["SL"]->x["depts"][$i]["whichOver"];
                                $swap .= $GLOBALS["SL"]->x["depts"][$deptID][$which]->OverAgncName . ': ' 
                                    . $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->x["depts"][$i][$which]->OverComplaintPDF);
                            }
                        }
                        break;
                    case '[{ Complaint Department Complaint Web }]':
                        $which = $GLOBALS["SL"]->x["depts"][$deptID]["whichOver"];
                        if (isset($GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintWebForm) 
                            && trim($GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintWebForm) != '') {
                            $swap = '';
                            if (sizeof($GLOBALS["SL"]->x["depts"]) > 1) {
                                $swap = $GLOBALS["SL"]->x["depts"][$deptID][$which]->OverAgncName;
                            }
                            $swap .= ': ' . $GLOBALS["SL"]->swapURLwrap(
                                $GLOBALS["SL"]->x["depts"][$deptID][$which]->OverComplaintWebForm);
                        }
                        if (sizeof($GLOBALS["SL"]->x["depts"]) > 1) {
                            for ($i = 1; $i < sizeof($GLOBALS["SL"]->x["depts"]); $i++) {
                                if (trim($swap) != '') $swap .= '<br />';
                                $currWhich = $GLOBALS["SL"]->x["depts"][$i]["whichOver"];
                                $swap .= $GLOBALS["SL"]->x["depts"][$deptID][$which]->OverAgncName . ': ' 
                                    . $GLOBALS["SL"]->swapURLwrap(
                                        $GLOBALS["SL"]->x["depts"][$i][$currWhich]->OverComplaintWebForm);
                            }
                        }
                        break;
                    case '[{ Flex Article Suggestions Based On Responses }]':
                        $this->loadRelatedArticles();
                        $swap = view('vendor.openpolice.nodes.1708-report-flex-articles-email', [
                            "allUrls"     => $this->v["allUrls"], 
                            "showVidUrls" => true
                            ])->render();
                        break;
                }
                $emailBody = str_replace($dy, $swap, $emailBody);
            }
        }
        
        $emailBody = str_replace('Hello Complainant,', 'Hello,', $emailBody);
        $emailBody = str_replace('Congratulations, [{ Complainant Name }]!', 'Congratulations!', $emailBody);

        return $emailBody;
    }
    
    public function deptPage(Request $request, $deptSlug = '')
    {
        $deptID = -3;
        $deptRow = OPDepartments::where('DeptSlug', $deptSlug)->first();
        if ($deptRow && isset($deptRow->DeptID)) {
            $deptID = $deptRow->DeptID;
            $request->d = $deptRow->DeptID;
        }
        $this->loadPageVariation($request, 1, 25, '/dept/' . $deptSlug);
        if ($deptID > 0) $this->v["deptID"] = $deptRow->DeptID;
        return $this->index($request);
    }
    
    public function shareStoryDept(Request $request, $deptSlug = '')
    {
        $this->loadPageVariation($request, 1, 24, '/sharing-your-story/' . $deptSlug);
        $deptRow = OPDepartments::where('DeptSlug', $deptSlug)->first();
        if ($deptRow && isset($deptRow->DeptID)) session()->put('opcDeptID', $deptRow->DeptID);
        return $this->index($request);
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
        if ($usr && isset($usr->name)) return $usr->name;
        return '';
    }
    
    public function getOverUpdateRow($cid, $deptID)
    {
        if (!isset($this->v["currOverRow"])) {
            $this->v["currOverRow"] = NULL;
            $overs = OPOversight::where('OverDeptID', $deptID)
                ->get();
            if ($overs->isNotEmpty()) {
                if ($overs->count() == 1) {
                    $this->v["currOverRow"] = $overs[0];
                } else {
                    foreach ($overs as $i => $ovr) {
                        if ($ovr && isset($ovr->OverType) && $ovr->OverType 
                            == $GLOBALS["SL"]->def->getID('Oversight Agency Types', 'Civilian Oversight')) {
                            $this->v["currOverRow"] = $ovr;
                        }
                    }
                }
            }
        }
        if ($this->v["currOverRow"] && isset($this->v["currOverRow"]->OverID)) {
            $this->v["currOverUpdateRow"] = OPLinksComplaintOversight::where('LnkComOverComplaintID', $cid)
                ->where('LnkComOverOverID', $this->v["currOverRow"]->OverID)
                ->first();
            if (!$this->v["currOverUpdateRow"]) {
                $this->v["currOverUpdateRow"] = new OPLinksComplaintOversight;
                $this->v["currOverUpdateRow"]->LnkComOverComplaintID = $cid;
                $this->v["currOverUpdateRow"]->LnkComOverDeptID = $deptID;
                $this->v["currOverUpdateRow"]->LnkComOverOverID = $this->v["currOverRow"]->OverID;
                $this->v["currOverUpdateRow"]->save();
            }
        }
        if (!isset($this->v["currOverUpdateRow"])) $this->v["currOverUpdateRow"] = null;
        return $this->v["currOverUpdateRow"];
    }
    
    public function logOverUpDate($cid, $deptID, $type = 'Submitted', $row = [])
    {
        if (!$row || !isset($row->LnkComOverID)) $row = $this->getOverUpdateRow($cid, $deptID);
        $row->{ 'LnkComOver' . $type } = date("Y-m-d H:i:s");
        $row->save();
        return true;
    }
    
    
    
    
    // Volunteer Area
    
    protected function overWhichEng($which = 'IA')
    {
        return (($which == 'IA') ? 'Internal Affairs' : 'Civilian Oversight');
    }
    
    protected function overWhichDefID($which = 'IA')
    {
        return $GLOBALS["SL"]->def->getID('Oversight Agency Types', $this->overWhichEng($which));
    }
    
    protected function getOverRow($which = 'IA')
    {
        if (isset($this->v["overRow" . $which])) return $this->v["overRow" . $which];
        $rows = $this->sessData->getRowIDsByFldVal('Oversight', [ 'OverType' => $this->overWhichDefID($which) ], true);
        if (sizeof($rows) > 0) {
            $this->v["overRow" . $which] = $rows[0];
            return $this->v["overRow" . $which];
        }
        return [];
    }
    
    protected function printSidebarLeaderboard() 
    {
        $this->v["leaderboard"] = new VolunteerLeaderboard;
        return view('vendor.openpolice.volun.volun-sidebar-leaderboard', [
            "leaderboard" => $this->v["leaderboard"]
        ])->render();
    }
    
    public function printVolunPriorityList()
    {
        $this->loadDeptPriorityRows();
        return view('vendor.openpolice.nodes.1755-volun-home-priority-depts', $this->v)->render();
    }
    
    public function printVolunAllList()
    {
        if ($GLOBALS["SL"]->REQ->has('refresh')) {
            $this->v["deptScores"] = new DepartmentScores;
            $this->v["deptScores"]->recalcAllDepts();
        }
        if ($GLOBALS["SL"]->REQ->has('newDept') && intVal($GLOBALS["SL"]->REQ->get('newDept')) == 1
            && $GLOBALS["SL"]->REQ->has('deptName') && trim($GLOBALS["SL"]->REQ->get('deptName')) != '') {
            $newDept = $this->newDeptAdd($GLOBALS["SL"]->REQ->get('deptName'), 
                $GLOBALS["SL"]->REQ->get('DeptAddressState'));
            return '<script type="text/javascript"> setTimeout("window.location=\'/dashboard/start-' . $newDept->DeptID 
                . '/volunteers-research-departments\'", 10); </script>';
        }
        $this->v["viewType"] = (($GLOBALS["SL"]->REQ->has('sort')) ? $GLOBALS["SL"]->REQ->get('sort') : 'recent');
        $this->v["deptRows"] = [];
        $this->v["searchForm"] = $this->deptSearchForm();
        $orderby = [ [ 'DeptVerified', 'desc' ], [ 'DeptName', 'asc' ] ];
        switch ($this->v["viewType"]) {
            case 'best': $orderby[0] = [ 'DeptScoreOpenness', 'desc' ]; break;
            case 'name': $orderby[0] = [ 'DeptName', 'asc' ]; break;
            case 'city': $orderby = [ [ 'DeptAddressState', 'asc' ], [ 'DeptAddressCity', 'asc' ] ]; break;
        }
        $this->v["state"] = $whrState = '';
        if ($GLOBALS["SL"]->REQ->has('state') && trim($GLOBALS["SL"]->REQ->get('state')) != '') {
            $this->v["state"] = trim($GLOBALS["SL"]->REQ->get('state'));
            $whrState = "->where('DeptAddressState', '" . $this->v["state"] . "')";
        }
        if ($GLOBALS["SL"]->REQ->has('s') && trim($GLOBALS["SL"]->REQ->get('s')) != '') {
            $this->chkRecsPub($GLOBALS["SL"]->REQ, 36);
            $searches = [];
            if ($GLOBALS["SL"]->REQ->has('s') && trim($GLOBALS["SL"]->REQ->get('s')) != '') {
                $searches = $GLOBALS["SL"]->parseSearchWords($GLOBALS["SL"]->REQ->get('s'));
            }
            if (sizeof($searches) > 0) {
                foreach ($searches as $s) {
                    eval("\$rows = App\\Models\\OPDepartments::where('DeptName', 'LIKE', '%' . \$s . '%')
                        ->orWhere('DeptEmail', 'LIKE', '%' . \$s . '%')
                        ->orWhere('DeptPhoneWork', 'LIKE', '%' . \$s . '%')
                        ->orWhere('DeptAddress', 'LIKE', '%' . \$s . '%')
                        ->orWhere('DeptAddressCity', 'LIKE', '%' . \$s . '%')
                        ->orWhere('DeptAddressZip', 'LIKE', '%' . \$s . '%')
                        ->orWhere('DeptAddressCounty', 'LIKE', '%' . \$s . '%')
                        " . $whrState . "->get();");
                    $GLOBALS["SL"]->addSrchResults('depts', $rows, 'DeptID');
                }
            }
            $this->v["deptRows"] = $GLOBALS["SL"]->x["srchRes"]["depts"];
            unset($GLOBALS["SL"]->x["srchRes"]["depts"]);
        } else {
            eval("\$this->v['deptRows'] = App\\Models\\OPDepartments::select('DeptID', 'DeptName', 'DeptScoreOpenness', 
                'DeptVerified', 'DeptAddressCity', 'DeptAddressState')
                ->orderBy(\$orderby[0][0], \$orderby[0][1])
                ->orderBy(\$orderby[1][0], \$orderby[1][1])
                " . $whrState . "->get();");
        }
        $this->loadRecentDeptEdits();
        $GLOBALS["SL"]->loadStates();
        return view('vendor.openpolice.nodes.1211-volun-home-all-depts', $this->v)->render();
    }
    
    protected function loadDeptPriorityRows()
    {
        $this->v["deptPriorityRows"] = $done = [];
        $chk = DB::table('OP_Departments')
            ->join('OP_LinksComplaintDept', 'OP_Departments.DeptID', 
                '=', 'OP_LinksComplaintDept.LnkComDeptDeptID')
            ->join('OP_Complaints', 'OP_LinksComplaintDept.LnkComDeptComplaintID', '=', 'OP_Complaints.ComID')
            ->where('OP_Departments.DeptScoreOpenness', 0)
            ->whereIn('OP_Complaints.ComStatus', [
                $GLOBALS["SL"]->def->getID('Complaint Status', 'New'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Reviewed'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight')
                ])
            ->where('OP_Complaints.ComSummary', 'NOT LIKE', '')
            ->select('OP_Departments.*')
            ->orderBy('OP_Complaints.created_at', 'asc')
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $dept) {
                if (!in_array($dept->DeptID, $done)) {
                    $this->v["deptPriorityRows"][] = $dept;
                    $done[] = $dept->DeptID;
                }
            }
        }
        $this->v["yearold"] = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")-1);
        $chk = DB::table('OP_Departments')
            ->join('OP_LinksComplaintDept', 'OP_Departments.DeptID', 
                '=', 'OP_LinksComplaintDept.LnkComDeptDeptID')
            ->join('OP_Complaints', 'OP_LinksComplaintDept.LnkComDeptComplaintID', '=', 'OP_Complaints.ComID')
            ->where('OP_Departments.DeptScoreOpenness', '>', 0)
            ->where('OP_Departments.DeptVerified', '<', date("Y-m-d H:i:s", $this->v["yearold"]))
            ->whereIn('OP_Complaints.ComStatus', [
                $GLOBALS["SL"]->def->getID('Complaint Status', 'New'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Reviewed'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d'),
                $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight')
                ])
            ->where('OP_Complaints.ComSummary', 'NOT LIKE', '')
            ->select('OP_Departments.*')
            ->orderBy('OP_Complaints.created_at', 'asc')
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $dept) {
                if (!in_array($dept->DeptID, $done)) {
                    $this->v["deptPriorityRows"][] = $dept;
                    $done[] = $dept->DeptID;
                }
            }
        }
        return $this->v["deptPriorityRows"];
    }
    
    protected function loadRecentDeptEdits()
    {
        if (!isset($GLOBALS["SL"]->x["usernames"])) $GLOBALS["SL"]->x["usernames"] = [];
        $GLOBALS["SL"]->x["recentDeptEdits"] = [];
        $cutoff = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("n"), date("j")-7, date("Y")));
        $rows = OPZeditDepartments::where('OP_Zedit_Departments.ZedDeptDeptVerified', '>', $cutoff)
            ->leftJoin('users', 'users.id', '=', 'OP_Zedit_Departments.ZedDeptUserID')
            ->select('OP_Zedit_Departments.ZedDeptDeptID', 'OP_Zedit_Departments.ZedDeptDeptVerified', 
                'users.id', 'users.name')
            ->orderBy('OP_Zedit_Departments.ZedDeptDeptVerified', 'desc')
            ->get();
        if ($rows->isNotEmpty()) {
            foreach ($rows as $row) {
                if (!isset($GLOBALS["SL"]->x["recentDeptEdits"][$row->ZedDeptDeptID])) {
                    $GLOBALS["SL"]->x["recentDeptEdits"][$row->ZedDeptDeptID] = [];
                }
                if (!isset($GLOBALS["SL"]->x["recentDeptEdits"][$row->ZedDeptDeptID][$row->id])) {
                    $GLOBALS["SL"]->x["recentDeptEdits"][$row->ZedDeptDeptID][$row->id] = $row->ZedDeptDeptVerified;
                }
                if (!isset($GLOBALS["SL"]->x["usernames"][$row->id])) {
                    $GLOBALS["SL"]->x["usernames"][$row->id] = $row->name;
                }
            }
        }
        return $GLOBALS["SL"]->x["recentDeptEdits"];
    }
    
    public function printVolunLocationForm()
    {
        $GLOBALS["SL"]->loadStates();
        return view('vendor.openpolice.nodes.1217-volun-home-your-info', $this->v)->render();
    }
    
    public function saveDefaultState(Request $request)
    {
        $this->survLoopInit($request);
        $this->loadYourContact();
        if (isset($this->v["yourContact"]) && isset($this->v["yourContact"]->PrsnID)) {
            if ($request->has('newState')) {
                $this->v["yourContact"]->update([ "PrsnAddressState" => $request->get('newState') ]);
            }
            if ($request->has('newPhone')) {
                $this->v["yourContact"]->update([ "PrsnPhoneMobile" => $request->get('newPhone') ]);
            }
        }
        exit;
    }
    
    protected function deptSearchForm($state = '', $deptName = '')
    {
        $GLOBALS["SL"]->loadStates();
        return view('vendor.openpolice.volun.volunEditSearch', [ 
            "deptName"  => $deptName, 
            "stateDrop" => $GLOBALS["SL"]->states->stateDrop($state) 
        ])->render();
    }
    
    public function deptIndexSearch($deptRows = [], $state = '', $deptName = '')
    {
        $this->v["viewType"] = 'search';
        $this->v["deptRows"] = $deptRows;
        $this->v["userTots"] = $this->getVolunEditsOverview();
        $this->v["searchForm"] = str_replace('<select', '<div class="p5"><select', 
            str_replace('class="w33"', 'class="w33 f22"', $this->deptSearchForm($state, $deptName) )) . '</div>';
        $this->v["belowAdmMenu"] = $this->printSidebarLeaderboard();
        $GLOBALS["SL"]->loadStates();
        return view('vendor.openpolice.volun.volunteer', $this->v)->render();
    }
    
    public function deptIndexSearchS(Request $request, $state = '')
    {
        $this->survLoopInit($request, '/dashboard/volunteer');
        $deptRows = OPDepartments::where('DeptAddressState', '=', $state)
            ->orderBy('DeptName', 'asc')
            ->paginate(50);
        return $this->deptIndexSearch($deptRows, $state, '');
    }
    
    public function deptIndexSearchD(Request $request, $deptName = '')
    {
        $this->survLoopInit($request, '/dashboard/volunteer');
        $this->v["deptName"] = '';
        if (trim($deptName) != '') $this->v["deptName"] = $deptName;
        return $this->deptIndexSearch($this->processSearchDepts('', $deptName), '', $deptName);
    }
    
    public function deptIndexSearchSD(Request $request, $state = '', $deptName = '')
    {
        $this->survLoopInit($request, '/dashboard/volunteer');
        $this->v["deptName"] = '';
        if (trim($deptName) != '') $this->v["deptName"] = $deptName;
        return $this->deptIndexSearch($this->processSearchDepts($state, $deptName), $state, $deptName);
    }
    
    protected function processSearchDepts($state = '', $deptName = '')
    {
        $deptName = str_replace('  ', ' ', str_replace('  ', ' ', str_replace('  ', ' ', $deptName)));
        $searches = array('%'.$deptName.'%');
        if (strpos($deptName, ' ') !== false) {
            $words = explode(' ', $deptName);
            foreach ($words as $w) {
                if (!in_array(strtolower($w), ['city', 'county', 'sherrif\'s', 'police', 'department', 'dept'])) {
                    $searches = '%'.$w.'%';
                }
            }
        }
        $deptRows = [];
        $evalQry = "\$deptRows = App\\Models\\OPDepartments::"
            . ((trim($state) != '') ? "where('DeptAddressState', '=', \$state)->" : "")
            . "where(function(\$query) { return \$query->where('DeptName', 'LIKE', '" 
            . addslashes($searches[0]) . "')";
            for ($i = 1; $i < sizeof($searches); $i++) {
                $evalQry .= "->orWhere('DeptName', 'LIKE', '" . addslashes($searches[$i]) . "')";
            }
        $evalQry .= "; })->orderBy('DeptName', 'asc')->paginate(50);";
        eval($evalQry);
        return $deptRows;
    }
    
    protected function getNextDept()
    {
        $this->v["nextDept"] = array(0, '', '');
        $recentDate = date("Y-m-d H:i:s", time(date("H")-3, date("i"), date("s"), date("n"), date("j"), date("Y")));
        /*
        OPzVolunTmp::where('TmpType', 'ZedDept')
            ->where('TmpDate', '<', $recentDate)
            ->delete();
        // First check for department temporarily reserved for this user
        $tmpReserve = OPzVolunTmp::where('TmpType', 'ZedDept')
            ->where('TmpUser', Auth::user()->id)
            ->first();
        if ($tmpReserve && isset($tmpReserve->TmpVal) && intVal($tmpReserve->TmpVal) > 0) {
            $nextRow = OPDepartments::where('DeptID', $tmpReserve->TmpVal)
                ->first();
            $this->v["nextDept"] = [ $nextRow->DeptID, $nextRow->DeptName, $nextRow->DeptSlug ];
        } else { // no department reserved yet, find best next choice...
        */
            $this->loadDeptPriorityRows();
            if (sizeof($this->v["deptPriorityRows"]) > 0) {
                $nextRow = $this->v["deptPriorityRows"][0];
                $this->v["nextDept"] = [ $nextRow->DeptID, $nextRow->DeptName, $nextRow->DeptSlug ];
            } else {
                $nextRow = NULL;
                $qmen = [];
                $qBase = "SELECT `DeptID`, `DeptName`, `DeptSlug` FROM `OP_Departments` WHERE ";
                $qReserves = " AND `DeptID` NOT IN (SELECT `TmpVal` FROM `OP_zVolunTmp` WHERE "
                    . "`TmpType` LIKE 'ZedDept' AND `TmpUser` NOT LIKE '" . Auth::user()->id . "')";
                $qmen[] = $qBase . "(`DeptVerified` < '2015-01-01 00:00:00' OR `DeptVerified` IS NULL) " 
                    . $qReserves . " ORDER BY RAND()";
                $qmen[] = $qBase . "1 " . $qReserves . " ORDER BY `DeptVerified`";
                $qmen[] = $qBase . "1 ORDER BY RAND()"; // worst-case backup
                for ($i = 0; ($i < sizeof($qmen) && !$nextRow); $i++) {
                    $nextRow = DB::select( DB::raw( $qmen[$i]." LIMIT 1" ) );
                }
                $this->v["nextDept"] = [
                    $nextRow[0]->DeptID, 
                    str_replace('Department', 'Dept', $nextRow[0]->DeptName), 
                    $nextRow[0]->DeptSlug
                ];
                
                // Temporarily reserve this department for this user
                $newTmp = new OPzVolunTmp;
                $newTmp->TmpUser = Auth::user()->id;
                $newTmp->TmpDate = date("Y-m-d H:i:s");
                $newTmp->TmpType = 'ZedDept';
                $newTmp->TmpVal  = $this->v["nextDept"][0];
                $newTmp->save();
            }
        //}
        return $this->v["nextDept"];
    }
    
    public function nextDept()
    {
        $this->getNextDept();
        return $this->redir('/dashboard/volunteer/verify/' . $this->v["nextDept"][2]);
    }
    
    public function newDeptAdd($deptName = '', $deptState = '') {
        if (trim($deptName) != '' && trim($deptState) != '') {
            $newDept = OPDepartments::where('DeptName', $deptName)->where('DeptAddressState', $deptState)->first();
            if ($newDept && isset($newDept->DeptSlug)) return $this->redir('/dashboard/volunteer/verify/'.$newDept->DeptSlug);
            $newDept = new OPDepartments;
            $newIA   = new OPOversight;
            $newIA->OverType           = $this->overWhichDefID('IA');
            $newDept->DeptName         = $deptName;
            $newDept->DeptAddressState = (($deptState != 'US') ? $deptState : '');
            $newDept->DeptSlug         = $deptState . '-' . Str::slug($deptName);
            $newDept->DeptType         = (($deptState == 'US') ? 366 : 0);
            $newDept->DeptStatus       = 1;
            $newDept->DeptUserID       = Auth::user()->id;
            $newDept->save();
            $newIA->OverDeptID         = $newDept->DeptID;
            $newIA->OverType           = $this->overWhichDefID('IA');
            $newIA->OverUserID         = Auth::user()->id;
            $newIA->save();
            return $newDept;
        }
        return [];
    }
    
    public function loadDeptEditsSummary()
    {
        $this->v["editsSummary"] = [
            '<b class="mL20">Last Verified: ' 
            . (($this->v["neverEdited"]) ? 'Never' : date('n/j/y', strtotime($this->v["deptRow"]->DeptVerified))) 
            . '</b> <span class="mL20"><nobr>' . intVal($this->v["editTots"]["online"]) 
            . '<span class="fPerc80">x</span><i class="fa fa-laptop"></i> Online Research,</nobr></span> '
            . '<span class="mL20"><nobr>' . intVal($this->v["editTots"]["callDept"]) 
            . '<span class="fPerc80">x</span><i class="fa fa-phone"></i> Department Calls,</nobr></span> '
            . '<span class="mL20"><nobr>' . intVal($this->v["editTots"]["callIA"]) 
            . '<span class="fPerc80">x</span><i class="fa fa-phone"></i> Internal Affairs Calls</nobr></span>',
            
            (($this->v["neverEdited"]) ? '<span class="slGrey">' : '') 
            . intVal($this->v["editTots"]["online"]) . '<i class="fa fa-laptop"></i>, ' 
            . intVal($this->v["editTots"]["callDept"]) . '<i class="fa fa-phone"></i>, ' 
            . intVal($this->v["editTots"]["callIA"]) . '<i class="fa fa-phone"></i>'
            . (($this->v["neverEdited"]) ? '</span>' : '')
            ];
        return true;
    }
    
    protected function genDeptAdmTopMenu()
    {
        $lnks = [
            [ 'deptContact',   'Contact Info' ],
            [ 'deptWeb',       'Web Presence' ],
            [ 'deptIA',        'Internal Affairs' ],
            [ 'deptCiv',       'Civilian Oversight' ],
            [ 'deptSave',      '<i class="fa fa-floppy-o mR5" aria-hidden="true"></i> Save Changes' ],
            [ 'deptEdits',     'Dept Edit History' ],
            [ 'deptChecklist', 'Checklist & Scripts' ]
            ];
        foreach ($lnks as $lnk) $GLOBALS["SL"]->pageJAVA .= 'addHshoo("#' . $lnk[0] . '"); ';
        return view('vendor.openpolice.volun.volun-dept-edit-adm-menu', [
            "deptRow" => $this->v["deptRow"],
            "lnks"    => $lnks
            ])->render();
    }
    
    protected function printDeptEditHeader()
    {
        $this->v["deptRow"]     = $this->sessData->dataSets["Departments"][0];
        $this->v["deptSlug"]    = $this->v["deptRow"]->DeptSlug;
        $this->v["editsIA"]     = $this->v["editsCiv"] = $this->v["userEdits"] = $this->v["userNames"] = [];
        $this->v["editTots"]    = ["notes" => 0, "online" => 0, "callDept" => 0, "callIA" => 0];
        $this->v["user"]        = Auth::user();
        $this->v["neverEdited"] = false;
        $this->v["recentEdits"] = '';
        
        if (!isset($this->v["deptRow"]->DeptID) || intVal($this->v["deptRow"]->DeptID) <= 0) {
            return $this->redir('/dashboard/volunteer');
        }
        
        $this->v["editsDept"] = OPZeditDepartments::where('ZedDeptDeptID', $this->v["deptRow"]->DeptID)
            ->orderBy('ZedDeptDeptVerified', 'desc')
            ->get();
        if ($this->v["editsDept"]->isNotEmpty()) {
            foreach ($this->v["editsDept"] as $i => $edit) {
                $this->v["editsIA"][$i]  = OPZeditOversight::where('ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', $this->overWhichDefID('IA'))
                    ->first();
                $this->v["editsCiv"][$i] = OPZeditOversight::where('ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', $this->overWhichDefID('Civ'))
                    ->first();
                if ($this->v["editsIA"][$i]) {
                    if (trim($this->v["editsIA"][$i]->ZedOverNotes) != '')           $this->v["editTots"]["notes"]++;
                    if (intVal($this->v["editsIA"][$i]->ZedOverOnlineResearch) == 1) $this->v["editTots"]["online"]++;
                    if (intVal($this->v["editsIA"][$i]->ZedOverMadeDeptCall) == 1)   $this->v["editTots"]["callDept"]++;
                    if (intVal($this->v["editsIA"][$i]->ZedOverMadeIACall) == 1)     $this->v["editTots"]["callIA"]++;
                }
                if (!isset($this->v["userNames"][$edit->ZedDeptUserID])) {
                    $this->v["userNames"][$edit->ZedDeptUserID] = $this->printUserLnk($edit->ZedDeptUserID);
                }
                if ($this->v["user"]->hasRole('administrator|staff')) {
                    $this->v["recentEdits"] .= view('vendor.openpolice.volun.admPrintDeptEdit', [
                        "user"     => $this->v["userNames"][$edit->ZedDeptUserID], 
                        "deptRow"  => $this->v["deptRow"], 
                        "deptEdit" => $edit, 
                        "deptType" => $GLOBALS["SL"]->def->getVal('Department Types', $edit->DeptType),
                        "iaEdit"   => $this->v["editsIA"][$i], 
                        "civEdit"  => $this->v["editsCiv"][$i]
                    ])->render();
                }
            }
        } else {
            $this->v["neverEdited"] = true;
        }
        $this->loadDeptEditsSummary();
        $GLOBALS["SL"]->loadStates();
        $GLOBALS["SL"]->x["admMenuCustom"] = $this->genDeptAdmTopMenu();
        if (!isset($this->v["deptScores"])) $this->v["deptScores"] = new DepartmentScores;
        return view('vendor.openpolice.nodes.1225-volun-dept-edit-header', $this->v)->render();
    }
    
    protected function printDeptEditContact($nID)
    {
        $this->getOverRow('IA');
        $this->getOverRow('Civ');
        $which = [ 'IA', 1305 ];
        $hasCiv = false;
        if ($nID == 1229) {
            if (isset($this->v["overRowCiv"]->OverAgncName) && trim($this->v["overRowCiv"]->OverAgncName) != '') {
                $hasCiv = true;
            }
            $which = [ 'Civ', 1267 ];
        }
        $overRow = $this->getOverRow($which[0]);
        $hasC = ((isset($overRow->OverNameFirst) && trim($overRow->OverNameFirst) != '')
            || (isset($overRow->OverNameLast) && trim($overRow->OverNameLast) != '') 
            || (isset($overRow->OverTitle) && trim($overRow->OverTitle) != ''));
        return view('vendor.openpolice.volun.volun-dept-edit-ia-contact', [
            "type"   => $this->overWhichEng($which[0]),
            "whch"   => $which[0],
            "n"      => $which[1],
            "hasCiv" => $hasCiv,
            "hasC"   => $hasC
            ])->render();
    }
    
    protected function redirAfterDeptEdit()
    {
        $redir = '/dash/volunteer';
        $msg = 'Back To The Department List...';
        if ($GLOBALS["SL"]->REQ->has('n1335fld')) {
            if (trim($GLOBALS["SL"]->REQ->get('n1335fld')) == 'again') {
                $redir = '/dashboard/start-' . $this->sessData->dataSets["Departments"][0]->DeptID 
                    . '/volunteers-research-departments';
                $msg = 'Saving Changes...';
            } elseif (trim($GLOBALS["SL"]->REQ->get('n1335fld')) == 'another') {
                session()->forget('sessID36');
                session()->forget('coreID36');
                $this->getNextDept();
                $redir = '/dashboard/start-' . $this->v["nextDept"][0] . '/volunteers-research-departments';
                $msg = 'To The Next Department...';
            }
        }
        $GLOBALS["SL"]->pageJAVA .= 'setTimeout("window.location=\'' . $redir . '\'", 10); ';
        return '<center><h2 class="slBlueDark">' . $msg . '</h2>' . $GLOBALS["SL"]->sysOpts["spinner-code"]
            . '</center><style> #nodeSubBtns, #sessMgmt { display: none; } </style>';
    }

    protected function redirNextDeptEdit()
    {
        session()->forget('sessID36');
        session()->forget('coreID36');
        $this->getNextDept();
        $redir = '/dashboard/start-' . $this->v["nextDept"][0] . '/volunteers-research-departments';
        $GLOBALS["SL"]->pageJAVA .= 'setTimeout("window.location=\'' . $redir . '\'", 10); ';
        return '<center><h2 class="slBlueDark">To The Next Department...</h2>' . $GLOBALS["SL"]->sysOpts["spinner-code"]
            . '</center><style> #nodeSubBtns, #sessMgmt { display: none; } </style>';
    }
    
    protected function volunStars()
    {
        $this->v["leaderboard"] = new VolunteerLeaderboard;
        $this->v["yourStats"] = [];
        if ($this->v["leaderboard"]->UserInfoStars && sizeof($this->v["leaderboard"]->UserInfoStars) > 0) {
            foreach ($this->v["leaderboard"]->UserInfoStars as $i => $volunUser) {
                if ($volunUser->UserInfoUserID == $this->v["uID"]) $this->v["yourStats"] = $volunUser;
            }
        }
        return view('vendor.openpolice.volun.stars', $this->v)->render();
    }
        
    public function volunDeptsRecent()
    {
        $this->v["statTots"] = [];
        $statRanges = [ [
                'Last 24 Hours', 
                date("Y-m-d H:i:s", mktime(date("H")-24, date("i"), date("s"), date("n"), date("j"), date("Y")))
            ], [
                'This Week', 
                date("Y-m-d H:i:s", mktime(date("H"), 0, 0, date("n"), date("j")-7, date("Y")))
            ], [
                'All-Time Totals', 
                date("Y-m-d H:i:s", mktime(0, 0, 0, 1, 1, 1000))
            ],
        ];
        foreach ($statRanges as $i => $stat) {
            $this->v["statTots"][$i] = [ $stat[0] ];
            $this->v["statTots"][$i][] = OPZeditDepartments::distinct('ZedDeptUserID')
                ->where('ZedDeptDeptVerified', '>', $stat[1])
                ->count();
            $this->v["statTots"][$i][] = OPZeditDepartments::select('ZedDeptID')
                ->where('ZedDeptDeptVerified', '>', $stat[1])
                ->count();
            $overQry = ((strpos($stat[1], "WHERE") === false) 
                ? " WHERE `ZedOverOverType` LIKE '303'" : " AND `ZedOverOverType` LIKE '303'");
            $res = DB::select( DB::raw("SELECT SUM(`ZedOverOnlineResearch`) as `tot` FROM `OP_Zedit_Oversight` WHERE
                ZedOverOverVerified > '" . $stat[1] . "' AND `ZedOverOverType` LIKE '303'") );
            $this->v["statTots"][$i][] = $res[0]->tot;
            $res = DB::select( DB::raw("SELECT SUM(`ZedOverMadeDeptCall`) as `tot` FROM `OP_Zedit_Oversight` WHERE
                ZedOverOverVerified > '" . $stat[1] . "' AND `ZedOverOverType` LIKE '303'") );
            $this->v["statTots"][$i][] = $res[0]->tot;
            $res = DB::select( DB::raw("SELECT SUM(`ZedOverMadeIACall`) as `tot` FROM `OP_Zedit_Oversight` WHERE
                ZedOverOverVerified > '" . $stat[1] . "' AND `ZedOverOverType` LIKE '303'") );
            $this->v["statTots"][$i][] = $res[0]->tot;
            $res = DB::select( DB::raw("SELECT DISTINCT `ZedDeptDeptID` FROM `OP_Zedit_Departments` WHERE 
                ZedDeptDeptVerified > '" . $stat[1] . "'") );
            $this->v["statTots"][$i][] = (($res) ? sizeof($res) : 0);
        }
        return true;
    }
        
    public function volunDepts()
    {
        $this->volunDeptsRecent();
        $deptEdits = [];
        $recentEdits = OPZeditDepartments::take(100)
            ->orderBy('ZedDeptDeptVerified', 'desc')
            ->get();
        if ($recentEdits->isNotEmpty()) {
            foreach ($recentEdits as $i => $edit) {
                $iaEdit  = OPZeditOversight::where('ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', 303)
                    ->first();
                $civEdit = OPZeditOversight::where('ZedOverZedDeptID', $edit->ZedDeptID)
                    ->where('ZedOverOverType', 302)
                    ->first();
                $userObj = User::find($edit->ZedDeptUserID);
                $deptEdits[] = [ ($userObj) ? $userObj->printUsername() : '', $edit, $iaEdit, $civEdit ];
            }
        }
        //echo '<pre>'; print_r($deptEdits); echo '</pre>';
        $this->v["recentEdits"] = '';
        foreach ($deptEdits as $deptEdit) {
            $this->v["recentEdits"] .= view('vendor.openpolice.volun.admPrintDeptEdit', [
                "user"     => $deptEdit[0], 
                "deptRow"  => OPDepartments::find($deptEdit[1]->ZedDeptDeptID), 
                "deptEdit" => $deptEdit[1], 
                "deptType" => $GLOBALS["SL"]->def->getVal('Department Types', $deptEdit[1]->ZedDeptType),
                "iaEdit"   => $deptEdit[2], 
                "civEdit"  => $deptEdit[3]
            ])->render();
        }
        $this->volunStatsDailyGraph();
        return view('vendor.openpolice.nodes.1351-admin-volun-edit-history', $this->v)->render();
    }
    
    public function volunStatsDailyGraph()
    {
        if (!isset($this->v["statTots"])) $this->volunDeptsRecent();
        $this->recalcVolunStats();
        $past = 60;
        $startDate = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j")-$past, date("Y")));
        $this->v["statDays"] = OPzVolunStatDays::where('VolunStatDate', '>=', $startDate)
            ->orderBy('VolunStatDate', 'asc')
            ->get();
        $this->v["axisLabels"] = [];
        foreach ($this->v["statDays"] as $i => $s) {
            if ($i%5 == 0) {
                $this->v["axisLabels"][] = date('n/j', strtotime($s->VolunStatDate));
            } else {
                $this->v["axisLabels"][] = '';
            }
        }
        $lines = [];
        $lines[0] = [
            "label"     => 'Unique Departments', 
            "brdColor"     => '#2b3493', 
            "dotColor"     => 'rgba(75,192,192,1)', 
            "data"         => [], 
        ];
        foreach ($this->v["statDays"] as $s) $lines[0]["data"][] = $s->VolunStatDeptsUnique;
        $lines[1] = [
            "label"     => 'Unique Users', 
            "brdColor"     => '#63c6ff', 
            "dotColor"     => 'rgba(75,192,192,1)', 
            "data"         => [], 
        ];
        foreach ($this->v["statDays"] as $s) $lines[1]["data"][] = $s->VolunStatUsersUnique;
        $lines[2] = [
            "label"     => 'Total Edits', 
            "brdColor"     => '#c3ffe1', 
            "dotColor"     => 'rgba(75,192,192,1)', 
            "data"         => [], 
        ];
        foreach ($this->v["statDays"] as $s) $lines[2]["data"][] = $s->VolunStatTotalEdits;
        $lines[3] = [
            "label"     => 'Total Calls', 
            "brdColor"     => '#29B76F', 
            "dotColor"     => 'rgba(75,192,192,1)', 
            "data"         => [], 
        ];
        foreach ($this->v["statDays"] as $s) $lines[3]["data"][] = $s->VolunStatCallsTot;
        $lines[4] = [
            "label"     => 'Signups', 
            "brdColor"     => '#ffd2c9', 
            "dotColor"     => 'rgba(75,192,192,1)', 
            "data"         => [], 
        ];
        foreach ($this->v["statDays"] as $s) $lines[4]["data"][] = $s->VolunStatSignups;
        $this->v["dataLines"] = '';
        foreach ($lines as $l) {
            $this->v["dataLines"] .= view('vendor.survloop.graph-data-line', $l)->render();
        }
        $this->v["volunDataGraph"] = view('vendor.openpolice.nodes.1351-volun-graph', $this->v)->render();
        $GLOBALS["SL"]->x["needsCharts"] = true;
        return $this->v["volunDataGraph"];
    }
    
    public function volunStatsInitDay()
    {
        return [
            'signups'         => 0, 
            'logins'          => 0, 
            'usersUnique'     => 0, 
            'deptsUnique'     => 0, 
            'onlineResearch'  => 0, 
            'callsDept'       => 0, 
            'callsIA'         => 0, 
            'callsTot'        => 0, 
            'totalEdits'      => 0,
            'onlineResearchV' => 0, 
            'callsDeptV'      => 0, 
            'callsIAV'        => 0, 
            'callsTotV'       => 0, 
            'totalEditsV'     => 0,
            'users'           => [], 
            'depts'           => []
        ];
    }
    
    public function recalcVolunStats()
    {
        $past = 100;
        $startDate = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j")-$past, date("Y")));
        $days = [];
        for ($i = 0; $i < $past; $i++) {
            $day = date("Y-m-d", mktime(0, 0, 0, date("n"), date("j")-$i, date("Y")));
            $days[$day] = $this->volunStatsInitDay();
        }
        
        $volunteers = [];
        $users = DB::table('users')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('SL_UsersRoles')
                    ->where('SL_UsersRoles.RoleUserRID', 17) // 'volunteer'
                    ->whereRaw('SL_UsersRoles.RoleUserUID = users.id');
            })
            ->get();
        if ($users->isNotEmpty()) {
            foreach ($users as $i => $u) {
                $volunteers[] = $u->id;
                if (strtotime($u->created_at) > strtotime($startDate)) {
                    $dataInd = date("Y-m-d", strtotime($u->created_at));
                    if (isset($days[$dataInd])) $days[$dataInd]["signups"]++;
                }
            }
        }
        
        $edits  = OPZeditOversight::where('OP_Zedit_Oversight.ZedOverOverType', 303)
            ->join('OP_Zedit_Departments', 'OP_Zedit_Departments.ZedDeptID', '=', 'OP_Zedit_Oversight.ZedOverZedDeptID')
            ->where('OP_Zedit_Oversight.ZedOverOverVerified', '>', date("Y-m-d", strtotime($startDate)).' 00:00:00')
            ->select('OP_Zedit_Oversight.*', 'OP_Zedit_Departments.ZedDeptUserID')
            ->get();
        if ($edits->isNotEmpty()) {
            foreach ($edits as $i => $e) {
                $day = date("Y-m-d", strtotime($e->ZedOverOverVerified));
                if (!isset($days[$day])) $days[$day] = $this->volunStatsInitDay();
                $days[$day]["totalEdits"]++;
                $days[$day]["onlineResearch"] += intVal($e->ZedOverOnlineResearch);
                $days[$day]["callsDept"]      += intVal($e->ZedOverMadeDeptCall);
                $days[$day]["callsIA"]        += intVal($e->ZedOverMadeIACall);
                $days[$day]["callsTot"]       += intVal($e->ZedOverMadeDeptCall) + intVal($e->ZedOverMadeIACall);
                if (in_array($e->ZedDeptUserID, $volunteers)) {
                    $days[$day]["totalEditsV"]++;
                    $days[$day]["onlineResearchV"] += intVal($e->ZedOverOnlineResearch);
                    $days[$day]["callsDeptV"]      += intVal($e->ZedOverMadeDeptCall);
                    $days[$day]["callsIAV"]        += intVal($e->ZedOverMadeIACall);
                    $days[$day]["callsTotV"]       += intVal($e->ZedOverMadeDeptCall) + intVal($e->ZedOverMadeIACall);
                }
                if (!in_array($e->ZedDeptUserID, $days[$day]["users"])) $days[$day]["users"][] = $e->ZedDeptUserID;
                if (!in_array($e->ZedOverDeptID, $days[$day]["depts"])) $days[$day]["depts"][] = $e->ZedOverDeptID;
            }
        }
        
        OPzVolunStatDays::where('VolunStatDate', '>=', $startDate)
            ->delete();
        foreach ($days as $day => $stats) {
            $newDay = new OPzVolunStatDays;
            $newDay->VolunStatDate            = $day;
            $newDay->VolunStatSignups         = $stats["signups"];
            $newDay->VolunStatLogins          = $stats["logins"];
            $newDay->VolunStatUsersUnique     = sizeof($stats["users"]);
            $newDay->VolunStatDeptsUnique     = sizeof($stats["depts"]);
            $newDay->VolunStatOnlineResearch  = $stats["onlineResearch"];
            $newDay->VolunStatCallsDept       = $stats["callsDept"];
            $newDay->VolunStatCallsIA         = $stats["callsIA"];
            $newDay->VolunStatCallsTot        = $stats["callsTot"];
            $newDay->VolunStatTotalEdits      = $stats["totalEdits"];
            $newDay->VolunStatOnlineResearchV = $stats["onlineResearchV"];
            $newDay->VolunStatCallsDeptV      = $stats["callsDeptV"];
            $newDay->VolunStatCallsIAV        = $stats["callsIAV"];
            $newDay->VolunStatCallsTotV       = $stats["callsTotV"];
            $newDay->VolunStatTotalEditsV     = $stats["totalEditsV"];
            $newDay->save();
        }
        
        return true;
    }
    
    protected function printDashSessGraph()
    {
        $GLOBALS["SL"]->x["needsCharts"] = true;
        $GLOBALS["SL"]->pageAJAX .= '$("#1342graph").load("/dashboard/surv-1/sessions/graph-daily"); '
            . '$("#1342graph2").load("/dashboard/surv-1/sessions/graph-durations"); ';
        return '<div id="1342graph" class="w100" style="height: 400px;"></div><div class="p10">&nbsp;</div>'
            . '<div id="1342graph2" class="w100" style="height: 400px;"></div><div class="p10">&nbsp;</div>'
            . '<div class="pT5"><a href="/dashboard/surv-1/sessions?refresh=1">Full Session Stats Report</a></div>'
            . '<div class="p20"> </div>';
    }
    
    protected function printDashTopStats()
    {
        $this->v["statRanges"] = [
            ['Last 24 Hrs', mktime(date("H")-24, date("i"), date("s"), date("n"), date("j"), date("Y"))],
            ['This Week', mktime(date("H"), 0, 0, date("n"), date("j")-7, date("Y"))],
            ['All-Time Totals', mktime(0, 0, 0, 1, 1, 1900)]
            ];
        $this->v["statusDefs"] = $GLOBALS["SL"]->def->getSet('Complaint Status');
        $this->v["dashTopStats"] = [];
        foreach ($this->v["statRanges"] as $j => $range) {
            $this->v["dashTopStats"][$j] = [];
            foreach ($this->v["statusDefs"] as $def) $this->v["dashTopStats"][$j][$def->DefID] = 0;
        }
        $chk = OPComplaints::select('ComID', 'ComPublicID', 'ComStatus', 'created_at')
            ->where('ComStatus', '>', 0)
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $i => $complaint) {
                foreach ($this->v["statRanges"] as $j => $range) {
                    if (strtotime($complaint->created_at) > $range[1]) {
                        $this->v["dashTopStats"][$j][$complaint->ComStatus]++;
                    }
                }
            }
        }
        return view('vendor.openpolice.nodes.1361-dash-top-stats', $this->v)->render();
    }
    
    protected function setUserOversightFilt()
    {
        if (!isset($this->v["fltIDs"])) $this->v["fltIDs"] = [];
        $this->v["fltDept"] = -3;
        if ($this->v["user"]->hasRole('partner') && $this->v["user"]->hasRole('oversight')) {
            $this->v["fltIDs"][0] = [];
            $overRow = OPOversight::where('OverEmail', $this->v["user"]->email)->first();
            if ($overRow && isset($overRow->OverDeptID)) {
                $this->v["fltDept"] = $overRow->OverDeptID;
                $lnkChk = OPLinksComplaintDept::select('LnkComDeptComplaintID')
                    ->where('LnkComDeptDeptID', $overRow->OverDeptID)
                    ->get();
                if ($lnkChk->isNotEmpty()) {
                    foreach ($lnkChk as $lnk) $this->v["fltIDs"][0][] = $lnk->LnkComDeptComplaintID;
                }
            }
        }
        return true;
    }
    
    protected function printComplaintListing($view = 'all')
    {
        $this->v["listView"] = $view;
        if ($GLOBALS["SL"]->REQ->has('fltView')) $this->v["listView"] = $GLOBALS["SL"]->REQ->fltView;
        $this->v["fltStatus"] = (($GLOBALS["SL"]->REQ->has('fltStatus')) ? $GLOBALS["SL"]->REQ->fltStatus : 0);
        $sort = "date";
        $qman = "SELECT c.*, p.`PrsnNameFirst`, p.`PrsnNameLast`, i.* 
            FROM `OP_Complaints` c 
            JOIN `OP_Incidents` i ON c.`ComIncidentID` LIKE i.`IncID` 
            LEFT OUTER JOIN `OP_Civilians` civ ON c.`ComID` LIKE civ.`CivComplaintID` 
            LEFT OUTER JOIN `OP_PersonContact` p ON p.`PrsnID` LIKE civ.`CivPersonID` WHERE "
            . ((isset($this->v["fltQry"])) ? $this->v["fltQry"] : "");
        if (isset($this->v["fltIDs"]) && sizeof($this->v["fltIDs"]) > 0) {
            foreach ($this->v["fltIDs"] as $ids) $qman .= " c.`ComID` IN (" . implode(', ', $ids) . ") AND ";
        }
        $qman .= " civ.`CivIsCreator` LIKE 'Y' ";
        if ($this->v["fltStatus"] > 0) $qman .= " AND c.`ComStatus` LIKE '" . $this->v["fltStatus"] . "' ";
        switch ($this->v["listView"]) {
            case 'review':         
                $qman .= " AND (c.`ComStatus` LIKE '" . $GLOBALS["SL"]->def->getID('Complaint Status', 'New') . "' 
                    OR (c.`ComType` IN ('" . $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Unreviewed')
                    . "', '" . $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Not Sure') 
                    . "') AND c.`ComStatus` NOT LIKE '" . $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete') 
                    . "') )"; 
                break;
            case 'mine':     
                $qman .= " AND c.`ComAdminID` LIKE '" . $this->v["user"]->id . "' 
                    AND c.`ComStatus` NOT LIKE '" . $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete') . "'";
                break;
            case 'flagged':
                $qman .= " AND (c.`ComStatus` IN ('" . $GLOBALS["SL"]->def->getID('Complaint Status', 'Hold') . "', '" 
                    . $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney') . "') )"; 
                break;
            case 'waiting':
                $qman .= " AND (c.`ComStatus` IN ('" . $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d') 
                    . "', '" . $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight') . "', '" 
                    . $GLOBALS["SL"]->def->getID('Complaint Status', 'Received by Oversight') . "', '" 
                    . $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Oversight Investigation') . "') )"; 
                break;
            case 'incomplete':     
                $qman .= " AND c.`ComStatus` LIKE '" 
                    . $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete') . "'";
                break;
            case 'all':     
            default:
                $qman .= " AND c.`ComStatus` NOT LIKE '" 
                    . $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete') . "'";
                break;
        }
        $this->v["complaints"] = $this->v["comInfo"] = $this->v["lastNodes"] = $this->v["ajaxRefreshs"] = [];
        $compls = DB::select( DB::raw($qman) );
        if ($compls && sizeof($compls) > 0) {
            foreach ($compls as $com) {
                $this->v["comInfo"][$com->ComPublicID] = [ '', '' ];
                $dChk = DB::table('OP_LinksComplaintDept')
                    ->where('OP_LinksComplaintDept.LnkComDeptComplaintID', $com->ComID)
                    ->leftJoin('OP_Departments', 'OP_Departments.DeptID', '=', 'OP_LinksComplaintDept.LnkComDeptDeptID')
                    ->select('OP_Departments.DeptName', 'OP_Departments.DeptSlug')
                    ->orderBy('OP_Departments.DeptName', 'asc')
                    ->get();
                if ($dChk && sizeof($dChk) > 0) {
                    foreach ($dChk as $i => $d) {
                        $this->v["comInfo"][$com->ComPublicID][1] .= (($i > 0) ? ', ' : '') 
                            . str_replace('Department' , 'Dept', $d->DeptName);
                    }
                }
                $comTime = strtotime($com->updated_at);
                if (trim($com->ComRecordSubmitted) != '' && $com->ComRecordSubmitted != '0000-00-00 00:00:00') {
                    $comTime = strtotime($com->ComRecordSubmitted);
                }
                if (!isset($com->ComStatus) || intVal($com->ComStatus) <= 0) {
                    $com->ComStatus = $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete');
                    OPComplaints::find($com->ComID)->update([ "ComStatus" => $com->ComStatus ]);
                }
                if (!isset($com->ComType) || intVal($com->ComType) <= 0) {
                    $com->ComType = $GLOBALS['SL']->def->getID('OPC Staff/Internal Complaint Type', 'Unreviewed');
                    OPComplaints::find($com->ComID)->update([ "ComType" => $com->ComType ]);
                }
                $cutoffTime = mktime(date("H"), date("i"), date("s"), date("m"), date("d")-1, date("Y"));
                if ($comTime < $cutoffTime) {
                    if (!isset($com->ComSummary) || trim($com->ComSummary) == '') {
                        OPComplaints::find($com->ComID)->delete();
                        $comTime = false;
                    }
                }
                if ($comTime !== false) {
                    $sortInd = $comTime;
                    $this->v["comInfo"][$com->ComPublicID][0] = date("n/j/Y", $comTime);
                    if ($com->ComStatus == $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete')) {
                        if ($com->ComSubmissionProgress > 0 
                            && !isset($this->v["lastNodes"][$com->ComSubmissionProgress])) {
                            $node = SLNode::find($com->ComSubmissionProgress);
                            if ($node && isset($node->NodePromptNotes)) {
                                $this->v["lastNodes"][$com->ComSubmissionProgress] = $node->NodePromptNotes;
                            }
                        }
                    }
                    $this->v["complaints"][$sortInd] = $com;
                }
                if (!isset($com->ComAllegList) || trim($com->ComAllegList) == '') {
                    $this->v["ajaxRefreshs"][] = $com->ComPublicID;
                }
            }
            krsort($this->v["complaints"]);
        }
        $GLOBALS["SL"]->pageAJAX 
            .= view('vendor.openpolice.nodes.1418-admin-complaints-listing-ajax', $this->v)->render();
        return view('vendor.openpolice.nodes.1418-admin-complaints-listing', $this->v)->render();
    }
    
    protected function getNameTopAnon()
    {
        return '<span style="color: #2b3493;" title="This complainant did not provide their name to investigators.">'
            . 'Complainant</span>';
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
    
    
    
    /******************************************
    *
    * ADMIN TOOLS
    *
    ******************************************/
    
    
    protected function printComplaintAdmin()
    {
        $this->v["firstRevDone"] = false;
        if ($this->v["user"]->hasRole('administrator|databaser|staff')) {
            $GLOBALS["SL"]->addTopNavItem('All Complaints', '/dash/all-complete-complaints');
        }
        if ($GLOBALS["SL"]->REQ->has('firstReview') && intVal($GLOBALS["SL"]->REQ->firstReview) > 0) {
            $newTypeVal = $GLOBALS["SL"]->def->getVal('OPC Staff/Internal Complaint Type', 
                $GLOBALS["SL"]->REQ->firstReview);
            $newRev = new OPzComplaintReviews;
            $newRev->ComRevComplaint = $this->coreID;
            $newRev->ComRevUser      = $this->v["user"]->id;
            $newRev->ComRevDate      = date("Y-m-d H:i:s");
            $newRev->ComRevType      = 'First';
            $newRev->ComRevStatus    = $newTypeVal;
            $newRev->save();
            $com = OPComplaints::find($this->coreID);
            $com->comType = $GLOBALS["SL"]->REQ->firstReview;
            $com->save();
            $this->v["firstRevDone"] = true;
        } elseif ($GLOBALS["SL"]->REQ->has('save')) {
            $newRev = new OPzComplaintReviews;
            $newRev->ComRevComplaint = $this->coreID;
            $newRev->ComRevUser      = $this->v["user"]->id;
            $newRev->ComRevDate      = date("Y-m-d H:i:s");
            $newRev->ComRevType      = 'Update';
            $newRev->ComRevNote      = (($GLOBALS["SL"]->REQ->has('revNote')) ? $GLOBALS["SL"]->REQ->revNote : '');
            if ($GLOBALS["SL"]->REQ->has('revStatus')) {
                $newRev->ComRevStatus = $GLOBALS["SL"]->REQ->revStatus;
                if (in_array($GLOBALS["SL"]->REQ->revStatus, ['Hold: Go Gold', 'Hold: Not Sure'])) {
                    $this->sessData->dataSets["Complaints"][0]->ComStatus 
                        = $GLOBALS["SL"]->def->getID('Complaint Status', 'Hold');
                } elseif (in_array($GLOBALS["SL"]->REQ->revStatus, [
                    'Pending Attorney: Needed', 'Pending Attorney: Hook-Up'])) {
                    $this->sessData->dataSets["Complaints"][0]->ComStatus 
                        = $GLOBALS["SL"]->def->getID('Complaint Status', 'Pending Attorney');
                } elseif (in_array($GLOBALS["SL"]->REQ->revStatus, ['Attorney\'d'])) {
                    $this->sessData->dataSets["Complaints"][0]->ComStatus 
                        = $GLOBALS["SL"]->def->getID('Complaint Status', 'Attorney\'d');
                } else {
                    $this->sessData->dataSets["Complaints"][0]->ComStatus 
                        = $GLOBALS["SL"]->def->getID('Complaint Status', $GLOBALS["SL"]->REQ->revStatus);
                }
            }
            if ($GLOBALS["SL"]->REQ->has('revComplaintType')) {
                $newTypeVal = $GLOBALS["SL"]->def->getVal('OPC Staff/Internal Complaint Type', 
                    $GLOBALS["SL"]->REQ->revComplaintType);
                if ($newTypeVal != 'Police Complaint') $newRev->ComRevStatus = $newTypeVal;
                $this->sessData->dataSets["Complaints"][0]->ComType = $GLOBALS["SL"]->REQ->revComplaintType;
            }
            $newRev->save();
            $this->sessData->dataSets["Complaints"][0]->save();
        }
        $this->v["firstReview"]  = true;
        $this->v["lastReview"]   = true;
        $this->v["history"]      = [];
        $allUserNames = [];
        $reviews = OPzComplaintReviews::where('ComRevComplaint', '=', $this->coreID)
            ->where('ComRevType', 'NOT LIKE', 'Draft')
            ->orderBy('ComRevDate', 'desc')
            ->get();
        if ($reviews->isNotEmpty()) {
            foreach ($reviews as $i => $r) {
                if ($i == 0) $this->v["lastReview"] = $r;
                $this->v["firstReview"] = false;
                if (!isset($allUserNames[$r->ComRevUser])) {
                    $allUserNames[$r->ComRevUser] = $this->printUserLnk($r->ComRevUser);
                }
                $desc = '<span class="slBlueDark">' 
                    . ((isset($r->ComRevNextAction) && trim($r->ComRevNextAction) == 'Complaint Received'
                        && $r->ComRevStatus == 'Submitted to Oversight') ? $r->ComRevNextAction : $r->ComRevStatus)
                    . '</span>';
                $this->v["history"][] = [
                    "type" => 'Status', 
                    "date" => strtotime($r->ComRevDate), 
                    "desc" => $desc, 
                    "who"  => $allUserNames[$r->ComRevUser],
                    "note" => ((isset($r->ComRevNote)) ? trim($r->ComRevNote) : '')
                ];
            }
        }
        $this->v["emailList"] = SLEmails::orderBy('EmailName', 'asc')
            ->orderBy('EmailType', 'asc')
            ->get();
        $emails = SLEmailed::where('EmailedTree', 1)
            ->where('EmailedRecID', $this->coreID)
            ->orderBy('created_at', 'asc')
            ->get();
        if ($emails->isNotEmpty()) {
            foreach ($emails as $i => $e) {
                if (!isset($allUserNames[$e->EmailedFromUser])) {
                    $allUserNames[$e->EmailedFromUser] = $this->printUserLnk($e->EmailedFromUser);
                }
                $desc = '<a href="javascript:;" id="hidFldBtnEma' . $e->EmailedID . '" class="hidFldBtn">' 
                    . $e->EmailedSubject . '</a> <i>to ' . substr($e->EmailedTo, 0, strpos($e->EmailedTo, '<'))  
                    . '<span class="fPerc66">&lt; ' 
                    . str_replace('>', '', substr($e->EmailedTo, 1+strpos($e->EmailedTo, '<'))) . ' &gt;</span></i>'
                    . '<div id="hidFldEma' . $e->EmailedID . '" class="disNon p10">' . $e->EmailedBody . '</div>';
                $this->v["history"][] = [
                    "type" => 'Email', 
                    "date" => strtotime($e->created_at), 
                    "desc" => $desc, 
                    "who"  => $allUserNames[$e->EmailedFromUser]
                ];
            }
        }
        $this->v["history"] = $GLOBALS["SL"]->sortArrByKey($this->v["history"], 'date', 'desc');
        $this->prepEmailComData();
        $isOverCompatible = false;
        if (isset($this->v["comDepts"][0])) {
            $w = $this->v["comDepts"][0]["whichOver"];
            if (isset($this->v["comDepts"][0][$w])) {
                $isOverCompatible = $this->isOverCompatible($this->v["comDepts"][0][$w]);
            }
        }
        $this->v["emailsTo"] = [ "To Complainant" => [], "To Oversight" => [] ];
        $complainantUser = User::find($this->sessData->dataSets["Complaints"][0]->ComUserID);
        if ($complainantUser && isset($complainantUser->email)) {
            $name = $complainantUser->name;
            if (isset($this->sessData->dataSets["PersonContact"])
                && sizeof($this->sessData->dataSets["PersonContact"]) > 0
                && isset($this->sessData->dataSets["PersonContact"][0]->PrsnNameFirst)) {
                $name = $this->sessData->dataSets["PersonContact"][0]->PrsnNameFirst . ' '
                    . $this->sessData->dataSets["PersonContact"][0]->PrsnNameLast;
            }
            $this->v["emailsTo"]["To Complainant"][] = [ $complainantUser->email, $name, true ];
        }
        if ($isOverCompatible) {
            $this->v["emailsTo"]["To Oversight"][] = [
                $this->v["comDepts"][0][$this->v["comDepts"][0]["whichOver"]]->OverEmail,
                $this->v["comDepts"][0][$this->v["comDepts"][0]["whichOver"]]->OverAgncName,
                true
            ];
        }
        $this->v["emailMap"] = [ // 'Review Status' => Email ID#
                'Submitted to Oversight'    => [7, 12], 
                'Hold: Go Gold'             => [6],
                'Pending Attorney: Needed'  => [17],
                'Pending Attorney: Hook-Up' => [18]
            ];
        $this->v["emailID"] = ($GLOBALS["SL"]->REQ->has('email') ? $GLOBALS["SL"]->REQ->email : -3);
        if ($this->v["emailID"] <= 0) {
            switch ($this->sessData->dataSets["Complaints"][0]->ComStatus) {
                case $GLOBALS["SL"]->def->getID('Complaint Status', 'OK to Submit to Oversight'):
                    if ($isOverCompatible) {
                        $this->v["emailID"] = 12; // Send to oversight agency
                    } else {
                        $this->v["emailID"] = 9; // How to manually submit
                    }
                    break;
                case $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight'):
                case $GLOBALS["SL"]->def->getID('Complaint Status', 'Received by Oversight'):
                    $chk = SLEmailed::where('EmailedTree', 1)
                        ->where('EmailedRecID', $this->coreID)
                        ->where('EmailedEmailID', 7)
                        ->first();
                    if (!$chk || !isset($chk->created_at)) {
                        $this->v["emailID"] = 7; // Sent to oversight agency
                    }
                    break;
            }
        }
        
        $this->v["currEmail"] = [];
        if (isset($this->sessData->dataSets["LinksComplaintDept"]) 
            && sizeof($this->sessData->dataSets["LinksComplaintDept"]) > 0) {
            foreach ($this->sessData->dataSets["LinksComplaintDept"] as $deptLnk) {
                $this->loadDeptStuff($deptLnk->LnkComDeptDeptID);
                $this->v["currEmail"][] = $this->processEmail($this->v["emailID"], $deptLnk->LnkComDeptDeptID);
            }
        }
        if (sizeof($this->v["currEmail"]) > 0) { 
            foreach ($this->v["currEmail"] as $j => $email) {
                $this->v["needsWsyiwyg"] = true;
                $GLOBALS["SL"]->pageAJAX .= ' $("#emailBodyCust' . $j . 'ID").summernote({ height: 350 }); ';
            }
        }
        
        $emailSent = false;
        $emaInd = 0;
        while ($GLOBALS["SL"]->REQ->has('emailID') && $GLOBALS["SL"]->REQ->has('emailTo' . $emaInd . '') 
            && trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . '')) != '') {
            $userToID = -3;
            $chk = User::where('email', trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . '')))->first();
            if ($chk && isset($chk->id)) $userToID = $chk->id;
            $coreID = ((isset($this->coreID)) ? $this->coreID : -3);
            $emaTo = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . ''));
            if ($emaTo == '--CUSTOM--') {
                $emaTo = trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . 'CustEmail'));
                //trim($GLOBALS["SL"]->REQ->get('emailTo' . $emaInd . 'CustName'))
            }
            $this->sendNewEmailSimple(trim($GLOBALS["SL"]->REQ->get('emailBodyCust' . $emaInd . '')), 
                trim($GLOBALS["SL"]->REQ->get('emailSubj' . $emaInd . '')), $emaTo, $GLOBALS["SL"]->REQ->get('emailID'), 
                $GLOBALS["SL"]->treeID, $coreID, $userToID);
            if (intVal($GLOBALS["SL"]->REQ->get('emailID')) == 12) {
                $this->sessData->dataSets["Complaints"][0]->update([ 
                    "ComStatus" => $GLOBALS["SL"]->def->getID('Complaint Status', 'Submitted to Oversight') ]);
                $deptID = $this->v["currEmail"][$emaInd]["deptID"];
                if (isset($GLOBALS["SL"]->x["depts"][$deptID]) 
                    && isset($GLOBALS["SL"]->x["depts"][$deptID]["whichOver"])) {
                    $whichRow = $GLOBALS["SL"]->x["depts"][$deptID][$GLOBALS["SL"]->x["depts"][$deptID]["whichOver"]];
                    if ($whichRow && isset($whichRow->OverID)) {
                        $this->logOverUpDate($coreID, $deptID, 'Submitted');
                    }
                }
                $newRev = new OPzComplaintReviews;
                $newRev->ComRevComplaint = $this->coreID;
                $newRev->ComRevUser      = $this->v["user"]->id;
                $newRev->ComRevDate      = date("Y-m-d H:i:s");
                $newRev->ComRevType      = 'Update';
                $newRev->ComRevStatus    = 'Submitted to Oversight';
                $newRev->save();
            }
            $emailSent = true;
            $emaInd++;
        }
        if ($emailSent) {
            return $this->redir('/complaint/read-' . $this->corePublicID, true);
        }
        $GLOBALS["SL"]->pageAJAX .= '$("#legitTypeBtn").click(function(){ $("#legitTypeDrop").slideToggle("fast"); });
        $("#newStatusUpdate").click(function(){ $("#newStatusUpdateBlock").slideToggle("fast"); });
        $("#newEmails").click(function(){ $("#analystEmailer").slideToggle("fast"); });
        $(document).on("change", ".changeEmailTo", function() { 
            var emaInd = $(this).attr("name").replace("emailTo", "");
            if (document.getElementById("emailTo"+emaInd+"ID") && document.getElementById("emailTo"+emaInd+"ID").value == "--CUSTOM--") {
                $("#emailTo"+emaInd+"CustID").slideDown("fast");
            } else {
                $("#emailTo"+emaInd+"CustID").slideUp("fast"); 
            }
        }); ' . (($this->v["view"] == 'update') ? 'window.location = "#new"; ' : '');
        $this->v["needsWsyiwyg"] = true;
        $this->v["complaintRec"] = $this->sessData->dataSets["Complaints"][0];
        return view('vendor.openpolice.nodes.1712-report-inc-staff-tools', $this->v)->render();
    }
    
    public function prepEmailComData()
    {
        $cnt = 0;
        $this->v["comDepts"] = [];
        if (isset($this->sessData->dataSets["LinksComplaintDept"]) 
            && sizeof($this->sessData->dataSets["LinksComplaintDept"]) > 0) {
            foreach ($this->sessData->dataSets["LinksComplaintDept"] as $i => $lnk) {
                if (isset($lnk->LnkComDeptDeptID) && intVal($lnk->LnkComDeptDeptID) > 0) {
                    $this->v["comDepts"][$cnt] = [ "id" => $lnk->LnkComDeptDeptID ];
                    $this->v["comDepts"][$cnt]["deptRow"] = OPDepartments::find($lnk->LnkComDeptDeptID);
                    $this->v["comDepts"][$cnt]["iaRow"] = OPOversight::where('OverDeptID', $lnk->LnkComDeptDeptID)
                        ->where('OverType', $GLOBALS["SL"]->def->getID('Oversight Agency Types', 'Internal Affairs'))
                        ->first();
                    $this->v["comDepts"][$cnt]["civRow"] = OPOversight::where('OverDeptID', $lnk->LnkComDeptDeptID)
                        ->where('OverType', $GLOBALS["SL"]->def->getID('Oversight Agency Types', 'Civilian Oversight'))
                        ->first();
                    if (!isset($this->v["comDepts"][$cnt]["iaRow"]) || !$this->v["comDepts"][$cnt]["iaRow"]) {
                        $this->v["comDepts"][$cnt]["iaRow"] = new OPOversight;
                        $this->v["comDepts"][$cnt]["iaRow"]->OverDeptID = $lnk->LnkComDeptDeptID;
                        $this->v["comDepts"][$cnt]["iaRow"]->OverType
                            = $GLOBALS["SL"]->def->getID('Oversight Agency Types', 'Internal Affairs');
                        $this->v["comDepts"][$cnt]["iaRow"]->OverAgncName
                            = $this->v["comDepts"][$cnt]["deptRow"]->DeptName;
                        $this->v["comDepts"][$cnt]["iaRow"]->OverAddress
                            = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddress;
                        $this->v["comDepts"][$cnt]["iaRow"]->OverAddress2
                            = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddress2;
                        $this->v["comDepts"][$cnt]["iaRow"]->OverAddressCity
                            = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddressCity;
                        $this->v["comDepts"][$cnt]["iaRow"]->OverAddressState
                            = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddressState;
                        $this->v["comDepts"][$cnt]["iaRow"]->OverAddressZip
                            = $this->v["comDepts"][$cnt]["deptRow"]->DeptAddressZip;
                        $this->v["comDepts"][$cnt]["iaRow"]->OverPhoneWork
                            = $this->v["comDepts"][$cnt]["deptRow"]->DeptPhoneWork;
                        $this->v["comDepts"][$cnt]["iaRow"]->save();
                    }
                    $this->v["comDepts"][$cnt]["whichOver"] = '';
                    if (isset($this->v["comDepts"][0]["civRow"]) 
                        && isset($this->v["comDepts"][0]["civRow"]->OverAgncName)) {
                        $this->v["comDepts"][$cnt]["whichOver"] = "civRow";
                    } elseif (isset($this->v["comDepts"][0]["iaRow"]) 
                        && isset($this->v["comDepts"][0]["iaRow"]->OverAgncName)) {
                        $this->v["comDepts"][$cnt]["whichOver"] = "iaRow";
                    }
                    $this->v["comDepts"][$cnt]["overInfo"] = '';
                    if (isset($this->v["comDepts"][$cnt])) {
                        $w = $this->v["comDepts"][$cnt]["whichOver"];
                        if (isset($this->v["comDepts"][$cnt][$w])) {
                            $this->v["comDepts"][$cnt]["overInfo"] 
                                = $this->getOversightInfo($this->v["comDepts"][$cnt][$w]);
                        }
                    }
                    $cnt++;
                }
            }
        }
        return true;
    }
    
    public function getOversightInfo($overRow)
    {
        $ret = '';
        if ($overRow && isset($overRow->OverAgncName) && trim($overRow->OverAgncName) != '') {
            $ret .= '<b>' . $overRow->OverAgncName . '</b><br />';
            if (isset($overRow->OverWebsite) && trim($overRow->OverWebsite) != '') {
                $ret .= '<a href="' . $overRow->OverWebsite . '" target="_blank">' . $overRow->OverWebsite 
                    . '</a><br />';
            }
            if (isset($overRow->OverFacebook) && trim($overRow->OverFacebook) != '') {
                $ret .= '<a href="' . $overRow->OverFacebook . '" target="_blank">' . $overRow->OverFacebook 
                    . '</a><br />';
            }
            if (isset($overRow->OverTwitter) && trim($overRow->OverTwitter) != '') {
                $ret .= '<a href="' . $overRow->OverTwitter . '" target="_blank">' . $overRow->OverTwitter 
                    . '</a><br />';
            }
            if (isset($overRow->OverYouTube) && trim($overRow->OverYouTube) != '') {
                $ret .= '<a href="' . $overRow->OverYouTube . '" target="_blank">' . $overRow->OverYouTube 
                    . '</a><br />';
            }
            if (isset($overRow->OverPhoneWork) && trim($overRow->OverPhoneWork) != '') {
                $ret .= $overRow->OverPhoneWork . '<br />';
            }
            if (isset($overRow->OverAddress) && trim($overRow->OverAddress) != '') {
                $ret .= $overRow->OverAddress . '<br />';
                if (isset($overRow->OverAddress2) && trim($overRow->OverAddress2) != '') {
                    $ret .= $overRow->OverAddress2 . '<br />';
                }
                $ret .= $overRow->OverAddressCity . ', ' . $overRow->OverAddressState . ' ' 
                    . $overRow->OverAddressZip . '<br />';
            }
            $ret .= '<br />';
            if (isset($overRow->OverWaySubOnline) && intVal($overRow->OverWaySubOnline) == 1
                && isset($overRow->OverComplaintWebForm) && trim($overRow->OverComplaintWebForm) != '') {
                $ret .= 'You can submit your complaint through your oversight agency\'s online complaint form. '
                    . 'Pro Tip: Somewhere in their official form, include a link to your OPC complaint.<br /><a href="'
                    . $overRow->OverComplaintWebForm . '" target="_blank">' . $overRow->OverComplaintWebForm 
                    . '</a><br /><br />';
            }
            if (isset($overRow->OverWaySubEmail) && intVal($overRow->OverWaySubEmail) == 1
                && isset($overRow->OverEmail) && trim($overRow->OverEmail) != '') {
                $ret .= 'You can submit your complaint by emailing your oversight agency. We recommend you '
                    . 'include a link to your OPC complaint in your email.<br />'
                    . '<a href="mailto:' . $overRow->OverEmail . '">' . $overRow->OverEmail . '</a><br /><br />';
            }
            if (isset($overRow->OverComplaintPDF) && trim($overRow->OverComplaintPDF) != '') {
                $ret .= 'You can print out and use your oversight agency\'s official complaint form online. '
                    . 'We recommend you also print your full OPC complaint and submit it along with their '
                    . 'official form.<br /><a href="' . $overRow->OverComplaintPDF . '" target="_blank">' 
                    . $overRow->OverComplaintPDF . '</a><br /><br />';
            }
            $ret .= '<i>More about this complaint process:</i><br />';
            if (isset($overRow->OverWebComplaintInfo) && trim($overRow->OverWebComplaintInfo) != '') {
                $ret .= '<a href="' . $overRow->OverWebComplaintInfo . '" target="_blank">' 
                    . $overRow->OverWebComplaintInfo . '</a><br />';
            }
            if (!isset($overRow->OverOfficialFormNotReq) || intVal($overRow->OverOfficialFormNotReq) == 0) {
                $ret .= 'Only complaints submitted on official forms will be investigated.<br />'; 
            }
            if (isset($overRow->OverWaySubNotary) && intVal($overRow->OverWaySubNotary) == 1) {
                $ret .= 'Submitted complaints may require a notary to be investigated.<br />'; 
            }
            if (!isset($overRow->OverOfficialAnon) || intVal($overRow->OverOfficialAnon) == 0) {
                $ret .= 'Anonymous complaints will not be investigated.<br />'; 
            }
            if (isset($overRow->OverWaySubVerbalPhone) && intVal($overRow->OverWaySubVerbalPhone) == 1) {
                $ret .= 'Complaints submitted by phone will be investigated.<br />'; 
            }
            if (isset($overRow->OverWaySubPaperMail) && intVal($overRow->OverWaySubPaperMail) == 1) {
                $ret .= 'Complaints submitted by snail mail will be investigated.<br />'; 
            }
            if (isset($overRow->OverWaySubPaperInPerson) && intVal($overRow->OverWaySubPaperInPerson) == 1) {
                $ret .= 'Complaints submitted in person will be investigated.<br />'; 
            }
            if (isset($overRow->OverSubmitDeadline) && intVal($overRow->OverSubmitDeadline) > 0) {
                $ret .= 'Complaints must be submitted within ' . number_format($overRow->OverSubmitDeadline) 
                    . ' days of your incident to be investigated.<br />'; 
            }
        }
        return $ret;
    }
    
    public function processEmail($emailID, $deptID = -3)
    {
        $email = [ "rec" => false, "body" => '', "subject" => '', "deptID" => $deptID ];
        if ($emailID > 0) {
            if (sizeof($this->v["emailList"]) > 0) {
                foreach ($this->v["emailList"] as $e) {
                    if ($e->EmailID == $emailID) $email["rec"] = $e;
                }
                if ($email["rec"] !== false && isset($email["rec"]->EmailBody) 
                    && trim($email["rec"]->EmailBody) != '') {
                    $email["body"] = $GLOBALS["SL"]->swapEmailBlurbs($email["rec"]->EmailBody);
                    $email["body"] = $this->sendEmailBlurbsCustom($email["body"], $deptID);
                    $email["subject"] = $GLOBALS["SL"]->swapEmailBlurbs($email["rec"]->EmailSubject);
                    $email["subject"] = $this->sendEmailBlurbsCustom($email["subject"], $deptID);
                }
            }
        }
        return $email;
    }
    
    protected function processTokenAccessEmail()
    {
        $ret = '';
        $deptID = -3;
        $overRow = OPOversight::where('OverEmail', $user->email)->first();
        if ($overRow && isset($overRow->OverDeptID)) $deptID = $overRow->OverDeptID;
        $emailRow = SLEmails::where('EmailName', '21. Fresh MFA to Oversight Agency')->first();
        if ($emailRow && isset($emailRow->EmailBody)) {
            $body = $this->sendEmailBlurbsCustom($emailRow->EmailBody, $deptID);
            $subject = $this->sendEmailBlurbsCustom($emailRow->EmailSubject, $deptID);
            echo '<h2>' . $subject . '</h2><p>to ' . $user->email . '</p><p>' . $body . '</p>'; exit;
            $this->sendNewEmailSimple($body, $subject, $user->email, $emailRow->EmailID, 
                $this->treeID, $this->coreID, $user->id);
            $ret .= '<div class="alert alert-success mB10" role="alert">'
                . '<strong>Fresh Access Code Sent!</strong> '
                . 'Check your email (and spam folder), and copy the access code there.</div>';
        } else {
            $ret .= '<div class="alert alert-danger mB10" role="alert"><strong>Email Problem!</strong> '
                . 'Something went wrong trying to email you a fresh access code. '
                . 'Please <a href="/contact">contact us</a>.</div>';
        }
        return $ret;
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
    
}
