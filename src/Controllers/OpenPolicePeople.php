<?php
/**
  * OpenPolicePeople is a mid-level class for functions handling
  * records describing people in the system.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.2.5
  */
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use App\Models\OPComplaints;
use App\Models\OPOfficers;
use App\Models\OPOfficersVerified;
use App\Models\OPPersonContact;
use App\Models\OPLinksOfficerDept;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksComplimentDept;
use App\Models\OPLinksCivilianEvents;
use FlexYourRights\OpenPolice\Controllers\OpenPoliceUtils;

class OpenPolicePeople extends OpenPoliceUtils
{
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

    protected function getPersonLabel($type = 'civilians', $id = -3, $row = [])
    {
        $name = '';
        $persID = 0;
        if (isset($row->{ substr($type, 0, 3) . '_person_id' })) {
            $persID = intVal($row->{ substr($type, 0, 3) . '_person_id' });
        } elseif (isset($row->civ_comp_person_id)) {
            $persID = intVal($row->civ_comp_person_id);
        }
        $civ2 = $this->sessData->dataFind('person_contact', $persID);
        //$civ2 = $this->sessData->getChildRow($type, $id, 'person_contact');
//echo 'type: ' . $type . ', id: ' . $id . '<pre>'; print_r($civ2); echo '</pre>';
        if ($civ2 && trim($civ2->prsn_nickname) != '') {
            $name = $civ2->prsn_nickname;
        } elseif ($civ2
            && (trim($civ2->prsn_name_first) != '' || trim($civ2->prsn_name_last) != '')) {
            $name = $civ2->prsn_name_first . ' ' . $civ2->prsn_name_last . ' ' . $name;
        } else {
            if ($type == 'officers'
                && isset($row->off_badge_number)
                && intVal($row->off_badge_number) > 0) {
                $name = 'Badge #' . $row->off_badge_number . ' ' . $name;
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
        if (!isset($civ->civ_id)) {
            if (isset($civ->inj_subject_id)) {
                $civ = $this->sessData->getRowById('civilians', $civ->inj_subject_id);
            } elseif (isset($civ->inj_care_subject_id)) {
                $civ = $this->sessData->getRowById('civilians', $civ->inj_care_subject_id);
            }
        }
        if ($civ->civ_is_creator == 'Y'
            && (($loop == 'Victims' && $civ->civ_role == 'Victim')
            || ($loop == 'Witnesses' && $civ->civ_role == 'Witness'))) {
            if ($this->isReport) {
                $name = $this->getCivFirstLastName($civ);
                if (trim($name) == '') {
                    $name = 'Complainant';
                }
            } else {
                $name = 'You ' . $name;
            }
        } else {
            $name = $this->getPersonLabel('civilians', $civ->civ_id, $civ);
        }
        $name = trim($name);
        if ($name != '' && $name != 'You') {
            $name .= ' (' . $civ->civ_role . ' #'
                . (1+$this->sessData->getLoopIndFromID($loop, $civ->civ_id)) . ')';
        }
        if ($name == '') {

        }
        return trim($name);
    }

    protected function getCivFirstLastName($civ = [])
    {
        if ($civ && isset($civ->civ_person_id) && intVal($civ->civ_person_id) > 0) {
            $contact = $this->sessData->getRowById('person_contact', $civ->civ_person_id);
            $name = '';
            if (isset($contact->prsn_name_first)
                && trim($contact->prsn_name_first) != '') {
                $name .= $contact->prsn_name_first . ' ';
            }
            if (isset($contact->prsn_name_last)
                && trim($contact->prsn_name_last) != '') {
                $name .= $contact->prsn_name_last;
            }
            if ($name == ''
                && isset($contact->prsn_nickname)
                && trim($contact->prsn_nickname) != '') {
                $name = $contact->prsn_nickname;
            }
            return trim($name);
        }
        return '';
    }

    protected function getComplaintFilenamePDF()
    {
        $ret = '';
        if (isset($this->sessData->dataSets["civilians"])) {
            $civ = $this->sessData->dataSets["civilians"][0];
            $name = str_replace(' ', '_', $this->getCivFirstLastName($civ));
            if ($name != '') {
                $ret .= $name . '-';
            }
            $ret .= 'Complaint_' . $this->corePublicID
                . '-' . $GLOBALS["SL"]->dataPerms . '.pdf';
        }
        return $ret;
    }

    public function getCivilianNameFromID($civID)
    {
        if ($this->sessData->dataSets["civilians"][0]->civ_id == $civID) {
            $role = '';
            if ($this->sessData->dataSets["civilians"][0]->civ_role == 'Victim') {
                $role = 'Victims';
            } elseif ($this->sessData->dataSets["civilians"][0]->civ_role == 'Witness') {
                $role = 'Witnesses';
            }
            return $this->getCivName($role, $this->sessData->dataSets["civilians"][0], 1);
        }
        $civInd = $this->sessData->getLoopIndFromID('Victims', $civID);
        if ($civInd >= 0) {
            $civRow = $this->sessData->getRowById('civilians', $civID);
            $name = $this->getCivName('Victims', $civRow, (1+$civInd));
            if ($name == '') {
                $name = 'Victim #' . (1+$civInd);
            }
            return $name;
        }
        $civInd = $this->sessData->getLoopIndFromID('Witnesses', $civID);
        if ($civInd >= 0) {
            $civRow = $this->sessData->getRowById('civilians', $civID);
            $name = $this->getCivName('Witnesses', $civRow, (1+$civInd));
            if ($name == '') {
                $name = 'Witness #' . (1+$civInd);
            }
            return $name;
        }
        return '';
    }

    protected function getFirstVictimCivInd()
    {
        $civInd = -3;
        if (sizeof($this->sessData->dataSets["civilians"]) > 0) {
            foreach ($this->sessData->dataSets["civilians"] as $i => $civ) {
                if (isset($civ->civ_role) && trim($civ->civ_role) == 'Victim' && $civInd < 0) {
                    $civInd = $i;
                }
            }
        }
        return $civInd;
    }

    protected function getCivNamesFromEvent($eveID)
    {
        $ret = '';
        $lnks = OPLinksCivilianEvents::where('lnk_civ_eve_eve_id', $eveID)
            ->get();
        if ($lnks->isNotEmpty()) {
            $civs = [];
            foreach ($lnks as $lnk) {
                if (!in_array($lnk->lnk_civ_eve_civ_id, $civs)) {
                    $civs[] = $lnk->lnk_civ_eve_civ_id;
                }
            }
            foreach ($civs as $i => $civID) {
                $ret .= (($i > 0) ? ', '
                    . ((sizeof($civs) > 2 && $i == (sizeof($civs)-1)) ? 'and ' : '') : '')
                    . $this->getPersonLabel('Civilians', $civID);
            }
        }
        return $ret;
    }

    protected function getLinkedToEvent($Ptype = 'officer', $eveSeqID = -3)
    {
        $retArr = [];
        $abbr = (($Ptype == 'officer') ? 'off' : 'civ');
        if ($eveSeqID > 0 && isset($this->sessData->dataSets["links_" . $Ptype . "_events"])
            && sizeof($this->sessData->dataSets["links_" . $Ptype . "_events"]) > 0) {
            foreach ($this->sessData->dataSets["links_" . $Ptype . "_events"] as $PELnk) {
                if ($PELnk->{ 'lnk_' . $abbr . '_eve_eve_id' } == $eveSeqID) {
                    $retArr[] = $PELnk->{ 'lnk_' . $abbr . '_eve_' . $abbr . '_id' };
                }
            }
        }
        return $retArr;
    }

    // converts Officer row into identifying name used in most of the complaint process
    protected function getOfficerName($officer = [], $itemIndex = -3)
    {
        if (isset($officer->off_comp_off_id) && intVal($officer->off_comp_off_id) > 0) {
            $officer = $this->sessData->dataFind('officers', $officer->off_comp_off_id);
        }
        $name = $this->getPersonLabel('officers', $officer->off_id, $officer);
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
            return $this->getOfficerName($this->sessData->getRowById('officers', $offID), (1+$offInd));
        }
        return '';
    }

    /**
     * Looks up a verified officer record plus more, given a its primary ID.
     *
     * @param  int $offVID
     * @return App\Models\OPOfficersVerified
     */
    protected function getOfficerVerifiedRecordByID($offVID = -3)
    {
        return DB::table('op_officers_verified')
            ->join('op_person_contact', 'op_officers_verified.off_ver_person_id',
                '=', 'op_person_contact.prsn_id')
            ->where('op_officers_verified.off_ver_id', $offVID)
            ->select(
                'op_officers_verified.*',
                'op_person_contact.prsn_name_prefix',
                'op_person_contact.prsn_name_first',
                'op_person_contact.prsn_nickname',
                'op_person_contact.prsn_name_middle',
                'op_person_contact.prsn_name_last',
                'op_person_contact.prsn_name_suffix'
            )
            ->first();
    }

    /**
     * Looks up a verified officer record given a
     * basic officer record originally tied to a complaint.
     *
     * @param  int $offID
     * @param  boolean $autoCreate
     * @return App\Models\OPOfficersVerified
     */
    protected function getOfficerVerifiedRecord($offID = -3, $autoCreate = false)
    {
        $off = DB::table('op_officers_verified')
            ->join('op_person_contact', 'op_officers_verified.off_ver_person_id',
                '=', 'op_person_contact.prsn_id')
            ->where('op_officers_verified.off_ver_id', function($query) use ($offID)
            {
                $query->select('off_verified_id')
                    ->from(with(new OPOfficers)->getTable())
                    ->whereNotNull('off_verified_id')
                    ->where('off_id', $offID)
                    ->first();
            })
            ->select(
                'op_officers_verified.*',
                'op_person_contact.prsn_name_prefix',
                'op_person_contact.prsn_name_first',
                'op_person_contact.prsn_nickname',
                'op_person_contact.prsn_name_middle',
                'op_person_contact.prsn_name_last',
                'op_person_contact.prsn_name_suffix'
            )
            ->first();
        if ($autoCreate && (!$off || !isset($off->off_ver_id))) {
            $this->createOfficerVerifiedRecord($offID);
        }
        return $off;
    }

    /**
     * Checks for the existence of a verified officer record given a
     * basic officer record ID originally tied to a complaint.
     *
     * @param  int $offID
     * @param  boolean $autoCreate
     * @return boolean
     */
    protected function chkOfficerVerifiedRecord($offID = -3, $autoCreate = false)
    {
        $off = OPOfficersVerified::where('off_ver_id', function($query) use ($offID)
            {
                $query->select('off_verified_id')
                    ->from(with(new OPOfficers)->getTable())
                    ->whereNotNull('off_verified_id')
                    ->where('off_id', $offID)
                    ->first();
            })
            ->first();
        $off = $this->getOfficerVerifiedRecord($offID);
        $found = ($off && isset($off->off_ver_id));
        if (!$found && $autoCreate) {
            $this->createOfficerVerifiedRecord($offID);
        }
        return $found;
    }

    /**
     * Create a verified officer record for a given basic
     * officer record ID originally tied to a complaint.
     *
     * @param  int $offID
     * @param  App/Models/OPOfficers $off
     * @return App\Models\OPOfficersVerified
     */
    protected function createOfficerVerifiedRecord($offID = -3, $off = null)
    {
        // Accept either starting point
        if ($offID <= 0 && $off && isset($off->off_id)) {
            $offID = $off->off_id;
        } elseif ($offID > 0 && (!$off || !isset($off->off_id))) {
            $off = OPOfficers::find($offID);
        }
        $prsn = OPPersonContact::find($off->OffPersonID);
        if (!$prsn || !isset($prsn->prsn_id)) {
            return null;
        }

        // Avoid creating duplicate records
        $offV = $this->getOfficerVerifiedRecord($offID);
        if ($offV && isset($offV->off_ver_id)) {
            return $offV;
        }

        // Create verified record
        $prsnV = new OPPersonContact;
        $prsnV->prsn_name_prefix = $prsn->prsn_name_prefix;
        $prsnV->prsn_name_first  = $prsn->prsn_name_first;
        $prsnV->prsn_name_middle = $prsn->prsn_name_middle;
        $prsnV->prsn_name_last   = $prsn->prsn_name_last;
        $prsnV->prsn_name_suffix = $prsn->prsn_name_suffix;
        $prsnV->save();

        $offV = new OPOfficersVerified;
        $offV->off_ver_person_id = $prsnV->prsn_id;
        $offV->off_ver_status = $GLOBALS["SL"]->def->getID(
            'Verified Officer Status',
            'Department Employment Not Verified'
        );
        $offV->save();
        $off->off_verified_id = $offV->off_ver_id;
        $off->save();

        $this->linkVerifOffWithDept($offV->off_ver_id, $off);
        return $this->getOfficerVerifiedRecordByID($offV->off_ver_id);
    }

    /**
     * Creates linkage between a verified officer record and a department,
     * if it does not already exist.
     *
     * @param int $offVID
     * @param App\Models\OPOfficers $off
     * @return App\Models\OPLinksOfficerDept
     */
    protected function linkVerifOffWithDept($offVID = -3, $off = null)
    {
        $deptID = 0;
        if (isset($off->off_dept_id) && intVal($off->off_dept_id) > 0) {
            $deptID = intVal($off->off_dept_id);
        }
        if ($deptID <= 0
            && isset($off->off_complaint_id)
            && intVal($off->off_complaint_id) > 0) {
            $depts = OPLinksComplaintDept::where('lnk_com_dept_complaint_id', $off->off_complaint_id)
                ->get();
            if ($depts->isNotEmpty()
                && sizeof($depts) == 1
                && isset($depts[0]->lnk_com_dept_dept_id)
                && intVal($depts[0]->lnk_com_dept_dept_id) > 0) {
                $deptID = $depts[0]->lnk_com_dept_dept_id;
                $off->off_dept_id = $deptID;
                $off->save();
            }
        }
        $lnk = OPLinksOfficerDept::where('lnk_off_dept_dept_id', $deptID)
            ->where('lnk_off_dept_officer_id', $offVID)
            ->first();
        if (!$lnk || !isset($lnk)) {
            $lnk = new OPLinksOfficerDept;
            $lnk->lnk_off_dept_dept_id = $deptID;
            $lnk->lnk_off_dept_officer_id = $offVID;
            $lnk->save();
        }
        return $lnk;
    }

    /**
     * Checks for the existence of a verified officer records for all officers
     * in published complaints.
     *
     * @return boolean
     */
    protected function chkAllOfficerVerifiedRecords()
    {
        $found = true;
        $chk = OPOfficers::whereIn('off_complaint_id', function($query)
            {
                $status = $this->getPublishedStatusList('complaints');
                $typeDef = $GLOBALS["SL"]->def->getID(
                    'Complaint Type',
                    'Police Complaint'
                );
                $query->select('com_id')
                    ->from(with(new OPComplaints)->getTable())
                    ->whereIn('com_status', $status)
                    ->where('com_type', $typeDef);
            })
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $off) {
                if (!isset($off->off_verified_id)
                    || intVal($off->off_verified_id) <= 0) {
                    $this->createOfficerVerifiedRecord($off->off_id, $off);
                    $found = false;
                }
            }
        }
        return $found;
    }

    protected function chkDeptLinks($newDeptID)
    {
        $deptChk = false;
        // First, check for exact matches to avoid duplicates
        if ($this->treeID == 5) {
            $deptChk = OPLinksComplimentDept::where('lnk_compli_dept_dept_id', $newDeptID)
                ->where('lnk_compli_dept_compliment_id', $this->coreID)
                ->get();
        } else {
            $deptChk = OPLinksComplaintDept::where('lnk_com_dept_dept_id', $newDeptID)
                ->where('lnk_com_dept_complaint_id', $this->coreID)
                ->get();
        }
        if ($deptChk->isEmpty()) {
            // Next, check for empty records started by loop
            if ($this->treeID == 5) {
                $deptChk = OPLinksComplimentDept::whereNull('lnk_compli_dept_dept_id')
                    ->where('lnk_compli_dept_compliment_id', $this->coreID)
                    ->orderBy('created_at', 'desc')
                    ->first();
            } else {
                $deptChk = OPLinksComplaintDept::whereNull('lnk_com_dept_dept_id')
                    ->where('lnk_com_dept_complaint_id', $this->coreID)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
            if ($deptChk && isset($deptChk->lnk_com_dept_id)) {
                if ($this->treeID == 5) {
                    $deptChk->lnk_compli_dept_dept_id = $newDeptID;
                } else {
                    $deptChk->lnk_com_dept_dept_id = $newDeptID;
                }
                $deptChk->save();
            } else {
                // Finally, manually create new linking record
                if ($this->treeID == 5) {
                    $newDeptLnk = new OPLinksComplimentDept;
                    $newDeptLnk->lnk_compli_dept_dept_id = $newDeptID;
                    $newDeptLnk->lnk_compli_dept_compliment_id = $this->coreID;
                    $newDeptLnk->save();
                } else {
                    $newDeptLnk = new OPLinksComplaintDept;
                    $newDeptLnk->lnk_com_dept_dept_id = $newDeptID;
                    $newDeptLnk->lnk_com_dept_complaint_id = $this->coreID;
                    $newDeptLnk->save();
                }
            }
        }
        $this->sessData->refreshDataSets();
        $this->runLoopConditions();
        return true;
    }

    protected function cleanDeptLnks()
    {
        if ($this->treeID == 5) {
            $deptChk = OPLinksComplimentDept::whereNull('lnk_compli_dept_dept_id')
                ->delete();
        } else {
            $deptChk = OPLinksComplaintDept::whereNull('lnk_com_dept_dept_id')
                ->delete();
        }
        return true;
    }

    protected function civRow2Set($civ)
    {
        if (!$civ || !isset($civ->civ_is_creator)) {
            return '';
        }
        return (($civ->civ_is_creator == 'Y') ? ''
            : (($civ->civ_role == 'Victim') ? 'Victims' : 'Witnesses') );
    }

    protected function getCivilianList($loop = 'Victims')
    {
        if ($loop == 'Victims' || $loop == 'Witness') {
            return $this->sessData->loopItemIDs[$loop];
        }
        $civs = [];
        if (isset($this->sessData->dataSets["civilians"])
            && sizeof($this->sessData->dataSets["civilians"]) > 0) {
            foreach ($this->sessData->dataSets["civilians"] as $civ) {
                $civs[] = $civ->civ_id;
            }
        }
        return $civs;
    }

    protected function loadPartnerTypes()
    {
        if (!isset($this->v["prtnTypes"])) {
            $this->v["prtnTypes"] = [
                [
                    "abbr"  => 'org',
                    "sing"  => 'Organization',
                    "plur"  => 'Organizations',
                    "defID" => $GLOBALS["SL"]->def->getID('Partner Types', 'Organization')
                ], [
                    "abbr"  => 'attorney',
                    "sing"  => 'Attorney',
                    "plur"  => 'Attorneys',
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

    protected function profilePhotoUploadInstruct()
    {
        return 'Please upload an <a href="/privacy-policy#n3075">'
            . 'appropriate photo of yourself</a>. This will '
            . 'be <nobr>visible to the public.</nobr>';
    }

}