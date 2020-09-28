<?php
/**
  * OpenPoliceEvents is a mid-level class for functions handling
  * records describing events and allegations of a complaint.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.2.5
  */
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use App\Models\OPEventSequence;
use App\Models\OPStops;
use App\Models\OPSearches;
use App\Models\OPArrests;
use App\Models\OPForce;
use App\Models\OPLinksOfficerEvents;
use App\Models\OPLinksCivilianEvents;
use App\Models\OPPersonContact;
use FlexYourRights\OpenPolice\Controllers\OpenPoliceAllegations;

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
            if ($this->allNodes[$nID]->checkBranch($this->allNodes[$nodeRoot]->nodeTierPath)) {
                return $eventType;
            }
        }
        return '';
    }
    
    protected function getEveSeqOrd($eveSeqID)
    {
        /* if (isset($this->sessData->dataSets["event_sequence"]) 
            && sizeof($this->sessData->dataSets["event_sequence"]) > 0) { 
            foreach ($this->sessData->dataSets["event_sequence"] as $i => $eveSeq) {
                if ($eveSeq->eve_id == $eveSeqID) {
                    return $eveSeq->eve_order;
                }
            }
        } */
        return 0;
    }
    
    protected function getLastEveSeqOrd()
    {
        $newOrd = 0;
        /* if (isset($this->sessData->dataSets["event_sequence"]) 
            && sizeof($this->sessData->dataSets["event_sequence"]) > 0) {
            $ind = sizeof($this->sessData->dataSets["event_sequence"])-1;
            $newOrd = $this->sessData->dataSets["event_sequence"][$ind]->eve_order;
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
            if ($this->getCivEveSeqIdByType($civ->civ_id, 'Stops') > 0) {
                $this->eventCivLookup["Stops"][] = $civ->civ_id;
            }
            if ($this->getCivEveSeqIdByType($civ->civ_id, 'Searches') > 0) {
                $this->eventCivLookup["Searches"][] = $civ->civ_id;
            }
            if ($this->getCivEveSeqIdByType($civ->civ_id, 'Force') > 0) {
                $this->eventCivLookup["Force"][] = $civ->civ_id;
            }
            if ($this->getCivEveSeqIdByType($civ->civ_id, 'Arrests') > 0) {
                $this->eventCivLookup["Arrests"][] = $civ->civ_id;
            } elseif (($civ->civ_given_citation == 'N' || trim($civ->civ_given_citation) == '') 
                && ($civ->civ_given_warning == 'N' || trim($civ->civ_given_warning) == '')) {
                $this->eventCivLookup["No Punish"][] = $civ->civ_id;
            }
            if ($civ->civ_given_citation == 'Y') {
                $this->eventCivLookup["Citations"][] = $civ->civ_id;
            }
            if ($civ->civ_given_warning == 'Y') {
                $this->eventCivLookup["Warnings"][] = $civ->civ_id;
            }
        }
        if (isset($this->sessData->dataSets["force"]) 
            && sizeof($this->sessData->dataSets["force"]) > 0) {
            foreach ($this->sessData->dataSets["force"] as $forceRow) {
                if ($forceRow->for_against_animal == 'Y') {
                    $this->eventCivLookup["Force Animal"][] = $forceRow->for_id;
                }
            }
        }
        return true;
    }
    
    protected function addNewEveSeq($eventType, $forceType = -3)
    {
        if ($this->coreID > 0) {
            $newEveSeq = new OPEventSequence;
            $newEveSeq->eve_complaint_id = $this->coreID;
            $newEveSeq->eve_type = $eventType;
            //$newEveSeq->eve_order = (1+$this->getLastEveSeqOrd());
            $newEveSeq->save();
            eval("\$newEvent = new App\\Models\\" 
                . $GLOBALS["SL"]->tblModels[strtolower($eventType)] . ";");
            $fld = $GLOBALS["SL"]->tblAbbr[strtolower($eventType)] . 'event_sequence_id';
            $newEvent->{ $fld } = $newEveSeq->getKey();
            if ($eventType == 'Force' && $forceType > 0) {
                $newEvent->for_type = $forceType;
            }
            $newEvent->save();
            $this->sessData->dataSets["event_sequence"][] = $newEveSeq;
            $this->sessData->dataSets[$eventType][] = $newEvent;
            return $newEvent;
        }
        return null;
    }
    
    protected function getCivEventID($nID, $eveType, $civID)
    {
        $civLnk = DB::table('op_links_civilian_events')
            ->join('op_event_sequence', 'op_event_sequence.eve_id', 
                '=', 'op_links_civilian_events.lnk_civ_eve_eve_id')
            ->where('op_event_sequence.eve_type', $eveType)
            ->where('op_links_civilian_events.lnk_civ_eve_civ_id', $civID)
            ->select('op_event_sequence.*')
            ->first();
        if ($civLnk && isset($civLnk->eve_id)) {
            return $civLnk->eve_id;
        }
        return -3;
    }
    
    protected function getForceEveID($forceType, $animal = false)
    {
        if (isset($this->sessData->dataSets["force"]) 
            && sizeof($this->sessData->dataSets["force"]) > 0) {
            foreach ($this->sessData->dataSets["force"] as $force) {
                if (isset($force->for_type) && $force->for_type == $forceType) {
                    if ($animal) {
                        if (isset($force->for_against_animal) 
                            && trim($force->for_against_animal) == 'Y') {
                            return $force->for_event_sequence_id;
                        }
                    } elseif (!isset($force->for_against_animal) 
                        || trim($force->for_against_animal) != 'Y') {
                        return $force->for_event_sequence_id;
                    }
                }
            }
        }
        return -3;
    }
    
    protected function getCivAnimalForces()
    {
        return DB::table('op_event_sequence')
            ->join('op_force', 'op_force.for_event_sequence_id', '=', 'op_event_sequence.eve_id')
            ->where('op_event_sequence.eve_complaint_id', $this->coreID)
            ->where('op_event_sequence.eve_type', 'Force')
            ->where('op_force.for_against_animal', 'Y')
            ->select('op_force.*')
            ->get();
    }
    
    protected function createCivAnimalForces()
    {
        $eve = new OPEventSequence;
        $eve->eve_complaint_id = $this->coreID;
        $eve->eve_type = 'Force';
        $eve->save();
        $frc = new OPForce;
        $frc->for_event_sequence_id = $eve->getKey();
        $frc->for_against_animal = 'Y';
        $frc->save();
        if (!isset($this->sessData->dataSets["force"])) {
            $this->sessData->dataSets["force"] = [];
        }
        $this->sessData->dataSets["force"][] = $frc;
        if (!isset($this->sessData->dataSets["event_sequence"])) {
            $this->sessData->dataSets["event_sequence"] = [];
        }
        $this->sessData->dataSets["event_sequence"][] = $eve;
        return $eve;
    }
    
    protected function delCivEvent($nID, $eveType, $civID)
    {
        return $this->deleteEventByID($this->getCivEventID($nID, $eveType, $civID));
    }
    
    protected function deleteEventByID($eveSeqID)
    {
        if ($eveSeqID > 0) {
            $chk = OPEventSequence::find($eveSeqID);
            if ($chk && isset($chk->eve_id)) {
                OPEventSequence::find($eveSeqID)
                    ->delete();
                OPStops::where('stop_event_sequence_id', $eveSeqID)
                    ->delete();
                OPSearches::where('srch_event_sequence_id', $eveSeqID)
                    ->delete();
                OPArrests::where('arst_event_sequence_id', $eveSeqID)
                    ->delete();
                OPForce::where('for_event_sequence_id', $eveSeqID)
                    ->delete();
                OPLinksCivilianEvents::where('lnk_civ_eve_eve_id', $eveSeqID)
                    ->delete();
                OPLinksOfficerEvents::where('lnk_off_eve_eve_id', $eveSeqID)
                    ->delete();
            }
        }
        return true;
    }
    
    protected function getEventSequence($eveSeqID = -3)
    {
        $eveSeqs = [];
        if (isset($this->sessData->dataSets["event_sequence"]) 
            && sizeof($this->sessData->dataSets["event_sequence"]) > 0) {
            foreach ($this->sessData->dataSets["event_sequence"] as $eveSeq) {
                if ($eveSeqID <= 0 || $eveSeqID == $eveSeq->eve_id) {
                    $event = $this->sessData->getChildRow(
                        'event_sequence', 
                        $eveSeq->eve_id, 
                        $eveSeq->eve_type
                    );
                    $civs = $this->getLinkedToEvent('civilian', $eveSeq->eve_id);
                    $offs = $this->getLinkedToEvent('officer', $eveSeq->eve_id);
                    $eveSeqs[] = [ 
                        "EveID"     => $eveSeq->eve_id, 
                        //"EveOrder"  => $eveSeq->eve_order, 
                        "EveType"   => $eveSeq->eve_type, 
                        "Civilians" => $civs, 
                        "Officers"  => $offs, 
                        "Event"     => $event
                    ];
                }
            }
        }
        return $eveSeqs;
    }
    
    protected function getCivEveSeqIdByType($civID, $eventType)
    {
        if ($eventType != '' 
            && isset($this->sessData->dataSets["event_sequence"]) 
            && sizeof($this->sessData->dataSets["event_sequence"]) > 0) {
            foreach ($this->sessData->dataSets["event_sequence"] as $eveSeq) {
                $linked = $this->getLinkedToEvent('civilian', $eveSeq->eve_id);
                if ($eveSeq->eve_type == $eventType && in_array($civID, $linked)) {
                    return $eveSeq->eve_id;
                }
            }
        }
        return -3;
    }
    
    protected function getEveSeqRowType($eveSeqID = -3)
    {
        $eveSeq = $this->sessData->getRowById('event_sequence', $eveSeqID);
        if ($eveSeq) {
            return $eveSeq->eve_type;
        }
        return '';
    }
    
    protected function chkCivHasForce($civID = -3)
    {
        if ($civID > 0 
            && isset($this->sessData->dataSets["links_civilian_force"]) 
            && sizeof($this->sessData->dataSets["links_civilian_force"]) > 0) {
            foreach ($this->sessData->dataSets["links_civilian_force"] as $frc) {
                if (isset($frc->lnk_civ_frc_civ_id) 
                    && intVal($frc->lnk_civ_frc_civ_id) == $civID) {
                    return 1;
                }
            }
        }
        return 0;
    }


}