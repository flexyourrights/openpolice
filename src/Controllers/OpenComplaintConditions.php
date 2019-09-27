<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use App\Models\OPPartners;
use App\Models\SLEmailed;
use OpenPolice\Controllers\OpenSessDataOverride;

class OpenComplaintConditions extends OpenSessDataOverride
{
    // CUSTOM={OnlyIfNoAllegationsOtherThan:WrongStop,Miranda,PoliceRefuseID]
    protected function checkNodeConditionsCustom($nID, $condition = '')
    {
        if ($condition == '#VehicleStop') { // could be replaced by OR functionality
            if (isset($this->sessData->dataSets["Scenes"]) 
                && sizeof($this->sessData->dataSets["Scenes"]) > 0) {
                if (isset($this->sessData->dataSets["Scenes"][0]->ScnIsVehicle) 
                    && trim($this->sessData->dataSets["Scenes"][0]->ScnIsVehicle) == 'Y') {
                    return 1;
                }
                if (isset($this->sessData->dataSets["Scenes"][0]->ScnIsVehicleAccident) 
                    && trim($this->sessData->dataSets["Scenes"][0]->ScnIsVehicleAccident) == 'Y') {
                    return 1;
                }
            }
            return 0;
        } elseif ($condition == '#PartnerIntake') {
            if (isset($this->sessData->dataSets["Complaints"][0]->ComAttID)
                && intVal($this->sessData->dataSets["Complaints"][0]->ComAttID) > 0) {
                return 1;
            }
            return 0;
        } elseif ($condition == '#AttorneyIntake') {
            if (isset($this->sessData->dataSets["Complaints"][0]->ComAttID)
                && intVal($this->sessData->dataSets["Complaints"][0]->ComAttID) > 0) {
                $partner = OPPartners::find(intVal($this->sessData->dataSets["Complaints"][0]->ComAttID));
                if ($partner && isset($partner->PartType) 
                    && $partner->PartType 
                        == $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney')) {
                    return 1;
                }
            }
            return 0;
        } elseif ($condition == '#LawyerInvolved') {
            if ((isset($this->sessData->dataSets["Complaints"][0]->ComAttorneyHas) 
                && in_array(trim($this->sessData->dataSets["Complaints"][0]->ComAttorneyHas), ['Y', '?']))
                || (isset($this->sessData->dataSets["Complaints"][0]->ComAttorneyWant) 
                && in_array(trim($this->sessData->dataSets["Complaints"][0]->ComAttorneyWant), ['Y']))) {
                return 1;
            }
            if ((isset($this->sessData->dataSets["Complaints"][0]->ComAnyoneCharged) 
                && in_array(trim($this->sessData->dataSets["Complaints"][0]->ComAnyoneCharged), ['Y', '?']))
                && (!isset($this->sessData->dataSets["Complaints"][0]->ComAllChargesResolved) 
                    || trim($this->sessData->dataSets["Complaints"][0]->ComAllChargesResolved) != 'Y')) {
                return 1;
            }
            if (isset($this->sessData->dataSets["Complaints"][0]->ComAnyoneCharged) 
                && trim($this->sessData->dataSets["Complaints"][0]->ComAnyoneCharged) == 'N'
                && isset($this->sessData->dataSets["Complaints"][0]->ComFileLawsuit) 
                && trim($this->sessData->dataSets["Complaints"][0]->ComFileLawsuit) == 'Y') {
                return 1;
            }
            return 0;
        } elseif ($condition == '#NoSexualAllegation') {
            $noSexAlleg = true;
            if (isset($this->sessData->dataSets["Allegations"]) 
                && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
                foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                    if (in_array($alleg->AlleType, [
                        $GLOBALS["SL"]->def->getID('Allegation Type', 'Sexual Assault'), 
                        $GLOBALS["SL"]->def->getID('Allegation Type', 'Sexual Harassment')
                        ])) {
                        $noSexAlleg = false;
                    }
                }
            }
            return ($noSexAlleg) ? 1 : 0;
        } elseif ($condition == '#IncidentHasAddress') {
            if (isset($this->sessData->dataSets["Incidents"]) 
                && isset($this->sessData->dataSets["Incidents"][0]->IncAddress)
                && trim($this->sessData->dataSets["Incidents"][0]->IncAddress) != '') {
                return 1;
            } else {
                return 0;
            }
        } elseif ($condition == '#HasArrestOrForce') {
            if ($this->sessData->dataHas('Arrests') || $this->sessData->dataHas('Force')) {
                return 1;
            } else {
                return 0;
            }
        } elseif ($condition == '#CivHasForce') {
            if (isset($GLOBALS["SL"]->closestLoop["itemID"])) {
                return $this->chkCivHasForce($GLOBALS["SL"]->closestLoop["itemID"]);
            }
            return 0;
        } elseif ($condition == '#HasForceHuman') {
            $ret = 0;
            if (isset($this->sessData->dataSets["Civilians"]) && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
                foreach ($this->sessData->dataSets["Civilians"] as $civ) {
                    if ($civ->CivRole == 'Victim' && $this->chkCivHasForce($civ->CivID) == 1) {
                        $ret = 1;
                    }
                }
            }
            return $ret;
        } elseif ($condition == '#HasInjury') {
            if (isset($this->sessData->dataSets["Civilians"]) && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
                foreach ($this->sessData->dataSets["Civilians"] as $civ) {
                    if ($civ->CivRole == 'Victim' && isset($civ->CivHasInjury) && trim($civ->CivHasInjury) == 'Y') {
                        return 1;
                    }
                }
            }
            return 0;
        } elseif ($condition == '#MedicalCareNotYou') {
            $civ = $this->sessData->getDataBranchRow('Civilians');
            if ($civ && isset($civ->CivIsCreator) && trim($civ->CivIsCreator) == 'Y') {
                return 0;
            }
        } elseif ($condition == '#Property') {
            $search = $this->sessData->getChildRow('EventSequence', $GLOBALS["SL"]->closestLoop["itemID"], 'Searches');
            if ((isset($search->SrchSeized) && trim($search->SrchSeized) == 'Y')
                || (isset($search->SrchDamage) && trim($search->SrchDamage) == 'Y')) {
                return 1;
            } else {
                return 0;
            }
        } elseif ($condition == '#AllegationsMany') {
            return (($this->hasTooManyAllegations()) ? 1 : 0);
        } elseif ($condition == '#AllegationsNone') {
            return (($this->hasNoAllegations()) ? 1 : 0);
        } elseif ($condition == '#NeedsAudit') {
            return (($this->hasTooManyAllegations() || $this->hasNoAllegations()) ? 1 : 0);
        } elseif ($condition == '#EmailConfirmSentToday') {
            if (isset($this->v["user"]) && isset($this->v["user"]->id)) {
                $cutoff = date("Y-m-d H:i:s", 
                    mktime(date("H"), date("i"), date("s"), date("n"), date("j")-1, date("Y")));
                $chk = SLEmailed::where('EmailedEmailID', 1)
                    ->where('EmailedFromUser', $this->v["user"]->id)
                    ->where('created_at', '>', $cutoff)
                    ->get();
                 if ($chk->isNotEmpty()) {
                     return 1;                
                 }
            }
            return 0;
        } elseif ($condition == '#HasUploads') {
            return $this->complaintHasUploads();
        } elseif ($condition == '#ShowUploads') {
            if ($this->complaintHasUploads() == 0) {
                return 0;
            }
            if ($this->v["isAdmin"] || $this->v["isOwner"]) {
                return 1;
            }
            if ($this->v["uID"] > 0 && $this->v["user"]->hasRole('oversight')) { // needs more strength here
                return 1;
            }
            if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy 
                    != $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')) {
                return 0;
            } // else Full Transparency, but check status first...
            return $this->complaintHasPublishedStatus();
        } elseif ($condition == '#CanEditUploads') {
            if ($this->v["isAdmin"]) {
                return 1;
            }
        } elseif ($condition == '#PrintAnonOnly') {
            $unPub = $this->getUnPublishedStatusList();
            $unPub[] = $GLOBALS["SL"]->def->getID('Complaint Status',  'OK to Submit to Oversight');
            if (isset($GLOBALS["SL"]->pageView) && in_array($GLOBALS["SL"]->pageView, ['public', 'pdf'])
                && isset($this->sessData->dataSets["Complaints"][0]->ComStatus)
                && in_array($this->sessData->dataSets["Complaints"][0]->ComStatus, $unPub)) {
                return 1;
            }
            if (isset($GLOBALS["SL"]->pageView) && in_array($GLOBALS["SL"]->pageView, ['public', 'pdf'])
                && isset($this->sessData->dataSets["Complaints"][0]->ComPrivacy)
                && $this->sessData->dataSets["Complaints"][0]->ComPrivacy 
                    != $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')) {
                return 1;
            }
            return 0;
        } elseif ($condition == '#PrintFullReport') {
            if ($this->canPrintFullReport()) {
                return 1;
            }
            return 0;
        } elseif ($condition == '#PrintSensitiveReport') {
            if (isset($GLOBALS["SL"]->pageView) && in_array($GLOBALS["SL"]->pageView, ['full', 'full-pdf'])) {
                return 1;
            }
            return 0;
        } elseif ($condition == '#PrintPublishingOnHold') {
            if ($this->v["isAdmin"] || $this->v["isOwner"]) {
                return 0;
            }
            if (isset($this->sessData->dataSets["Complaints"][0]->ComStatus)
                && in_array($this->sessData->dataSets["Complaints"][0]->ComStatus, $this->getUnPublishedStatusList())) {
                return 1;
            }
            return 0;
        } elseif ($condition == '#IsOversightAgency') {
            return (($this->v["uID"] > 0 && $this->v["user"]->hasRole('oversight')) ? 1 : 0);
        } elseif ($condition == '#ComplaintNotIncompleteOrCurrIsStaff') {
            if ((isset($this->sessData->dataSets["Complaints"][0]->ComStatus)
                && $this->sessData->dataSets["Complaints"][0]->ComStatus
                    != $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete'))
                || ($this->v["uID"] > 0 && $this->v["user"]->hasRole('administrator|staff'))) {
                return 1;
            }
            return 0;
        }
        return -1;
    }
    
    protected function complaintHasUploads()
    {
        $uploads = $this->getUploadsMultNodes($this->cmplntUpNodes, $this->v["isAdmin"], $this->v["isOwner"]);
        if ($uploads && sizeof($uploads) > 0) {
            return 1;
        }
        return 0;
    }
    
    protected function hasTooManyAllegations()
    {
        return (sizeof($GLOBALS["SL"]->mexplode(',', $this->commaAllegationList())) > 5);
    }
    
    protected function hasNoAllegations()
    {
        return (sizeof($GLOBALS["SL"]->mexplode(',', $this->commaAllegationList())) == 0);
    }
    
    protected function printAllegAudit()
    {
        return $this->commaAllegationList(true);
    }
    
}