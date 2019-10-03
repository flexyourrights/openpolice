<?php
/**
  * OpenComplaintConditions is a mid-level class which handles custom 
  * processing of conditions.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.0.15
  */
namespace OpenPolice\Controllers;

use DB;
use Auth;
use App\Models\OPPartners;
use App\Models\SLEmailed;
use App\Models\OPPartnerCapac;
use OpenPolice\Controllers\OpenSessDataOverride;

class OpenComplaintConditions extends OpenSessDataOverride
{
    // CUSTOM={OnlyIfNoAllegationsOtherThan:WrongStop,Miranda,PoliceRefuseID]
    protected function checkNodeConditionsCustom($nID, $condition = '')
    {
        $complaint = null;
        if (isset($this->sessData->dataSets["Complaints"]) 
            && isset($this->sessData->dataSets["Complaints"][0])
            && isset($this->sessData->dataSets["Complaints"][0]->ComID)) {
            $complaint = $this->sessData->dataSets["Complaints"][0];
        }

        if ($condition == '#VehicleStop') {
            return $this->condVehicleStop();
            // could be replaced by OR functionality

        } elseif ($condition == '#PartnerIntake') {
            return $this->condPartnerIntake($complaint);

        } elseif ($condition == '#AttorneyIntake') {
            return $this->condAttorneyIntake($complaint);

        } elseif ($condition == '#LawyerInvolved') {
            return $this->condLawyerInvolved($complaint);

        } elseif ($condition == '#NoSexualAllegation') {
            return $this->condNoSexualAllegation();

        } elseif ($condition == '#IncidentHasAddress') {
            return $this->condIncidentHasAddress();

        } elseif ($condition == '#HasArrestOrForce') {
            return $this->condHasArrestOrForce();

        } elseif ($condition == '#CivHasForce') {
            return $this->condCivHasForce();

        } elseif ($condition == '#HasForceHuman') {
            return $this->condHasForceHuman();

        } elseif ($condition == '#HasInjury') {
            return $this->condHasInjury();

        } elseif ($condition == '#MedicalCareNotYou') {
            return $this->condMedicalCareNotYou();

        } elseif ($condition == '#Property') {
            return $this->condProperty();

        } elseif ($condition == '#AllegationsMany') {
            return (($this->hasTooManyAllegations()) ? 1 : 0);

        } elseif ($condition == '#AllegationsNone') {
            return (($this->hasNoAllegations()) ? 1 : 0);

        } elseif ($condition == '#NeedsAudit') {
            return $this->condNeedsAudit();

        } elseif ($condition == '#EmailConfirmSentToday') {
            return $this->condEmailConfirmSentToday();

        } elseif ($condition == '#HasUploads') {
            return $this->complaintHasUploads();

        } elseif ($condition == '#ShowUploads') {
            return $this->complaintShowUploads($complaint);

        } elseif ($condition == '#CanEditUploads') {
            return $this->complaintCanEditUploads();

        } elseif ($condition == '#PrintAnonOnly') {
            return $this->condPrintAnonOnly($complaint);

        } elseif ($condition == '#PrintFullReport') {
            return $this->condPrintFullReport();

        } elseif ($condition == '#PrintSensitiveReport') {
            return $this->condPrintSensitiveReport();

        } elseif ($condition == '#PrintPublishingOnHold') {
            return $this->condPrintPublishingOnHold($complaint);

        } elseif ($condition == '#IsOversightAgency') {
            return $this->condIsOversightAgency();

        } elseif ($condition == '#ComplaintNotIncompleteOrCurrIsStaff') {
            return $this->condComplaintNotIncompleteOrCurrIsStaff($complaint);

        } elseif ($condition == '#PartnerDoesOnlyClinics') {
            return $this->condPartnerDoesOnlyClinics($nID);

        } elseif ($condition == '#PartnerActiveOrTestLink') {
            return $this->condPartnerActiveOrTestLink();

        }
        return -1;
    }
    
    protected function condVehicleStop()
    {
        if (isset($this->sessData->dataSets["Scenes"]) 
            && sizeof($this->sessData->dataSets["Scenes"]) > 0) {
            $scene = $this->sessData->dataSets["Scenes"][0];
            if (isset($scene->ScnIsVehicle) 
                && trim($scene->ScnIsVehicle) == 'Y') {
                return 1;
            }
            if (isset($scene->ScnIsVehicleAccident) 
                && trim($scene->ScnIsVehicleAccident) == 'Y') {
                return 1;
            }
        }
        return 0;
    }

    protected function condPartnerIntake($complaint)
    {
        if (isset($complaint->ComAttID) && intVal($complaint->ComAttID) > 0) {
            return 1;
        }
        return 0;
    }
    
    protected function condAttorneyIntake($complaint)
    {
        if (isset($complaint->ComAttID) && intVal($complaint->ComAttID) > 0) {
            $attDef = $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney');
            $partner = OPPartners::where('PartID', intVal($complaint->ComAttID))
                ->where('PartType', $attDef)
                ->first();
            if ($partner && isset($partner->PartType)) {
                return 1;
            }
        }
        return 0;
    }
    
    protected function condLawyerInvolved($complaint)
    {
        if ((isset($complaint->ComAttorneyHas) 
            && in_array(trim($complaint->ComAttorneyHas), ['Y', '?']))
            || (isset($complaint->ComAttorneyWant) 
            && in_array(trim($complaint->ComAttorneyWant), ['Y']))) {
            return 1;
        }
        if ((isset($complaint->ComAnyoneCharged) 
            && in_array(trim($complaint->ComAnyoneCharged), ['Y', '?']))
            && (!isset($complaint->ComAllChargesResolved) 
                || trim($complaint->ComAllChargesResolved) != 'Y')) {
            return 1;
        }
        if (isset($complaint->ComAnyoneCharged) 
            && trim($complaint->ComAnyoneCharged) == 'N'
            && isset($complaint->ComFileLawsuit) 
            && trim($complaint->ComFileLawsuit) == 'Y') {
            return 1;
        }
        return 0;
    }
    
    protected function condNoSexualAllegation()
    {
        $noSexAlleg = true;
        $types = [
            $GLOBALS["SL"]->def->getID('Allegation Type', 'Sexual Assault'), 
            $GLOBALS["SL"]->def->getID('Allegation Type', 'Sexual Harassment')
        ];
        if (isset($this->sessData->dataSets["Allegations"]) 
            && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
            foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                if (in_array($alleg->AlleType, $types)) {
                    $noSexAlleg = false;
                }
            }
        }
        return ($noSexAlleg) ? 1 : 0;
    }
    
    protected function condIncidentHasAddress()
    {
        if (isset($this->sessData->dataSets["Incidents"]) 
            && isset($this->sessData->dataSets["Incidents"][0]->IncAddress)
            && trim($this->sessData->dataSets["Incidents"][0]->IncAddress) != '') {
            return 1;
        }
        return 0;
    }
    
    protected function condHasArrestOrForce()
    {
        if ($this->sessData->dataHas('Arrests') 
            || $this->sessData->dataHas('Force')) {
            return 1;
        }
        return 0;
    }
    
    protected function condCivHasForce()
    {
        if (isset($GLOBALS["SL"]->closestLoop["itemID"])) {
            return $this->chkCivHasForce($GLOBALS["SL"]->closestLoop["itemID"]);
        }
        return 0;
    }
    
    protected function condHasForceHuman()
    {
        $ret = 0;
        if (isset($this->sessData->dataSets["Civilians"]) 
            && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $civ) {
                if ($civ->CivRole == 'Victim' 
                    && $this->chkCivHasForce($civ->CivID) == 1) {
                    $ret = 1;
                }
            }
        }
        return $ret;
    }
    
    protected function condHasInjury()
    {
        if (isset($this->sessData->dataSets["Civilians"]) 
            && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $civ) {
                if ($civ->CivRole == 'Victim' && isset($civ->CivHasInjury) 
                    && trim($civ->CivHasInjury) == 'Y') {
                    return 1;
                }
            }
        }
        return 0;
    }
    
    protected function condMedicalCareNotYou()
    {
        $civ = $this->sessData->getDataBranchRow('Civilians');
        if ($civ && isset($civ->CivIsCreator) 
            && trim($civ->CivIsCreator) == 'Y') {
            return 0;
        }
        return -1;
    }
    
    protected function condProperty()
    {
        $search = $this->sessData->getChildRow(
            'EventSequence', 
            $GLOBALS["SL"]->closestLoop["itemID"], 
            'Searches'
        );
        if ((isset($search->SrchSeized) && trim($search->SrchSeized) == 'Y')
            || (isset($search->SrchDamage) && trim($search->SrchDamage) == 'Y')) {
            return 1;
        }
        return 0;
    }

    protected function cntAllegations()
    {
        return sizeof($GLOBALS["SL"]->mexplode(',', $this->commaAllegationList()));
    }
    
    protected function hasTooManyAllegations()
    {
        return ($this->cntAllegations() > 5);
    }
    
    protected function hasNoAllegations()
    {
        return ($this->cntAllegations() == 0);
    }
    
    protected function condNeedsAudit()
    {
        if ($this->hasTooManyAllegations() || $this->hasNoAllegations()) {
            return 1;
        }
        return 0;
    }
    
    protected function condEmailConfirmSentToday()
    {
        if (isset($this->v["user"]) && isset($this->v["user"]->id)) {
            $cutoff = date("Y-m-d H:i:s", 
                mktime(date("H"), date("i"), date("s"), 
                    date("n"), date("j")-1, date("Y")));
            $chk = SLEmailed::where('EmailedEmailID', 1)
                ->where('EmailedFromUser', $this->v["user"]->id)
                ->where('created_at', '>', $cutoff)
                ->get();
             if ($chk->isNotEmpty()) {
                 return 1;                
             }
        }
        return 0;
    }
    
    protected function complaintHasUploads()
    {
        $uploads = $this->getUploadsMultNodes(
            $this->cmplntUpNodes, 
            $this->v["isAdmin"], 
            $this->v["isOwner"]
        );
        if ($uploads && sizeof($uploads) > 0) {
            return 1;
        }
        return 0;
    }
    
    protected function complaintShowUploads($complaint)
    {
        if ($this->complaintHasUploads() == 0) {
            return 0;
        }
        if ($this->v["isAdmin"] || $this->v["isOwner"]) {
            return 1;
        }
        if ($this->v["uID"] > 0 && $this->v["user"]->hasRole('oversight')) {
            // needs more strength here
            return 1;
        }
        $pubDef = $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly');
        if ($complaint->ComPrivacy != $pubDef) {
            return 0;
        } // else Full Transparency, but check status first...
        return $this->complaintHasPublishedStatus();
    }
    
    protected function complaintCanEditUploads()
    {
        if ($this->v["isAdmin"]) {
            return 1;
        }
        return -1;
    }
    
    protected function condPrintAnonOnly($complaint)
    {
        $unPub = $this->getUnPublishedStatusList();
        $unPub[] = $GLOBALS["SL"]->def->getID(
            'Complaint Status',  
            'OK to Submit to Oversight'
        );
        if (isset($GLOBALS["SL"]->pageView) 
            && in_array($GLOBALS["SL"]->pageView, ['public', 'pdf'])
            && isset($complaint->ComStatus)
            && in_array($complaint->ComStatus, $unPub)) {
            return 1;
        }
        if (isset($GLOBALS["SL"]->pageView) 
            && in_array($GLOBALS["SL"]->pageView, ['public', 'pdf'])
            && isset($complaint->ComPrivacy) && $complaint->ComPrivacy 
                != $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')) {
            return 1;
        }
        return 0;
    }
    
    protected function condPrintFullReport()
    {
        if ($this->canPrintFullReport()) {
            return 1;
        }
        return 0;
    }
    
    protected function condPrintSensitiveReport()
    {
        if (isset($GLOBALS["SL"]->pageView) 
            && in_array($GLOBALS["SL"]->pageView, ['full', 'full-pdf'])) {
            return 1;
        }
        return 0;
    }
    
    protected function condPrintPublishingOnHold($complaint)
    {
        if ($this->v["isAdmin"] || $this->v["isOwner"]) {
            return 0;
        }
        if (isset($complaint->ComStatus)) {
            $status = $complaint->ComStatus;
            if (in_array($status, $this->getUnPublishedStatusList())) {
                return 1;
            }
        }
        return 0;
    }
    
    protected function condIsOversightAgency()
    {
        if ($this->v["uID"] > 0 && $this->v["user"]->hasRole('oversight')) {
            return 1;
        }
        return 0;
    }
    
    protected function condComplaintNotIncompleteOrCurrIsStaff($complaint)
    {
        $incDef = $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete');
        if (isset($complaint->ComStatus) && $complaint->ComStatus != $incDef) {
            return 1;
        }
        if ($this->v["uID"] > 0 
            && $this->v["user"]->hasRole('administrator|staff')) {
            return 1;
        }
        return 0;
    }
    
    protected function condPartnerDoesOnlyClinics($nID)
    {
        $capDef = $GLOBALS["SL"]->def->getID(
            'Organization Capabilities', 
            'Hosts Clinics for Using OpenPolice.org'
        );
        $caps = [];
        if (isset($this->sessData->dataSets["PartnerCapac"])) {
            $caps = $this->sessData->dataSets["PartnerCapac"];
        } elseif (isset($this->sessData->dataSets["Partners"])
            && isset($this->sessData->dataSets["Partners"][0])) {
            $partID = $this->sessData->dataSets["Partners"][0]->PartID;
            $caps = OPPartnerCapac::where('PrtCapPartID', $partID)
                ->get();
        }
        if (sizeof($caps) == 1 && $caps[0]->PrtCapCapacity == $capDef) {
            return 1;
        }
        return 0;
    }
    
    protected function condPartnerActiveOrTestLink()
    {
        if (isset($this->sessData->dataSets["Partners"])
            && isset($this->sessData->dataSets["Partners"][0])) {
            $partner = $this->sessData->dataSets["Partners"][0];
            if (isset($partner->PartStatus) 
                && intVal($partner->PartStatus) == 1) {
                return 1;
            }
        }
        if ($GLOBALS["SL"]->REQ->has('test')) {
            return 1;
        }
        return 0;
    }

}