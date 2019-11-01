<?php
/**
  * OpenPoliceEvents is a mid-level class for functions handling
  * records describing events and allegations of a complaint.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.2.5
  */
namespace OpenPolice\Controllers;

use DB;
use App\Models\OPEventSequence;
use App\Models\OPStops;
use App\Models\OPSearches;
use App\Models\OPArrests;
use App\Models\OPForce;
use App\Models\OPLinksOfficerEvents;
use App\Models\OPLinksCivilianEvents;
use App\Models\OPPersonContact;
use OpenPolice\Controllers\OpenPoliceAllegations;

class OpenPoliceEvents extends OpenPoliceAllegations
{
    // get Incident Event Type from Node location in the Gold process
    protected function getEveSeqTypeFromNode($nID)
    {
        $eveSeqLoop = [
            'Stops'    => 149, 
            'Searches' => 150, 
            'Force'    => 151,
            'Arrests'  => 152
        ];
        foreach ($eveSeqLoop as $eventType => $nodeRoot) {
            if ($this->allNodes[$nID]->checkBranch(
                $this->allNodes[$nodeRoot]->nodeTierPath)) {
                return $eventType;
            }
        }
        return '';
    }
    
    protected function getEveSeqOrd($eveSeqID)
    {
        /* if (isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) { 
            foreach ($this->sessData->dataSets["EventSequence"] as $i => $eveSeq) {
                if ($eveSeq->EveID == $eveSeqID) {
                    return $eveSeq->EveOrder;
                }
            }
        } */
        return 0;
    }
    
    protected function getLastEveSeqOrd()
    {
        $newOrd = 0;
        /* if (isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            $ind = sizeof($this->sessData->dataSets["EventSequence"])-1;
            $newOrd = $this->sessData->dataSets["EventSequence"][$ind]->EveOrder;
        } */
        return $newOrd;
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
            } elseif (($civ->CivGivenCitation == 'N' 
                    || trim($civ->CivGivenCitation) == '') 
                && ($civ->CivGivenWarning == 'N' 
                    || trim($civ->CivGivenWarning) == '')) {
                $this->eventCivLookup["No Punish"][] = $civ->CivID;
            }
            if ($civ->CivGivenCitation == 'Y') {
                $this->eventCivLookup["Citations"][] = $civ->CivID;
            }
            if ($civ->CivGivenWarning == 'Y') {
                $this->eventCivLookup["Warnings"][] = $civ->CivID;
            }
        }
        if (isset($this->sessData->dataSets["Force"]) 
            && sizeof($this->sessData->dataSets["Force"]) > 0) {
            foreach ($this->sessData->dataSets["Force"] as $forceRow) {
                if ($forceRow->ForAgainstAnimal == 'Y') {
                    $this->eventCivLookup["Force Animal"][] = $forceRow->ForID;
                }
            }
        }
        return true;
    }
    
    protected function addNewEveSeq($eventType, $forceType = -3)
    {
        if ($this->coreID > 0) {
            $newEveSeq = new OPEventSequence;
            $newEveSeq->EveComplaintID = $this->coreID;
            $newEveSeq->EveType = $eventType;
            //$newEveSeq->EveOrder = (1+$this->getLastEveSeqOrd());
            $newEveSeq->save();
            eval("\$newEvent = new App\\Models\\" 
                . $GLOBALS["SL"]->tblModels[$eventType] . ";");
            $fld = $GLOBALS["SL"]->tblAbbr[$eventType] . 'EventSequenceID';
            $newEvent->{ $fld } = $newEveSeq->getKey();
            if ($eventType == 'Force' && $forceType > 0) {
                $newEvent->ForType = $forceType;
            }
            $newEvent->save();
            $this->sessData->dataSets["EventSequence"][] = $newEveSeq;
            $this->sessData->dataSets[$eventType][] = $newEvent;
            return $newEvent;
        }
        return null;
    }
    
    protected function getCivEventID($nID, $eveType, $civID)
    {
        $civLnk = DB::table('OP_LinksCivilianEvents')
            ->join('OP_EventSequence', 'OP_EventSequence.EveID', 
                '=', 'OP_LinksCivilianEvents.LnkCivEveEveID')
            ->where('OP_EventSequence.EveType', $eveType)
            ->where('OP_LinksCivilianEvents.LnkCivEveCivID', $civID)
            ->select('OP_EventSequence.*')
            ->first();
        if ($civLnk && isset($civLnk->EveID)) {
            return $civLnk->EveID;
        }
        return -3;
    }
    
    protected function getForceEveID($forceType, $animal = false)
    {
        if (isset($this->sessData->dataSets["Force"]) 
            && sizeof($this->sessData->dataSets["Force"]) > 0) {
            foreach ($this->sessData->dataSets["Force"] as $force) {
                if (isset($force->ForType) && $force->ForType == $forceType) {
                    if ($animal) {
                        if (isset($force->ForAgainstAnimal) 
                            && trim($force->ForAgainstAnimal) == 'Y') {
                            return $force->ForEventSequenceID;
                        }
                    } elseif (!isset($force->ForAgainstAnimal) 
                        || trim($force->ForAgainstAnimal) != 'Y') {
                        return $force->ForEventSequenceID;
                    }
                }
            }
        }
        return -3;
    }
    
    protected function getCivAnimalForces()
    {
        return DB::table('OP_EventSequence')
            ->join('OP_Force', 'OP_Force.ForEventSequenceID', 
                '=', 'OP_EventSequence.EveID')
            ->where('OP_EventSequence.EveComplaintID', $this->coreID)
            ->where('OP_EventSequence.EveType', 'Force')
            ->where('OP_Force.ForAgainstAnimal', 'Y')
            ->select('OP_Force.*')
            ->get();
    }
    
    protected function createCivAnimalForces()
    {
        $eve = new OPEventSequence;
        $eve->EveComplaintID = $this->coreID;
        $eve->EveType = 'Force';
        $eve->save();
        $frc = new OPForce;
        $frc->ForEventSequenceID = $eve->getKey();
        $frc->ForAgainstAnimal = 'Y';
        $frc->save();
        if (!isset($this->sessData->dataSets["Force"])) {
            $this->sessData->dataSets["Force"] = [];
        }
        $this->sessData->dataSets["Force"][] = $frc;
        if (!isset($this->sessData->dataSets["EventSequence"])) {
            $this->sessData->dataSets["EventSequence"] = [];
        }
        $this->sessData->dataSets["EventSequence"][] = $eve;
        return $eve;
    }
    
    protected function delCivEvent($nID, $eveType, $civID)
    {
        return $this->deleteEventByID(
            $this->getCivEventID($nID, $eveType, $civID)
        );
    }
    
    protected function deleteEventByID($eveSeqID)
    {
        if ($eveSeqID > 0) {
            $chk = OPEventSequence::find($eveSeqID);
            if ($chk && isset($chk->EveID)) {
                OPEventSequence::find($eveSeqID)
                    ->delete();
                OPStops::where('StopEventSequenceID', $eveSeqID)
                    ->delete();
                OPSearches::where('SrchEventSequenceID', $eveSeqID)
                    ->delete();
                OPArrests::where('ArstEventSequenceID', $eveSeqID)
                    ->delete();
                OPForce::where('ForEventSequenceID', $eveSeqID)
                    ->delete();
                OPLinksCivilianEvents::where('LnkCivEveEveID', $eveSeqID)
                    ->delete();
                OPLinksOfficerEvents::where('LnkOffEveEveID', $eveSeqID)
                    ->delete();
            }
        }
        return true;
    }
    
    protected function getEventSequence($eveSeqID = -3)
    {
        $eveSeqs = [];
        if (isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            foreach ($this->sessData->dataSets["EventSequence"] as $eveSeq) {
                if ($eveSeqID <= 0 || $eveSeqID == $eveSeq->EveID) {
                    $event = $this->sessData->getChildRow(
                        'EventSequence', 
                        $eveSeq->EveID, 
                        $eveSeq->EveType
                    );
                    $civs = $this->getLinkedToEvent('Civilian', $eveSeq->EveID);
                    $offs = $this->getLinkedToEvent('Officer', $eveSeq->EveID);
                    $eveSeqs[] = [ 
                        "EveID"     => $eveSeq->EveID, 
                        //"EveOrder"  => $eveSeq->EveOrder, 
                        "EveType"   => $eveSeq->EveType, 
                        "Civilians" => $civs, 
                        "Officers"  => $offs, 
                        "Event"     => $event
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
        if (!isset($eveSeq["EveType"]) && is_array($eveSeq) 
            && sizeof($eveSeq) > 0) {
            $eveSeq = $eveSeq[0];
        }
        if (!is_array($eveSeq) || !isset($eveSeq["EveType"])) {
            return '';
        }
        $ret = '<span class="slBlueDark">';
        if ($eveSeq["EveType"] == 'Force' && isset($eveSeq["Event"]->ForType) 
            && trim($eveSeq["Event"]->ForType) != ''){
            if ($eveSeq["Event"]->ForType 
                == $GLOBALS["SL"]->def->getID('Force Type', 'Other')) {
                $ret .= $eveSeq["Event"]->ForTypeOther . ' Force ';
            } else {
                $ret .= $GLOBALS["SL"]->def->getVal('Force Type', 
                        $eveSeq["Event"]->ForType) 
                    . ' Force ';
            }
        } elseif (isset($this->eventTypeLabel[$eveSeq["EveType"]])) {
            $ret .= $this->eventTypeLabel[$eveSeq["EveType"]] . ' ';
        }
        if ($eveSeq["EveType"] == 'Force' 
            && $eveSeq["Event"]->ForAgainstAnimal == 'Y') {
            $ret .= '<span class="blk">on</span> ' 
                . $eveSeq["Event"]->ForAnimalDesc;
        } else {
            $civNames = $offNames = '';
            if ($this->moreThan1Victim() 
                && in_array($info, array('', 'Civilians'))) { 
                foreach ($eveSeq["Civilians"] as $civ) {
                    $civNames .= ', ' . $this->getCivilianNameFromID($civ);
                }
                if (trim($civNames) != '') {
                    $ret .= '<span class="blk">' 
                        . (($eveSeq["EveType"] == 'Force') ? 'on ' : 'of ')
                        . '</span>' . substr($civNames, 1);
                }
            }
            if ($this->moreThan1Officer() 
                && in_array($info, array('', 'Officers'))) { 
                foreach ($eveSeq["Officers"] as $off) {
                    $offNames .= ', ' . $this->getOfficerNameFromID($off);
                }
                if (trim($offNames) != '') {
                    $ret .= ' <span class="blk">by</span> ' . substr($offNames, 1);
                }
            }
        }
        $ret .= '</span>';
        return $ret;
    }
    
    protected function getCivEveSeqIdByType($civID, $eventType)
    {
        if ($eventType != '' && isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            foreach ($this->sessData->dataSets["EventSequence"] as $eveSeq) {
                $linked = $this->getLinkedToEvent('Civilian', $eveSeq->EveID);
                if ($eveSeq->EveType == $eventType
                    && in_array($civID, $linked)) {
                    return $eveSeq->EveID;
                }
            }
        }
        return -3;
    }
    
    protected function getEveSeqRowType($eveSeqID = -3)
    {
        $eveSeq = $this->sessData->getRowById('EventSequence', $eveSeqID);
        if ($eveSeq) {
            return $eveSeq->EveType;
        }
        return '';
    }
    
    protected function chkCivHasForce($civID = -3)
    {
        if ($civID > 0 && isset($this->sessData->dataSets["EventSequence"]) 
            && sizeof($this->sessData->dataSets["EventSequence"]) > 0) {
            foreach ($this->sessData->dataSets["EventSequence"] as $eve) {
                if ($eve->EveType == 'Force') {
                    $chk = OPLinksCivilianEvents::where('LnkCivEveEveID', $eve->EveID)
                        ->where('LnkCivEveCivID', $civID)
                        ->first();
                    if ($chk && isset($chk->LnkCivEveID)) {
                        return 1;
                    }
                }
            }
        }
        return 0;
    }
    
    protected function isEventAnimalForce($eveSeqID = -3, $force = [])
    {
        if (empty($force)) {
            $eveSeq = $this->sessData->getRowById('EventSequence', $eveSeqID);
            if ($eveSeq && $eveSeq->EveType == 'Force') {
                $force = $this->sessData->getChildRow(
                    'EventSequence', 
                    $eveSeq->EveID, 
                    $eveSeq->EveType
                );
            }
        }
        if ($force && isset($force->ForAgainstAnimal) 
            && $force->ForAgainstAnimal == 'Y') {
            return $force->ForAnimalDesc;
        }
        return '';
    }


}