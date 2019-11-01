<?php
/**
  * OpenComplaintSaves is a mid-level class which handles custom overrides 
  * for storing survey form field submissions.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use DB;
use Auth;
use App\Models\OPStops;
use App\Models\OPInjuries;
use App\Models\OPLinksCivilianEvents;
use App\Models\OPLinksCivilianVehicles;
use App\Models\OPLinksOfficerVehicles;
use OpenPolice\Controllers\OpenComplaintConditions;

class OpenComplaintSaves extends OpenComplaintConditions
{
    /**
     * Override default behavior for submitting survey forms,
     * delegateing specifc saving procedures for custom nodes.
     *
     * @param  int $nID
     * @param  string $nIDtxt
     * @param  array $tmpSubTier
     * @return boolean
     */
    protected function postNodePublicCustom($nID = -3, $nIDtxt = '', $tmpSubTier = [])
    {
        if (empty($tmpSubTier)) {
            $tmpSubTier = $this->loadNodeSubTier($nID);
        }
        list($tbl, $fld) = $this->allNodes[$nID]->getTblFld();
        if ($this->treeID == 1 && isset($this->sessData->dataSets["Complaints"])) {
            $this->sessData->dataSets["Complaints"][0]->update([
                "updated_at" => date("Y-m-d H:i:s")
            ]);
        }
        // Main Complaint Survey...
        if (in_array($nID, [16, 17, 2262, 2263])) {
            return $this->saveStartTime($nID, $tbl, $fld);
        } elseif (in_array($nID, [145, 920])) {
            return $this->saveNewDept($nID);
        } elseif ($nID == 234) {
            return $this->saveCitationVictim($nID);
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
        } elseif ($nID == 2232) {
            $date = date('Y-m-d H:i:s');
            $this->sessData->currSessData($nID, $tbl, $fld, 'update', $date);
            return true;
        } elseif ($nID == 1285) {
            return $this->saveDeptSubWays1($nID);
        } elseif ($nID == 1287) {
            return $this->saveDeptSubWays2($nID);
        } elseif ($nID == 1329) {
            return $this->saveEditLog($nID);
            
        // Page Nodes ...
        } elseif ($nID == 1007) {
            return $this->postContactEmail($nID);
        }
        return false; // false to continue standard post processing
    }
    
    /**
     * Store the start date — and optionally start time — of the incident.
     *
     * @param  int $nID
     * @param  string $tbl
     * @param  string $fld
     * @return boolean
     */
    protected function saveStartTime($nID, $tbl, $fld)
    {
        $dateNode = ((in_array($nID, [16, 17])) ? 15 : 2261);
        $date = $this->getRawFormDate($dateNode);
        if (trim($date) == '') {
            $date = '0000-00-00';
        }
        $date = date("Y-m-d", strtotime($date));
        if (substr($date, 0, 5) == '-0001') {
            $date = '0000-00-00';
        }
        $time = $this->postFormTimeStr($nID);
        if ($time === null) {
            $date .= ' 00:00:00';
        } else {
            $date .= ' ' . $time;
        }
        $this->sessData->currSessData($nID, $tbl, $fld, 'update', $date);
        return true;
    }
    
    /**
     * If there is only one civilian involved, store whether or not
     * they were given any citations.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveCitationVictim($nID)
    {
        if (isset($this->sessData->dataSets["Civilians"]) 
            && sizeof($this->sessData->dataSets["Civilians"]) == 1) {
            if ($GLOBALS["SL"]->REQ->has('n234fld') 
                && trim($GLOBALS["SL"]->REQ->get('n234fld')) == 'Y') {
                $this->sessData->dataSets["Civilians"][0]->update([
                    'CivGivenCitation' => 'Y'
                ]);
            } else {
                $this->sessData->dataSets["Civilians"][0]->update([
                    'CivGivenCitation' => 'N'
                ]);
            }
        }
        return false;
    }
    
    /**
     * Since there are more than one civilian involved, store 
     * which of them were given any citations.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveCitationVictims($nID)
    {
        $nodeFld = 'n' . $nID . 'fld';
        $isEmpty = (!$GLOBALS["SL"]->REQ->has($nodeFld) 
            || !is_array($GLOBALS["SL"]->REQ->get($nodeFld)) 
            || sizeof($GLOBALS["SL"]->REQ->get($nodeFld)) == 0);
        $civs = $this->sessData->getLoopRows('Victims');
        if ($civs && sizeof($civs) > 0) {
            foreach ($civs as $i => $civ) {
                $reqArr = $GLOBALS["SL"]->REQ->get($nodeFld);
                if ($isEmpty || !in_array($civ->CivID, $reqArr)) {
                    $civ->update([ 'CivGivenCitation' => 'N' ]);
                } else {
                    $civ->update([ 'CivGivenCitation' => 'Y' ]);
                }
            }
        }
        return true;
    }
    /**
     * If there is only one of a type of person involved, 
     * store whether or not they were given any citations.
     *
     * @param  int $nID
     * @param  string $type
     * @return boolean
     */
    protected function saveProfanePerson($nID, $type = 'Off')
    {
        $tbl = (($type == 'Off') ? 'Officers' : 'Civilians');
        $nodeFld = 'n' . $nID . 'fld';
        $profFld = $type . 'UsedProfanity';
        if ($GLOBALS["SL"]->REQ->has($nodeFld)) {
            $this->sessData->dataSets[$tbl][0]->{ $profFld } 
                = trim($GLOBALS["SL"]->REQ->get($nodeFld));
        } else {
            $this->sessData->dataSets[$tbl][0]->{ $profFld } = '';
        }
        $this->sessData->dataSets[$tbl][0]->save();
        return true;
    }
    
    /**
     * Since there are more than one of a type of person 
     * civilian involved, store which of them used profanity.
     *
     * @param  int $nID
     * @param  string $type
     * @return boolean
     */
    protected function saveProfanePersons($nID, $type = 'Off')
    {
        $tbl = (($type == 'Off') ? 'Officers' : 'Civilians');
        $nodeFld = 'n' . $nID . 'fld';
        $profFld = $type . 'UsedProfanity';
        foreach ($this->sessData->dataSets[$tbl] as $i => $off) {
            if ($GLOBALS["SL"]->REQ->has($nodeFld) 
                && in_array($off->getKey(), $GLOBALS["SL"]->REQ->get($nodeFld))) {
                $this->sessData->dataSets[$tbl][$i]->{ $profFld } = 'Y';
            } else {
                $this->sessData->dataSets[$tbl][$i]->{ $profFld } = '';
            }
            $this->sessData->dataSets[$tbl][$i]->save();
        }
        return true;
    }
    
    /**
     * Store the different types of force used against which civilians.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveForceTypes($nID)
    {
        $GLOBALS["SL"]->def->loadDefs('Force Type');
        $nodeFld = 'n' . $nID . 'fld';
        $forceTypes = $GLOBALS["SL"]->def->defValues["Force Type"];
        if ($GLOBALS["SL"]->REQ->has($nodeFld) 
            && is_array($GLOBALS["SL"]->REQ->get($nodeFld))
            && sizeof($GLOBALS["SL"]->REQ->get($nodeFld)) > 0) {
            foreach ($GLOBALS["SL"]->REQ->get($nodeFld) as $forceType) {
                if ($this->getForceEveID($forceType) <= 0 
                    && $this->coreID > 0) {
                    $this->addNewEveSeq('Force', $forceType);
                }
                $eveID = $this->getForceEveID($forceType);
                if ($nID == 742) {
                    $fInd = 0;
                    foreach ($forceTypes as $i => $typ) {
                        if ($typ->DefID == $forceType) {
                            $fInd = $i;
                        }
                    }
                    $currCivs = $this->getLinkedToEvent('Civilian', $eveID);
                    if ($GLOBALS["SL"]->REQ->has('n2043res' . $fInd . 'fld')) {
                        $reqArr = $GLOBALS["SL"]->REQ->get('n2043res' . $fInd . 'fld');
                        if (is_array($reqArr) && sizeof($reqArr) > 0) {
                            foreach ($reqArr as $civID) {
                                if (!in_array($civID, $currCivs)) {
                                    $newLnk = new OPLinksCivilianEvents;
                                    $newLnk->LnkCivEveEveID = $eveID;
                                    $newLnk->LnkCivEveCivID = $civID;
                                    $newLnk->save();
                                }
                            }
                        }
                    }
                    if (sizeof($currCivs) > 0) {
                        foreach ($currCivs as $currCivID) {
                            $fld = 'n2043res' . $fInd . 'fld';
                            if (!$GLOBALS["SL"]->REQ->has($fld) 
                                || !is_array($GLOBALS["SL"]->REQ->get($fld))
                                || !in_array($currCivID, $GLOBALS["SL"]->REQ->get($fld))) {
                                OPLinksCivilianEvents::where('LnkCivEveEveID', $eveID)
                                    ->where('LnkCivEveCivID', $currCivID)
                                    ->delete();
                            }
                        }
                    }
                } elseif ($nID == 2044) {
                    $civs = $this->sessData->getLoopRows('Victims');
                    if (sizeof($civs) > 0 && isset($civs[0]->CivID)) {
                        $newLnk = new OPLinksCivilianEvents;
                        $newLnk->LnkCivEveEveID = $eveID;
                        $newLnk->LnkCivEveCivID = $civs[0]->CivID;
                        $newLnk->save();
                    }
                }
            }
        }
        foreach ($forceTypes as $i => $def) {
            if (!$GLOBALS["SL"]->REQ->has($nodeFld) 
                || !in_array($def->DefID, $GLOBALS["SL"]->REQ->get($nodeFld))) {
                $e = $this->getForceEveID($def->DefID);
                $this->deleteEventByID($e);
            }
        }
        $this->sessData->refreshDataSets();
        return true;
    }
    
    /**
     * Store whether or not force was used against an animal.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveForceAnimYN($nID)
    {
        $nodeFld = 'n' . $nID . 'fld';
        if (!$GLOBALS["SL"]->REQ->has($nodeFld) 
            || $GLOBALS["SL"]->REQ->get($nodeFld) == 'N') {
            $animalsForce = $this->getCivAnimalForces();
            if ($animalsForce && sizeof($animalsForce) > 0) {
                foreach ($animalsForce as $force) {
                    $this->deleteEventByID($force->ForEventSequenceID);
                }
            }
        }
        return false;
    }
    
    /**
     * Store the types of force used against an animal.
     *
     * @param  int $nID1
     * @param  int $nID2
     * @param  int $nID3
     * @return boolean
     */
    protected function saveForceTypesAnim($nID1, $nID2, $nID3)
    {
        $fld1 = 'n' . $nID1 . 'fld';
        $fld2 = 'n' . $nID2 . 'fld';
        $fld3 = 'n' . $nID3 . 'fld';
        if ($GLOBALS["SL"]->REQ->has($fld2) 
            && $GLOBALS["SL"]->REQ->get($fld2) == 'Y') { 
            if ($GLOBALS["SL"]->REQ->has($fld1) 
                && is_array($GLOBALS["SL"]->REQ->get($fld1)) 
                && sizeof($GLOBALS["SL"]->REQ->get($fld1)) > 0) {
                $animalDesc = (($GLOBALS["SL"]->REQ->has($fld3)) 
                    ? trim($GLOBALS["SL"]->REQ->get($fld3)) : '');
                $animalsForce = $this->getCivAnimalForces();
                foreach ($GLOBALS["SL"]->REQ->n744fld as $forceType) {
                    $foundType = false;
                    if ($animalsForce && sizeof($animalsForce) > 0) {
                        foreach ($animalsForce as $force) {
                            if ($force->ForType == $forceType) {
                                $foundType = true;
                            }
                        }
                    }
                    if (!$foundType && $this->coreID > 0) {
                        $newForce = $this->addNewEveSeq('Force', $forceType);
                        $newForce->ForAgainstAnimal = 'Y';
                        $newForce->ForAnimalDesc = $animalDesc;
                        $newForce->save();
                    }
                }
            }
            $types = $GLOBALS["SL"]->def->defValues["Force Type"];
            foreach ($types as $i => $def) {
                if (!$GLOBALS["SL"]->REQ->has($fld1) 
                    || !in_array($def->DefID, 
                        $GLOBALS["SL"]->REQ->get($fld1))) {
                    $this->deleteEventByID(
                        $this->getForceEveID($def->DefID, true));
                }
            }
        }
        return true;
    }
    
    /**
     * Store whether or not there was an injury related to handcuffs.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveHandcuffInjury($nID)
    {
        $handcuffDefID = $GLOBALS["SL"]->def->getID(
            'Injury Types', 
            'Handcuff Injury'
        );
        $stopRow = $this->getEventSequence(
            $this->sessData->dataBranches[1]["itemID"]);
        $injID = $stopRow[0]["Event"]->StopSubjectHandcuffInjury;
        if ($GLOBALS["SL"]->REQ->has('n316fld') 
            && trim($GLOBALS["SL"]->REQ->n316fld) == 'Y') {
            if (intVal($injID) <= 0) {
                $newInj = new OPInjuries;
                $newInj->InjType = $handcuffDefID;
                $newInj->InjSubjectID 
                    = ((isset($stopRow[0]["Civilians"][0])) 
                        ? $stopRow[0]["Civilians"][0] : -3);
                $newInj->save();
                $this->sessData->dataSets["Injuries"]["Handcuff"][] 
                    = $newInj;
                OPStops::find($stopRow[0]["Event"]->StopID)->update([
                    'StopSubjectHandcuffInjury' => $newInj->InjID
                ]);
            }
        } elseif (intVal($injID) > 0) {
            OPStops::find($stopRow[0]["Event"]->StopID)->update([
                'StopSubjectHandcuffInjury' => NULL
            ]);
            $inj = $stopRow[0]["Event"]->StopSubjectHandcuffInjury;
            $this->sessData->deleteDataItem($nID, 'Injuries', $inj);
        }
        return false;
    }
    
    /**
     * Update the complaint status to 'New', AKA complete.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveStatusCompletion($nID)
    {
        if ($GLOBALS["SL"]->REQ->get('step') != 'next') {
            return true;
        }
        $this->sessData->dataSets['Complaints'][0]->ComStatus 
            = $GLOBALS["SL"]->def->getID('Complaint Status', 'New');
        $this->sessData->dataSets['Complaints'][0]->save();
        return false;
    }
    
}