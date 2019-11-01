<?php
/**
  * OpenPolicePeople is a mid-level class for functions handling
  * records describing people in the system.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.2.5
  */
namespace OpenPolice\Controllers;

use DB;
use App\Models\OPComplaints;
use App\Models\OPOfficers;
use App\Models\OPOfficersVerified;
use App\Models\OPPersonContact;
use App\Models\OPLinksOfficerDept;
use App\Models\OPLinksComplaintDept;
use App\Models\OPLinksComplimentDept;
use App\Models\OPLinksCivilianEvents;
use OpenPolice\Controllers\OpenPoliceUtils;

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

    protected function getPersonLabel($type = 'Civilians', $id = -3, $row = [])
    {
        $name = '';
        $persID = 0;
        if (isset($row->{ substr($type, 0, 3) . 'PersonID' })) {
            $persID = intVal($row->{ substr($type, 0, 3) . 'PersonID' });
        } elseif (isset($row->CivCompPersonID)) {
            $persID = intVal($row->CivCompPersonID);
        }
        $civ2 = $this->sessData->dataFind('PersonContact', $persID);
        //$civ2 = $this->sessData->getChildRow($type, $id, 'PersonContact');
//echo 'type: ' . $type . ', id: ' . $id . '<pre>'; print_r($civ2); echo '</pre>';
        if ($civ2 && trim($civ2->PrsnNickname) != '') {
            $name = $civ2->PrsnNickname;
        } elseif ($civ2 && (trim($civ2->PrsnNameFirst) != '' 
            || trim($civ2->PrsnNameLast) != '')) {
            $name = $civ2->PrsnNameFirst . ' ' . $civ2->PrsnNameLast 
                . ' ' . $name;
        } else {
            if ($type == 'Officers' && isset($row->OffBadgeNumber) 
                && intVal($row->OffBadgeNumber) > 0) {
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
                $civ = $this->sessData
                    ->getRowById('Civilians', $civ->InjSubjectID);
            } elseif (isset($civ->InjCareSubjectID)) {
                $civ = $this->sessData
                    ->getRowById('Civilians', $civ->InjCareSubjectID);
            }
        }
        if ($civ->CivIsCreator == 'Y' 
            && (($loop == 'Victims' && $civ->CivRole == 'Victim') 
            || ($loop == 'Witnesses' && $civ->CivRole == 'Witness'))) {
            if ($this->isReport) {
                if (isset($civ->CivPersonID) && intVal($civ->CivPersonID) > 0) {
                    $contact = $this->sessData->getChildRow(
                        'Civilians', 
                        $civ->CivPersonID, 
                        'PersonContact'
                    );
                    $name = $contact->PrsnNameFirst . ' ' 
                        . $contact->PrsnNameLast;
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
            $name .= ' (' . $civ->CivRole . ' #' 
                . (1+$this->sessData->getLoopIndFromID($loop, $civ->CivID)) 
                . ')';
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
            $name = $this->getCivName('Victims', 
                $this->sessData->getRowById('Civilians', $civID), (1+$civInd));
            if ($name == '') {
                $name = 'Victim #' . (1+$civInd);
            }
            return $name;
        }
        $civInd = $this->sessData->getLoopIndFromID('Witnesses', $civID);
        if ($civInd >= 0) {
            $name = $this->getCivName('Witnesses', 
                $this->sessData->getRowById('Civilians', $civID), (1+$civInd));
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
        if (sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if (isset($civ->CivRole) && trim($civ->CivRole) == 'Victim' 
                    && $civInd < 0) {
                    $civInd = $i;
                }
            }
        }
        return $civInd;
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
                $ret .= (($i > 0) ? ', ' 
                    . ((sizeof($civs) > 2 && $i == (sizeof($civs)-1)) ? 'and ' : '') : '')
                    . $this->getPersonLabel('Civilians', $civID);
            }
        }
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
    
    // converts Officer row into identifying name used in most of the complaint process
    protected function getOfficerName($officer = [], $itemIndex = -3)
    {
        if (isset($officer->OffCompOffID) && intVal($officer->OffCompOffID) > 0) {
            $officer = $this->sessData->dataFind('Officers', $officer->OffCompOffID);
        }
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
            return $this->getOfficerName(
                $this->sessData->getRowById('Officers', $offID), 
                (1+$offInd)
            );
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
        return DB::table('OP_OfficersVerified')
            ->join('OP_PersonContact', 'OP_OfficersVerified.OffVerPersonID', 
                '=', 'OP_PersonContact.PrsnID')
            ->where('OP_OfficersVerified.OffVerID', $offVID)
            ->select(
                'OP_OfficersVerified.*', 
                'OP_PersonContact.PrsnNamePrefix', 
                'OP_PersonContact.PrsnNameFirst', 
                'OP_PersonContact.PrsnNickname', 
                'OP_PersonContact.PrsnNameMiddle', 
                'OP_PersonContact.PrsnNameLast',  
                'OP_PersonContact.PrsnNameSuffix'
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
        $off = DB::table('OP_OfficersVerified')
            ->join('OP_PersonContact', 'OP_OfficersVerified.OffVerPersonID', 
                '=', 'OP_PersonContact.PrsnID')
            ->where('OP_OfficersVerified.OffVerID', function($query) use ($offID)
            {
                $query->select('OffVerifiedID')
                    ->from(with(new OPOfficers)->getTable())
                    ->whereNotNull('OffVerifiedID')
                    ->where('OffID', $offID)
                    ->first();
            })
            ->select(
                'OP_OfficersVerified.*', 
                'OP_PersonContact.PrsnNamePrefix', 
                'OP_PersonContact.PrsnNameFirst', 
                'OP_PersonContact.PrsnNickname', 
                'OP_PersonContact.PrsnNameMiddle', 
                'OP_PersonContact.PrsnNameLast',  
                'OP_PersonContact.PrsnNameSuffix'
            )
            ->first();
        if ($autoCreate && (!$off || !isset($off->OffVerID))) {
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
        $off = OPOfficersVerified::where('OffVerID', function($query) use ($offID)
            {
                $query->select('OffVerifiedID')
                    ->from(with(new OPOfficers)->getTable())
                    ->whereNotNull('OffVerifiedID')
                    ->where('OffID', $offID)
                    ->first();
            })
            ->first();
        $off = $this->getOfficerVerifiedRecord($offID);
        $found = ($off && isset($off->OffVerID));
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
        if ($offID <= 0 && $off && isset($off->OffID)) {
            $offID = $off->OffID;
        } elseif ($offID > 0 && (!$off || !isset($off->OffID))) {
            $off = OPOfficers::find($offID);
        }
        $prsn = OPPersonContact::find($off->OffPersonID);
        if (!$prsn || !isset($prsn->PrsnID)) {
            return null;
        }

        // Avoid creating duplicate records
        $offV = $this->getOfficerVerifiedRecord($offID);
        if ($offV && isset($offV->OffVerID)) {
            return $offV;
        }

        // Create verified record
        $prsnV = new OPPersonContact;
        $prsnV->PrsnNamePrefix = $prsn->PrsnNamePrefix;
        $prsnV->PrsnNameFirst  = $prsn->PrsnNameFirst;
        $prsnV->PrsnNameMiddle = $prsn->PrsnNameMiddle;
        $prsnV->PrsnNameLast   = $prsn->PrsnNameLast;
        $prsnV->PrsnNameSuffix = $prsn->PrsnNameSuffix;
        $prsnV->save();

        $offV = new OPOfficersVerified;
        $offV->OffVerPersonID = $prsnV->PrsnID;
        $offV->OffVerStatus = $GLOBALS["SL"]->def->getID(
            'Verified Officer Status', 
            'Department Employment Not Verified'
        );
        $offV->save();
        $off->OffVerifiedID = $offV->OffVerID;
        $off->save();

        $this->linkVerifOffWithDept($offV->OffVerID, $off);
        return $this->getOfficerVerifiedRecordByID($offV->OffVerID);
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
        if (isset($off->OffDeptID) && intVal($off->OffDeptID) > 0) {
            $deptID = intVal($off->OffDeptID);
        }
        if ($deptID <= 0 && isset($off->OffComplaintID) 
            && intVal($off->OffComplaintID) > 0) {
            $depts = OPLinksComplaintDept::where('LnkComDeptComplaintID', $off->OffComplaintID)
                ->get();
            if ($depts->isNotEmpty() && sizeof($depts) == 1
                && isset($depts[0]->LnkComDeptDeptID) 
                && intVal($depts[0]->LnkComDeptDeptID) > 0) {
                $deptID = $depts[0]->LnkComDeptDeptID;
                $off->OffDeptID = $deptID;
                $off->save();
            }
        }
        $lnk = OPLinksOfficerDept::where('LnkOffDeptDeptID', $deptID)
            ->where('LnkOffDeptOfficerID', $offVID)
            ->first();
        if (!$lnk || !isset($lnk)) {
            $lnk = new OPLinksOfficerDept;
            $lnk->LnkOffDeptDeptID = $deptID;
            $lnk->LnkOffDeptOfficerID = $offVID;
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
        $chk = OPOfficers::whereIn('OffComplaintID', function($query)
            {
                $status = $this->getPublishedStatusList('Complaints');
                $query->select('ComID')
                    ->from(with(new OPComplaints)->getTable())
                    ->whereIn('ComStatus', $status);
            })
            ->get();
        if ($chk->isNotEmpty()) {
            foreach ($chk as $off) {
                if (!isset($off->OffVerifiedID) 
                    || intVal($off->OffVerifiedID) <= 0) {
                    $this->createOfficerVerifiedRecord($off->OffID, $off);
                    $found = false;
                }
            }
        }
        return $found;
    }
    
    protected function chkDeptLinks($newDeptID)
    {
        $deptChk = false;
        if ($this->treeID == 5) {
            $deptChk = OPLinksComplimentDept::where('LnkCompliDeptDeptID', $newDeptID)
                ->where('LnkCompliDeptComplimentID', $this->coreID)
                ->get();
        } else {
            $deptChk = OPLinksComplaintDept::where('LnkComDeptDeptID', $newDeptID)
                ->where('LnkComDeptComplaintID', $this->coreID)
                ->get();
        }
        if ($deptChk->isEmpty()) {
            if ($this->treeID == 5) {
                $newDeptLnk = new OPLinksComplimentDept;
                $newDeptLnk->LnkCompliDeptDeptID = $newDeptID;
                $newDeptLnk->LnkCompliDeptComplimentID = $this->coreID;
                $newDeptLnk->save();
            } else {
                $newDeptLnk = new OPLinksComplaintDept;
                $newDeptLnk->LnkComDeptDeptID = $newDeptID;
                $newDeptLnk->LnkComDeptComplaintID = $this->coreID;
                $newDeptLnk->save();
            }
        }
        $this->getOverUpdateRow($this->coreID, $newDeptID);
        $this->sessData->refreshDataSets();
        $this->runLoopConditions();
        return true;
    }
    
    protected function civRow2Set($civ)
    {
        if (!$civ || !isset($civ->CivIsCreator)) {
            return '';
        }
        return (($civ->CivIsCreator == 'Y') ? '' 
            : (($civ->CivRole == 'Victim') ? 'Victims' : 'Witnesses') );
    }
    
    protected function getCivilianList($loop = 'Victims')
    {
        if ($loop == 'Victims' || $loop == 'Witness') {
            return $this->sessData->loopItemIDs[$loop];
        }
        $civs = [];
        if (isset($this->sessData->dataSets["Civilians"]) 
            && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
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
                    "defID" => $GLOBALS["SL"]
                        ->def->getID('Partner Types', 'Organization')
                ], [
                    "abbr" => 'attorney',
                    "sing" => 'Attorney',
                    "plur" => 'Attorneys', 
                    "defID" => $GLOBALS["SL"]
                        ->def->getID('Partner Types', 'Attorney')
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

}