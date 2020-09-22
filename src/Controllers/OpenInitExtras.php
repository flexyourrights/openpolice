<?php
/**
  * OpenInitExtras is a mid-level class which handles variable
  * and lookup initializations used throughout the engine.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.15
  */
namespace OpenPolice\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OPComplaints;
use App\Models\OPDepartments;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksComplimentDept;
use App\Models\OPPersonContact;
use App\Models\OPTesterBeta;
use App\Models\OPzVolunUserInfo;
use OpenPolice\Controllers\OpenPartners;

class OpenInitExtras extends OpenPartners
{
    /**
     * Initializing a bunch of things which are not [yet] automatically 
     * determined by the Survloop, nor the OpenPolice.org instance.
     *
     * @param  Illuminate\Http\Request  $request
     * @return boolean
     */
    protected function initExtra(Request $request)
    {
        // Establishing Main Navigation Organization, 
        // with Node ID# and Section Titles
        $this->loadYourContact();
        $this->v["reportUploadFolder"] = '../storage/app/up/reports/';
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
                $chk = OPDepartments::where('dept_slug', 'LIKE', trim($request->get('d')))
                    ->first();
                if ($chk && isset($chk->dept_id)) {
                    $this->loadAllSessData($GLOBALS["SL"]->coreTbl, $chk->dept_id);
                }
            }
        }
        $this->initComplaintToolbox();
        return true;
    }
    
    /**
     * Initializing extra things after loading a core record's data.
     *
     * @return boolean
     */
    protected function loadAllSessDataChecks()
    {
        if ($this->treeID == 201) {
            if (isset($this->sessData->dataSets["departments"])
                && (!isset($this->sessData->dataSets["oversight"])
                    || sizeof($this->sessData->dataSets["oversight"]) == 0)) {
                $dID = $this->sessData->dataSets["departments"][0]->dept_id;
                $subObjs = [];
                $tmp = $this->sessData->dataWhere('oversight', 'over_dept_id', $dID);
                if (sizeof($tmp) > 0) {
                    foreach ($tmp as $over) {
                        if (isset($over->over_agnc_name) 
                            && trim($over->over_agnc_name) != '') {
                            $subObjs[] = $over;
                        }
                    }
                }
                $this->sessData->processSubObjs('departments', $dID, 0, 'oversight', $subObjs);
            }
            return true;
        }
        if (isset($this->sessData->dataSets["complaints"])
            && sizeof($this->sessData->dataSets["complaints"]) == 1
            && isset($this->sessData->dataSets["incidents"])
            && sizeof($this->sessData->dataSets["incidents"]) == 1
            && isset($this->sessData->dataSets["person_contact"])
            && sizeof($this->sessData->dataSets["person_contact"]) > 0) {
            $this->loadAllSessDataChecksComplaint();
        } 
        return true;
    }
    
    /**
     * Initializing extra things after loading a core record's data.
     *
     * @return boolean
     */
    protected function loadAllSessDataChecksComplaint()
    {
        $inc = $this->sessData->dataSets["incidents"][0];
        if (isset($inc->inc_address_city)
            && trim($inc->inc_address_city) != '') {
            $fix = $GLOBALS["SL"]->fixAllUpOrLow($inc->inc_address_city);
            if ($inc->inc_address_city != $fix) {
                $this->sessData->dataSets["incidents"][0]->inc_address_city = $fix;
                $this->sessData->dataSets["incidents"][0]->save();
            }
        }
        foreach ($this->sessData->dataSets["person_contact"] as $i => $civ) {
            if (isset($civ->prsn_name_first) && trim($civ->prsn_name_first) != '') {
                $fix = $GLOBALS["SL"]->fixAllUpOrLow($civ->prsn_name_first);
                if ($civ->prsn_name_first != $fix) {
                    $this->sessData->dataSets["person_contact"][$i]->prsn_name_first = $fix;
                    $this->sessData->dataSets["person_contact"][$i]->save();
                }
            }
            if (isset($civ->prsn_name_middle) && trim($civ->prsn_name_middle) != '') {
                $fix = $GLOBALS["SL"]->fixAllUpOrLow($civ->prsn_name_middle);
                if ($civ->prsn_name_middle != $fix) {
                    $this->sessData->dataSets["person_contact"][$i]->prsn_name_middle = $fix;
                    $this->sessData->dataSets["person_contact"][$i]->save();
                }
            }
            if (isset($civ->prsn_name_last) && trim($civ->prsn_name_last) != '') {
                $fix = $GLOBALS["SL"]->fixAllUpOrLow($civ->prsn_name_last);
                if ($civ->prsn_name_last != $fix) {
                    $this->sessData->dataSets["person_contact"][$i]->prsn_name_last = $fix;
                    $this->sessData->dataSets["person_contact"][$i]->save();
                }
            }
        }
        $this->loadAllSessDataChecksComplaintPrivacy();
        return true;
    }
    
    /**
     * Convert old privacy settings. To be deleted after transition.
     *
     * @return boolean
     */
    protected function loadAllSessDataChecksComplaintPrivacy()
    {
        $com = $this->sessData->dataSets["complaints"][0];
        if (isset($com->com_privacy)
            && intVal($com->com_privacy) > 0
            && (!isset($com->com_publish_user_name)
                || !isset($com->com_publish_officer_name))) {
            $set = 'Privacy Types';
            $d = $GLOBALS['SL']->def->getID($set, 'Submit Publicly');
            if (intVal($com->com_privacy) == $d) {
                $com->com_publish_user_name
                    = $com->com_publish_officer_name
                    = 1;
                if (!isset($com->com_anon)) {
                    $com->com_anon = 0;
                }
            } else {
                $d = 'Names Visible to Police but not Public';
                $d = $GLOBALS['SL']->def->getID($set, $d);
                if (intVal($com->com_privacy) == $d) {
                    $com->com_publish_user_name
                        = $com->com_publish_officer_name
                        = 0;
                    if (!isset($com->com_anon)) {
                        $com->com_anon = 0;
                    }
                } else {
                    $d = $GLOBALS['SL']->def->getID($set, 'Completely Anonymous');
                    $d2 = $GLOBALS['SL']->def->getID($set, 'Anonymized');
                    if (in_array(intVal($com->com_privacy), [$d, $d2])) {
                        $com->com_publish_user_name
                            = $com->com_publish_officer_name
                            = 0;
                        if (!isset($com->com_anon)) {
                            $com->com_anon = 1;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * Initializing extra things for special admin pages.
     *
     * @param  Illuminate\Http\Request  $request
     * @return boolean
     */
    protected function constructorExtra()
    {
        $this->runOpenPoliceDataChecks();
        return true;
    }
    
    /**
     * Mapping the Survey Tree Nodes which wrap navigational sections.
     * for the complaint process.
     *
     * @param  Illuminate\Http\Request  $request
     * @return boolean
     */
    protected function navMenuComplaint()
    {
        $this->majorSections = [];
        $this->majorSections[] = [1,   'Your Story',      'active'];
        $this->majorSections[] = [4,   'Who\'s Involved', 'active'];
        $this->majorSections[] = [5,   'What Happened',   'active'];
        $this->majorSections[] = [6,   'Go Gold',         'disabled'];
        $this->majorSections[] = [419, 'Finish',          'active'];
        
        $this->minorSections = [ [], [], [], [], [] ];
        $this->minorSections[0][] = [157,  'Start Your Story'];
        $this->minorSections[0][] = [437,  'Legal Needs'];
        $this->minorSections[0][] = [158,  'About You'];
        $this->minorSections[0][] = [707,  'The Scene'];
        $this->minorSections[0][] = [2122, 'Evidence Uploads'];
        
        $this->minorSections[1][] = [140,  'Victims'];
        $this->minorSections[1][] = [141,  'Witnesses'];
        $this->minorSections[1][] = [144,  'Police Departments'];
        $this->minorSections[1][] = [142,  'Officers'];
        
        $this->minorSections[2][] = [2918, 'Allegations'];
        $this->minorSections[2][] = [2919, 'Other Allegations'];
        $this->minorSections[2][] = [2890, 'Your Feelings'];
        
        //$this->minorSections[3][] = [196, 'GO GOLD!']; // 483
        $this->minorSections[3][] = [149,  'Stops & Searches'];
        $this->minorSections[3][] = [153,  'Arrests & Citations'];
        $this->minorSections[3][] = [151,  'Uses of Force'];
        $this->minorSections[3][] = [410,  'Injuries & Medical'];
        
        $this->minorSections[4][] = [420,  'Review Narrative'];
        $this->minorSections[4][] = [431,  'Sharing Options'];
        $this->minorSections[4][] = [156,  'Confirm Complaint'];
        $this->minorSections[4][] = [2794, 'Complaint Submitted'];
        return true;
    }
    
    /**
     * Mapping the Survey Tree Nodes which wrap navigational sections
     * for the compliment process.
     *
     * @param  Illuminate\Http\Request  $request
     * @return boolean
     */
    protected function navMenuCompliment()
    {
        $this->majorSections[] = [752, 'Your Story',      'active'];
        $this->majorSections[] = [761, 'Who\'s Involved', 'active'];
        $this->majorSections[] = [763, 'Compliments',     'active'];
        $this->majorSections[] = [764, 'Finish',          'active'];
        
        $this->minorSections = [ [], [], [], [], [] ];
        $this->minorSections[0][] = [753, 'Start Your Story'];
        $this->minorSections[0][] = [867, 'Legal & Options'];
        $this->minorSections[0][] = [877, 'When & Where'];
        $this->minorSections[0][] = [887, 'The Scene'];
        
        $this->minorSections[1][] = [762, 'About You'];
        $this->minorSections[1][] = [765, 'Police Departments'];
        $this->minorSections[1][] = [766, 'Officers'];
        
        $this->minorSections[2][] = [945, 'Compliment Officers'];
        
        $this->minorSections[3][] = [957, 'Review Narrative'];
        $this->minorSections[3][] = [961, 'Feedback'];
        $this->minorSections[3][] = [964, 'Submit Complaint'];
        return true;
    }

    /**
     * Load anything else needed after default loading of a Tree Session.
     *
     * @return boolean
     */
    protected function loadExtra()
    {
        if ($this->treeID == 1 && $this->isGold()) {
            $this->majorSections[3][2] = 'active';
        }
        if ($this->treeID == 1 || $GLOBALS["SL"]->getReportTreeID() == 1) {
            if ($this->v["user"] 
                && intVal($this->v["user"]->id) > 0 
                && isset($this->sessData->dataSets["civilians"]) 
                && isset($this->sessData->dataSets["civilians"][0])
                && (!isset($this->sessData->dataSets["civilians"][0]->civ_user_id) 
                    || intVal($this->sessData->dataSets["civilians"][0]->civ_user_id) <= 0)) {
                $this->sessData->dataSets["civilians"][0]->update([
                    'civ_user_id' => $this->v["user"]->id
                ]);
            }
            $this->chkPersonRecs();
            if (isset($this->sessData->dataSets["departments"]) 
                && sizeof($this->sessData->dataSets["departments"]) > 0) {
                foreach ($this->sessData->dataSets["departments"] as $i => $d) {
                    $this->chkDeptLinks($d->dept_id);
                }
            }
            if (isset($this->sessData->dataSets["complaints"]) 
                && !isset($this->sessData->dataSets["complaints"][0]->com_record_submitted) 
                && $this->sessData->dataSets["complaints"][0]->com_status 
                    != $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) {
                $this->sessData->dataSets["complaints"][0]->com_record_submitted 
                    = $this->sessData->dataSets["complaints"][0]->created_at;
                $chk = DB::table('sl_node_saves_page')
                    ->join('sl_sess', 'sl_sess.sess_id', 
                        '=', 'sl_node_saves_page.page_save_session')
                    ->where('sl_sess.sess_tree', 1)
                    ->where('sl_sess.sess_core_id', $this->coreID)
                    ->select('sl_node_saves_page.created_at')
                    ->orderBy('sl_node_saves_page.created_at', 'desc')
                    ->first();
                if ($chk && isset($chk->created_at)) {
                    $this->sessData->dataSets["complaints"][0]
                        ->com_record_submitted = $chk->created_at;
                }
                $this->sessData->dataSets["complaints"][0]->save();
            }
        }
        if ($this->treeID == 5 || $GLOBALS["SL"]->getReportTreeID() == 5) {
            if (isset($this->sessData->dataSets["complaints"])
                && (!isset($this->sessData->dataSets["complaints"][0]->com_is_compliment)
                    || intVal($this->sessData->dataSets["complaints"][0]
                        ->com_is_compliment) != 1)) {
                $this->sessData->dataSets["complaints"][0]->com_is_compliment = 1;
                $this->sessData->dataSets["complaints"][0]->save();
            }
        }
        if (session()->has('opcDeptID') 
            && intVal(session()->get('opcDeptID')) > 0) {
            if ($this->treeID == 1) {
                if (isset($this->sessData->dataSets["complaints"])
                    && intVal($this->sessData->dataSets["complaints"][0]
                        ->com_submission_progress) > 0) {
                    if (!isset($this->sessData->dataSets["links_complaint_dept"])) {
                        $this->sessData->dataSets["links_complaint_dept"] = [];
                    }
                    if (empty($this->sessData->dataSets["links_complaint_dept"])) {
                        $newDept = new OPLinksComplaintDept;
                        $newDept->lnk_com_dept_complaint_id = $this->coreID;
                        $newDept->lnk_com_dept_dept_id = intVal(session()->get('opcDeptID'));
                        $newDept->save();
                        session()->forget('opcDeptID');
                    }
                }
            } elseif ($this->treeID == 5) {
                if (isset($this->sessData->dataSets["compliments"])
                    && intVal($this->sessData->dataSets["compliments"][0]
                        ->com_submission_progress) > 0) {
                    if (!isset($this->sessData->dataSets["links_compliment_dept"])) {
                        $this->sessData->dataSets["links_compliment_dept"] = [];
                    }
                    if (empty($this->sessData->dataSets["links_compliment_dept"])) {
                        $newDept = new OPLinksComplimentDept;
                        $newDept->lnk_compli_dept_compliment_id = $this->coreID;
                        $newDept->lnk_compli_dept_dept_id = intVal(session()->get('opcDeptID'));
                        $newDept->save();
                        session()->forget('opcDeptID');
                    }
                }
            }
        }
        if ($this->treeID == 1 
            && session()->has('opcPartID') 
            && intVal(session()->get('opcPartID')) > 0
            && isset($this->sessData->dataSets["complaints"]) 
            && intVal($this->sessData->dataSets["complaints"][0]
                ->com_submission_progress) > 0) {
            $this->sessData->dataSets["complaints"][0]->com_att_id 
                = intVal(session()->get('opcPartID'));
            $this->sessData->dataSets["complaints"][0]->save();
        }
        $this->v["isPublic"] = $this->isPublic();
        
        // used to be admin initializations:
        $this->v["allowEdits"] = ($this->v["uID"] > 0
            && $this->v["user"] 
            && $this->isStaffOrAdmin());
        $this->v["management"] = ($this->v["uID"] > 0 
            && $this->v["user"] 
            && $this->isStaffOrAdmin());
        $this->v["volunOpts"] = 1;
        if ($GLOBALS["SL"]->REQ->session()->has('volunOpts')) {
            $this->v["volunOpts"] = $GLOBALS["SL"]->REQ->session()->get('volunOpts');
        }
        
        // Department Research Survey
        if ($this->treeID == 36) {
            if (isset($this->sessData->dataSets['oversight']) 
                && sizeof($this->sessData->dataSets['oversight']) == 1) {
                $new = $this->sessData->newDataRecord(
                    'oversight', 
                    'over_dept_id', 
                    $this->sessData->dataSets['departments'][0]->dept_id, 
                    true
                );
                $new->over_type = 302;
                $new->save();
                $this->sessData->refreshDataSets();
            }
        }
        return true;
    }
    
    /**
     * Run any validation and cleanup needed specific to OP.org
     *
     * @return boolean
     */
    protected function runOpenPoliceDataChecks()
    {
        if (!isset($this->v["uID"]) 
            || $this->v["uID"] <= 0 
            || !isset($this->v["user"])
            || !$this->isStaffOrAdmin()) {
            return false;
        }
        if (!session()->has('opcChks') 
            || !session()->get('opcChks') 
            || $GLOBALS["SL"]->REQ->has('clean')) {
            $incDef = $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete');
            $chk = OPComplaints::whereNull('com_public_id')
                ->where('com_status', 'NOT LIKE', $incDef)
                ->get();
            if ($chk->isNotEmpty()) {
                foreach ($chk as $i => $complaint) {
                    $newPubID = $GLOBALS["SL"]->genNewCorePubID('complaints');
                    $complaint->update([ 'com_public_id' => $newPubID ]);
                }
            }
            $this->clearEmptyComplaints();
            $this->clearEmptyBetas();
            $this->clearLostSessionHelpers();
            session()->put('opcChks', true);
        }

        return true;
    }
    
    /**
     * Clean out old complaints.
     *
     * @return boolean
     */
    protected function clearEmptyComplaints()
    {
        $cutoff = mktime(date("H"), date("i"), date("s"), 
            date("n"), date("j")-14, date("Y"));
        $cutoff = date("Y-m-d H:i:s", $cutoff);
        $incDef = $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete');
        DB::select(DB::raw(
            "DELETE FROM `op_complaints` 
            WHERE `com_public_id` IS NULL
                AND `com_status` LIKE '" . $incDef . "'
                AND `created_at` < '" . $cutoff . "'
                AND (`com_user_id` IS NULL OR `com_user_id` <= 0)
                AND (`com_summary` IS NULL OR `com_summary` LIKE '')
            LIMIT 1000"
        ));
        DB::select(DB::raw(
            "DELETE FROM `sl_sess` 
            WHERE `sl_sess`.`sess_tree` = 1
                AND `sl_sess`.`sess_core_id` NOT IN 
                    (SELECT `op_complaints`.`com_id` FROM `op_complaints`)"
        ));
        DB::select(DB::raw(
            "DELETE FROM `op_event_sequence` 
            WHERE `op_event_sequence`.`eve_complaint_id` NOT IN 
                (SELECT `op_complaints`.`com_id` FROM `op_complaints`)"
        ));
        return true;
    }
    
    /**
     * Clean out old beta test signups.
     *
     * @return boolean
     */
    protected function clearEmptyBetas()
    {
        $cutoff = mktime(date("H"), date("i"), date("s"), 
            date("n"), date("j")-14, date("Y"));
        $cutoff = date("Y-m-d H:i:s", $cutoff);
        $chk = OPTesterBeta::whereNull('beta_email')
            ->whereNull('beta_narrative')
            ->where('created_at', '<', $cutoff)
            ->limit(2000)
            ->delete();
        DB::select(DB::raw(
            "DELETE FROM `sl_sess` 
            WHERE `sl_sess`.`sess_tree` = 79
                AND `sl_sess`.`sess_core_id` NOT IN 
                    (SELECT `op_tester_beta`.`beta_id` FROM `op_tester_beta`)"
        ));
        return true;
    }
    
    /**
     * Override current page as represented in the admin menu.
     *
     * @return string
     */
    public function initAdmMenuExtras()
    {
        if (in_array($this->treeID, [99, 46])) {
            // admin area view of complaint reports
            return '/dash/all-complete-complaints';
        }
        return '';
    }
    
    /**
     * Load additional data related to users who are logged in.
     *
     * @param   int  $uID
     * @return  array
     */
    public function initPowerUser($uID = -3)
    {
        if ($uID <= 0) {
            $uID = $this->v["uID"];
        }
        if ($uID > 0) {
            $GLOBALS["SL"]->x["yourUserInfo"] 
                = OPzVolunUserInfo::where('user_info_user_id', $uID)->first();
            if (!$GLOBALS["SL"]->x["yourUserInfo"]) {
                $GLOBALS["SL"]->x["yourUserInfo"] = new OPzVolunUserInfo;
                $GLOBALS["SL"]->x["yourUserInfo"]->user_info_user_id = $uID;
                $GLOBALS["SL"]->x["yourUserInfo"]->save();
            }
            $this->v["yourUserContact"] = [];
            if (!isset($GLOBALS["SL"]->x["yourUserInfo"]->user_info_person_contact_id) 
                || intVal($GLOBALS["SL"]->x["yourUserInfo"]->user_info_person_contact_id) <= 0) {
                $thisUser = User::select('email')->find($uID);
                $this->v["yourUserContact"] = new OPPersonContact;
                $this->v["yourUserContact"]->prsn_user_id = $uID;
                $this->v["yourUserContact"]->prsn_email = $thisUser->email;
                $this->v["yourUserContact"]->save();
                $GLOBALS["SL"]->x["yourUserInfo"]->user_info_person_contact_id 
                    = $this->v["yourUserContact"]->prsn_id;
                $GLOBALS["SL"]->x["yourUserInfo"]->save();
            } else {
                $this->v["yourUserContact"] = OPPersonContact::find(
                    $GLOBALS["SL"]->x["yourUserInfo"]->user_info_person_contact_id
                );
            }
            return [ $GLOBALS["SL"]->x["yourUserInfo"], $this->v["yourUserContact"] ];
        }
        return [ [], [] ];
    }
    
    /**
     * Override the default behavior for wrapping a tree which has
     * been called through an ajax call.
     *
     * @return string
     */
    protected function ajaxContentWrapCustom($str, $nID = -3)
    {
        if ($this->treeID == 1) {
            if ($GLOBALS["SL"]->REQ->has('treeSlug') 
                && trim($GLOBALS["SL"]->REQ->get('treeSlug')) == 'complaint'
                && $GLOBALS["SL"]->REQ->has('nodeSlug') 
                && trim($GLOBALS["SL"]->REQ->get('nodeSlug')) == 'login') {
                return $this->redir('/u/complaint/when-and-where', true);
            }
        }
        return $str;
    }
    
    /**
     * Override the default data permissions for this page load.
     *
     * @return boolean
     */
    protected function tweakPageViewPerms($initPageView = '')
    {
        if (!isset($this->sessData->dataSets["complaints"])) {
            return false;
        }
        if ($this->v["uID"] <= 0 
            || !$this->v["user"] 
            || !$this->isStaffOrAdmin()) {
            $com = $this->sessData->dataSets["complaints"][0];
            $isPublished = $this->isPublished('complaints', $this->coreID, $com);
            if ($isPublished) {
                if ($this->chkOverUserHasCore()) { // Investigative Access
                    $GLOBALS["SL"]->dataPerms = 'sensitive';
                } elseif ($this->isPublic() 
                    && in_array($GLOBALS["SL"]->dataPerms, ['', 'public'])) {
                    $GLOBALS["SL"]->dataPerms = 'private';
                }
            } else {
                $GLOBALS["SL"]->dataPerms = 'none';
            }
        }
        return true;
    }
    
    /**
     * Run anything else extra which needs to be run for this page load.
     *
     * @return boolean
     */
    protected function runPageExtra($nID = -3)
    {
//if ($GLOBALS["SL"]->REQ->has('ajax')) { echo 'runPageExtra(' . $nID . ', perms: ' . $GLOBALS["SL"]->dataPerms . '<br />'; exit; }
        if ($nID == 1362) { 
            // Loading Complaint Report: Check for oversight permissions
            if (!isset($GLOBALS["SL"]->pageView)) {
                $this->maxUserView(); // shouldn't be needed?
            }
            if ($this->chkOverUserHasCore()) {
                $GLOBALS["SL"]->dataPerms = 'sensitive';
            }
        }
        return true;
    }

    /**
     * Run anything else extra needed to clear data in between sessions.
     *
     * @return boolean
     */
    protected function loadSessionClear($coreTbl = '', $coreID = -3)
    {
        $this->allegations = [];
        return true;
    }    

}