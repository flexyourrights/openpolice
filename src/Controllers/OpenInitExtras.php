<?php
/**
  * OpenInitExtras is a mid-level class which handles variable
  * and lookup initializations used throughout the engine.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.0.15
  */
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SLSess;
use App\Models\OPComplaints;
use App\Models\OPDepartments;
use App\Models\OPEventSequence;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksComplimentDept;
use App\Models\OPPersonContact;
use App\Models\OPPhysicalDesc;
use App\Models\OPTesterBeta;
use App\Models\OPzVolunUserInfo;
use FlexYourRights\OpenPolice\Controllers\OpenDashAdmin;
use FlexYourRights\OpenPolice\Controllers\OpenPartners;

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
        $this->loadSearchCoreTbls();
        return true;
    }

    /**
     * Initializing core table options for search bar.
     *
     * @return boolean
     */
    protected function loadSearchCoreTbls()
    {
        $GLOBALS["SL"]->x["searchCoreTbls"] = [
            [
                "id"   => 112,
                "name" => 'Complaints',
                "slug" => 'complaint'
            ], [
                "id"   => 111,
                "name" => 'Police Departments',
                "slug" => 'dept'

            ]
        ];
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
        $this->loadAllSessDataCheckFullTransparency();
        return true;
    }

    /**
     * Initializing the full transparency flag on the core record.
     *
     * @return boolean
     */
    protected function loadAllSessDataCheckFullTransparency()
    {
        if (isset($this->sessData->dataSets["complaints"])) {
            $com = $this->sessData->dataSets["complaints"][0];
            $this->sessData->dataSets["complaints"][0]->com_privacy = 0;
            $pubUser = (isset($com->com_publish_user_name)
                && intVal($com->com_publish_user_name) == 1);
            $pubOffs = (isset($com->com_publish_officer_name)
                && intVal($com->com_publish_officer_name) == 1);
            $noOffs = (!isset($this->sessData->dataSets["officers"])
                || sizeof($this->sessData->dataSets["officers"]) == 0);
            if ($pubUser && ($pubOffs || $noOffs)) {
                $this->sessData->dataSets["complaints"][0]->com_privacy = 304;
            }
            $this->sessData->dataSets["complaints"][0]->save();
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

        $notSureInd = -1;
        $this->v["notSureDeptID"] = 18124;
        $lnkTbl = 'links_complaint_dept';
        if (isset($this->sessData->dataSets[$lnkTbl])) {
            $deptLinkCnt = sizeof($this->sessData->dataSets[$lnkTbl]);
            if ($deptLinkCnt > 0) {
                $deptIDs = $delInds = [];
                foreach ($this->sessData->dataSets[$lnkTbl] as $i => $lnk) {
                    if (isset($lnk->lnk_com_dept_dept_id)
                        && !in_array($lnk->lnk_com_dept_dept_id, $deptIDs)) {
                        $deptIDs[] = $lnk->lnk_com_dept_dept_id;
                    } else {
                        $delInds[] = $i;
                    }
                }
                if (sizeof($delInds) > 0) {
                    for ($i = sizeof($delInds)-1; $i >= 0; $i--) {
                        $this->sessData->dataSets[$lnkTbl][$delInds[$i]]->delete();
                    }
                }
                foreach ($this->sessData->dataSets[$lnkTbl] as $i => $lnk) {
                    if (isset($lnk->lnk_com_dept_dept_id)
                        && intVal($lnk->lnk_com_dept_dept_id) == $this->v["notSureDeptID"]) {
                        $notSureInd = $i;
                    }
                }
                if ($notSureInd >= 0
                    && $notSureInd < ($deptLinkCnt-1)) {
                    $this->sessData->dataSets[$lnkTbl][$notSureInd]->lnk_com_dept_dept_id
                        = $this->sessData->dataSets[$lnkTbl][($deptLinkCnt-1)]->lnk_com_dept_dept_id;
                    $this->sessData->dataSets[$lnkTbl][$notSureInd]->save();
                    $this->sessData->dataSets[$lnkTbl][($deptLinkCnt-1)]->lnk_com_dept_dept_id
                        = $this->v["notSureDeptID"];
                    $this->sessData->dataSets[$lnkTbl][($deptLinkCnt-1)]->save();
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
    public function constructorExtra()
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
        if (session()->has('volunOpts')) {
            $this->v["volunOpts"] = session()->get('volunOpts');
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
     * Look up the person contact record and physical description record
     * for a civilian or officer.
     *
     * @return boolean
     */
    protected function chkPersonRecs()
    {
        // This should've been automated via the data table subset option
        // but for now, I'm replacing that complication with this check...
        $found = false;
        $types = [
            ['civilians', 'civ'],
            ['officers',  'off']
        ];
        foreach ($types as $type) {
            if (isset($this->sessData->dataSets[$type[0]])
                && sizeof($this->sessData->dataSets[$type[0]]) > 0) {
                foreach ($this->sessData->dataSets[$type[0]] as $i => $civ) {
                    if (!isset($civ->{ $type[1] . '_person_id' })
                        || intVal($civ->{ $type[1] . '_person_id' }) <= 0) {
                        $new = new OPPersonContact;
                        $new->save();
                        $this->sessData->dataSets[$type[0]][$i]->update([
                            $type[1] . '_person_id' => $new->prsn_id
                        ]);
                        $found = true;
                    }
                    if (!isset($civ->{ $type[1] . '_phys_desc_id' })
                        || intVal($civ->{ $type[1] . '_phys_desc_id' }) <= 0) {
                        $new = new OPPhysicalDesc;
                        $new->save();
                        $this->sessData->dataSets[$type[0]][$i]->update([
                            $type[1] . '_phys_desc_id' => $new->phys_id
                        ]);
                        $found = true;
                    }
                }
            }
        }
        if ($found) {
            $this->sessData->refreshDataSets();
        }
        // // // //
        return true;
    }

    /**
     * Look up the record linking fields which should be skipped
     * when auto-creating a new loop item's database record.
     *
     * @return array
     */
    protected function newLoopItemSkipLinks($tbl = '')
    {
        // Until this can be auto-inferred for
        // outgoing linkages to data subsets
        if ($tbl == 'civilians') {
            return [ 'civ_person_id', 'civ_phys_desc_id' ];
        } elseif ($tbl == 'officers') {
            return [ 'off_person_id', 'off_phys_desc_id' ];
        }
        return [];
    }

    /**
     * Double-check behavior after a new item has been created for a data loop.
     *
     * @param  string $tbl
     * @param  int $itemID
     * @return boolean
     */
    protected function afterCreateNewDataLoopItem($tbl = '', $itemID = -3)
    {
        if (in_array($tbl, ['civilians', 'officers']) && $itemID > 0) {
            $this->chkPersonRecs();
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
        return true;
    }

    /**
     * Hook into Survloop's Admin Cleaning Scripts
     * /dashboard/systems-clean
     *
     * @return int
     */
    protected function customSysClean($step = 0)
    {
        if (!$this->isStaffOrAdmin()) {
            return 1;
        }
        $cutoff = mktime(date("H"), date("i"), date("s"),
            date("n"), date("j")-14, date("Y"));
        $cutoff = date("Y-m-d H:i:s", $cutoff);
        if ($step == 4) {
            $this->clearEmptyComplaints($cutoff);
        } elseif ($step == 5) {
            $this->clearEmptyBetas($cutoff);
        }
        $step++;
        if ($step == 6) {
            $step = 1;
        }
        return $step;
    }

    /**
     * Clean out old complaints.
     *
     * @return boolean
     */
    protected function clearEmptyComplaints($cutoff)
    {
        $incDef = $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete');
        OPComplaints::whereNull('com_public_id')
            ->where('com_status', 'LIKE', $incDef)
            ->where('created_at', '<', $cutoff)
            ->where(function($query) {
                $query->whereNull('com_user_id')
                      ->orWhere('com_user_id', '<', 1);
            })
            ->where(function($query) {
                $query->whereNull('com_summary')
                      ->orWhere('com_summary', 'LIKE', '');
            })
            ->limit(1000)
            ->delete();
        $chk = OPComplaints::select('com_id')
            ->get();
        $comIDs = $GLOBALS["SL"]->resToArrIds($chk, 'com_id');
        SLSess::where('sess_tree', 1)
            ->whereNotIn('sess_core_id', $comIDs)
            ->limit(1000)
            ->delete();
        OPEventSequence::whereNotIn('eve_complaint_id', $comIDs)
            ->limit(1000)
            ->delete();
        return true;
    }

    /**
     * Clean out old beta test signups.
     *
     * @return boolean
     */
    protected function clearEmptyBetas($cutoff)
    {
        $chk = OPTesterBeta::whereNull('beta_email')
            ->whereNull('beta_narrative')
            ->where('created_at', '<', $cutoff)
            ->limit(2000)
            ->delete();
        $chk = OPTesterBeta::select('beta_id')
            ->get();
        $betaIDs = $GLOBALS["SL"]->resToArrIds($chk, 'beta_id');
        SLSess::where('sess_tree', 79)
            ->whereNotIn('sess_core_id', $betaIDs)
            ->limit(1000)
            ->delete();
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
            // if (!isset($GLOBALS["SL"]->pageView)) {
            //     $this->maxUserView(); // shouldn't be needed?
            // }
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

    /**
     * Print warning message for uploading tool.
     *
     * @param  int $nID
     * @return string
     */
    protected function uploadWarning($nID)
    {
        return 'WARNING: If documents show sensitive personal information, set this to "private."
            This includes addresses, phone numbers, emails, or social security numbers.';
    }

    /**
     * Initializes the admin dashboard side-class.
     *
     * @return boolean
     */
    protected function initAdmDash()
    {
        $this->v["isDash"] = true;
        if (!isset($this->v["openDash"])) {
            $this->v["openDash"] = new OpenDashAdmin;
        }
        return true;
    }

}