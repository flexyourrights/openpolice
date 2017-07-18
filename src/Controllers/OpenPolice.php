<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SLZips;

use App\Models\OPComplaints;
use App\Models\OPCompliments;
use App\Models\OPIncidents;
use App\Models\OPEventSequence;
use App\Models\OPStops;
use App\Models\OPSearches;
use App\Models\OPArrests;
use App\Models\OPForce;
use App\Models\OPOrders;
use App\Models\OPInjuries;
use App\Models\OPEvidence;
use App\Models\OPCivilians;
use App\Models\OPDepartments;
use App\Models\OPOversight;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksOfficerEvents;
use App\Models\OPLinksOfficerOrders;
use App\Models\OPLinksCivilianEvents;
use App\Models\OPLinksCivilianOrders;
use App\Models\OPPersonContact;
use App\Models\OPzVolunUserInfo;

use OpenPolice\Controllers\OpenPoliceReport;
use OpenPolice\Controllers\VolunteerController;

use SurvLoop\Controllers\DatabaseLookups;
use SurvLoop\Controllers\SurvFormTree;

class OpenPolice extends SurvFormTree
{
    
    public $classExtension     = 'OpenPolice';
    public $treeID             = 1;
    
    protected $allCivs         = [];
    protected $allegations     = [];
    public $worstAllegations   = [ // Allegations in descending order of severity, Definition IDs
        [126, 'Sexual Assault'],
        [115, 'Unreasonable Force'],
        [116, 'Wrongful Arrest'], 
        [120, 'Wrongful Property Seizure or Damage'],
        [480, 'Sexual Harassment'], 
        [125, 'Intimidating Display of Weapon'], 
        [119, 'Wrongful Search'],
        [118, 'Wrongful Entry'],
        [117, 'Wrongful Detention'], 
        [121, 'Bias-Based Policing'],
        [122, 'Retaliation'],
        [123, 'Retaliation: Unnecessary Charges'], 
        [128, 'Conduct Unbecoming an Officer'],
        [129, 'Discourtesy'],
        [127, 'Neglect of Duty'], 
        [127, 'Policy or Procedure'], 
        [124, 'Excessive Citation'],
        [131, 'Miranda Rights'],
        [130, 'Officer Refused To Provide ID']
    ];
    public $eventTypes         = ['Stops', 'Searches', 'Force', 'Arrests'];
    protected $eventTypeLabel  = [
        'Stops'    => 'Stop/Questioning',
        'Searches' => 'Search/Seizure',
        'Force'    => 'Use of Force',
        'Arrests'  => 'Arrest'
    ];
    protected $eventTypeLookup = []; // $eveSeqID => 'Event Type'
    protected $eventCivLookup  = []; // array('Event Type' => array($civID, $civID, $civID))
    public $deptStuff          = []; // for records related to complaint's departments and oversight
    
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
            == $GLOBALS["SL"]->getDefID('Privacy Types', 'Submit Publicly'));
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
    
    public function chkCoreRecEmpty($coreID = -3, $coreRec = [])
    {
        if ($coreID <= 0) $coreID = $this->coreID;
        if (sizeof($coreRec) == 0 && $coreID > 0) $coreRec = OPComplaints::find($coreID);
        if (!isset($coreRec->ComSubmissionProgress) || intVal($coreRec->ComSubmissionProgress) <= 0) return true;
        if (!isset($coreRec->ComSummary) || trim($coreRec->ComSummary) == '') return true;
        return false;
    }
    
    protected function recordIsEditable($coreTbl, $coreID, $coreRec = [])
    {
        if (sizeof($coreRec) == 0 && $coreID > 0) $coreRec = OPComplaints::find($coreID);
        if (!isset($coreRec->ComStatus)) return true;
        if ($this->treeID == 1) {
            if ($coreRec->ComStatus == $GLOBALS["SL"]->getDefID('Complaint Status', 'Incomplete')) return true;
        } elseif ($this->treeID == 5) {
            if ($coreRec->ComStatus == $GLOBALS["SL"]->getDefID('Compliment Status', 'Incomplete')) return true;
        }
        return false;
    }
    
    protected function getAllPublicCoreIDs($coreTbl = '')
    {
        if (trim($coreTbl) == '') $coreTbl = $GLOBALS["SL"]->coreTbl;
        $this->allPublicCoreIDs = $list = [];
        if ($coreTbl == 'Complaints') {
            $list = OPComplaints::whereIn('ComStatus', $this->getPublishedStatusList())
                ->where('ComType', $GLOBALS["SL"]->getDefID('OPC Staff/Internal Complaint Type', 'Police Complaint'))
                ->select('ComID')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($coreTbl == 'Compliments') {
            $list = OPCompliments::whereIn('CompliStatus', $this->getPublishedStatusList())
                ->where('CompliType', $GLOBALS["SL"]->getDefID('OPC Staff/Internal Complaint Type', 'Police Complaint'))
                ->select('CompliID')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        if ($list && sizeof($list) > 0) {
            foreach ($list as $l) {
                if ($l->getKey() > 0) $this->allPublicCoreIDs[] = $l->getKey();
            }
        }
        //echo '<pre>'; print_r($this->allPublicCoreIDs); echo '</pre>';
        return $this->allPublicCoreIDs;
    }
    
    protected function getPublishedStatusList()
    {
        return [
            $GLOBALS["SL"]->getDefID('Complaint Status',  'Reviewed'), 
            $GLOBALS["SL"]->getDefID('Complaint Status',  'Submitted to Oversight'), 
            $GLOBALS["SL"]->getDefID('Complaint Status',  'Received by Oversight'), 
            $GLOBALS["SL"]->getDefID('Complaint Status',  'OK to Submit to Oversight'), 
            $GLOBALS["SL"]->getDefID('Complaint Status',  'Declined To Investigate (Closed)'), 
            $GLOBALS["SL"]->getDefID('Complaint Status',  'Investigated (Closed)'), 
            
            $GLOBALS["SL"]->getDefID('Compliment Status', 'Reviewed'), 
            $GLOBALS["SL"]->getDefID('Compliment Status', 'Submitted to Oversight'), 
            $GLOBALS["SL"]->getDefID('Compliment Status', 'Received by Oversight'), 
            $GLOBALS["SL"]->getDefID('Compliment Status', 'Closed')
        ];
    }
    
    // Initializing a bunch of things which are not [yet] automatically determined by the software
    protected function initExtra(Request $request)
    {
        // Establishing Main Navigation Organization, with Node ID# and Section Titles
        $this->majorSections = [];
        if ($GLOBALS["SL"]->treeID == 1) {
            $this->majorSections[] = array(1,      'Your Story',        'active');
            $this->majorSections[] = array(4,      'Who\'s Involved',   'active');
            $this->majorSections[] = array(5,      'Allegations',       'active');
            $this->majorSections[] = array(6,      'Go Gold',           'disabled');
            $this->majorSections[] = array(419,    'Finish',            'active');
            
            $this->minorSections = array( [], [], [], [], [] );
            $this->minorSections[0][] = array(157, 'Start Your Story');
            $this->minorSections[0][] = array(437, 'Privacy Options');
            $this->minorSections[0][] = array(158, 'When');
            $this->minorSections[0][] = array(159, 'Where');
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
        }
        return true;
    }
    
    protected function extraNavItems()
    {
        return 'setNavItem("Home", "/"); '
            . 'setNavItem("Search Complaints", "/search"); '
            . 'setNavItem("About Us", "/about"); '
            . 'setNavItem("FAQs", "/frequently-asked-questions"); '
            . 'setNavItem("Terms, Policies, & Rules", "/privacy-policy"); '
            . 'setNavItem("Contact Us", "/contact"); '
            . 'setNavItem("Donate", "https://flexyourrights-org.myshopify.com/products/md"); ';
    }
        
    // Initializing a bunch of things which are not [yet] automatically determined by the software
    protected function loadExtra()
    {
        if ($GLOBALS["SL"]->treeID == 1 && $this->isGold()) $this->majorSections[3][2] = 'active';
        if ($this->v["user"] && intVal($this->v["user"]->id) > 0 && isset($this->sessData->dataSets["Civilians"]) 
            && isset($this->sessData->dataSets["Civilians"][0])
            && (!isset($this->sessData->dataSets["Civilians"][0]->CivUserID) 
                || intVal($this->sessData->dataSets["Civilians"][0]->CivUserID) <= 0)) {
            $this->sessData->dataSets["Civilians"][0]->update([
                'CivUserID' => $this->v["user"]->id
            ]);
        }
        if ($this->treeID == 5 && isset($this->sessData->dataSets["Complaints"])
            && (!isset($this->sessData->dataSets["Complaints"][0]->ComIsCompliment)
                || intVal($this->sessData->dataSets["Complaints"][0]->ComIsCompliment) != 1)) {
            $this->sessData->dataSets["Complaints"][0]->ComIsCompliment = 1;
            $this->sessData->dataSets["Complaints"][0]->save();
        }
        if (session()->has('opcDeptID') && intVal(session()->get('opcDeptID')) > 0) {
            if ($this->treeID == 1) {
                if (isset($this->sessData->dataSets["Complaints"])
                    && intVal($this->sessData->dataSets["Complaints"][0]->ComSubmissionProgress) > 0) {
                    if (!isset($this->sessData->dataSets["LinksComplaintDept"])) {
                        $this->sessData->dataSets["LinksComplaintDept"] = [];
                    }
                    if (sizeof($this->sessData->dataSets["LinksComplaintDept"]) == 0) {
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
                    if (sizeof($this->sessData->dataSets["LinksComplimentDept"]) == 0) {
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
    
    protected function overrideMinorSection($nID = -3, $majorSectInd = -3)
    {
        if (in_array($nID, [483, 484, 485])) return 148;
        return -1;
    }
    
    // CUSTOM={OnlyIfNoAllegationsOtherThan:WrongStop,Miranda,PoliceRefuseID]
    protected function checkNodeConditionsCustom($nID, $condition = '')
    {
        if ($condition == '#NoSexualAllegation') {
            $noSexAlleg = true;
            if (isset($this->sessData->dataSets["Allegations"]) 
                && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
                foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                    if (in_array($alleg->AlleType, [
                        $GLOBALS["SL"]->getDefID('Allegation Type', 'Sexual Assault'), 
                        $GLOBALS["SL"]->getDefID('Allegation Type', 'Sexual Harassment')
                        ])) {
                        $noSexAlleg = false;
                    }
                }
            }
            return $noSexAlleg;
        } elseif ($condition == '#IncidentHasAddress') {
            if (isset($this->sessData->dataSets["Incidents"]) 
                && isset($this->sessData->dataSets["Incidents"][0]->IncAddress)
                && trim($this->sessData->dataSets["Incidents"][0]->IncAddress) != '') {
                return true;
            } else {
                return false;
            }
        } elseif ($condition == '#HasArrestOrForce') {
            if ($this->sessData->dataHas('Arrests') || $this->sessData->dataHas('Force')) return true;
            else return false;
        } elseif (in_array($condition, [
            '#PreviousEnteredStops', 
            '#PreviousEnteredSearches', 
            '#PreviousEnteredForce', 
            '#PreviousEnteredArrests'
            ])) {
            return (sizeof($this->getPreviousEveSeqsOfType($GLOBALS["SL"]->closestLoop["itemID"])) > 0);
        } elseif ($condition == '#HasForceHuman') {
            if (!$this->sessData->dataHas('Force') || sizeof($this->sessData->dataSets["Force"]) == 0) return false;
            $foundHuman = false;
            foreach ($this->sessData->dataSets["Force"] as $force) {
                if (trim($force->ForAgainstAnimal) != 'Y') $foundHuman = true;
            }
            return $foundHuman;
        } elseif ($condition == '#Property') {
            $search = $this->sessData->getChildRow('EventSequence', $GLOBALS["SL"]->closestLoop["itemID"], 'Searches');
            if ((isset($search->SrchSeized) && trim($search->SrchSeized) == 'Y')
                || (isset($search->SrchDamage) && trim($search->SrchDamage) == 'Y')) {
                return true;
            } else {
                return false;
            }
        }
        return true; 
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
                    == intVal($GLOBALS["SL"]->getDefID('Privacy Types', 'Completely Anonymous'))));
        } elseif ($this->treeID == 5) {
            return (isset($this->sessData->dataSets["Compliments"]) 
                && intVal($this->sessData->dataSets["Compliments"][0]->CompliPrivacy)
                    == intVal($GLOBALS["SL"]->getDefID('Privacy Types', 'Completely Anonymous')));
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
                ->where('OP_Complaints.ComStatus', $GLOBALS["SL"]->getDefID('Complaint Status', 'Incomplete'))
                ->whereNotNull('OP_Complaints.ComSummary')
                ->where('OP_Complaints.ComSummary', 'NOT LIKE', '')
                ->select('OP_Complaints.*') //, 'OP_Civilians.CivID', 'OP_Civilians.CivRole'
                ->orderBy('OP_Complaints.created_at', 'desc')
                ->get();
            if ($incompletes && sizeof($incompletes) > 0) {
                foreach ($incompletes as $i => $com) {
                    $this->coreIncompletes[] = [$com->ComID, $com];
                }
                return $this->coreIncompletes[0][0];
            }
        }
        return -3;
    } */
    
    public function multiRecordCheckIntro($cnt = 1)
    {
        $ret = '<a id="unfinishedBtn" class="btn btn-lg btn-default w100" href="javascript:;">'
            . $this->v["user"]->name . ', You Have ';
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
    
    protected function rawOrderPercentTweak($nID, $rawPerc, $found = -3)
    { 
        if ($this->isGold() && !$this->isCompliment()) return $rawPerc;
        return round(100*($found/(sizeof($this->nodesRawOrder)-200)));
    }

    protected function customNodePrint($nID = -3, $tmpSubTier = [])
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
        } elseif ($nID == 576) {
            $GLOBALS["SL"]->pageJAVA .= 'addFld("n576fld0"); addFld("n576fld1");';
            $GLOBALS["SL"]->pageAJAX .= view('vendor.openpolice.nodes.576-vehicle-details-ajax', [
                "nodes"            => [576, 577, 578, 91, 92, 93, 94, 95]
            ])->render();
            $ret .= view('vendor.openpolice.nodes.576-vehicle-details', [
                "nodes"            => [576, 577, 578, 91, 92, 93, 94, 95], 
                "alreadyDescribed" => $this->getNodeCurrSessData($nID),
                "whichVehic"       => $this->getNodeCurrSessData(577),
                "vehicDeets"       => $this->printNodePublic(578),
                "vehicles"         => $this->getVehicles()
            ])->render();
        } elseif ($nID == 571) {
            $GLOBALS["SL"]->pageJAVA .= 'addFld("n571fld0"); addFld("n571fld1");';
            $GLOBALS["SL"]->pageAJAX .= view('vendor.openpolice.nodes.576-vehicle-details-ajax', [
                "nodes"            => [571, 572, 583, 131, 132, 133, 134, 135], 
            ])->render();
            $ret .= view('vendor.openpolice.nodes.576-vehicle-details', [
                "nodes"            => [571, 572, 583, 131, 132, 133, 134, 135], 
                "alreadyDescribed" => $this->getNodeCurrSessData($nID),
                "whichVehic"       => $this->getNodeCurrSessData(572),
                "vehicDeets"       => $this->printNodePublic(583),
                "vehicles"         => $this->getVehicles()
            ])->render();
        } elseif ($nID == 584) {
            $GLOBALS["SL"]->pageJAVA .= 'addFld("n584fld0"); addFld("n584fld1");';
            $GLOBALS["SL"]->pageAJAX .= view('vendor.openpolice.nodes.576-vehicle-details-ajax', [
                "nodes"            => [584, 585, 589, 189, 190, 191, 192, -3], 
            ])->render();
            $ret .= view('vendor.openpolice.nodes.576-vehicle-details', [
                "nodes"            => [584, 585, 589, 189, 190, 191, 192, -3], 
                "alreadyDescribed" => $this->getNodeCurrSessData($nID),
                "whichVehic"       => $this->getNodeCurrSessData(585),
                "vehicDeets"       => $this->printNodePublic(589),
                "vehicles"         => $this->getVehicles()
            ])->render();
        } elseif (in_array($nID, [145, 920])) {
            $this->nextBtnOverride = 'Find & Select A Department';
            $searchSuggest = [];
            $deptCitys = OPDepartments::select('DeptAddressCity')
                ->distinct()
                ->where('DeptAddressState', $this->sessData->dataSets["Incidents"][0]->IncAddressState)
                ->get();
            if ($deptCitys && sizeof($deptCitys) > 0) {
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
            if ($deptCounties && sizeof($deptCounties) > 0) {
                foreach ($deptCounties as $dept) {
                    if (!in_array($dept->DeptAddressCounty, $searchSuggest)) {
                        $searchSuggest[] = json_encode($dept->DeptAddressCounty);
                    }
                }
            }
            $deptFed = OPDepartments::select('DeptName')->where('DeptType', 366)->get();
            if ($deptFed && sizeof($deptFed) > 0) {
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
            $ret .= view('vendor.openpolice.nodes.145-dept-search', [ 
                "nID"                => $nID,
                "IncAddressCity"     => $this->sessData->dataSets["Incidents"][0]->IncAddressCity, 
                "stateDropstateDrop" => $GLOBALS["SL"]->states->stateDrop(
                    $this->sessData->dataSets["Incidents"][0]->IncAddressState, true) 
            ])->render();
        } elseif ($nID == 415) {
            $civInjs = $this->getCivSetPossibilities('Injuries', 'InjuryCare');
            $ret .= '<div id="node415" class="nodeWrap">
            <div class="nPrompt"><div class="nPromptHeader">Medical Care</div>
            Did ' . ((sizeof($civInjs["opts"]) == 1) 
                ? $this->youLower($this->getCivilianNameFromID($civInjs["opts"][0])) 
                : 'anyone with injuries') . ' receive medical care?
            </div>';
            if (sizeof($civInjs["opts"]) == 1) {
                $ret .= '<div class="nFld" style="font-size: 130%;"><nobr>'
                    . '<input type="checkbox" autocomplete="off" value="' . $civInjs["opts"][0] 
                    . '" name="InjuryCare[]" id="InjuryCare' . $civInjs["opts"][0] . '" ' 
                    . ((sizeof($civInjs["active"]) > 0) ? 'CHECKED' : '') . ' > 
                    <label for="InjuryCare'.$civInjs["opts"][0].'">Yes</label></nobr></div>';
            } else {
                $ret .= $this->formCivSetPossibilities('Injuries', 'InjuryCare', $civInjs);
            }
            /* <div class="nFld slRedDark mB20">
                <b>IMPORTANT!</b> If anyone has been injured, they need to get medical attention now! 
                Official medical evidence will also help improve your complaint.
            </div> */
            $ret .= '</div>';
        } elseif (in_array($nID, [270, 973])) {
            //$this->sessData->dataSets["Complaints"][0]->
            $url = '/complaint-read/' . $this->coreID;
            if ($nID == 973) $url = '/compliment-report/' . $this->coreID;
            /* if ($nID == 270 && trim($this->sessData->dataSets["Complaints"][0]->ComSlug) != '') {
                $url = '/report' . $this->sessData->dataSets["Complaints"][0]->ComSlug;
            } elseif ($nID == 973 && trim($this->sessData->dataSets["Compliments"][0]->CompliSlug) != '') {
                $url = '/report' . $this->sessData->dataSets["Compliments"][0]->CompliSlug;
            } */
            $ret .= '<br /><br /><center><h1>All Done! Taking you to <a href="' . $url . '">your finished '
                . (($nID == 270) ? 'complaint' : 'compliment') 
                . '</a>...</h1></center><script type="text/javascript"> setTimeout("window.location=\'' 
                . $url . '\'", 1500); </script>';
            $this->restartSess($GLOBALS["SL"]->REQ);
            
        // Custom Nodes on site pages
        } elseif ($nID == 1099) {
            
            if (!isset($this->v["deptID"]) || intVal($this->v["deptID"]) <= 0) {
                if ($GLOBALS["SL"]->REQ->has('d') && intVal($GLOBALS["SL"]->REQ->get('d')) > 0) {
                    $this->v["deptID"] = $GLOBALS["SL"]->REQ->get('d');
                } else {
                    $this->v["deptID"] = -3;
                }
            }
            $this->loadDeptStuff($this->v["deptID"]);
            $GLOBALS["SL"]->pageAJAX .= '$(document).on("click", ".toggleScoreInfo", function() { $("#toggleScoreInfoDeets").slideToggle("fast"); });
            $(document).on("click", ".toggleOPCcompat", function() { $("#toggleOPCcompatDeets").slideToggle("fast"); });';
            $ret .= view('vendor.openpolice.dept-page', [ "deptStuff" => $this->deptStuff ])->render();
            
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
                            <span class="mL5">' . $this->printEventSequenceLine($eveSeq[0]) . '</span>
                        </label></div>' . "\n";
                    }
                }
                $ret .= '<div class="nFld pT20 pB20" style="font-size: 125%;"><label for="eventMergeNo">
                    <input type="radio" name="eventMerge" id="eventMergeNo" value="-3"> <span class="mL5">No</span>
                </label></div>
            </div>';
        } elseif ($promptNotesSpecial == '[[OfficerOrders]]') {
            $eventType = $this->getEveSeqTypeFromNode($nID);
            $eventOffs = $this->getLinkedToEvent('Officer', $GLOBALS["SL"]->closestLoop["itemID"]);
            $eventCivs = $this->getLinkedToEvent('Civilian', $GLOBALS["SL"]->closestLoop["itemID"]);
            $eveSeqOrders = $this->getEventOrders($GLOBALS["SL"]->closestLoop["itemID"], $eventType);
            for ($i=0; $i<20; $i++) {
                $currOrder = [];
                if (isset($eveSeqOrders[$i])) {
                    $currOrder = $eveSeqOrders[$i];
                    $currOrder->Officers  = $this->getLinkedToEvent('Officer', -3, -3, $currOrder->OrdID);
                    $currOrder->Civilians = $this->getLinkedToEvent('Civilian', -3, -3, $currOrder->OrdID);
                }
                $ret .= '<input type="hidden" name="ordID' . $i . '" value="' 
                    . ((isset($currOrder->OrdID)) ? $currOrder->OrdID : -3) . '">
                    <input type="hidden" id="orderVis' . $i . 'ID" name="orderVis' . $i . '" value="' 
                    . (($i == 0 || isset($currOrder->OrdID)) ? 'Y' : 'N') . '">
                    <a name="ord' . $i . '"></a><div id="order' . $i . '" class="dis' 
                    . (($i == 0 || isset($currOrder->OrdID)) ? 'Blo' : 'Non') . ' w100 orderBlock">' . "\n";
                
                if ($i > 0) {
                    $ret .= '<a href="#ord'.($i-1).'" id="delOrder' . $i . '" class="nFormLnkDel fR mTn10">'
                        . '<i class="fa fa-minus-square-o"></i></a><div class="fC mBn10"></div>';
                }
                
                $offs = $this->sessData->getLoopRows('Officers');
                if (sizeof($offs) > 1) { 
                    $ret .= '<div class="nodeWrap pB20"><div class="nPrompt">Order From: '
                        . '<div class="nFld disIn mL20 mR20">' . "\n";
                    foreach ($offs as $offInd => $off) {
                        $ret .= '<div class="nFld disIn mR20"><nobr><input type="checkbox" '
                             . 'name="order' . $i . 'Off[]" id="order' . $i . 'Off'.$offInd.'" value="' . $off->OffID . '" ';
                        if (isset($currOrder->OrdID)) {
                            if (in_array($off->OffID, $currOrder->Officers)) $ret .= 'CHECKED'; 
                        }
                        elseif (in_array($off->OffID, $eventOffs)) $ret .= 'CHECKED';
                        $ret .= ' > <label for="order' . $i . 'Off'.$offInd.'">' 
                            . $this->getLoopItemLabel('Officers', $off, $offInd) 
                            . '</label></nobr></div> ' . "\n";
                    }
                    $ret .= '</div></div></div>' . "\n";
                } elseif (sizeof($offs) == 1) {
                    $ret .= '<input type="hidden" name="order' . $i . 'Off" '
                        . 'id="order' . $i . 'OffID" value="' . $offs[0]->OffID . '">' . "\n";
                }
                
                $civs = $this->getCivilianList('Civilians');
                if (sizeof($civs) > 1) { 
                    $ret .= '<div class="nodeWrap pB20"><div class="nPrompt">Order To: '
                        . '<div class="nFld disIn mL20 mR20">' . "\n";
                    foreach ($civs as $civInd => $civ) {
                        $ret .= '<div class="nFld disIn mR20"><nobr><input type="checkbox" '
                            . 'name="order' . $i . 'Civ[]" id="order' . $i . 'Civ'.$civInd.'" value="' . $civ . '" ';
                        if (isset($currOrder->OrdID)) {
                            if (in_array($civ, $currOrder->Civilians)) $ret .= 'CHECKED'; 
                        } elseif (in_array($civ, $eventCivs)) {
                            $ret .= 'CHECKED';
                        }
                        $ret .= ' > <label for="order' . $i . 'Civ'.$civInd.'">' 
                            . $this->getLoopItemLabel('Civilians', $this->sessData->getRowById('Civilians', $civ), $civInd) 
                            . '</label></nobr></div> ' . "\n";
                    }
                    $ret .= '</div></div></div>' . "\n";
                } elseif (sizeof($civs) == 1) {
                    $ret .= '<input type="hidden" name="order' . $i . 'Civ" '
                        . 'id="order' . $i . 'CivID" value="' . $civs[0] . '">' . "\n";
                }
                
                $ret .= '<div class="nodeWrap"><div class="nPrompt">Order Given:</div><div class="nFld">'
                    . '<input type="text" name="order' . $i . '" id="order' . $i . 'ID" class="form-control" value="' 
                    . ((isset($currOrder->OrdID)) ? $currOrder->OrdDescription : '') . '"></div></div>';
                
                if ($nID == 342) { // only if Order for Use of Force
                    $ret .= '<div class="nodeWrap">
                        <div class="nPrompt disIn mR20">Did recipient have trouble hearing order?</div>
                        <div class="nFld disIn mR20"><nobr><label for="hearOrder' . $i . 'Y">
                            <input id="hearOrder' . $i . 'Y" name="hearOrder' . $i . '" value="Y" type="radio" autocomplete="off" class="ordHearCls" ' 
                            . ((isset($currOrder->OrdID) && $currOrder->OrdTroubleUnderYN == 'Y') ? 'CHECKED' : '') 
                            . ' > Yes
                        </label></nobr></div>
                        <div class="nFld disIn mR20"><nobr><label for="hearOrder' . $i . 'N">
                            <input id="hearOrder' . $i . 'N" name="hearOrder' . $i . '" value="N" type="radio" autocomplete="off" class="ordHearCls" ' 
                            . ((isset($currOrder->OrdID) && $currOrder->OrdTroubleUnderYN == 'N') ? 'CHECKED' : '') 
                            . ' > No
                        </label></nobr></div>
                        <div class="nFld disIn"><nobr><label for="hearOrder' . $i . 'Q">
                            <input id="hearOrder' . $i . 'Q" name="hearOrder' . $i . '" value="?" type="radio" autocomplete="off" class="ordHearCls" ' 
                            . ((isset($currOrder->OrdID) && $currOrder->OrdTroubleUnderYN == '?') ? 'CHECKED' : '') 
                            . ' > Don\'t Know
                        </label></nobr></div>
                    </div>
                    <div id="orderHearing' . $i . '" class="nodeWrap pL20 dis' 
                        . ((isset($currOrder->OrdID) && $currOrder->OrdTroubleUnderYN == 'Y') ? 'Blo' : 'Non') . '">
                        <div class="nPrompt">What did the victim say or do before use of force?</div>
                        <div class="nFld"><input type="text" name="hearOrder' . $i . 'Why" class="form-control" value="' 
                        . ((isset($currOrder->OrdTroubleUnderstading)) ? $currOrder->OrdTroubleUnderstading : '') . '"></div>
                    </div>';
                }
                $ret .= '</div>';
            }
            $ret .= '<div class="orderBlock"><center><a href="javascript:;" id="nFormAddOrder" class="btn btn-default"><nobr><i class="fa fa-plus-circle"></i> Add Another Order/Question</nobr></a></center></div>
            <div class="fC"></div>';
            $GLOBALS["SL"]->pageAJAX .= "\t\t".'$("#nFormAddOrder").click(function() { var maxOrder = 1;
                for (var i=0; i<20; i++) { if (document.getElementById("orderVis"+i+"ID").value=="Y") maxOrder = 1+i; }
                document.getElementById("nFormAddOrder").href="#ord"+maxOrder+"";
                if (document.getElementById("orderVis"+maxOrder+"ID")) { $("#order"+maxOrder+"").slideDown("slow"); document.getElementById("orderVis"+maxOrder+"ID").value="Y"; }
            });
            $(document).on("click", "a.nFormLnkDel", function() {
                delOrd = $(this).attr("id").replace("delOrder", "");
                $("#order"+delOrd+"").slideUp("slow"); document.getElementById("orderVis"+delOrd+"ID").value="N";
            });' . "\n";
            if ($nID == 342) { // only if Order for Use of Force
                $GLOBALS["SL"]->pageAJAX .= "\t\t".'$(document).on("click", ".ordHearCls", function() {
                    ordHearInd = $(this).attr("id").replace("hearOrder", "").replace("Y", "").replace("N", "").replace("Q", "");
                    ordHearVal = $(this).attr("value");
                    if (ordHearVal == "Y") { $("#orderHearing"+ordHearInd+"").slideDown("slow"); }
                    else { $("#orderHearing"+ordHearInd+"").slideUp("slow"); }
                });' . "\n";
            }
        } elseif ($promptNotesSpecial == '[[VictimsWithForce]]') { // node 411
            $civInjs = $this->getCivSetPossibilities('Force', 'Injuries', 'Type');
            $GLOBALS["SL"]->loadDefinitions("Injury Types");
            if (sizeof($civInjs["opts"]) > 0) {
                $colWidth = 12/sizeof($civInjs["opts"]);
                $ret .= '<div class="row">
                    <div class="col-md-'.$colWidth.'">';
                    foreach ($civInjs["opts"] as $i => $civID) {
                        if ($i > 0) $ret .= '</div><div class="col-md-'.$colWidth.'">';
                        $ret .= '<div class="nFld pB20"><nobr><label for="injCivs'.$civID.'">
                            <input name="injCivs[]" id="injCivs'.$civID.'" autocomplete="off" type="checkbox" 
                            value="'.$civID.'" ' . ((isset($civInjs["active"][$civID])) ? 'CHECKED' : '') 
                            . ' class="civInjury" > 
                            <span style="font-size: 130%;">' . $this->getCivilianNameFromID($civID) . '</span>
                            </label></nobr></div>
                        <div id="civ'.$civID.'InjTypes" class="dis' 
                        . ((isset($civInjs["active"][$civID])) ? 'Blo' : 'Non') . ' pL20 mL20">';
                        foreach ($GLOBALS["SL"]->defValues["Injury Types"] as $j => $injType) {
                            if ($injType->DefValue != 'Handcuff Injury') {
                                $ret .= '<div class="nFld pB5"><nobr><input name="inj'.$civID.'Type[]" id="inj'
                                .$civID.'Type'.$j.'" autocomplete="off" type="checkbox" value="'.$injType->DefID.'" ' 
                                . ((isset($civInjs["active"][$civID]) 
                                    && in_array($injType->DefID, $civInjs["active"][$civID])) ? 'CHECKED' : '') . ' > 
                                <label for="inj'.$civID.'Type'.$j.'">' . $injType->DefValue . '</label></nobr></div>';
                            }
                        }
                        $ret .= '<div class="p20 m20"></div>
                        </div>';
                    }
                    $ret .= '</div>
                </div>';
                $GLOBALS["SL"]->pageAJAX .= "\t\t".'$(document).on("click", ".civInjury", function() {
                    injInd = $(this).attr("id").replace("injCivs", "");
                    if (document.getElementById("injCivs"+injInd+"").checked) { '
                        . '$("#civ"+injInd+"InjTypes").slideDown("slow"); }
                    else { $("#civ"+injInd+"InjTypes").slideUp("slow"); }
                });' . "\n";
            }
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
        if (sizeof($tmpSubTier) == 0) $tmpSubTier = $this->loadNodeSubTier($nID);
        list($tbl, $fld) = $this->allNodes[$nID]->getTblFld();
        if (isset($this->sessData->dataSets["Complaints"])) {
            $this->sessData->dataSets["Complaints"][0]->update(["updated_at" => date("Y-m-d H:i:s")]);
        }
        if ($nID == 439) { // Unresolved criminal charges decision
            if ($GLOBALS["SL"]->REQ->has('n439fld')) {
                $defID = $GLOBALS["SL"]->getDefID('Unresolved Charges Actions', 'Full complaint to print or save');
                if ($GLOBALS["SL"]->REQ->input('n439fld') == $defID) {
                    $defID = $GLOBALS["SL"]->getDefID('Privacy Types', 'Anonymized');
                    if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == $defID) {
                        $this->sessData->dataSets["Complaints"][0]->update([
                            "ComPrivacy" => $GLOBALS["SL"]->getDefID('Privacy Types', 'Submit Publicly')
                        ]);
                    }
                } else {
                    $defID = $GLOBALS["SL"]->getDefID('Unresolved Charges Actions', 'Anonymous complaint data only');
                    if ($GLOBALS["SL"]->REQ->input('n439fld') == $defID) {
                        $this->sessData->dataSets["Complaints"][0]->update([
                            "ComPrivacy" => $GLOBALS["SL"]->getDefID('Privacy Types', 'Anonymized')
                        ]);
                    }
                }
            }
            return false;
        } elseif ($nID == 15) { // Incident Start-End Date & Times
            $this->sessData->currSessData($nID, $tbl, $fld, 'update', 
                date("Y-m-d", strtotime($GLOBALS["SL"]->REQ->n15fld)).' '.$this->postFormTimeStr(16));
            return true;
        } elseif ($nID == 16) {
            return true;
        } elseif ($nID == 17) {
            $this->sessData->currSessData($nID, $tbl, $fld, 'update', 
                date("Y-m-d", strtotime($GLOBALS["SL"]->REQ->n15fld)).' '.$this->postFormTimeStr($nID));
            return true;
        } elseif ($nID == 47) { // Complainant Recorded Incident?
            $this->sessData->dataSets["Civilians"][0]->CivCameraRecord = $GLOBALS["SL"]->REQ->input('n47fld');
            $this->sessData->dataSets["Civilians"][0]->save();
            return true;
        } elseif ($nID == 577) { // Victim Vehicle is a previously entered?
            $previously = $this->getNodeCurrSessData(577);
            if ($previously > 0 && ($previously != $GLOBALS["SL"]->REQ->n577fld 
                || ($GLOBALS["SL"]->REQ->previouslyAlreadyDescribed == 'Y' && $GLOBALS["SL"]->REQ->n576fld == 'N'))) {
                $this->sessData->currSessData(577, 'LinksCivilianVehicles', 'LnkCivVehicVehicID', 'update', 0);
                $this->sessData->refreshDataSets();
                $this->runLoopConditions();
            }
            if ($GLOBALS["SL"]->REQ->n576fld == 'N') return true;
        } elseif ($nID == 578) { // Victim Vehicle is a previously entered?
            if ($GLOBALS["SL"]->REQ->n576fld == 'Y') return true;
        } elseif ($nID == 572) { // Witness Vehicle is a previously entered?
            $previously = $this->getNodeCurrSessData(572);
            if ($previously > 0 && ($previously != $GLOBALS["SL"]->REQ->n572fld 
                || ($GLOBALS["SL"]->REQ->previouslyAlreadyDescribed == 'Y' && $GLOBALS["SL"]->REQ->n571fld == 'N'))) {
                $this->sessData->currSessData(572, 'LinksCivilianVehicles', 'LnkCivVehicVehicID', 'update', 0);
                $this->sessData->refreshDataSets();
                $this->runLoopConditions();
            }
            if ($GLOBALS["SL"]->REQ->n571fld == 'N') return true;
        } elseif ($nID == 583) { // Witness Vehicle is a previously entered?
            if ($GLOBALS["SL"]->REQ->n571fld == 'Y') return true;
        } elseif ($nID == 585) { // Officer Vehicle is a previously entered?
            $previously = $this->getNodeCurrSessData(585);
            if ($previously > 0 && ($previously != $GLOBALS["SL"]->REQ->n585fld 
                || ($GLOBALS["SL"]->REQ->previouslyAlreadyDescribed == 'Y' && $GLOBALS["SL"]->REQ->n584fld == 'N'))) {
                $this->sessData->currSessData(585, 'LinksCivilianVehicles', 'LnkCivVehicVehicID', 'update', 0);
                $this->sessData->refreshDataSets();
                $this->runLoopConditions();
            }
            if ($GLOBALS["SL"]->REQ->n584fld == 'N') return true;
        } elseif ($nID == 589) { // Officer Vehicle is a previously entered?
            if ($GLOBALS["SL"]->REQ->n584fld == 'Y') return true;
        } elseif (in_array($nID, [145, 920])) { // Searched & Found Police Department
            $newDeptID = -3;
            if (intVal($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld')) > 0) {
                $newDeptID = intVal($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'));
                $this->sessData->logDataSave($nID, 'NEW', -3, 'ComplaintDeptLinks', 
                    $GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'));
            } elseif ($GLOBALS["SL"]->REQ->has('newDeptName') && trim($GLOBALS["SL"]->REQ->newDeptName) != '') {
                $tmpVolunCtrl = new VolunteerController;
                $newDept = $tmpVolunCtrl->newDeptAdd($GLOBALS["SL"]->REQ->newDeptName, 
                    $GLOBALS["SL"]->REQ->newDeptAddressState);
                $newDeptID = $newDept->DeptID;
                $logTxt = 'ComplaintDeptLinks - !New Department Added!';
                $this->sessData->logDataSave($nID, 'NEW', -3, $logTxt, $newDeptID);
            }
            if ($newDeptID > 0) {
                $deptChk = OPLinksComplaintDept::where('LnkComDeptComplaintID', $this->coreID)
                    ->where('LnkComDeptDeptID', $newDeptID)
                    ->get();
                if (!$deptChk || sizeof($deptChk) <= 0) {
                    $newDeptLnk = new OPLinksComplaintDept;
                    $newDeptLnk->LnkComDeptComplaintID = $this->coreID;
                    $newDeptLnk->LnkComDeptDeptID = $newDeptID;
                    $newDeptLnk->save();
                    $this->sessData->refreshDataSets();
                    $this->runLoopConditions();
                }
            }
            return true;
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
            $GLOBALS["SL"]->loadDefinitions('Force Type');
            $loopRows = $this->sessData->getLoopRows('Victims');
            foreach ($loopRows as $i => $civ) {
                $nIDtxt = 'n' . $nID . 'cyc' . $i . 'fld';
                $nIDtxt2 = 'n742cyc' . $i . 'fld';
                if ($GLOBALS["SL"]->REQ->has($nIDtxt) && sizeof($GLOBALS["SL"]->REQ->input($nIDtxt)) > 0 
                    && trim($GLOBALS["SL"]->REQ->input($nIDtxt)[0]) == 'Y' && $GLOBALS["SL"]->REQ->has($nIDtxt2) 
                    && sizeof($GLOBALS["SL"]->REQ->input($nIDtxt2)) > 0) {
                    foreach ($GLOBALS["SL"]->REQ->input($nIDtxt2) as $forceType) {
                        if ($GLOBALS["SL"]->REQ->n740cyc0fld == 'Y' 
                            && $this->getCivForceEventID($nID, $civ->CivID, $forceType) <= 0) {
                            $this->addNewEveSeq('Force', $civ->CivID, $forceType);
                        }
                    }
                }
                foreach ($GLOBALS["SL"]->defValues["Force Type"] as $i => $def) {
                    if ($GLOBALS["SL"]->REQ->n740cyc0fld == 'N' || !$GLOBALS["SL"]->REQ->has($nIDtxt2) 
                        || !in_array($def->DefID, $GLOBALS["SL"]->REQ->input($nIDtxt2))) {
                        $this->deleteEventByID($nID, $this->getCivForceEventID($nID, $civ->CivID, $def->DefID));
                    }
                }
            }
        } elseif ($nID == 742) { // Use of Force on Victims: Sub-Types processed by 740
            return true;
        } elseif ($nID == 743) { // Use of Force against Animal: Yes/No
            if (!$GLOBALS["SL"]->REQ->has('n'.$nID.'fld') || sizeof($GLOBALS["SL"]->REQ->input('n'.$nID.'fld')) == 0) {
                $animalsForce = $this->getCivAnimalForces();
                if ($animalsForce && sizeof($animalsForce) > 0) {
                    foreach ($animalsForce as $force) $this->deleteEventByID($nID, $force->ForEventSequenceID);
                }
            }
        } elseif ($nID == 744) { // Use of Force against Animal: Sub-types
            if ($GLOBALS["SL"]->REQ->has('n743fld') && sizeof($GLOBALS["SL"]->REQ->n743fld) > 0 
                && $GLOBALS["SL"]->REQ->has('n744fld') && sizeof($GLOBALS["SL"]->REQ->n744fld) > 0 
                && intVal($GLOBALS["SL"]->REQ->n743fld[0]) == 'Y') {
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
                if ($animalsForce && sizeof($animalsForce) > 0) {
                    foreach ($animalsForce as $force) {
                        if (!$GLOBALS["SL"]->REQ->has('n743fld') 
                            || !in_array($force->ForType, $GLOBALS["SL"]->REQ->n743fld)) {
                            $this->deleteEventByID($nID, $force->ForEventSequenceID);
                        }
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
        } elseif ($this->allNodes[$nID]->nodeRow->NodePromptNotes == '[[OfficerOrders]]') {
            return $this->processOrders($nID);
        } elseif ($nID == 316) {
            return $this->processHandcuffInjury($nID);
        } elseif ($nID == 411) { // Gold Victims with Force, checkin for Injuries, from [[VictimsWithForce]]
            $logs = '';
            $civInjs = $this->getCivSetPossibilities('Force', 'Injuries', 'Type');
            if (sizeof($civInjs["active"]) > 0) { 
                foreach ($civInjs["active"] as $civID => $injTypes) {
                    if (sizeof($injTypes) > 0) { 
                        foreach ($injTypes as $injType) {
                            if (!$GLOBALS["SL"]->REQ->has('injCivs') || !in_array($civID, $GLOBALS["SL"]->REQ->injCivs) 
                                || !$GLOBALS["SL"]->REQ->has('inj'.$civID.'Type')) {
                                OPInjuries::where('InjSubjectID', $civID)
                                    ->where('InjType', $injType)
                                    ->where('InjComplaintID', $this->coreID)
                                    ->delete();
                            }
                        }
                    }
                }
            }
            if ($GLOBALS["SL"]->REQ->has('injCivs') && sizeof($GLOBALS["SL"]->REQ->injCivs) > 0) {
                foreach ($GLOBALS["SL"]->REQ->injCivs as $civID) {
                    $logs .= '-|-civ:'.$civID.'::';
                    if ($GLOBALS["SL"]->REQ->has('inj'.$civID.'Type') 
                        && sizeof($GLOBALS["SL"]->REQ->input('inj'.$civID.'Type')) > 0) {
                        foreach ($GLOBALS["SL"]->REQ->input('inj'.$civID.'Type') as $injType) {
                            $logs .= $injType.';;';
                            $found = false;
                            if (isset($civInjs["active"][$civID])) {
                                foreach ($civInjs["active"][$civID] as $injChk) {
                                    if ($injChk == $injType) $found = true;
                                }
                            }
                            if (!$found) {
                                $injRow = $this->sessData->newDataRecord('Injuries', 'InjSubjectID', $civID);
                                $injRow->InjType = $injType;
                                $injRow->save();
                            }
                        }
                    }
                }
            }
            $this->sessData->logDataSave($nID, 'Injuries', -3, 'New Records', str_replace(';;-|-', '-|-', $logs));
        } elseif ($nID == 415) { // Gold Victims with Injuries, checkin for Care, from [[VictimsWithInjuries]]
            $this->postCivSetPossibilities('Injuries', 'InjuryCare', 'InjCareSubjectID');
            $this->sessData->logDataSave($nID, 'InjuryCare', -3, 'New Records', 
                (($GLOBALS["SL"]->REQ->has('InjuryCare')) ? implode(';;', $GLOBALS["SL"]->REQ->InjuryCare) : ''));
        } elseif ($nID == 391) { // Gold Victims without Arrests, but with Citations, from [[VictimsWithoutArrests]]
            $this->postCivSetPossibilities('Arrests', 'Charges');
            $this->sessData->logDataSave($nID, 'Charges', -3, 'New Records', 
                (($GLOBALS["SL"]->REQ->has('Charges')) ? implode(';;', $GLOBALS["SL"]->REQ->Charges) : ''));
        } elseif ($nID == 274) { // CONFIRM COMPLAINT SUBMISSION
            $slug = $this->sessData->dataSets["Incidents"][0]->IncAddressCity . '-' 
                . $this->sessData->dataSets["Incidents"][0]->IncAddressState;
            if ($GLOBALS["SL"]->REQ->has('n274fld') && trim($GLOBALS["SL"]->REQ->n274fld) != '') {
                $slug = $GLOBALS["SL"]->REQ->n274fld;
            }
            $slug = '/' . $this->sessData->dataSets["Complaints"][0]->ComID . '/' . Str::slug($slug);
            $this->sessData->dataSets["Complaints"][0]->update(["ComSlug" => $slug]);
        } elseif ($nID == 269) { // CONFIRM COMPLAINT SUBMISSION
            if ($GLOBALS["SL"]->REQ->has('n269fld')) {
                if ($GLOBALS["SL"]->REQ->n269fld == 'Y') {
                    $this->sessData->currSessData($nID, 'Complaints', 'ComStatus', 'update', 
                        $GLOBALS["SL"]->getDefID('Complaint Status', 'New')); // 'New'
                    $this->sessData->currSessData($nID, 'Complaints', 'ComRecordSubmitted', 'update', 
                        date("Y-m-d H:i:s"));
                }
            }
        }
        return false; // false to continue standard post processing
    }
    
    // returns an array of overrides for ($currNodeSessionData, ???... 
    protected function printNodeSessDataOverride($nID = -3, $tmpSubTier = [], $currNodeSessionData = '')
    {
        if (sizeof($this->sessData->dataSets) == 0) return [];
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
                return [$user->email];
            }
        } elseif ($nID == 671) { // Officers Used Profanity?
            $currVals = [];
            foreach ($this->sessData->dataSets["Officers"] as $i => $off) {
                if ($off->OffUsedProfanity == 'Y') $currVals[] = $off->getKey();
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
        } elseif ($nID == in_array($nID, [732, 736, 733])) { // Gold Stops & Searches, Multiple Victims
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
                if (in_array($civ, $this->eventCivLookup['Force'])) $ret[] = 'cyc' . $i . 'Y';
            }
            if (sizeof($ret) == 0) $ret = ['N'];
            return $ret;
        } elseif ($nID == 742) { // Use of Force on Victims: Sub-Types
            $ret = [];
            $GLOBALS["SL"]->loadDefinitions('Force Type');
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
        } elseif ($nID == 746) { // Use of Force against Animal: Description
            if (isset($this->eventCivLookup["Force Animal"][0]) 
                && intVal($this->eventCivLookup["Force Animal"][0]) > 0) {
                $forceAnimal = $this->sessData->getRowById('Force', $this->eventCivLookup["Force Animal"][0]);
                return [$forceAnimal->ForAnimalDesc];
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
        } elseif ($nID == 269) { // Confirm Submission, Complaint Completed!
            return [(($this->sessData->dataSets["Complaints"][0]->ComStatus == 'New') ? 'Y' : '')];
        }
        return [];
    }
    
    protected function customLabels($nID = -3, $nodePromptText = '')
    {
        $event = [];
        if ($GLOBALS["SL"]->closestLoop["loop"] == 'Events') {
            $event = $this->getEventSequence($GLOBALS["SL"]->closestLoop["itemID"]);
        }
        if (isset($event[0]) && isset($event[0]["EveID"])) {
            if (strpos($nodePromptText, '[LoopItemLabel]') !== false) {
                $civName = $this->isEventAnimalForce($event[0]["EveID"], $event[0]["Event"]);
                if (trim($civName) == '' && isset($event[0]["Civilians"])) {
                    $civName = $this->getCivilianNameFromID($event[0]["Civilians"][0]);
                }
                $nodePromptText = str_replace('[LoopItemLabel]', 
                    '<span class="slBlueDark"><b>' . $civName . '</b></span>', $nodePromptText);
            }
            if (strpos($nodePromptText, '[ForceType]') !== false) {
                $forceDesc = $GLOBALS["SL"]->getDefValue('Force Type', $event[0]["Event"]->ForType);
                if ($forceDesc == 'Other') $forceDesc = $event[0]["Event"]->ForTypeOther;
                $nodePromptText = str_replace('[ForceType]', '<span class="slBlueDark"><b>'
                    . $forceDesc . '</b></span>', $nodePromptText);
            }
        }
        if (strpos($nodePromptText, '[InjuryType]') !== false) {
            $inj = $this->sessData->getRowById('Injuries', $GLOBALS["SL"]->closestLoop["itemID"]);
            if ($inj && sizeof($inj) > 0) {
                $nodePromptText = str_replace('[InjuryType]', '<span class="slBlueDark"><b>'
                    . $GLOBALS["SL"]->getDefValue('Injury Types', $inj->InjType) . '</b></span>', $nodePromptText);
                $nodePromptText = $this->cleanLabel(str_replace('[LoopItemLabel]', '<span class="slBlueDark"><b>'
                    . $this->getCivilianNameFromID($inj->InjSubjectID) . '</b></span>', $nodePromptText));
            }
        }
        if (strpos($nodePromptText, '[[ListCitationCharges]]') !== false) {
            $stop = $this->sessData->getRowById('Stops', $GLOBALS["SL"]->closestLoop["itemID"]);
            $chargesType = 'Citation Charges Pedestrian';
            $defID = $GLOBALS["SL"]->getDefID('Scene Type', 'Vehicle Stop');
            if ($this->sessData->dataSets["Scenes"][0]->ScnType == $defID) $chargesType = 'Citation Charges';
            $list = '';
            if (isset($this->sessData->dataSets["Charges"]) && sizeof($this->sessData->dataSets["Charges"]) > 0) {
                foreach ($this->sessData->dataSets["Charges"] as $chrg) {
                    if ($chrg->ChrgStopID == $stop->StopID) {
                        $list .= ', ' . $GLOBALS["SL"]->getDefValue($chargesType, $chrg->ChrgCharges);
                    }
                }
            }
            if (trim($stop->StopChargesOther) != '') $list .= ', ' . $stop->StopChargesOther;
            if (substr($list, 0, 2) == ', ') $list = trim(substr($list, 1));
            $nodePromptText = str_replace('[[ListCitationCharges]]', $list, $nodePromptText);
        }
        if (strpos($nodePromptText, '[[List of Allegations]]') !== false) {
            $nodePromptText = str_replace('[[List of Allegations]]', 
                $this->commaAllegationList(), $nodePromptText);
        }
        if (strpos($nodePromptText, '[[List of Events and Allegations]]') !== false) {
            $nodePromptText = str_replace('[[List of Events and Allegations]]', 
                $this->basicAllegationList(true), $nodePromptText);
        }
        if (strpos($nodePromptText, '[[List of Compliments]]') !== false) {
            $nodePromptText = str_replace('[[List of Compliments]]', 
                $this->commaComplimentList(), $nodePromptText);
        }
        return $nodePromptText;
    }
    
    protected function jumpToNodeCustom($nID)
    { 
        $newID = -3;
        // maybe needed?
        return $newID; 
    }
    
    protected function nodePrintJumpToCustom($nID = -3)
    {
       //if ($nID == 137 && intVal(session()->get('privacyJumpBackTo')) > 0) return session()->get('privacyJumpBackTo');
        return -3; 
    }
    
    protected function getLoopItemLabelCustom($loop, $itemRow = [], $itemInd = -3)
    {
        //if ($itemIndex < 0) return '';
        if (in_array($loop, ['Victims', 'Witnesses'])) {
            return $this->getCivName($loop, $itemRow, $itemInd);
        } elseif ($loop == 'Civilians') {
            return $this->getCivilianNameFromID($itemRow->getKey());
        } elseif (in_array($loop, ['Officers', 'Excellent Officers'])) {
            return $this->getOfficerName($itemRow, $itemInd);
        } elseif ($loop == 'Departments') {
            return $this->getDeptName($itemRow, $itemInd);
        } elseif ($loop == 'Events') {
            return $this->getEventLabel($itemRow->getKey());
        } elseif ($loop == 'Injuries') {
            return $this->getCivilianNameFromID($itemRow->InjSubjectID) . ': ' 
                .  $GLOBALS["SL"]->getDefValue('Injury Types', $itemRow->InjType);
        } elseif ($loop == 'Medical Care') {
            return $this->getCivilianNameFromID($itemRow->InjCareSubjectID);
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
        if (in_array($nID, [143, 917]) && $loopItem && sizeof($loopItem) > 0) { // $tbl == 'Departments'
            return view('vendor.openpolice.nodes.143-dept-loop-custom-row', [
                "loopItem" => $this->sessData->getChildRow('LinksComplaintDept', $loopItem->getKey(), 'Departments'), 
                "setIndex" => $setIndex, 
                "itemID"   => $loopItem->getKey()
            ])->render();
        }
        return '';
    }
    
/*****************************************************************************
// END Processes Which Override Default Behaviors of SetNav LOOPS
*****************************************************************************/
    
    












    
    
/*****************************************************************************
// START Processes Which Handle Allegations
*****************************************************************************/
    
    
    public function simpleAllegationList()
    {
        if (sizeof($this->allegations) == 0 && isset($this->sessData->dataSets["AllegSilver"]) 
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
                    case 'Wrongful Property Seizure or Damage':
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
                    case 'Retaliation':
                        $allegInfo[1] .= $this->chkSilvAlleg('AlleSilRetaliation', $alleg[1], $alleg[0]);
                        break;
                    case 'Retaliation: Unnecessary Charges':
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
                    case 'Policy or Procedure':
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
        $defA = $GLOBALS["SL"]->getDefID('Allegation Type', 'Intimidating Display of Weapon');
        $defB = $GLOBALS["SL"]->getDefID('Intimidating Displays Of Weapon', 'N/A');
        $defC = $GLOBALS["SL"]->getDefID('Intimidating Displays Of Weapon', 'Don\'t Know');
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
            && $this->sessData->dataSets["AllegSilver"][0]->{ $fldName } == 'Y') {
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
                                $allegOffs[$alleg->AlleID] .= ', '.$this->getOfficerNameFromID($off);
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
                    if ($alleg->AlleType == $GLOBALS["SL"]->getDefID('Allegation Type', $allegType)) {
                        if ($this->checkAllegIntimidWeapon($alleg)) {
                            $ret .= '<div class="f18">' . $allegType;
                            if (!$isAnon && !$printedOfficers && isset($allegOffs[$alleg->AlleID])) {
                                $ret .= ' <span class="f16 mL20 gry6">' . $allegOffs[$alleg->AlleID] . '</span>';
                            }
                            $ret .= '</div>' 
                            . (($showWhy) ? '<div class="gry9 f14 mTn10 pL20">'.$alleg->AlleDescription.'</div>' : '')
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
        if ($GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') 
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
        if ($GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') && sizeof($GLOBALS["SL"]->REQ->input('n' . $nID . 'fld')) > 0
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
        $handcuffDefID = $GLOBALS["SL"]->getDefID('Injury Types', 'Handcuff Injury');
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
        if ($eveSeq && sizeof($eveSeq) > 0) return $eveSeq->EveType;
        return '';
    }
    
    protected function isEventAnimalForce($eveSeqID = -3, $force = [])
    {
        if (sizeof($force) == 0) {
            $eveSeq = $this->sessData->getRowById('EventSequence', $eveSeqID);
            if ($eveSeq && sizeof($eveSeq) > 0 && $eveSeq->EveType == 'Force') {
                $force = $this->sessData->getChildRow('EventSequence', $eveSeq->EveID, $eveSeq->EveType);
            }
        }
        if (sizeof($force) > 0 && isset($force->ForAgainstAnimal) && $force->ForAgainstAnimal == 'Y') {
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
        if (!isset($eveSeq["EveType"]) && sizeof($eveSeq) > 0) $eveSeq = $eveSeq[0];
        if (!isset($eveSeq["EveType"]) || sizeof($eveSeq["Event"]) <= 0) return '';
        $ret = '<span class="slBlueDark">';
        if ($eveSeq["EveType"] == 'Force' 
            && isset($eveSeq["Event"]->ForType) && trim($eveSeq["Event"]->ForType) != '') {
            if ($eveSeq["Event"]->ForType == $GLOBALS["SL"]->getDefID('Force Type', 'Other')) {
                $ret .= $eveSeq["Event"]->ForTypeOther . ' Force ';
            } else {
                $ret .= $GLOBALS["SL"]->getDefValue('Force Type', $eveSeq["Event"]->ForType) . ' Force ';
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
                    $civNames .= ', '.$this->getCivilianNameFromID($civ);
                }
                if (trim($civNames) != '') {
                    $ret .= '<span class="blk">' . (($eveSeq["EveType"] == 'Force') ? 'on ' : 'of ')
                        . '</span>' . substr($civNames, 1);
                }
            }
            if ($this->moreThan1Officer() && in_array($info, array('', 'Officers'))) { 
                foreach ($eveSeq["Officers"] as $off) {
                    $offNames .= ', '.$this->getOfficerNameFromID($off);
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
        $race = $GLOBALS["SL"]->getDefValue('Races', $raceDefID);
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
        if (sizeof($civ2) > 0 && trim($civ2->PrsnNickname) != '') {
            $name = $civ2->PrsnNickname;
        } elseif (sizeof($civ2) > 0 && (trim($civ2->PrsnNameFirst) != '' || trim($civ2->PrsnNameLast) != '')) {
            $name = $civ2->PrsnNameFirst . ' ' . $civ2->PrsnNameLast . ' ' . $name;
        } else {
            if ($type == 'Officers' && isset($row->OffBadgeNumber) && intVal($row->OffBadgeNumber) > 0) {
                $name = 'Badge #' . $row->OffBadgeNumber . ' ' . $name;
            } else {
                $civ2 = $this->sessData->getChildRow($type, $id, 'PhysicalDesc');
                if (sizeof($civ2) > 0) {
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
        echo '<br /><br /><br />civID: ' . $civID . '<br />';
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
    
    protected function getDeptName($dept = [], $itemIndex = -3)
    {
        $name = ''; //(($itemIndex > 0) ? '<span class="fPerc66 gry9">(#'.$itemIndex.')</span>' : '');
        if (isset($dept->DeptName) && trim($dept->DeptName) != '') $name = $dept->DeptName.' '.$name;
        return trim($name);
    }
    
    protected function getDeptNameByID($deptID)
    {
        $dept = $this->sessData->getRowById('Departments', $deptID);
        if (sizeof($dept) > 0) return $this->getDeptName($dept);
        return '';
    }
    
    protected function civRow2Set($civ)
    {
        if (!$civ || sizeof($civ) == 0 || !isset($civ->CivIsCreator)) return '';
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
        if (sizeof($possible) == 0) $possible = $this->getCivSetPossibilities($tbl1, $tbl2);
        $ret = '<div class="nFld pB20">';
        if (sizeof($possible["opts"]) > 0) { 
            foreach ($possible["opts"] as $i => $civID) {
                $ret .= '<div class="nFld" style="font-size: 130%;"><nobr><input type="checkbox" autocomplete="off" 
                value="'.$civID.'" name="'.$tbl2.'[]" id="'.$tbl2.$civID.'" ' 
                . ((isset($possible["active"][$civID])) ? 'CHECKED' : '') . ' > 
                <label for="'.$tbl2.$civID.'">' . $this->getCivilianNameFromID($civID) . '</label></nobr></div>';
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
        if ($GLOBALS["SL"]->REQ->has($tbl2) && sizeof($GLOBALS["SL"]->REQ->input($tbl2)) > 0) { 
            foreach ($GLOBALS["SL"]->REQ->input($tbl2) as $civID) {
                if (!isset($possible["active"][$civID])) {
                    $injRow = $this->sessData->newDataRecord($tbl2, $activeFld, $civID);
                }
            }
        }
        return true;
    }
    
    protected function getVehicles()
    {
        $vehicles = [];
        if (isset($this->sessData->dataSets["Vehicles"]) && sizeof($this->sessData->dataSets["Vehicles"]) > 0) {
            foreach ($this->sessData->dataSets["Vehicles"] as $v) {
                $type = $GLOBALS["SL"]->getDefValue('Transportation Civilian', $v->VehicTransportation);
                if (trim($type) == '') {
                    $type = $GLOBALS["SL"]->getDefValue('Transportation Officer', $v->VehicTransportation);
                }
                $desc = [];
                if (trim($v->VehicVehicleMake) != '')    $desc[] = $v->VehicVehicleMake;
                if (trim($v->VehicVehicleModel) != '')   $desc[] = $v->VehicVehicleModel;
                if (trim($v->VehicVehicleDesc) != '')    $desc[] = $v->VehicVehicleDesc;
                if (trim($v->VehicVehicleLicence) != '') $desc[] = 'License Plate ' . $v->VehicVehicleLicence;
                if (trim($v->VehicVehicleNumber) != '')  $desc[] = '#' . $v->VehicVehicleNumber;
                $vehicles[] = [
                    $v->VehicID, 
                    $type . ((sizeof($desc) > 0) ? ': <span class="gry3">' . implode(', ', $desc) . '</span>' : '')
                ];
            }
        }
        return $vehicles;
    }
    
/*****************************************************************************
// END Processes Which Manage Lists of People
*****************************************************************************/





/*****************************************************************************
// START Processes Which Manage Uploads
*****************************************************************************/
    
    // $upArr = array('type' => '', 'title' => '', 'desc' => '', 'privacy' => '', 'upFile' => '', 'storeFile' => '', 
    // 'video' => '', 'vidDur' => 0);
    protected function getUploadLinks($nID)
    {
        $upLinks = [];
        $upLinks = array('CivilianID' => -3, 'DeptID' => -3, 'SceneID' => -3, 'EventSequenceID' => -3, 'InjuryID' => -3, 
            'NoteID' => -3);
        if ($nID == 280) {
            $upLinks["SceneID"] = $this->sessData->dataSets["Scenes"][0]->ScnID;
        } elseif ($nID == 317) {
            if (isset($this->sessData->dataSets["Injuries"]["Handcuff"]) 
                && sizeof($this->sessData->dataSets["Injuries"]["Handcuff"]) > 0) {
                $stopRow = $this->getEventSequence($GLOBALS["SL"]->closestLoop["itemID"]);
                foreach ($this->sessData->dataSets["Injuries"]["Handcuff"] as $i => $inj) {
                    if ($inj->InjSubjectID == $stopRow[0]["Civilians"][0]) {
                        $upLinks["InjuryID"] = $inj->InjID; // was "EvidInjuryID", on 1/28, for some reason?
                    }
                }
            }
        } elseif ($nID == 324) {
            $upLinks["EventSequenceID"] = $GLOBALS["SL"]->closestLoop["itemID"]; // search warrant
        } elseif ($nID == 413) {
            $upLinks["InjuryID"] = $GLOBALS["SL"]->closestLoop["itemID"]; // standard injury
        }
        return $upLinks;
    }
    
    protected function getUploadSet($nID)
    {
        if ($nID == 280) {
            return 'Scene';
        } elseif (in_array($nID, array(317, 413))) {
            return 'Injuries';
        } elseif ($nID == 324) {
            return 'EventSequence';
        }
        return '+';
    }

    protected function storeUploadRecord($nID, $upArr, $upLinks)
    {
        $newEvid = new OPEvidence;
        $newEvid->EvidComplaintID   = $this->coreID;
        $newEvid->EvidType          = $upArr["type"];
        $newEvid->EvidPrivacy       = $upArr["privacy"];
        $newEvid->EvidDateTime      = date("Y-m-d H:i:s");
        $newEvid->EvidTitle         = $upArr["title"];
        //$newEvid->EvidEvidenceDesc  = $upArr["desc"];
        $newEvid->EvidUploadFile    = $upArr["upFile"];
        $newEvid->EvidStoredFile    = $upArr["storeFile"];
        $newEvid->EvidVideoLink     = $upArr["video"];
        $newEvid->EvidVideoDuration = $upArr["vidDur"];
        if ($upLinks["CivilianID"] > 0)      $newEvid->EvidCivilianID = $upLinks["CivilianID"];
        if ($upLinks["DeptID"] > 0)          $newEvid->EvidDeptID = $upLinks["DeptID"];
        if ($upLinks["SceneID"] > 0)         $newEvid->EvidSceneID = $upLinks["SceneID"];
        if ($upLinks["EventSequenceID"] > 0) $newEvid->EvidEventSequenceID = $upLinks["EventSequenceID"];
        if ($upLinks["InjuryID"] > 0)        $newEvid->EvidInjuryID = $upLinks["InjuryID"];
        if ($upLinks["NoteID"] > 0)          $newEvid->EvidNoteID = $upLinks["NoteID"];
        $newEvid->save();
        return '';
    }
    
    protected function updateUploadRecord($nID, $upArr)
    {
        $evid = OPEvidence::find($upArr["id"]);
        if ($evid && sizeof($evid) > 0) {
            $evid->EvidType         = $upArr["type"];
            $evid->EvidPrivacy      = $upArr["privacy"];
            $evid->EvidTitle        = $upArr["title"];
            $evid->EvidEvidenceDesc = $upArr["desc"];
            $evid->save();
        }
        return '';
    }
    
    protected function prevUploadList($nID, $upLinks = [])
    {
        $retArr = [];
        $qman = "SELECT 
                `EvidID`            AS `id`, 
                `EvidType`          AS `type`, 
                `EvidTitle`         AS `title`, 
                `EvidEvidenceDesc`  AS `desc`, 
                `EvidPrivacy`       AS `privacy`, 
                `EvidUploadFile`    AS `upFile`, 
                `EvidStoredFile`    AS `storeFile`, 
                `EvidVideoLink`     AS `video`, 
                `EvidVideoDuration` AS `vidDur` 
            FROM `OP_Evidence` 
            WHERE `EvidComplaintID` LIKE '" . $this->coreID . "'";
        if (sizeof($upLinks) > 0) {
            foreach ($upLinks as $lnkType => $lnkVal) {
                if ($lnkVal > 0) $qman .= " AND `Evid" . $lnkType . "` LIKE '" . $lnkVal . "'";
            }
        }
        $retArr = DB::select( DB::raw( $qman . " ORDER BY `EvidID`" ) );
        return $retArr;
    }

/*****************************************************************************
// END Processes Which Manage Uploads
*****************************************************************************/



    
    public function runAjaxChecks(Request $request, $over = '')
    {
        if ($request->has('email') && $request->has('password')) {
            
            print_r($request);
            $chk = User::where('email', $request->email)->get();
            if ($chk && sizeof($chk) > 0) echo 'found';
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
                $deptsRes = OPDepartments::where('DeptName', 'LIKE', '%'.$request->policeDept.'%')
                    ->where('DeptAddressState', $reqState)
                    ->orderBy('DeptJurisdictionPopulation', 'desc')
                    ->orderBy('DeptTotOfficers', 'desc')
                    ->orderBy('DeptName', 'asc')
                    ->get();
                if ($deptsRes && sizeof($deptsRes) > 0) {
                    foreach ($deptsRes as $d) {
                        if (!in_array($d->DeptID, $deptIDs)) {
                            $deptIDs[] = $d->DeptID;
                            $depts[] = $d;
                        }
                    }
                }
                $deptsRes = OPDepartments::where('DeptAddressCity', 'LIKE', '%'.$request->policeDept.'%')
                    ->where('DeptAddressState', $reqState)
                    ->orderBy('DeptJurisdictionPopulation', 'desc')
                    ->orderBy('DeptTotOfficers', 'desc')
                    ->orderBy('DeptName', 'asc')
                    ->get();
                if ($deptsRes && sizeof($deptsRes) > 0) {
                    foreach ($deptsRes as $d) $depts[] = $d;
                }
                $deptsRes = OPDepartments::where('DeptAddress', 'LIKE', '%'.$request->policeDept.'%')
                    ->where('DeptAddressState', $reqState)
                    ->orderBy('DeptJurisdictionPopulation', 'desc')
                    ->orderBy('DeptTotOfficers', 'desc')
                    ->orderBy('DeptName', 'asc')
                    ->get();
                if ($deptsRes && sizeof($deptsRes) > 0) {
                    foreach ($deptsRes as $d) $depts[] = $d;
                }
                $zips = $counties = [];
                $cityZips = SLZips::where('ZipCity', 'LIKE', '%'.$request->policeDept.'%')
                    ->where('ZipState', 'LIKE', $reqState)
                    ->get();
                if ($cityZips && sizeof($cityZips) > 0) {
                    foreach ($cityZips as $z) {
                        $zips[] = $z->ZipZip;
                        $counties[] = $z->ZipCounty;
                    }
                    $deptsMore = OPDepartments::whereIn('DeptAddressZip', $zips)
                        ->orderBy('DeptName', 'asc')
                        ->get();
                    if ($deptsMore && sizeof($deptsMore) > 0) {
                        foreach ($deptsMore as $d) $depts[] = $d;
                    }
                    foreach ($counties as $c) {
                        $deptsMore = OPDepartments::where('DeptName', 'LIKE', '%'.$c.'%')
                            ->where('DeptAddressState', $reqState)
                            ->orderBy('DeptJurisdictionPopulation', 'desc')
                            ->orderBy('DeptTotOfficers', 'desc')
                            ->orderBy('DeptName', 'asc')
                            ->get();
                        if ($deptsMore && sizeof($deptsMore) > 0) {
                            foreach ($deptsMore as $d) $depts[] = $d;
                        }
                        $deptsMore = OPDepartments::where('DeptAddressCounty', 'LIKE', '%'.$c.'%')
                            ->where('DeptAddressState', $reqState)
                            ->orderBy('DeptJurisdictionPopulation', 'desc')
                            ->orderBy('DeptTotOfficers', 'desc')
                            ->orderBy('DeptName', 'asc')
                            ->get();
                        if ($deptsMore && sizeof($deptsMore) > 0) {
                            foreach ($deptsMore as $d) $depts[] = $d;
                        }
                    }
                }
            }
            $deptsFed = OPDepartments::where('DeptName', 'LIKE', '%'.$request->policeDept.'%')
                ->where('DeptType', 366)
                ->orderBy('DeptJurisdictionPopulation', 'desc')
                ->orderBy('DeptTotOfficers', 'desc')
                ->orderBy('DeptName', 'asc')
                ->get();
            if ($deptsFed && sizeof($deptsFed) > 0) {
                foreach ($deptsFed as $d) $depts[] = $d;
            }
            echo view('vendor.openpolice.ajax.search-police-dept', [
                "depts"            => $depts, 
                "search"           => $request->get('policeDept'), 
                "stateName"        => $GLOBALS["SL"]->states->getState($request->get('policeState')), 
                "newDeptStateDrop" => $GLOBALS["SL"]->states->stateDrop($request->get('policeState'), true)
            ]);
            return '';
            
        }
        exit;
    }
    
    public function allegationsList(Request $request)
    {
        $this->v["content"] = view('vendor.openpolice.allegations');
        return view('vendor.survloop.master', $this->v);
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
        return (isset($overRow->OverEmail) && trim($overRow->OverEmail) != '' 
            && isset($overRow->OverWaySubEmail) && intVal($overRow->OverWaySubEmail) == 1
            && isset($overRow->OverOfficialFormNotReq) && intVal($overRow->OverOfficialFormNotReq) == 1);
    }
    
    public function loadDeptStuff($deptID = -3)
    {
        if ($deptID > 0 && !isset($this->deptStuff[$deptID])) {
            $this->deptStuff[$deptID] = [ "id" => $deptID ];
            $this->deptStuff[$deptID]["deptRow"] = OPDepartments::where('DeptID', $deptID)->first();
            $this->deptStuff[$deptID]["iaRow"] = OPOversight::where('OverDeptID', $deptID)
                ->where('OverType', $GLOBALS["SL"]->getDefID('Oversight Agency Types', 'Internal Affairs'))
                ->first();
            $this->deptStuff[$deptID]["civRow"] = OPOversight::where('OverDeptID', $deptID)
                ->where('OverType', $GLOBALS["SL"]->getDefID('Oversight Agency Types', 'Civilian Oversight'))
                ->first();
            if (!isset($this->deptStuff[$deptID]["iaRow"]) || sizeof($this->deptStuff[$deptID]["iaRow"]) == 0) {
                $this->deptStuff[$deptID]["iaRow"] = new OPOversight;
                $this->deptStuff[$deptID]["iaRow"]->OverDeptID       = $deptID;
                $this->deptStuff[$deptID]["iaRow"]->OverType         = $GLOBALS["SL"]->getDefID('Oversight Agency Types', 'Internal Affairs');
                $this->deptStuff[$deptID]["iaRow"]->OverAgncName     = $this->deptStuff[$deptID]["deptRow"]->DeptName;
                $this->deptStuff[$deptID]["iaRow"]->OverAddress      = $this->deptStuff[$deptID]["deptRow"]->DeptAddress;
                $this->deptStuff[$deptID]["iaRow"]->OverAddress2     = $this->deptStuff[$deptID]["deptRow"]->DeptAddress2;
                $this->deptStuff[$deptID]["iaRow"]->OverAddressCity  = $this->deptStuff[$deptID]["deptRow"]->DeptAddressCity;
                $this->deptStuff[$deptID]["iaRow"]->OverAddressState = $this->deptStuff[$deptID]["deptRow"]->DeptAddressState;
                $this->deptStuff[$deptID]["iaRow"]->OverAddressZip   = $this->deptStuff[$deptID]["deptRow"]->DeptAddressZip;
                $this->deptStuff[$deptID]["iaRow"]->OverPhoneWork    = $this->deptStuff[$deptID]["deptRow"]->DeptPhoneWork;
                $this->deptStuff[$deptID]["iaRow"]->save();
            }
            $this->deptStuff[$deptID]["deptAddy"] = $this->deptStuff[$deptID]["deptRow"]->DeptAddress . ', ';
            if (isset($this->deptStuff[$deptID]["deptRow"]->DeptAddress2) 
                && trim($this->deptStuff[$deptID]["deptRow"]->DeptAddress2) != '') {
                $this->deptStuff[$deptID]["deptAddy"] .= $this->deptStuff[$deptID]["deptRow"]->DeptAddress2 . ', ';
            }
            $this->deptStuff[$deptID]["deptAddy"] .= $this->deptStuff[$deptID]["deptRow"]->DeptAddressCity . ', ' 
                . $this->deptStuff[$deptID]["deptRow"]->DeptAddressState . ' ' . $this->deptStuff[$deptID]["deptRow"]->DeptAddressZip;
            $this->deptStuff[$deptID]["iaAddy"] = '';
            if (isset($this->deptStuff[$deptID]["iaRow"]->OverAddress) && trim($this->deptStuff[$deptID]["iaRow"]->OverAddress) != '') {
                $this->deptStuff[$deptID]["iaAddy"] = $this->deptStuff[$deptID]["iaRow"]->OverAddress . ', ';
                if (isset($this->deptStuff[$deptID]["iaRow"]->OverAddress2) 
                    && trim($this->deptStuff[$deptID]["iaRow"]->OverAddress2) != '') {
                    $this->deptStuff[$deptID]["iaAddy"] .= $this->deptStuff[$deptID]["iaRow"]->OverAddress2 . ', ';
                }
                $this->deptStuff[$deptID]["iaAddy"] .= $this->deptStuff[$deptID]["iaRow"]->OverAddressCity . ', ' 
                    . $this->deptStuff[$deptID]["iaRow"]->OverAddressState . ' ' . $this->deptStuff[$deptID]["iaRow"]->OverAddressZip;
            }
            $this->deptStuff[$deptID]["civAddy"]  = '';
            if (isset($this->deptStuff[$deptID]["civRow"]->OverAddress) && trim($this->deptStuff[$deptID]["civRow"]->OverAddress) != '') {
                $this->deptStuff[$deptID]["civAddy"] = $this->deptStuff[$deptID]["civRow"]->OverAddress . ', ';
                if (isset($this->deptStuff[$deptID]["civRow"]->OverAddress2) 
                    && trim($this->deptStuff[$deptID]["civRow"]->OverAddress2) != '') {
                    $this->deptStuff[$deptID]["civAddy"] .= $this->deptStuff[$deptID]["civRow"]->OverAddress2 . ', ';
                }
                $this->deptStuff[$deptID]["civAddy"] .= $this->deptStuff[$deptID]["civRow"]->OverAddressCity . ', ' 
                    . $this->deptStuff[$deptID]["civRow"]->OverAddressState . ' ' . $this->deptStuff[$deptID]["civRow"]->OverAddressZip;
            }
            
            $this->deptStuff[$deptID]["whichOver"] = '';
            if (isset($this->deptStuff[$deptID]["civRow"]) && isset($this->deptStuff[$deptID]["civRow"]->OverAgncName)) {
                $this->deptStuff[$deptID]["whichOver"] = "civRow";
            } elseif (isset($this->deptStuff[$deptID]["iaRow"]) && isset($this->deptStuff[$deptID]["iaRow"]->OverAgncName)) {
                $this->deptStuff[$deptID]["whichOver"] = "iaRow";
            }
            
            $overRow = $this->deptStuff[$deptID][$this->deptStuff[$deptID]["whichOver"]];
            $this->deptStuff[$deptID]["score"] = [];
            $this->deptStuff[$deptID]["score"][] = [ 30, 'Has online complaint submission form', 
                (isset($overRow->OverWaySubOnline) && intVal($overRow->OverWaySubOnline) == 1
                && isset($overRow->OverComplaintWebForm) && trim($overRow->OverComplaintWebForm) != '') ];
            $this->deptStuff[$deptID]["score"][] = [ 20, 'Has complaint information on website', 
                (isset($overRow->OverWebComplaintInfo)) ];
            $this->deptStuff[$deptID]["score"][] = [ 15, 'Has complaint information linked from home page', 
                (isset($overRow->OverHomepageComplaintLink) && trim($overRow->OverHomepageComplaintLink) == 'Y') ];
            $this->deptStuff[$deptID]["score"][] = [ 15, 'Has complaint form PDF on website', 
                (isset($overRow->OverComplaintPDF) && trim($overRow->OverComplaintPDF) != '') ];
            $this->deptStuff[$deptID]["score"][] = [ 15, 'Investigates complaints sent via email', 
                (isset($overRow->OverWaySubEmail) && intVal($overRow->OverWaySubEmail) == 1
                && isset($overRow->OverEmail) && trim($overRow->OverEmail) != '') ];
            $this->deptStuff[$deptID]["score"][] = [ 15, 'Official department form not required for investigation', 
                (isset($overRow->OverOfficialFormNotReq) && intVal($overRow->OverOfficialFormNotReq) > 0) ];
            $this->deptStuff[$deptID]["score"][] = [ 15, 'Anonymous complaints investigated', 
                (isset($overRow->OverOfficialAnon) && intVal($overRow->OverOfficialAnon) == 1) ];
            $this->deptStuff[$deptID]["score"][] = [ 5, 'Has a unique department website', 
                (isset($overRow->OverWebsite) && trim($overRow->OverWebsite) != '') ];
            $this->deptStuff[$deptID]["score"][] = [ 5, 'Has a Facebook page', 
                (isset($overRow->OverFacebook) && trim($overRow->OverFacebook) != '') ];
            $this->deptStuff[$deptID]["score"][] = [ 5, 'Has a Twitter account', 
                (isset($overRow->OverTwitter) && trim($overRow->OverTwitter) != '') ];
            $this->deptStuff[$deptID]["score"][] = [ 5, 'Has a YouTube channel', 
                (isset($overRow->OverYouTube) && trim($overRow->OverYouTube) != '') ];
            $this->deptStuff[$deptID]["score"][] = [ 3, 'Investigates complaints sent via phone', 
                (isset($overRow->OverWaySubVerbalPhone) && intVal($overRow->OverWaySubVerbalPhone) == 1) ];
            $this->deptStuff[$deptID]["score"][] = [ 2, 'Investigates complaints sent via snail mail', 
                (isset($overRow->OverWaySubPaperMail) && intVal($overRow->OverWaySubPaperMail) == 1) ];
            $this->deptStuff[$deptID]["score"][] = [ 0, 'Requires complaints to be filed in person', 
                (isset($overRow->OverWaySubPaperInPerson) && intVal($overRow->OverWaySubPaperInPerson) == 1) ];
            $this->deptStuff[$deptID]["score"][] = [ -10, 'Requires Notary (for any type of complaint)', 
                (isset($overRow->OverWaySubNotary) && intVal($overRow->OverWaySubNotary) == 1) ];
        }
        return true;
    }
    
    public function sendEmailBlurbsCustom($emailBody, $deptID = -3)
    {
        if (sizeof($this->deptStuff) == 0) {
            if ($deptID > 0) {
                $this->loadDeptStuff($deptID);
            } elseif (isset($this->sessData->dataSets["LinksComplaintDept"]) 
                && sizeof($this->sessData->dataSets["LinksComplaintDept"]) > 0) {
                foreach ($this->sessData->dataSets["LinksComplaintDept"] as $deptLnk) {
                    $this->loadDeptStuff($deptLnk->LnkComDeptDeptID);
                }
            }
        } elseif (!isset($this->deptStuff[$deptID]) == 0) {
            $this->loadDeptStuff($deptID);
        }
        if (strpos($emailBody, '[{ Complaint Oversight Agency }]') !== false) {
            $overName = $this->deptStuff[$deptID][$this->deptStuff[$deptID]["whichOver"]]->OverAgncName;
            $forDept = (($overName != $this->deptStuff[$deptID]["deptRow"]->DeptName) 
                ? ' (for the ' . $this->deptStuff[$deptID]["deptRow"]->DeptName . ')' : '');
            $splits = $GLOBALS["SL"]->mexplode('[{ Complaint Oversight Agency }]', $emailBody);
            $emailBody = $splits[0] . $overName . $forDept;
            for ($i=1; $i<sizeof($splits); $i++) {
                $emailBody .= (($i > 1) ? $overName : '') . $splits[$i];
            }
        }
        $dynamos = [
            '[{ Complaint ID }]', 
            '[{ Complaint URL }]', 
            '[{ Complaint URL Link }]', 
            '[{ Complainant Name }]', 
            '[{ Confirmation URL }]', 
            '[{ Go Gold Secure URL }]', 
            '[{ Submit Silver Secure URL }]', 
            '[{ Update Complaint Secure URL }]', 
            '[{ Login URL }]', 
            '[{ Days From Now: 7, mm/dd/yyyy }]', 
            '[{ Complaint Number of Weeks Old }]', 
            '[{ Analyst Name }]', 
            '[{ Complaint Department Submission Ways }]', 
            '[{ Complaint Police Department }]', 
            '[{ Complaint Police Department URL }]', 
            '[{ Dear Primary Oversight Agency }]', 
            '[{ Complaint Investigability Score & Description }]', 
            '[{ Complaint Allegation List }]', 
            '[{ Oversight Complaint Secure URL }]', 
            '[{ Complaint Department Complaint PDF }]', 
            '[{ Complaint Department Complaint Web }]', 
            '[{ Flex Article Suggestions Based On Responses }]'
        ];
        foreach ($dynamos as $dy) {
            if (strpos($emailBody, $dy) !== false) {
                $swap = $dy;
                $dyCore = str_replace('[{ ', '', str_replace(' }]', '', $dy));
                switch ($dy) {
                    case '[{ Complaint ID }]': 
                        $swap = $this->coreID;
                        break;
                    case '[{ Complaint URL }]':
                        $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . '/complaint-read/' 
                            . $this->coreID);
                        break;
                    case '[{ Complaint URL Link }]':
                        $swap = $this->sessData->dataSets["Complaints"][0]->ComSlug;
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
                    case '[{ Confirmation URL }]':
                        $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . 'complaint-read/' 
                            . $this->coreID . '/confirm-email/goooobblygooook8923528350');
                        break;
                    case '[{ Go Gold Secure URL }]':
                        $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . 'complaint-read/' 
                            . $this->coreID . '/go-gold/goooobblygooook8923528350');
                        break;
                    case '[{ Submit Silver Secure URL }]':
                        $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . 'complaint-read/' 
                            . $this->coreID . '/submit-silver/goooobblygooook8923528350');
                        break;
                    case '[{ Update Complaint Secure URL }]':
                        $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . 'complaint-read/' 
                            . $this->coreID . '/update/goooobblygooook8923528350');
                        break;
                    case '[{ Login URL }]':
                        $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . 'login');
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
                    case '[{ Complaint Police Department }]':
                        $swap = $this->deptStuff[$deptID]["deptRow"]->DeptName;
                        break;
                    case '[{ Complaint Police Department URL }]':
                        $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . '/dept/' 
                            . $this->deptStuff[$deptID]["deptRow"]->DeptSlug);
                        break;
                    case '[{ Dear Primary Oversight Agency }]':
                        $swap = 'To whom it may concern:';
                        break;
                    case '[{ Complaint Allegation List }]':
                        $swap = $this->CustReport->commaAllegationList();
                        break;
                    case '[{ Oversight Complaint Secure URL }]':
                        $swap = $GLOBALS["SL"]->swapURLwrap($GLOBALS["SL"]->sysOpts["app-url"] . 'complaint-read/' 
                            . $this->coreID . '/oversight/goooobblygooook8923528350');
                        break;
                    case '[{ Complaint Department Complaint PDF }]':
                        $which = $this->deptStuff[$deptID]["whichOver"];
                        if (isset($this->deptStuff[$deptID][$which]->OverComplaintPDF) 
                            && trim($this->deptStuff[$deptID][$which]->OverComplaintPDF) != '') {
                            $swap = '';
                            if (sizeof($this->deptStuff) > 1) {
                                $swap .= $this->deptStuff[$deptID][$which]->OverAgncName;
                            }
                            $swap .= ': ' 
                                . $GLOBALS["SL"]->swapURLwrap($this->deptStuff[$deptID][$which]->OverComplaintPDF);
                        }
                        if (sizeof($this->deptStuff) > 1) {
                            for ($i = 1; $i < sizeof($this->deptStuff); $i++) {
                                if (trim($swap) != '') $swap .= '<br />';
                                $which = $this->deptStuff[$i]["whichOver"];
                                $swap .= $this->deptStuff[$deptID][$which]->OverAgncName . ': ' 
                                    . $GLOBALS["SL"]->swapURLwrap($this->deptStuff[$i][$which]->OverComplaintPDF);
                            }
                        }
                        break;
                    case '[{ Complaint Department Complaint Web }]':
                        $which = $this->deptStuff[$deptID]["whichOver"];
                        if (isset($this->deptStuff[$deptID][$which]->OverComplaintWebForm) 
                            && trim($this->deptStuff[$deptID][$which]->OverComplaintWebForm) != '') {
                            $swap = '';
                            if (sizeof($this->deptStuff) > 1) {
                                $swap = $this->deptStuff[$deptID][$which]->OverAgncName;
                            }
                            $swap .= ': ' . $GLOBALS["SL"]->swapURLwrap(
                                $this->deptStuff[$deptID][$which]->OverComplaintWebForm);
                        }
                        if (sizeof($this->deptStuff) > 1) {
                            for ($i = 1; $i < sizeof($this->deptStuff); $i++) {
                                if (trim($swap) != '') $swap .= '<br />';
                                $currWhich = $this->deptStuff[$i]["whichOver"];
                                $swap .= $this->deptStuff[$deptID][$which]->OverAgncName . ': ' 
                                    . $GLOBALS["SL"]->swapURLwrap(
                                        $this->deptStuff[$i][$currWhich]->OverComplaintWebForm);
                            }
                        }
                        break;
                    case '[{ Flex Article Suggestions Based On Responses }]':
                        $swap = '';
                        break;
                }
                $emailBody = str_replace($dy, $swap, $emailBody);
            }
        }
        
        $emailBody = str_replace('[{ Flex Article Suggestions Based On Responses }]', 
            '[{ Flex Article Suggestions Based On Responses }]', $emailBody);
        
        $emailBody = str_replace('Hello Complainant,', 'Hello,', $emailBody);

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
    
    
}
