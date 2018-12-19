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
use OpenPolice\Controllers\OpenPolice;
use OpenPolice\Controllers\VolunteerLeaderboard;
    use OpenPolice\Controllers\VolunteerController;
use OpenPolice\Controllers\DepartmentScores;
use OpenPolice\Controllers\OpenDashAdmin;
use SurvLoop\Controllers\SurvStatsGraph;

class OpenPolice extends OpenPartners
{
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
    
    protected function ajaxContentWrapCustom($str, $nID = -3)
    {
        if ($this->treeID == 1) {
            if ($GLOBALS["SL"]->REQ->has('treeSlug') && trim($GLOBALS["SL"]->REQ->get('treeSlug')) == 'complaint'
                && $GLOBALS["SL"]->REQ->has('nodeSlug') && trim($GLOBALS["SL"]->REQ->get('nodeSlug')) == 'login') {
                return $this->redir('/u/complaint/when-and-where', true);
            }
        }
        return $str;
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
            
        } elseif ($nID == 1907) { // Donate Social Media Buttons
            return view('vendor.openpolice.nodes.1907-donate-share-social')->render();
        } elseif (in_array($nID, [859, 1454])) {
            return $this->printDeptOverPublic($nID);
                
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
            
        // Partner Profiles
        } elseif ($nID == 1896) {
            return $this->printAttorneyReferrals($nID);
        } elseif (in_array($nID, [1961, 2062])) {
            return $this->publicPartnerHeader($nID);
        } elseif (in_array($nID, [1898, 2060])) {
            return $this->publicPartnerPage($nID);
        } elseif ($nID == 2069) {
            return $this->printPreparePartnerHeader($nID);
               
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
            
        // Admin Dashboard Page
        } elseif ($nID == 1359) {
            $this->initAdmDash();
            return $this->v["openDash"]->printDashSessGraph();
        } elseif ($nID == 1342) {
            $this->initAdmDash();
            return $this->v["openDash"]->printDashPercCompl();
        } elseif ($nID == 1361) {
            $this->initAdmDash();
            return $this->v["openDash"]->printDashTopStats();
        } elseif ($nID == 1349) {
            $this->initAdmDash();
            return $this->v["openDash"]->volunStatsDailyGraph();
        } elseif ($nID == 2100) {
            $this->initAdmDash();
            return $this->v["openDash"]->volunStatsTable();
        }
        return $ret;
    }
    
    protected function initAdmDash()
    {
        $this->v["isDash"] = true;
        if (!isset($this->v["openDash"])) {
            $this->v["openDash"] = new OpenDashAdmin;
        }
        return true;
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
    
/*****************************************************************************
// END Processes Which Override Default Behaviors
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
    
}