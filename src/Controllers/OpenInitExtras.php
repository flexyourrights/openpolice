<?php
namespace OpenPolice\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OPComplaints;
use App\Models\OPDepartments;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksComplimentDept;
use App\Models\OPPersonContact;
use App\Models\OPzVolunUserInfo;
use OpenPolice\Controllers\OpenPartners;

class OpenInitExtras extends OpenPartners
{
    // Initializing a bunch of things which are not [yet] automatically determined by the software
    protected function initExtra(Request $request)
    {
        // Establishing Main Navigation Organization, with Node ID# and Section Titles
        $this->loadYourContact();
        $this->majorSections = [];
        if (!isset($GLOBALS["SL"]->treeID)) {
            return true;
        }
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
        if ($this->treeID == 1 && $this->isGold()) {
            $this->majorSections[3][2] = 'active';
        }
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
        
        // used to be admin initializations:
        $this->v["allowEdits"] = ($this->v["uID"] > 0 && $this->v["user"] && $this->v["user"]->hasRole('administrator|staff'));
        $this->v["management"] = ($this->v["uID"] > 0 && $this->v["user"] && $this->v["user"]->hasRole('administrator|staff'));
        $this->v["volunOpts"] = 1;
        if ($GLOBALS["SL"]->REQ->session()->has('volunOpts')) {
            $this->v["volunOpts"] = $GLOBALS["SL"]->REQ->session()->get('volunOpts');
        }
        if ((!session()->has('opcChks') || !session()->get('opcChks') || $GLOBALS["SL"]->REQ->has('refresh'))
            && $this->treeID == 1) {
            $chk = OPComplaints::where('ComPublicID', null)
                ->where('ComStatus', 'NOT LIKE', $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete'))
                ->get();
            if ($chk->isNotEmpty()) {
                foreach ($chk as $i => $complaint) {
                    $complaint->update([ 'ComPublicID' => $GLOBALS["SL"]->genNewCorePubID('Complaints') ]);
                }
            }
            session()->put('opcChks', true);
        }
        
        // Department Research Survey
        if ($this->treeID == 36) {
            if (isset($this->sessData->dataSets['Oversight']) && sizeof($this->sessData->dataSets['Oversight']) == 1) {
                $new = $this->sessData->newDataRecord('Oversight', 'OverDeptID', 
                    $this->sessData->dataSets['Departments'][0]->DeptID, true);
                $new->OverType = 302;
                $new->save();
                $this->sessData->refreshDataSets();
            }
        }
        return true;
    }
    
    public function initAdmMenuExtras()
    {
        if (in_array($this->treeID, [99, 46])) { // admin area view of complaint reports
            return '/dash/all-complete-complaints';
        }
        return '';
    }
    
    public function initPowerUser($uID = -3)
    {
        if ($uID <= 0) {
            $uID = $this->v["uID"];
        }
        if ($uID > 0) {
            $GLOBALS["SL"]->x["yourUserInfo"] = OPzVolunUserInfo::where('UserInfoUserID', $uID)
                ->first();
            if (!$GLOBALS["SL"]->x["yourUserInfo"]) {
                $GLOBALS["SL"]->x["yourUserInfo"] = new OPzVolunUserInfo;
                $GLOBALS["SL"]->x["yourUserInfo"]->UserInfoUserID = $uID;
                $GLOBALS["SL"]->x["yourUserInfo"]->save();
            }
            $this->v["yourUserContact"] = [];
            if (!isset($GLOBALS["SL"]->x["yourUserInfo"]->UserInfoPersonContactID) 
                || intVal($GLOBALS["SL"]->x["yourUserInfo"]->UserInfoPersonContactID) <= 0) {
                $thisUser = User::select('email')->find($uID);
                $this->v["yourUserContact"] = new OPPersonContact;
                $this->v["yourUserContact"]->PrsnEmail = $thisUser->email;
                $this->v["yourUserContact"]->save();
                $GLOBALS["SL"]->x["yourUserInfo"]->UserInfoPersonContactID = $this->v["yourUserContact"]->PrsnID;
                $GLOBALS["SL"]->x["yourUserInfo"]->save();
            } else {
                $this->v["yourUserContact"] 
                    = OPPersonContact::find($GLOBALS["SL"]->x["yourUserInfo"]->UserInfoPersonContactID);
            }
            return [ $GLOBALS["SL"]->x["yourUserInfo"], $this->v["yourUserContact"] ];
        }
        return [ [], [] ];
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
    
    protected function runPageExtra($nID = -3)
    {
        if ($nID == 1362) { // Loading Complaint Report: Check for oversight permissions
            if (!isset($GLOBALS["SL"]->x["pageView"])) {
                $this->maxUserView(); // shouldn't be needed?
            }
            if ($this->chkOverUserHasCore()) {
                $GLOBALS["SL"]->x["dataPerms"] = 'sensitive';
            }
        }
        return true;
    }
    
}