<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use App\Models\OPStops;
use App\Models\OPInjuries;
use App\Models\OPLinksCivilianEvents;
use OpenPolice\Controllers\OpenComplaintPrints;

class OpenComplaintSaves extends OpenComplaintPrints
{
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
        if ($time === null) {
            $date .= ' 00:00:00';
        } else {
            $date .= ' ' . $time;
        }
        $this->sessData->currSessData($nID, $tbl, $fld, 'update', $date);
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
                if ($this->getForceEveID($forceType) <= 0) {
                    $this->addNewEveSeq('Force', $forceType);
                }
                $eveID = $this->getForceEveID($forceType);
                if ($nID == 742) {
                    $fInd = 0;
                    foreach ($GLOBALS["SL"]->def->defValues["Force Type"] as $i => $typ) {
                        if ($typ->DefID == $forceType) {
                            $fInd = $i;
                        }
                    }
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
    
    protected function saveStatusCompletion($nID)
    {
        if ($GLOBALS["SL"]->REQ->get('step') != 'next') return true;
        $this->sessData->dataSets['Complaints'][0]->ComStatus = $GLOBALS["SL"]->def->getID('Complaint Status', 'New');
        $this->sessData->dataSets['Complaints'][0]->save();
        return false;
    }
    
}