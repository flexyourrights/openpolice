<?php
namespace OpenPolice\Controllers;

use Auth;
use Storage\App\Models\SLNodeSavesPage;
use OpenPolice\Controllers\OpenComplaintPrints;

class OpenSessDataOverride extends OpenComplaintPrints
{
    // returns an array of overrides for ($currNodeSessionData, ???... 
    protected function printNodeSessDataOverride($nID = -3, $tmpSubTier = [], $nIDtxt = '', $currNodeSessionData = '')
    {
        if (empty($this->sessData->dataSets)) {
            return [];
        }
        if ($nID == 28) { // Complainant's Role
            if (isset($this->sessData->dataSets["Civilians"])) {
                return [trim($this->sessData->dataSets["Civilians"][0]->CivRole)];
            }
            return [];
        } elseif ($nID == 47) { // Complainant Recorded Incident?
            if (isset($this->sessData->dataSets["Civilians"])) {
                return [trim($this->sessData->dataSets["Civilians"][0]->CivCameraRecord)];
            }
            return [];
        } elseif ($nID == 19) { // Would you like to provide the GPS location?
            if (isset($this->sessData->dataSets["Incidents"]) 
                && intVal($this->sessData->dataSets["Incidents"][0]->IncAddressLat) != 0 
                || intVal($this->sessData->dataSets["Incidents"][0]->IncAddressLng) != 0) {
                return ['Yes'];
            } else {
                return [];
            }
        } elseif ($nID == 39) {
            if ($currNodeSessionData == '') {
                $user = Auth::user();
                if ($user && isset($user->email)) {
                    return [$user->email];
                }
                return [''];
            }
        } elseif ($nID == 671) { // Officers Used Profanity?
            $currVals = [];
            foreach ($this->sessData->dataSets["Officers"] as $i => $off) {
                if (isset($off->OffUsedProfanity) && $off->OffUsedProfanity == 'Y') {
                    $currVals[] = $off->getKey();
                }
            }
            return [';' . implode(';', $currVals) . ';'];
        } elseif ($nID == 237) {
            $ret = [];
            $civs = $this->sessData->getLoopRows('Victims');
            if ($civs && sizeof($civs) > 0) {
                foreach ($civs as $i => $civ) {
                    if ($civ->CivRole == 'Victim' && trim($civ->CivGivenCitation) == 'Y') {
                        $ret[] = $civ->CivID;
                    }
                }
            }
            return $ret;
        } elseif ($nID == 674) { // Officer Used Profanity?
            return [trim($this->sessData->dataSets["Officers"][0]->OffUsedProfanity)];
        } elseif ($nID == 670) { // Victims Used Profanity?
            $currVals = [];
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if ($civ->CivUsedProfanity == 'Y') {
                    $currVals[] = $civ->getKey();
                }
            }
            return [';' . implode(';', $currVals) . ';'];
        } elseif ($nID == 676) { // Victim Used Profanity?
            $civInd = $this->getFirstVictimCivInd();
            if ($civInd >= 0) {
                return [trim($this->sessData->dataSets["Civilians"][$civInd]->CivUsedProfanity)];
            }
        } elseif (in_array($nID, [732, 736, 733])) { // Gold Stops & Searches, Multiple Victims
            if (!isset($this->v["firstTimeGoGoldDeets"])) {
                $chk = SLNodeSavesPage::where('PageSaveSession', $this->coreID)
                    ->where('PageSaveNode', 484)
                    ->first();
                $this->v["firstTimeGoGoldDeets"] = (!$chk || !isset($chk->PageSaveID));
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
                } elseif (!isset($this->v["firstTimeGoGoldDeets"]) || !$this->v["firstTimeGoGoldDeets"]
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
            if (isset($this->sessData->dataSets["Force"]) && sizeof($this->sessData->dataSets["Force"]) > 0) {
                foreach ($this->sessData->dataSets["Force"] as $force) {
                    if (isset($force->ForType) && intVal($force->ForType) > 0 
                        && (!isset($force->ForAgainstAnimal) || trim($force->ForAgainstAnimal) != 'Y')) {
                        $ret[] = $force->ForType;
                    }
                }
            }
            return $ret;
        } elseif ($nID == 2043) {
            $force = $this->sessData->getDataBranchRow('Force');
            if ($force && isset($force->ForEventSequenceID) && intVal($force->ForEventSequenceID) > 0) {
                return $this->getLinkedToEvent('Civilian', $force->ForEventSequenceID);
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
            if ($animalsForce->isNotEmpty() && isset($animalsForce[0]->ForAnimalDesc)) {
                return [$animalsForce[0]->ForAnimalDesc];
            }
        } elseif ($nID == 744) { // Use of Force against Animal: Sub-types
            $ret = [];
            $animalsForce = $this->getCivAnimalForces();
            if ($animalsForce && sizeof($animalsForce) > 0) {
                foreach ($animalsForce as $force) {
                    $ret[] = $force->ForType;
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
                switch ($nID) {
                    case 334: $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Wrongful Search'); break;
                    case 409: $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Wrongful Property Seizure');break;
                    case 356: $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Unreasonable Force'); break;
                    case 384: $defID = $GLOBALS["SL"]->def->getID('Allegation Type', 'Wrongful Arrest'); break;
                }
                if (isset($this->sessData->dataSets["Allegations"]) 
                    && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
                    foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                        if (isset($alleg->AlleType) && $alleg->AlleType == $defID && isset($alleg->AlleDescription)
                            && (!isset($alleg->AlleEventSequenceID) || intVal($alleg->AlleEventSequenceID) == 0)) {
                            return [$alleg->AlleDescription];
                        }
                    }
                }
            }
        } elseif ($nID == 269) { // Confirm Submission, Complaint Completed!
            return [(($this->sessData->dataSets["Complaints"][0]->ComStatus 
                != $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) ? 'Y' : '')];
            
        } elseif ($nID == 2245) { // How Hear?
            if ($GLOBALS["SL"]->REQ->has('from') && trim($GLOBALS["SL"]->REQ->get('from')) != '') {
                $this->sessData->dataSets["TesterBeta"][0]->update([
                    'BetaHowHear' => $GLOBALS["SL"]->REQ->get('from')
                    ]);
                return [$GLOBALS["SL"]->REQ->get('from')];
            }
        
        // Volunteer Research Departments
        } elseif ($nID == 1285) {
            $this->getOverRow('IA');
            $currNodeSessionData = [];
            if (isset($this->v["overRowIA"]->OverWaySubEmail) && intVal($this->v["overRowIA"]->OverWaySubEmail) > 0) {
                $currNodeSessionData[] = 'Email';
            }
            if (isset($this->v["overRowIA"]->OverWaySubVerbalPhone) 
                && intVal($this->v["overRowIA"]->OverWaySubVerbalPhone) > 0) {
                $currNodeSessionData[] = 'VerbalPhone';
            }
            if (isset($this->v["overRowIA"]->OverWaySubPaperMail) 
                && intVal($this->v["overRowIA"]->OverWaySubPaperMail) > 0) {
                $currNodeSessionData[] = 'PaperMail';
            }
            if (isset($this->v["overRowIA"]->OverWaySubPaperInPerson) 
                && intVal($this->v["overRowIA"]->OverWaySubPaperInPerson) > 0) {
                $currNodeSessionData[] = 'PaperInPerson';
            }
            return $currNodeSessionData;
        } elseif ($nID == 1287) {
            $currNodeSessionData = [];
            if (isset($this->v["overRowIA"]->OverOfficialFormNotReq) 
                && intVal($this->v["overRowIA"]->OverOfficialFormNotReq) > 0) {
                $currNodeSessionData[] = 'OfficialFormNotReq';
            }
            if (isset($this->v["overRowIA"]->OverOfficialAnon) 
                && intVal($this->v["overRowIA"]->OverOfficialAnon) > 0) {
                $currNodeSessionData[] = 'OfficialAnon';
            }
            if (isset($this->v["overRowIA"]->OverWaySubNotary) 
                && intVal($this->v["overRowIA"]->OverWaySubNotary) > 0) {
                $currNodeSessionData[] = 'Notary';
            }
            if (isset($this->v["overRowIA"]->OverSubmitDeadline) 
                && intVal($this->v["overRowIA"]->OverSubmitDeadline) > 0) {
                $currNodeSessionData[] = 'TimeLimit';
            }
            return $currNodeSessionData;
        } elseif ($nID == 1229) {
            $civOver = $this->getOverRow('Civ');
//echo '<pre>'; print_r($civOver); print_r($this->sessData->dataSets["Oversight"]); echo '</pre>'; exit;
            if (isset($civOver) && isset($civOver->OverAgncName) && trim($civOver->OverAgncName) != '') {
                return ['Y'];
            }
            return ['N'];
        }
        return [];
    }
}