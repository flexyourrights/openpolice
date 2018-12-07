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
use App\Models\OPPartners;
use App\Models\OPPartnerCaseTypes;

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

class OpenPolice extends OpenPoliceUtils
{
    public $classExtension = 'OpenPolice';
    
    // Initializing a bunch of things which are not [yet] automatically determined by the software
    protected function initExtra(Request $request)
    {
        // Establishing Main Navigation Organization, with Node ID# and Section Titles
        $this->loadYourContact();
        $this->majorSections = [];
        if (!isset($GLOBALS["SL"]->treeID)) return true;
        if ($GLOBALS["SL"]->treeID == 1) {
            $this->navMenuComplaint();
        } elseif ($GLOBALS["SL"]->treeID == 5) {
            $this->navMenuCompliment();
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
    
    // Maps survey tree nodes which wrap navigational sections
    protected function navMenuComplaint()
    {
        $this->majorSections = [];
        $this->majorSections[] = [1,      'Your Story',      'active'];
        $this->majorSections[] = [4,      'Who\'s Involved', 'active'];
        $this->majorSections[] = [5,      'Allegations',     'active'];
        $this->majorSections[] = [6,      'Go Gold',         'disabled'];
        $this->majorSections[] = [419,    'Finish',          'active'];
        
        $this->minorSections = [ [], [], [], [], [] ];
        $this->minorSections[0][] = [157, 'Start Your Story'];
        $this->minorSections[0][] = [437, 'Privacy Options'];
        $this->minorSections[0][] = [158, 'When & Where'];
        $this->minorSections[0][] = [707, 'The Scene'];
        
        $this->minorSections[1][] = [139, 'About You'];
        $this->minorSections[1][] = [140, 'Victims'];
        $this->minorSections[1][] = [141, 'Witnesses'];
        $this->minorSections[1][] = [144, 'Police Departments'];
        $this->minorSections[1][] = [142, 'Officers'];
        
        $this->minorSections[2][] = [198, 'Stops'];
        $this->minorSections[2][] = [199, 'Searches'];
        $this->minorSections[2][] = [200, 'Force'];
        $this->minorSections[2][] = [201, 'Arrests & Citations'];
        $this->minorSections[2][] = [154, 'Other'];
        
        $this->minorSections[3][] = [196, 'GO GOLD!']; // 483
        $this->minorSections[3][] = [149, 'Stops & Searches'];
        $this->minorSections[3][] = [153, 'Arrests & Citations'];
        $this->minorSections[3][] = [151, 'Uses of Force'];
        $this->minorSections[3][] = [410, 'Injuries & Medical'];
        
        $this->minorSections[4][] = [420, 'Review Narrative'];
        $this->minorSections[4][] = [431, 'Sharing Options'];
        $this->minorSections[4][] = [156, 'Submit Complaint'];
        return true;
    }
    
    protected function navMenuCompliment()
    {
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
        return true;
    }
    
    protected function overrideMinorSection($nID = -3, $majorSectInd = -3)
    {
        //if ($nID == 482) return 148;
        return -1;
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
                $this->sessData->dataSets["Civilians"][0]->update([ 'CivUserID' => $this->v["user"]->id ]);
            }
            $this->chkPersonRecs();
            if (isset($this->sessData->dataSets["Departments"]) && 
                sizeof($this->sessData->dataSets["Departments"]) > 0) {
                foreach ($this->sessData->dataSets["Departments"] as $i => $d) $this->chkDeptLinks($d->DeptID);
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
            foreach ($this->eventTypeLabel as $type => $label) {
                if (!isset($this->sessData->dataSets[$type]) || sizeof($this->sessData->dataSets[$type]) == 0) {
                    $this->addNewEveSeq($type);
                }
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
        if ($this->treeID == 1 && session()->has('opcPartID') && intVal(session()->get('opcPartID')) > 0
            && isset($this->sessData->dataSets["Complaints"]) 
            && intVal($this->sessData->dataSets["Complaints"][0]->ComSubmissionProgress) > 0) {
            $this->sessData->dataSets["Complaints"][0]->ComAttID = intVal(session()->get('opcPartID'));
            $this->sessData->dataSets["Complaints"][0]->save();
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
    
    protected function afterCreateNewDataLoopItem($tbl = '', $itemID = -3)
    {
        if (in_array($tbl, ['Civilians', 'Officers']) && $itemID > 0) $this->chkPersonRecs();
        return true;
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
            if (isset($this->sessData->dataSets["Complaints"][0]->ComAnyoneCharged) 
                && trim($this->sessData->dataSets["Complaints"][0]->ComAnyoneCharged) == 'N'
                && isset($this->sessData->dataSets["Complaints"][0]->ComFileLawsuit) 
                && trim($this->sessData->dataSets["Complaints"][0]->ComFileLawsuit) == 'Y') {
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
        } elseif ($condition == '#CivHasForce') {
            if (isset($GLOBALS["SL"]->closestLoop["itemID"])) {
                return $this->chkCivHasForce($GLOBALS["SL"]->closestLoop["itemID"]);
            }
            return 0;
        } elseif ($condition == '#HasForceHuman') {
            $ret = 0;
            if (isset($this->sessData->dataSets["Civilians"]) && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
                foreach ($this->sessData->dataSets["Civilians"] as $civ) {
                    if ($civ->CivRole == 'Victim' && $this->chkCivHasForce($civ->CivID) == 1) $ret = 1;
                }
            }
            return $ret;
        } elseif ($condition == '#HasInjury') {
            if (isset($this->sessData->dataSets["Civilians"]) && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
                foreach ($this->sessData->dataSets["Civilians"] as $civ) {
                    if ($civ->CivRole == 'Victim' && isset($civ->CivHasInjury) && trim($civ->CivHasInjury) == 'Y') {
                        return 1;
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
        if ($nID == 1362) { // Loading Complaint Report: Check for oversight permissions
            if (!isset($GLOBALS["SL"]->x["pageView"])) $this->maxUserView(); // shouldn't be needed?
            if ($this->chkOverUserHasCore()) $GLOBALS["SL"]->x["dataPerms"] = 'sensitive';
        }
        return true;
    }
    
    
/*****************************************************************************
// START Processes Which Override Basic Node Printing and $_POST Processing
*****************************************************************************/

    protected function customNodePrint($nID = -3, $tmpSubTier = [], $nIDtxt = '', $nSffx = '', $currVisib = 1)
    {
        $ret = '';
        // Main Complaint Survey
        if (in_array($nID, [145, 920])) {
            return $this->printDeptSearch($nID);
        } elseif ($nID == 203) {
            $this->initBlnkAllegsSilv();
        } elseif (in_array($nID, [270, 973])) {
            return $this->printEndOfComplaintRedirect($nID);
            
        // Home Page
        } elseif ($nID == 1876) {
            return view('vendor.openpolice.nodes.1876-home-page-hero-credit')->render();
        } elseif ($nID == 1848) {
            return view('vendor.openpolice.nodes.1848-home-page-disclaimer-bar')->render();
                
        // FAQ
        } elseif ($nID == 1884) {
            $GLOBALS["SL"]->addBodyParams('onscroll="if (typeof bodyOnScroll === \'function\') bodyOnScroll();"');
            
        // Public Departments Accessibility Overview
        } elseif ($nID == 1968) {
            return $this->printDeptAccScoreTitleDesc($nID);
        } elseif ($nID == 1816) {
            return $this->printDeptAccScoreBars($nID);
        } elseif (in_array($nID, [1863, 1858]) || $nID == 2013) {
            return $this->publicDeptAccessMap($nID);
        } elseif ($nID == 1896) {
            return $this->printAttorneyReferrals($nID);
        } elseif ($nID == 1961) {
            return $this->publicAttorneyHeader($nID);
        } elseif ($nID == 1898) {
            return $this->publicAttorneyPage($nID);
        } elseif ($nID == 1907) { // Donate Social Media Buttons
            return view('vendor.openpolice.nodes.1907-donate-share-social')->render();
        } elseif (in_array($nID, [859, 1454])) {
            return view('vendor.openpolice.nodes.859-depts-overview-public', [
                "nID"        => $nID,
                "deptScores" => $this->v["deptScores"],
                "state"      => ((isset($this->searchOpts["state"])) ? $this->searchOpts["state"] : '')
                ])->render();
        } elseif (in_array($nID, [1456])) { // public oversight overview
            return $this->printOversightOverview($nID);
                
        // How We Rate Departments Page
        } elseif ($nID == 1127) {
            foreach ([1827, 1825, 1829, 1831, 1833, 1837, 1806, 1835, 1, 2, 3, 4, 5, 6, 7] as $n) {
                $GLOBALS["SL"]->addHshoo('/how-we-rate-departments#n' . $n);
            }
            
        // Department Profile
        } elseif ($nID == 1779) {
            return $this->printDeptComplaints($nID);
        } elseif ($nID == 1099) {
            return $this->printDeptPage1099($nID);
               
        // User Profile
        } elseif ($nID == 1893) {
            return $this->printProfileMyComplaints($nID);
            
        // Complaint Report
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
        } elseif ($nID == 1891) {
            return $this->getReportOffAge($nID);
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
        } elseif ($nID == 1939) {
            return $this->printManageAttorneys();
        } elseif ($nID == 1924) {
            return $this->initPartnerCaseTypes($nID);
            
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
    
    protected function customNodePrintButton($nID = -3, $promptNotes = '')
    { 
        if (in_array($nID, [270, 973])) return '<!-- no buttons, all done! -->';
        return '';
    }
    
    protected function postNodePublicCustom($nID = -3, $tmpSubTier = [])
    {
        if (empty($tmpSubTier)) $tmpSubTier = $this->loadNodeSubTier($nID);
        list($tbl, $fld) = $this->allNodes[$nID]->getTblFld();
        if ($this->treeID == 1 && isset($this->sessData->dataSets["Complaints"])) {
            $this->sessData->dataSets["Complaints"][0]->update([ "updated_at" => date("Y-m-d H:i:s") ]);
        }
        // Main Complaint Survey...
        if ($nID == 439) {
            return $this->saveUnresolvedCharges($nID);
        } elseif (in_array($nID, [16, 17])) {
            return $this->saveStartTime($nID, $tbl, $fld);
        } elseif (in_array($nID, [145, 920])) {
            return $this->saveNewDept($nID);
        } elseif ($nID == 237) {
            return $this->saveCitationVictims($nID);
        } elseif ($nID == 671) {
            return $this->saveProfanePersons($nID);
        } elseif ($nID == 674) {
            return $this->saveProfanePerson($nID);
        } elseif ($nID == 670) {
            return $this->saveProfanePersons($nID, 'Civ');
        } elseif ($nID == 676) {
            return $this->saveProfanePerson($nID, 'Civ');
        } elseif (in_array($nID, [742, 2044])) {
            return $this->saveForceTypes($nID);
        } elseif ($nID == 743) {
            return $this->saveForceAnimYN($nID);
        } elseif ($nID == 744) {
            return $this->saveForceTypesAnim($nID, 743, 746);
        } elseif ($nID == 316) {
            return $this->saveHandcuffInjury($nID);
        } elseif ($nID == 976) {
            return $this->saveStatusCompletion($nID);
            
        // Department Editor Survey ...
        } elseif ($nID == 1285) {
            return $this->saveDeptSubWays1($nID);
        } elseif ($nID == 1287) {
            return $this->saveDeptSubWays2($nID);
        } elseif ($nID == 1329) {
            return $this->saveEditLog($nID);
        } elseif ($nID == 1229) {
            return $this->saveInitDeptOversight($nID);
            
        // Page Nodes ...
        } elseif ($nID == 1007) {
            return $this->postContactEmail($nID);
        }
        return false; // false to continue standard post processing
    }
    
    protected function postContactEmail($nID)
    {
        $this->postNodeLoadEmail($nID);
        if ($GLOBALS["SL"]->REQ->has('n831fld') && trim($GLOBALS["SL"]->REQ->n831fld) != '') return true;
        $emaSubject = $this->postDumpFormEmailSubject();
        $emaContent = view('vendor.openpolice.contact-form-email-admin')->render();
        $this->sendEmail($emaContent, $emaSubject, $this->v["emaTo"], $this->v["emaCC"], $this->v["emaBCC"],
            ['noreply@openpolice.org', 'OPC Contact']);
        $emaID = ((isset($currEmail->EmailID)) ? $currEmail->EmailID : -3);
        $this->logEmailSent($emaContent, $emaSubject, $this->v["toList"], $emaID, $this->treeID, $this->coreID,
            $this->v["uID"]);
        $this->manualLogContact($nID, $emaContent, $emaSubject, $this->v["toList"], $GLOBALS["SL"]->REQ->n829fld);
        return true;
    }
    
    protected function postEmailFrom()
    {
        if ($this->treeID == 13) return ['', 'OPC Contact'];
        return [];
    }
    
    protected function postDumpFormEmailSubject()
    {
        if ($this->treeID == 13 && $GLOBALS["SL"]->REQ->has('n829fld')) {
            return $GLOBALS["SL"]->REQ->n829fld 
                . (($GLOBALS["SL"]->REQ->has('n1879fld')) ? ': ' . $GLOBALS["SL"]->REQ->n1879fld : '')
                . (($GLOBALS["SL"]->REQ->has('n1880fld')) ? ': ' . $GLOBALS["SL"]->REQ->n1880fld : '')
                . (($GLOBALS["SL"]->REQ->has('n1881fld')) ? ': ' . $GLOBALS["SL"]->REQ->n1881fld : '')
                . (($GLOBALS["SL"]->REQ->has('n1873fld')) ? ': ' . implode(', ', $GLOBALS["SL"]->REQ->n1873fld) : '')
                . (($GLOBALS["SL"]->REQ->has('n1872fld')) ? ' -' . $GLOBALS["SL"]->REQ->n1872fld : '');
        }
        return $GLOBALS["SL"]->sysOpts["site-name"] . ': ' . $GLOBALS["SL"]->treeRow->TreeName;
    }
    
    // returns an array of overrides for ($currNodeSessionData, ???... 
    protected function printNodeSessDataOverride($nID = -3, $tmpSubTier = [], $nIDtxt = '', $currNodeSessionData = '')
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
        } elseif ($nID == 237) {
            $ret = [];
            $civs = $this->sessData->getLoopRows('Victims');
            if ($civs && sizeof($civs) > 0) {
                foreach ($civs as $i => $civ) {
                    if ($civ->CivRole == 'Victim' && trim($civ->CivGivenCitation) == 'Y') $ret[] = $civ->CivID;
                }
            }
            return $ret;
        } elseif ($nID == 674) { // Officer Used Profanity?
            return [trim($this->sessData->dataSets["Officers"][0]->OffUsedProfanity)];
        } elseif ($nID == 670) { // Victims Used Profanity?
            $currVals = [];
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if ($civ->CivUsedProfanity == 'Y') $currVals[] = $civ->getKey();
            }
            return [';' . implode(';', $currVals) . ';'];
        } elseif ($nID == 676) { // Victim Used Profanity?
            $civInd = $this->getFirstVictimCivInd();
            if ($civInd >= 0) {
                return [trim($this->sessData->dataSets["Civilians"][$civInd]->CivUsedProfanity)];
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
        } elseif (in_array($nID, [742, 2044])) { // Use of Force on Victims: Sub-Types
            $ret = [];
            if (isset($this->sessData->dataSets["Force"]) && sizeof($this->sessData->dataSets["Force"]) > 0) {
                foreach ($this->sessData->dataSets["Force"] as $force) {
                    if (isset($force->ForType) && intVal($force->ForType) > 0 
                        && (!isset($force->ForAgainstAnimal) || trim($force->ForAgainstAnimal) != 'Y')) {
                        $ret[] = $force->ForType;
                    }
                }
            }
            return $ret;
        } elseif ($nID == 2043) {
            $force = $this->sessData->getDataBranchRow('Force');
            if ($force && isset($force->ForEventSequenceID) && intVal($force->ForEventSequenceID) > 0) {
                return $this->getLinkedToEvent('Civilian', $force->ForEventSequenceID);
            }
            return [];
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
            if (sizeof($this->sessData->dataBranches) > 1 
                && $this->sessData->dataBranches[1]["branch"] == 'EventSequence') {
                $event = $this->getEventSequence($this->sessData->dataBranches[1]["itemID"]);
            }
            if (isset($event[0]) && isset($event[0]["EveID"])) {
                if (strpos($str, '[LoopItemLabel]') !== false) {
                    $civName = $this->isEventAnimalForce($event[0]["EveID"], $event[0]["Event"]);
                    if (trim($civName) == '' && isset($event[0]["Civilians"]) && sizeof($event[0]["Civilians"]) > 0) {
                        $civName = $this->getCivilianNameFromID($event[0]["Civilians"][0]);
                    }
                    $str = str_replace('[LoopItemLabel]', '<span class="slBlueDark"><b>' . $civName . '</b></span>', 
                        $str);
                }
                if (strpos($str, '[ForceType]') !== false) {
                    $forceDesc = $GLOBALS["SL"]->def->getVal('Force Type', $event[0]["Event"]->ForType);
                    if ($forceDesc == 'Other') $forceDesc = $event[0]["Event"]->ForTypeOther;
                    $str = str_replace('[ForceType]', '<span class="slBlueDark"><b>' . $forceDesc .'</b></span>', $str);
                }
            } elseif (strpos($str, '[LoopItemLabel]') !== false) {
                $row = $this->sessData->getLatestDataBranchRow();
                if (isset($row->CivID)) {
                    $str = str_replace('[LoopItemLabel]', $this->getCivName('Victims', $row), $str);
                } elseif (isset($row->InjCareSubjectID)) {
                    $civ = $this->sessData->getRowById('Civilians', $row->InjCareSubjectID);
                    $str = str_replace('[LoopItemLabel]', $this->getCivName('Victims', $civ), $str);
                }
            }
            if (strpos($str, '[[List of Allegations]]') !== false) {
                $str = str_replace('[[List of Allegations]]', $this->commaAllegationList(), $str);
            }
            if (strpos($str, '[[List of Events and Allegations]]') !== false) {
                $str = str_replace('[[List of Events and Allegations]]', $this->basicAllegationList(true), $str);
            }
            if (strpos($str, '[[List of Compliments]]') !== false) {
                $str = str_replace('[[List of Compliments]]', $this->commaComplimentList(), $str);
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
                    if ($complainantVic && !$multipleVic) $str = str_replace('anybody', 'you', $str);
                } elseif (in_array($nIDtxt, ['228'])) {
                    if ($complainantVic && !$multipleVic) $str = str_replace('anybody was', 'you were', $str);
                }
                $str = str_replace('Did you who was not arrested get a ticket or citation?', 
                    'Did you get a ticket or citation?', $str);
            }
        }
        return $str;
    }
    
    protected function getLoopItemLabelCustom($loop, $itemRow = null, $itemInd = -3)
    {
//echo '<br /><br /><br />getLoopItemLabelCustom(' . $loop . '<br /><pre>'; print_r($itemRow); echo '</pre>';
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
    
    public function printPreviewReportCustom($isAdmin = false)
    {
        $coreAbbr = $GLOBALS["SL"]->coreTblAbbr();
        if (!isset($this->sessData->dataSets[$GLOBALS["SL"]->coreTbl]) 
            || !isset($this->sessData->dataSets["Incidents"])) {
            return '';
        }
        $storyPrev = $this->wordLimitDotDotDot($this->sessData->dataSets[$GLOBALS["SL"]->coreTbl][0]->{ 
            $coreAbbr . 'Summary' }, 100);
        $comDate = date('F Y', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeStart));
        if ($this->sessData->dataSets[$GLOBALS["SL"]->coreTbl][0]->{ $coreAbbr . 'Privacy' } == 304 
            || $this->v["isAdmin"]) {
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
            "uID"         => $this->v["uID"],
            "storyPrev"   => $storyPrev,
            "coreAbbr"    => $coreAbbr,
            "complaint"   => $this->sessData->dataSets[$GLOBALS["SL"]->coreTbl][0], 
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
// START Processes Overwrite The Posting of Specific Nodes
*****************************************************************************/
    
    protected function saveUnresolvedCharges($nID)
    {
        if ($GLOBALS["SL"]->REQ->has('n' . $nID . 'fld')) {
            $defID = $GLOBALS["SL"]->def->getID('Unresolved Charges Actions', 'Full complaint to print or save');
            if ($GLOBALS["SL"]->REQ->input('n' . $nID . 'fld') == $defID) {
                $defID = $GLOBALS["SL"]->def->getID('Privacy Types', 'Anonymized');
                if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == $defID) {
                    $this->sessData->dataSets["Complaints"][0]->update([
                        "ComPrivacy" => $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')
                    ]);
                }
            } else {
                $defID = $GLOBALS["SL"]->def->getID('Unresolved Charges Actions', 'Anonymous complaint data only');
                if ($GLOBALS["SL"]->REQ->input('n' . $nID . 'fld') == $defID) {
                    $this->sessData->dataSets["Complaints"][0]->update([
                        "ComPrivacy" => $GLOBALS["SL"]->def->getID('Privacy Types', 'Anonymized')
                    ]);
                }
            }
        }
        return false;
    }
    
    protected function saveStartTime($nID, $tbl, $fld)
    {
        $time = $this->postFormTimeStr($nID);
        $date = date("Y-m-d", strtotime($GLOBALS["SL"]->REQ->get("n15fld")));
        if ($time === null) $date .= ' 00:00:00';
        else $date .= ' ' . $time;
        $this->sessData->currSessData($nID, $tbl, $fld, 'update', $date);
        return true;
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
    
    protected function saveCitationVictims($nID)
    {
        $isEmpty = (!$GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') 
            || !is_array($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld')) 
            || sizeof($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld')) == 0);
        $civs = $this->sessData->getLoopRows('Victims');
        if ($civs && sizeof($civs) > 0) {
            foreach ($civs as $i => $civ) {
                if ($isEmpty || !in_array($civ->CivID, $GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'))) {
                    $civ->update([ 'CivGivenCitation' => 'N' ]);
                } else {
                    $civ->update([ 'CivGivenCitation' => 'Y' ]);
                }
            }
        }
        return true;
    }
    
    protected function saveProfanePerson($nID, $type = 'Off')
    {
        $tbl = (($type == 'Off') ? 'Officers' : 'Civilians');
        if ($GLOBALS["SL"]->REQ->has('n' . $nID . 'fld')) {
            $this->sessData->dataSets[$tbl][0]->{ $type . 'UsedProfanity' } 
                = trim($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'));
        } else {
            $this->sessData->dataSets[$tbl][0]->{ $type . 'UsedProfanity' } = '';
        }
        $this->sessData->dataSets[$tbl][0]->save();
        return true;
    }
    
    protected function saveProfanePersons($nID, $type = 'Off')
    {
        $tbl = (($type == 'Off') ? 'Officers' : 'Civilians');
        foreach ($this->sessData->dataSets[$tbl] as $i => $off) {
            if ($GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') 
                && in_array($off->getKey(), $GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'))) {
                $this->sessData->dataSets[$tbl][$i]->{ $type . 'UsedProfanity' } = 'Y';
            } else {
                $this->sessData->dataSets[$tbl][$i]->{ $type . 'UsedProfanity' } = '';
            }
            $this->sessData->dataSets[$tbl][$i]->save();
        }
        return true;
    }
    
    protected function saveForceTypes($nID)
    {
        $GLOBALS["SL"]->def->loadDefs('Force Type');
        if ($GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') && is_array($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'))
            && sizeof($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld')) > 0) {
            foreach ($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld') as $forceType) {
                if ($this->getForceEveID($forceType) <= 0) $this->addNewEveSeq('Force', $forceType);
                if ($nID == 742) {
                    $fInd = 0;
                    foreach ($GLOBALS["SL"]->def->defValues["Force Type"] as $i => $typ) {
                        if ($typ->DefID == $forceType) $fInd = $i;
                    }
                    $eveID = $this->getForceEveID($forceType);
                    $currCivs = $this->getLinkedToEvent('Civilian', $eveID);
                    if ($GLOBALS["SL"]->REQ->has('n2043res' . $fInd . 'fld') 
                        && is_array($GLOBALS["SL"]->REQ->get('n2043res' . $fInd . 'fld'))
                        && sizeof($GLOBALS["SL"]->REQ->get('n2043res' . $fInd . 'fld')) > 0) {
                        foreach ($GLOBALS["SL"]->REQ->get('n2043res' . $fInd . 'fld') as $civID) {
                            if (!in_array($civID, $currCivs)) {
                                $newLnk = new OPLinksCivilianEvents;
                                $newLnk->LnkCivEveEveID = $eveID;
                                $newLnk->LnkCivEveCivID = $civID;
                                $newLnk->save();
                            }
                        }
                    }
                    if (sizeof($currCivs) > 0) {
                        foreach ($currCivs as $currCivID) {
                            if (!$GLOBALS["SL"]->REQ->has('n2043res' . $fInd . 'fld') 
                                || !is_array($GLOBALS["SL"]->REQ->get('n2043res' . $fInd . 'fld'))
                                || !in_array($currCivID, $GLOBALS["SL"]->REQ->get('n2043res' . $fInd . 'fld'))) {
                                OPLinksCivilianEvents::where('LnkCivEveEveID', $eveID)
                                    ->where('LnkCivEveCivID', $currCivID)
                                    ->delete();
                            }
                        }
                    }
                }
            }
        }
        foreach ($GLOBALS["SL"]->def->defValues["Force Type"] as $i => $def) {
            if (!$GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') 
                || !in_array($def->DefID, $GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'))) {
                $e = $this->getForceEveID($def->DefID);
                $this->deleteEventByID($e);
            }
        }
        $this->sessData->refreshDataSets();
        return true;
    }
    
    protected function saveForceAnimYN($nID)
    {
        if (!$GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') || $GLOBALS["SL"]->REQ->get('n' . $nID . 'fld') == 'N') {
            $animalsForce = $this->getCivAnimalForces();
            if ($animalsForce && sizeof($animalsForce) > 0) {
                foreach ($animalsForce as $force) $this->deleteEventByID($force->ForEventSequenceID);
            }
        }
        return false;
    }
    
    protected function saveForceTypesAnim($nID1, $nID2, $nID3)
    {
        if ($GLOBALS["SL"]->REQ->has('n' . $nID2 . 'fld') && $GLOBALS["SL"]->REQ->get('n' . $nID2 . 'fld') == 'Y') { 
            if ($GLOBALS["SL"]->REQ->has('n' . $nID1 . 'fld') 
                && is_array($GLOBALS["SL"]->REQ->get('n' . $nID1 . 'fld')) 
                && sizeof($GLOBALS["SL"]->REQ->get('n' . $nID1 . 'fld')) > 0) {
                $animalDesc = (($GLOBALS["SL"]->REQ->has('n' . $nID3 . 'fld')) 
                    ? trim($GLOBALS["SL"]->REQ->get('n' . $nID3 . 'fld')) : '');
                $animalsForce = $this->getCivAnimalForces();
                foreach ($GLOBALS["SL"]->REQ->n744fld as $forceType) {
                    $foundType = false;
                    if ($animalsForce && sizeof($animalsForce) > 0) {
                        foreach ($animalsForce as $force) {
                            if ($force->ForType == $forceType) $foundType = true;
                        }
                    }
                    if (!$foundType) {
                        $newForce = $this->addNewEveSeq('Force', $forceType);
                        $newForce->ForAgainstAnimal = 'Y';
                        $newForce->ForAnimalDesc = $animalDesc;
                        $newForce->save();
                    }
                }
            }
            foreach ($GLOBALS["SL"]->def->defValues["Force Type"] as $i => $def) {
                if (!$GLOBALS["SL"]->REQ->has('n' . $nID1 . 'fld') 
                    || !in_array($def->DefID, $GLOBALS["SL"]->REQ->get('n' . $nID1 . 'fld'))) {
                    $this->deleteEventByID($this->getForceEveID($def->DefID, true));
                }
            }
        }
        return true;
    }
    
    protected function saveHandcuffInjury($nID)
    {
        $handcuffDefID = $GLOBALS["SL"]->def->getID('Injury Types', 'Handcuff Injury');
        $stopRow = $this->getEventSequence($this->sessData->dataBranches[1]["itemID"]);
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
    
    protected function saveDeptSubWays1($nID)
    {
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
        return false;
    }
    
    protected function saveDeptSubWays2($nID)
    {
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
        return false;
    }
    
    protected function saveEditLog($nID)
    {
        if ($GLOBALS["SL"]->REQ->get('step') != 'next') return true;
        if ($GLOBALS["SL"]->REQ->has('n' . $nID . 'fld') && is_array($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld'))
            && sizeof($GLOBALS["SL"]->REQ->get('n' . $nID . 'fld')) > 0) {
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
        return false;
    }
    
    protected function saveStatusCompletion($nID)
    {
        if ($GLOBALS["SL"]->REQ->get('step') != 'next') return true;
        $this->sessData->dataSets['Complaints'][0]->ComStatus = $GLOBALS["SL"]->def->getID('Complaint Status', 'New');
        $this->sessData->dataSets['Complaints'][0]->save();
        return false;
    }
    
    protected function saveInitDeptOversight($nID)
    {
//echo '<br /><br /><br />checking 1229: ' . (($GLOBALS["SL"]->REQ->has('n1341fld')) ? 'has name' : 'no name') . '<br />';
        if ($GLOBALS["SL"]->REQ->has('n1341fld') && trim($GLOBALS["SL"]->REQ->n1341fld) != ''
            && sizeof($this->sessData->dataSets['Oversight']) == 1) {
            $new = $this->sessData->newDataRecord('Oversight', 'OverDeptID', 
                $this->sessData->dataSets['Departments'][0]->DeptID, true);
            $new->OverType = 302;
            $new->save();
            $this->sessData->refreshDataSets();
        }
        return false;
    }

/*****************************************************************************
// END Processes Overwrite The Posting of Specific Nodes
*****************************************************************************/




/*****************************************************************************
// START Processes Overwrite The Printing of Specific Nodes
*****************************************************************************/

    protected function printDeptSearch($nID)
    {
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
        return view('vendor.openpolice.nodes.145-dept-search', [ 
            "nID"                => $nID,
            "IncAddressCity"     => $this->sessData->dataSets["Incidents"][0]->IncAddressCity, 
            "stateDropstateDrop" => $GLOBALS["SL"]->states->stateDrop(
                $this->sessData->dataSets["Incidents"][0]->IncAddressState, true) 
            ])->render();
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
    
    protected function printEndOfComplaintRedirect($nID)
    {
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
            $url = '/compliment/read-' . $this->sessData->dataSets["Compliments"][0]->ComPublicID;
        }
        $spin = $GLOBALS["SL"]->sysOpts["spinner-code"];
        $this->restartSess($GLOBALS["SL"]->REQ);
        return '<br /><br /><center><h1>All Done!<br />Taking you to <a href="' . $url . '">your finished '
            . (($nID == 270) ? 'complaint' : 'compliment') . '</a> ...</h1>' . $spin 
            . '</center><script type="text/javascript"> setTimeout("window.location=\'' . $url 
            . '\'", 1500); </script><style> #nodeSubBtns, #sessMgmt, #dontWorry { display: none; } </style>';
    }
    
    protected function printDeptAccScoreTitleDesc($nID)
    {
        $this->getSearchFilts();
        if (!isset($this->v["deptScores"])) {
            $this->v["deptScores"] = new DepartmentScores;
            $this->v["deptScores"]->loadAllDepts($this->searchOpts);
        }
        return view('vendor.openpolice.nodes.1968-accss-grades-title-desc', [
            "nID"   => $nID,
            "state" => (($GLOBALS["SL"]->REQ->has('state')) ? $GLOBALS["SL"]->REQ->state : '')
            ])->render();
    }
    
    protected function printDeptAccScoreBars($nID)
    {
        if (!$GLOBALS["SL"]->REQ->has('state') || trim($GLOBALS["SL"]->REQ->get('state')) == '') {
            $GLOBALS["SL"]->addBodyParams('onscroll="if (typeof bodyOnScroll === \'function\') bodyOnScroll();"');
        }
        return $this->v["deptScores"]->printTotsBars();
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
    }
    
    protected function printOversightOverview($nID)
    {
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
        return '';
    }
    
    protected function printDeptPage1099($nID)
    {
        /*
        if (!isset($this->v["deptID"]) || intVal($this->v["deptID"]) <= 0) {
            if ($GLOBALS["SL"]->REQ->has('d') && intVal($GLOBALS["SL"]->REQ->get('d')) > 0) {
                $this->v["deptID"] = $GLOBALS["SL"]->REQ->get('d');
            } else {
                $this->v["deptID"] = -3;
            }
        }
        $this->loadDeptStuff($this->v["deptID"]);
        */
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
        return view('vendor.openpolice.dept-page', [
            "nID"      => $nID,
            "d"        => $GLOBALS["SL"]->x["depts"][$this->v["deptID"]],
            "previews" => $previews
            ])->render();
    }
    
    protected function printProfileMyComplaints($nID)
    {
        $ret = '';
        if ($this->v["uID"] > 0) { // loading records for my own profile
            $chk = OPComplaints::where('ComUserID', $this->v["uID"])
                ->where('ComStatus', '>', 0)
                ->orderBy('created_at', 'desc')
                ->get();
            if ($chk->isNotEmpty()) {
                $loadURL = '/record-prevs/1?rawids=';
                foreach ($chk as $i => $rec) $loadURL .= (($i > 0) ? ',' : '') . $rec->ComID;
                $ret .= '<h2 class="slBlueDark m0">Your Complaints</h2><div id="n' . $nID 
                    . 'ajaxLoadA" class="w100">' . $GLOBALS["SL"]->sysOpts["spinner-code"] . '</div>';
                $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID . 'ajaxLoadA").load("' . $loadURL . '");' . "\n";
            } else {
                $ret .= '<div class="p10"><i>No Complaints</i></div>';
            }
            $ret .= '<div class="p20">&nbsp;</div>';
            $chk = OPCompliments::where('CompliUserID', $this->v["uID"])
                ->where('CompliStatus', '>', 0)
                ->orderBy('created_at', 'desc')
                ->get();
            if ($chk->isNotEmpty()) {
                $loadURL = '/record-prevs/5?rawids=';
                foreach ($chk as $i => $rec) $loadURL .= (($i > 0) ? ',' : '') . $rec->CompliID;
                $ret .= '<h2 class="slBlueDark m0">Your Compliments</h2><div id="n' . $nID 
                    . 'ajaxLoadB" class="w100">' . $GLOBALS["SL"]->sysOpts["spinner-code"] . '</div>';
                $GLOBALS["SL"]->pageAJAX .= '$("#n' . $nID . 'ajaxLoadB").load("' . $loadURL . '");' . "\n";
            } else {
                $ret .= '<div class="p10"><i>No Compliments</i></div>';
            }
        }
        return $ret;
    }
    
    protected function printDeptComplaints($nID)
    {
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
    }



/*****************************************************************************
// END Processes Overwrite The Printing of Specific Nodes
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
        if (!isset($this->v["civNames"][$civID]) || trim($this->v["civNames"][$civID]) == '') {
            if (!$prsn) list($prsn, $phys) = $this->queuePeopleSubsets($civID);
            $name = '';
            if ($GLOBALS["SL"]->x["pageView"] != 'public') {
                if ( $civID == $this->sessData->dataSets["Civilians"][0]->CivID 
                    && (trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) == ''
                    || $this->sessData->dataSets["Complaints"][0]->ComPrivacy == 306) ) {
                    $name = '<span style="color: #2b3493;" title="This complainant did not provide their name to '
                        . 'investigators.">Complainant</span>';
                } elseif (trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) != '' 
                    && ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 304 
                    || $GLOBALS["SL"]->x["pageView"] == 'full')) {
                    if (trim($prsn->PrsnNickname) != '') {
                        $name = trim($prsn->PrsnNickname);
                    } else {
                        $name = '<span style="color: #2b3493;" title="This complainant wanted to publicly provide '
                            . 'their name.">' . $prsn->PrsnNameFirst . ' ' . $prsn->PrsnNameLast 
                            . '</span>'; // ' . $prsn->PrsnNameMiddle . ' 
                    }
                }
            }
            $label = 'Complainant';
            if ($this->sessData->dataSets["Civilians"][0]->CivID != $civID) {
                if ($type == 'Subject') $label = 'Victim #' . (1+$this->sessData->getLoopIndFromID('Victims', $civID));
                else $label = 'Witness #' . (1+$this->sessData->getLoopIndFromID('Witnesses', $civID));
            } elseif ($this->sessData->dataSets["Civilians"][0]->CivRole == 'Victim') {
                $label = 'Victim #' . (1+$this->sessData->getLoopIndFromID('Victims', $civID));
            } elseif ($this->sessData->dataSets["Civilians"][0]->CivRole == 'Witness') {
                $label = 'Witness #' . (1+$this->sessData->getLoopIndFromID('Witnesses', $civID));
            }
            $this->v["civNames"][$civID] = $label . ((trim($name) != '') ? ': ' . $name : '');
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
    
    protected function getReportOffAge($nID)
    {
        $phys = $this->sessData->getLatestDataBranchRow();
        if ($phys && isset($phys->PhysAge) && intVal($phys->PhysAge) > 0) {
            return ['<span class="slGrey">Age Range</span>', 
                $GLOBALS["SL"]->def->getVal('Age Ranges Officers', $phys->PhysAge), $nID];
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
        if (in_array($this->treeID, [1, 42]) || $GLOBALS["SL"]->getReportTreeID() == 1) {
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
            "warning"     => $this->multiRecordCheckDelWarn()
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
// START Processes Which Manage AJAX or Frame Routines
*****************************************************************************/
    
    public function runAjaxChecks(Request $request, $over = '')
    {
        if ($request->has('email') && $request->has('password')) {
            return $this->ajaxEmailPass($request);
        } elseif ($request->has('policeDept')) {
            return $this->ajaxPoliceDeptSearch($request);
        }
        exit;
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
            
// check cache!
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
    
/*****************************************************************************
// END Processes Which Manage AJAX or Frame Routines
*****************************************************************************/




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
            '[{ Police Department State Abbr }]',
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
                    case '[{ Police Department State Abbr }]':
                        $swap = $GLOBALS["SL"]->x["depts"][$deptID]["deptRow"]->DeptAddressState;
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
        if ($deptID > 0) {
            $this->v["deptID"] = $deptRow->DeptID;
            $this->loadDeptStuff($deptID);
        }
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
    
    protected function printManageAttorneys()
    {
        $defAtt = $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney');
        if ($GLOBALS["SL"]->REQ->has('add')) {
            $newAtt = new OPPartners;
            $newAtt->PartType = $defAtt;
            $newAtt->save();
            $this->redir('/dashboard/start-' . $newAtt->PartID . '/attorney-profile', true);
        }
        $this->v["partners"] = DB::table('OP_Partners')
            ->join('OP_PersonContact', 'OP_PersonContact.PrsnID', '=', 'OP_Partners.PartPersonID')
            ->leftJoin('users', 'users.id', '=', 'OP_Partners.PartUserID')
            ->where('OP_Partners.PartType', $defAtt)
            ->select('OP_Partners.*', 'users.name', 'users.email', 'OP_PersonContact.PrsnNickname', 
                'OP_PersonContact.PrsnNameFirst', 'OP_PersonContact.PrsnNameLast',
                'OP_PersonContact.PrsnAddressCity', 'OP_PersonContact.PrsnAddressState')
            ->orderBy('OP_PersonContact.PrsnNickname', 'asc')
            ->get();
        return view('vendor.openpolice.nodes.1939-manage-attorneys', $this->v)->render();
    }
    
    protected function initPartnerCaseTypes($nID)
    {
        if (!isset($this->sessData->dataSets["PartnerCaseTypes"])
            || sizeof($this->sessData->dataSets["PartnerCaseTypes"]) == 0) {
            $this->sessData->dataSets["PartnerCaseTypes"] = [];
            for ($i = 0; $i < 3; $i++) {
                $this->sessData->dataSets["PartnerCaseTypes"][$i] = new OPPartnerCaseTypes;
                $this->sessData->dataSets["PartnerCaseTypes"][$i]->PrtCasPartnerID = $this->coreID;
                $this->sessData->dataSets["PartnerCaseTypes"][$i]->save();
            }
        }
        return true;
    }
    
    
    
    
/*****************************************************************************
// START Admin Tools
*****************************************************************************/
    
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
                    $deptRow = OPDepartments::find($lnk->LnkComDeptDeptID);
                    if ($deptRow && isset($deptRow->DeptName) && trim($deptRow->DeptName) != '') {
                        $this->v["comDepts"][$cnt] = [ "id" => $lnk->LnkComDeptDeptID ];
                        $this->v["comDepts"][$cnt]["deptRow"] = $deptRow;
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
        }
        return true;
    }
    
    public function getOversightInfo($overRow)
    {
        return '';
        /* return view('vendor.openpolice.nodes.1896-attorney-referral-listings', [
            "o"   => $overRow,
            "nID" => 1896
            ])->render(); */
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
    
    protected function printAttorneyReferrals($nID = -3)
    {
        
        return view('vendor.openpolice.nodes.1896-attorney-referral-listings', [
            "nID"        => $nID
            ])->render();
    }
    
    public function attorneyPage(Request $request, $prtnSlug = '')
    {
        $partID = -3;
        $partRow = OPPartners::where('PartSlug', $prtnSlug)
            ->first();
        if ($partRow && isset($partRow->PartID)) {
            $partID = $partRow->PartID;
            $request->atr = $partRow->PartID;
        }
        $this->loadPageVariation($request, 1, 56, '/attorney/' . $prtnSlug);
        if ($partID > 0) {
            $this->coreID = $partRow->PartID;
            $this->loadAllSessData($GLOBALS["SL"]->coreTbl, $this->coreID);
        }
        return $this->index($request);
    }
    
    public function shareStoryAttorney(Request $request, $prtnSlug = '')
    {
        $this->loadPageVariation($request, 1, 62, '/preparing-your-complaint-for-an-attorney/' . $prtnSlug);
        $partRow = OPPartners::where('PartSlug', $prtnSlug)
            ->first();
        if ($partRow && isset($partRow->PartID)) session()->put('opcPartID', $partRow->PartID);
        return $this->index($request);
    }
    
    protected function publicAttorneyHeader($nID = -3)
    {
        $coreID = (($this->coreID > 0) ? $this->coreID : 1);
        $this->loadSessionData('Partners', $coreID);
        if (!isset($this->sessData->dataSets["Partners"])) return '';
        return view('vendor.openpolice.nodes.1961-public-attorney-header', [
            "nID" => $nID,
            "dat" => $this->sessData->dataSets
            ])->render();
    }
    
    protected function publicAttorneyPage($nID = -3)
    {
        if (!isset($this->sessData->dataSets["Partners"])) return '';
        return view('vendor.openpolice.nodes.1898-public-attorney-page', [
            "nID" => $nID,
            "dat" => $this->sessData->dataSets
            ])->render();
    }
    
    protected function publicDeptAccessMap($nID = -3)
    {
        if ($GLOBALS["SL"]->REQ->has('state') && trim($GLOBALS["SL"]->REQ->get('state')) != '') {
            return '<!-- no state map yet -->';
        }
        $ret = '';
        if ($GLOBALS["SL"]->REQ->has('colorMarker')) {
            $colors = [];
            for ($i = 0; $i < 5; $i++) {
                $colors[$i] = $GLOBALS["SL"]->printColorFadeHex($i/7, '#EC2327', '#FFFFFF');
            }
                
            for ($i = 5; $i < 11; $i++) {
                $colors[$i] = $GLOBALS["SL"]->printColorFadeHex((11-$i)/7, '#2B3493', '#FFFFFF');
            }
            echo '<br /><br /><br /><table border=0 cellpadding=0 cellspacing=5 class="m20" ><tr>';
            foreach ($colors as $i => $c) {
                echo '<td><img src="/survloop/uploads/template-map-marker.png" border=0 '
                    . 'style="width: 80px; background: ' . $c . ';"><br /><br />' . $c . '</td>';
            }
            echo '</tr></table><table border=0 cellpadding=0 cellspacing=5 class="m20" ><tr>';
            foreach ($colors as $i => $c) {
                echo '<td><img src="/openpolice/uploads/map-marker-redblue-' . $i . '.png" border=0 ></td>';
            }
            echo '</tr></table>';
        }
        
        $GLOBALS["SL"]->loadStates();
        if (!isset($this->v["deptScores"])) {
            $this->v["deptScores"] = new DepartmentScores;
            $this->v["deptScores"]->loadAllDepts($this->searchOpts);
        }
        if ($GLOBALS["SL"]->REQ->has('state') && trim($GLOBALS["SL"]->REQ->get('state')) != '') {
            $ret .= '<!-- not yet for state filter -->';
        } else {
            $cnt = 0;
            $limit = 10;
//echo '<pre>'; print_r($this->v["deptScores"]->scoreDepts); echo '</pre>';
            for ($i = sizeof($this->v["deptScores"]->scoreDepts)-1; $i >= 0; $i--) {
                $dept = $this->v["deptScores"]->scoreDepts[$i];
//echo $dept->DeptID . ' - ' . $GLOBALS["SL"]->printRowAddy($dept, 'Dept') . ' - ' . $dept->DeptAddressLat . ', ' . $dept->DeptAddressLng . '<br />';
                if ($cnt < $limit && !isset($dept->DeptAddressLat) || intVal($dept->DeptAddressLat) == 0) {
                    $addy = $GLOBALS["SL"]->printRowAddy($dept, 'Dept');
                    if (trim($addy) != '') {
                        list($this->v["deptScores"]->scoreDepts[$i]->DeptAddressLat, 
                            $this->v["deptScores"]->scoreDepts[$i]->DeptAddressLng) 
                            = $GLOBALS["SL"]->states->getLatLng($addy);
                        $this->v["deptScores"]->scoreDepts[$i]->save();
                        $cnt++;
                    }
                }
                if (isset($dept->DeptAddressLat) && $dept->DeptAddressLat != 0 && isset($dept->DeptAddressLng) 
                    && $dept->DeptAddressLng != 0 && $dept->DeptScoreOpenness > 0) {
                    $GLOBALS["SL"]->states->addMapMarker($dept->DeptAddressLat, $dept->DeptAddressLng, 
                        'RBgradient' . round($dept->DeptScoreOpenness/10), 
                        $dept->DeptName . ': ' . $dept->DeptScoreOpenness, '',
                        '/ajax/dept-kml-desc?deptID=' . $dept->DeptID);
                }
            }
            for ($g = 0; $g < 11; $g++) {
                $GLOBALS["SL"]->states->addMarkerType('RBgradient' . $g,
                    $GLOBALS["SL"]->sysOpts["app-url"] . '/openpolice/uploads/map-marker-redblue-' . $g . '.png');
            }
            $ret = $GLOBALS["SL"]->states->embedMap($nID, 'dept-access-scores-all' . time(), 
                'All Department Accessibility Scores');
            if ($ret == '') $ret = "\n\n <!-- no map markers found --> \n\n";
            elseif ($nID == 2013 && $GLOBALS["SL"]->REQ->has('test')) {
                $GLOBALS["SL"]->pageAJAX .= '$("#map' . $nID . 'ajax").load("/ajax/dept-kml-desc?deptID=13668");';
            }
        }
        return $ret;
    }
    
    
    
}
