<?php
/**
  * OpenSessDataOverride is a mid-level class which provides
  * custom overrides for data which is pre-loaded
  * in survey form fields.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.15
  */
namespace OpenPolice\Controllers;

use Auth;
use App\Models\SLNodeSavesPage;
use OpenPolice\Controllers\OpenComplaintPrints;

class OpenSessDataOverride extends OpenComplaintPrints
{
    /**
     * Delegate the custom overrides for SurvLoop default 
     * methods to retrieve current session data required
     * by the current node.
     *
     * @param  int $nID
     * @param  array $tmpSubTier
     * @param  string $condition
     * @param  string $currNodeSessionData
     * @return array
     */
    protected function printNodeSessDataOverride($nID = -3, $tmpSubTier = [], $nIDtxt = '', $currNodeSessionData = '')
    {
        if (empty($this->sessData->dataSets)) {
            return [];
        }
        if ($nID == 28) { // Complainant's Role
            if (isset($this->sessData->dataSets["civilians"])) {
                return [trim($this->sessData->dataSets["civilians"][0]->civ_role)];
            }
            return [];
        } elseif ($nID == 47) { // Complainant Recorded Incident?
            if (isset($this->sessData->dataSets["civilians"])) {
                return [trim($this->sessData->dataSets["civilians"][0]->civ_camera_record)];
            }
            return [];
        } elseif ($nID == 19) { // Would you like to provide the GPS location?
            if (isset($this->sessData->dataSets["incidents"]) 
                && (intVal($this->sessData->dataSets["incidents"][0]->inc_address_lat) != 0 
                    || intVal($this->sessData->dataSets["incidents"][0]->inc_address_lng) != 0)) {
                return ['Yes'];
            } else {
                return [];
            }
        } elseif (in_array($nID, [39, 907])) {
            if ($currNodeSessionData == '') {
                $user = Auth::user();
                if ($user && isset($user->email)) {
                    return [$user->email];
                }
                return [''];
            }
        } elseif ($nID == 671) { // Officers Used Profanity?
            $currVals = [];
            foreach ($this->sessData->dataSets["officers"] as $i => $off) {
                if (isset($off->off_used_profanity) && $off->off_used_profanity == 'Y') {
                    $currVals[] = $off->getKey();
                }
            }
            return [';' . implode(';', $currVals) . ';'];
        } elseif ($nID == 237) {
            $ret = [];
            $civs = $this->sessData->getLoopRows('Victims');
            if ($civs && sizeof($civs) > 0) {
                foreach ($civs as $i => $civ) {
                    if ($civ->civ_role == 'Victim' && trim($civ->civ_given_citation) == 'Y') {
                        $ret[] = $civ->civ_id;
                    }
                }
            }
            return $ret;
        } elseif ($nID == 674) { // Officer Used Profanity?
            return [ trim($this->sessData->dataSets["officers"][0]->off_used_profanity) ];
        } elseif ($nID == 670) { // Victims Used Profanity?
            $currVals = [];
            foreach ($this->sessData->dataSets["civilians"] as $i => $civ) {
                if ($civ->civ_used_profanity == 'Y') {
                    $currVals[] = $civ->getKey();
                }
            }
            return [';' . implode(';', $currVals) . ';'];
        } elseif ($nID == 676) { // Victim Used Profanity?
            $civInd = $this->getFirstVictimCivInd();
            if ($civInd >= 0) {
                return [
                    trim($this->sessData->dataSets["civilians"][$civInd]->civ_used_profanity)
                ];
            }
        } elseif (in_array($nID, [732, 736, 733])) { // Gold Stops & Searches, Multiple Victims
            if (!isset($this->v["firstTimeGoGoldDeets"])) {
                $chk = SLNodeSavesPage::where('page_save_session', $this->coreID)
                    ->where('page_save_node', 484)
                    ->first();
                $this->v["firstTimeGoGoldDeets"] = (!$chk || !isset($chk->page_save_id));
            }
            $ret = [];
            $eveType = (in_array($nID, [732, 736])) ? 'Stops' : 'Searches';
            if (sizeof($this->sessData->loopItemIDs["Victims"]) > 0) {
                foreach ($this->sessData->loopItemIDs["Victims"] as $civ) {
                    if ($this->getCivEventID($nID, $eveType, $civ) > 0) {
                        $ret[] = $civ;
                    }
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
                } elseif (!isset($this->v["firstTimeGoGoldDeets"]) 
                    || !$this->v["firstTimeGoGoldDeets"]
                    || !in_array($civ, $this->eventCivLookup['Force'])) {
                    $ret[] = 'cyc' . $i . 'N';
                }
            }
            if (empty($ret)) {
                $ret = ['N'];
            }
            return $ret;
        } elseif (in_array($nID, [742, 2044])) { // Use of Force on Victims: Sub-Types
            $ret = [];
            if (isset($this->sessData->dataSets["force"]) 
                && sizeof($this->sessData->dataSets["force"]) > 0) {
                foreach ($this->sessData->dataSets["force"] as $force) {
                    if (isset($force->for_type) 
                        && intVal($force->for_type) > 0 
                        && (!isset($force->for_against_animal) 
                            || trim($force->for_against_animal) != 'Y')) {
                        $ret[] = $force->for_type;
                    }
                }
            }
            return $ret;
        } elseif ($nID == 2043) {
            $force = $this->sessData->getDataBranchRow('Force');
            if ($force && isset($force->for_event_sequence_id) 
                && intVal($force->for_event_sequence_id) > 0) {
                return $this->getLinkedToEvent('Civilian', $force->for_event_sequence_id);
            }
            return [];
        } elseif ($nID == 743) { // Use of Force against Animal: Yes/No
            $animalsForce = $this->getCivAnimalForces();
            if ($animalsForce && sizeof($animalsForce) > 0) {
                return ['Y'];
            } elseif (!isset($this->v["firstTimeGoGoldDeets"]) || !$this->v["firstTimeGoGoldDeets"]) {
                return ['N'];
            }
        } elseif ($nID == 746) { // Use of Force against Animal: Description
            $animalsForce = $this->getCivAnimalForces();
            if ($animalsForce->isNotEmpty() && isset($animalsForce[0]->for_animal_desc)) {
                return [ $animalsForce[0]->for_animal_desc ];
            }
        } elseif ($nID == 744) { // Use of Force against Animal: Sub-types
            $ret = [];
            $animalsForce = $this->getCivAnimalForces();
            if ($animalsForce && sizeof($animalsForce) > 0) {
                foreach ($animalsForce as $force) {
                    $ret[] = $force->for_type;
                }
            }
            return $ret;
        } elseif ($nID == 741) { // Arrests, Citations, Warnings
            $ret = [];
            $this->checkHasEventSeq($nID);
            foreach ($this->sessData->loopItemIDs["Victims"] as $i => $civ) {
                if (in_array($civ, $this->eventCivLookup['Arrests'])) {
                    $ret[] = 'cyc' . $i . 'Arrests';
                } elseif (in_array($civ, $this->eventCivLookup['Citations'])) {
                    $ret[] = 'cyc' . $i . 'Citations';
                } elseif (in_array($civ, $this->eventCivLookup['Warnings'])) {
                    $ret[] = 'cyc' . $i . 'Warnings';
                } else {
                    $ret[] = 'cyc' . $i . 'None';
                }
            }
            return $ret;
        } elseif (in_array($nID, [401, 334, 409, 356, 384])) { // Gold Allegations: Pre-Load "Why" From Silver
            if (trim($currNodeSessionData) == '') {
                $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Wrongful Detention'); // 401
                if ($nID == 334) {
                    $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Wrongful Search');
                } elseif ($nID == 409) {
                    $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Wrongful Property Seizure');
                } elseif ($nID == 356) {
                    $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Unreasonable Force');
                } elseif ($nID == 384) {
                    $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Wrongful Arrest');
                }
                if (isset($this->sessData->dataSets["allegations"]) 
                    && sizeof($this->sessData->dataSets["allegations"]) > 0) {
                    foreach ($this->sessData->dataSets["allegations"] as $alleg) {
                        if (isset($alleg->alle_type) 
                            && $alleg->alle_type == $defID 
                            && isset($alleg->alle_description)
                            && (!isset($alleg->alle_event_sequence_id) 
                                || intVal($alleg->alle_event_sequence_id) == 0)) {
                            return [$alleg->alle_description];
                        }
                    }
                }
            }
        } elseif ($nID == 269) { // Confirm Submission, Complaint Completed!
            if ($this->sessData->dataSets["complaints"][0]->com_status 
                != $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) {
                return ['Y'];
            }
            return [''];
            
        } elseif ($nID == 2245) { // How Hear?
            if ($GLOBALS["SL"]->REQ->has('from') && trim($GLOBALS["SL"]->REQ->get('from')) != '') {
                $this->sessData->dataSets["tester_beta"][0]->update([
                    'beta_how_hear' => $GLOBALS["SL"]->REQ->get('from')
                ]);
                return [$GLOBALS["SL"]->REQ->get('from')];
            }
        
        // Volunteer Research Departments
        } elseif ($nID == 1285) {
            $this->getOverRow('IA');
            $currNodeSessionData = [];
            if (isset($this->v["overRowIA"]->over_way_sub_email) 
                && intVal($this->v["overRowIA"]->over_way_sub_email) > 0) {
                $currNodeSessionData[] = 'email';
            }
            if (isset($this->v["overRowIA"]->over_way_sub_verbal_phone) 
                && intVal($this->v["overRowIA"]->over_way_sub_verbal_phone) > 0) {
                $currNodeSessionData[] = 'verbal_phone';
            }
            if (isset($this->v["overRowIA"]->over_way_sub_paper_mail) 
                && intVal($this->v["overRowIA"]->over_way_sub_paper_mail) > 0) {
                $currNodeSessionData[] = 'paper_mail';
            }
            if (isset($this->v["overRowIA"]->over_way_sub_paper_in_person) 
                && intVal($this->v["overRowIA"]->over_way_sub_paper_in_person) > 0) {
                $currNodeSessionData[] = 'paper_in_person';
            }
            return $currNodeSessionData;
        } elseif ($nID == 1287) {
            $currNodeSessionData = [];
            if (isset($this->v["overRowIA"]->over_official_form_not_req) 
                && intVal($this->v["overRowIA"]->over_official_form_not_req) > 0) {
                $currNodeSessionData[] = 'official_form_not_req';
            }
            if (isset($this->v["overRowIA"]->over_official_anon) 
                && intVal($this->v["overRowIA"]->over_official_anon) > 0) {
                $currNodeSessionData[] = 'official_anon';
            }
            if (isset($this->v["overRowIA"]->over_way_sub_notary) 
                && intVal($this->v["overRowIA"]->over_way_sub_notary) > 0) {
                $currNodeSessionData[] = 'way_sub_notary';
            }
            if (isset($this->v["overRowIA"]->over_submit_deadline) 
                && intVal($this->v["overRowIA"]->over_submit_deadline) > 0) {
                $currNodeSessionData[] = 'time_limit';
            }
            return $currNodeSessionData;
        } elseif ($nID == 1229) {
            $civOver = $this->getOverRow('civ');
//echo '<pre>'; print_r($civOver); print_r($this->sessData->dataSets["oversight"]); echo '</pre>'; exit;
            if (isset($civOver) 
                && isset($civOver->over_agnc_name) 
                && trim($civOver->over_agnc_name) != '') {
                return ['Y'];
            }
            return ['N'];
        }
        return [];
    }
}