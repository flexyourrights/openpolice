<?php
/**
  * OpenComplaintConditions is a mid-level class which handles custom 
  * processing of conditions.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
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
    /**
     * Delegate the conditional checks which are customized from
     * the simpler default Survloop existing thus far.
     *
     * @param  int $nID
     * @param  string $condition
     * @return int
     */
    protected function checkNodeConditionsCustom($nID, $condition = '')
    {
        $complaint = null;
        if (isset($this->sessData->dataSets["complaints"]) 
            && isset($this->sessData->dataSets["complaints"][0])
            && isset($this->sessData->dataSets["complaints"][0]->com_id)) {
            $complaint = $this->sessData->dataSets["complaints"][0];
        }

        if ($condition == '#VehicleStop') {
            return $this->condVehicleStop();
            // could be replaced by OR functionality

        } elseif ($condition == '#IntakeOnlyNotFiling') {
            return $this->condPartnerIntake($complaint)
                || $this->condAttorneyIntake($complaint)
                || $this->condLawyerInvolved($complaint);

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

        } elseif ($condition == '#NotSureAboutDepartment') {
            return $this->condNotSureAboutDepartment();

        } elseif ($condition == '#PrintAnonOnly') {
            return $this->condPrintAnonOnly($complaint);

        } elseif ($condition == '#PrintFullReport') {
            return $this->condPrintFullReport();

        } elseif ($condition == '#PrintIncidentLocation') {
            return $this->condPrintIncidentLocation();

        } elseif ($condition == '#PrintCivilianName') {
            return $this->condPrintCivilianName();

        } elseif ($condition == '#PrintOfficerName') {
            return $this->condPrintOfficerName();

        } elseif ($condition == '#PrintSensitiveReport') {
            return $this->condPrintSensitiveReport();

        } elseif ($condition == '#PrintPublishingOnHold') {
            return $this->condPrintPublishingOnHold($complaint);

        } elseif ($condition == '#IsOversightAgency') {
            return $this->condIsOversightAgency();

        } elseif ($condition == '#IsPartnerStaffAdminOrOwnerOversight') {
            return $this->condIsOversightAgency()
                || $this->isPartnerStaffAdminOrOwner();

        } elseif ($condition == '#ComplaintNotIncompleteOrCurrIsStaff') {
            return $this->condComplaintNotIncompleteOrCurrIsStaff($complaint);

        } elseif ($condition == '#PartnerDoesOnlyClinics') {
            return $this->condPartnerDoesOnlyClinics($nID);

        } elseif ($condition == '#PartnerActiveOrTestLink') {
            return $this->condPartnerActiveOrTestLink();

        }
        return -1;
    }
    
    /**
     * Checks whether or not this incident began 
     * with a vehicle stop.
     *
     * @return int
     */
    protected function condVehicleStop()
    {
        if (isset($this->sessData->dataSets["scenes"]) 
            && sizeof($this->sessData->dataSets["scenes"]) > 0) {
            $scene = $this->sessData->dataSets["scenes"][0];
            if (isset($scene->scn_is_vehicle) 
                && trim($scene->scn_is_vehicle) == 'Y') {
                return 1;
            }
            if (isset($scene->scn_is_vehicle_accident) 
                && trim($scene->scn_is_vehicle_accident) == 'Y') {
                return 1;
            }
        }
        return 0;
    }

    /**
     * Checks whether or not this complaint is 
     * associated with a partner's intake process.
     *
     * @return int
     */
    protected function condPartnerIntake($complaint)
    {
        if (isset($complaint->com_att_id) 
            && intVal($complaint->com_att_id) > 0) {
            return 1;
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint is 
     * associated with an attorney partner's intake process.
     *
     * @return int
     */
    protected function condAttorneyIntake($complaint)
    {
        if (isset($complaint->com_att_id) 
            && intVal($complaint->com_att_id) > 0) {
            $attID = intVal($complaint->com_att_id);
            $attDef = $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney');
            $partner = OPPartners::where('part_id', $attID)
                ->where('part_type', $attDef)
                ->first();
            if ($partner && isset($partner->part_type)) {
                return 1;
            }
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint has or needs
     * a lawyer to be involved.
     *
     * @return int
     */
    protected function condLawyerInvolved($complaint)
    {
        if ((isset($complaint->com_attorney_has) 
            && in_array(trim($complaint->com_attorney_has), ['Y', '?']))
            && (!isset($complaint->com_attorney_oked) 
                || trim($complaint->com_attorney_oked) != 'Y')) {
            return 1;
        }
        if (isset($complaint->com_attorney_want) 
            && in_array(trim($complaint->com_attorney_want), ['Y'])) {
            return 1;
        }
        if ((isset($complaint->com_anyone_charged) 
            && in_array(trim($complaint->com_anyone_charged), ['Y', '?']))
            && (!isset($complaint->com_all_charges_resolved) 
                || trim($complaint->com_all_charges_resolved) != 'Y')) {
            return 1;
        }
        if (isset($complaint->com_anyone_charged) 
            && trim($complaint->com_anyone_charged) == 'N'
            && isset($complaint->com_file_lawsuit) 
            && trim($complaint->com_file_lawsuit) == 'Y') {
            return 1;
        }
        return 0;
    }
    
    /**
     * Checks if no sexual allegations were made 
     * related to this complaint.
     *
     * @return int
     */
    protected function condNoSexualAllegation()
    {
        $noSexAlleg = true;
        $types = [
            $GLOBALS["SL"]->def->getID('Allegation Type', 'Sexual Assault'), 
            $GLOBALS["SL"]->def->getID('Allegation Type', 'Sexual Harassment')
        ];
        if (isset($this->sessData->dataSets["allegations"]) 
            && sizeof($this->sessData->dataSets["allegations"]) > 0) {
            foreach ($this->sessData->dataSets["allegations"] as $alleg) {
                if (in_array($alleg->alle_type, $types)) {
                    $noSexAlleg = false;
                }
            }
        }
        return ($noSexAlleg) ? 1 : 0;
    }
    
    /**
     * Checks whether or not this incident
     * has an associated street address.
     *
     * @return int
     */
    protected function condIncidentHasAddress()
    {
        if (isset($this->sessData->dataSets["incidents"])) {
            $inc = $this->sessData->dataSets["incidents"][0];
            if (isset($inc->inc_address) && trim($inc->inc_address) != '') {
                return 1;
            }
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint includes
     * an arrest of any use of force.
     *
     * @return int
     */
    protected function condHasArrestOrForce()
    {
        if ($this->sessData->dataHas('arrests') 
            || $this->sessData->dataHas('force')) {
            return 1;
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint includes
     * any use of force.
     *
     * @return int
     */
    protected function condCivHasForce()
    {
        if (isset($GLOBALS["SL"]->closestLoop["itemID"])) {
            return $this->chkCivHasForce($GLOBALS["SL"]->closestLoop["itemID"]);
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint includes
     * any use of force on civilians (not animals).
     *
     * @return int
     */
    protected function condHasForceHuman()
    {
        $ret = 0;
        if (isset($this->sessData->dataSets["civilians"]) 
            && sizeof($this->sessData->dataSets["civilians"]) > 0) {
            foreach ($this->sessData->dataSets["civilians"] as $civ) {
                if ($civ->civ_role == 'Victim' 
                    && $this->chkCivHasForce($civ->civ_id) == 1) {
                    $ret = 1;
                }
            }
        }
        return $ret;
    }
    
    /**
     * Checks whether or not this complaint includes
     * any civilian injuries reported.
     *
     * @return int
     */
    protected function condHasInjury()
    {
        if (isset($this->sessData->dataSets["civilians"])) {
            $civs = $this->sessData->dataSets["civilians"];
            if (sizeof($civs) > 0) {
                foreach ($civs as $civ) {
                    if ($civ->civ_role == 'Victim' 
                        && isset($civ->civ_has_injury) 
                        && trim($civ->civ_has_injury) == 'Y') {
                        return 1;
                    }
                }
            }
        }
        return 0;
    }
    
    /**
     * Checks whether or not the current 
     * civilian is the complainant. 
     *
     * @return int
     */
    protected function currCivIsCreator()
    {
        $civ = $this->sessData->getDataBranchRow('civilians');
        return ($civ 
            && isset($civ->civ_is_creator) 
            && trim($civ->civ_is_creator) == 'Y');
    }
    
    /**
     * Checks whether or not the current civilian is not
     * the complainant. This helps avoid impossibly asking 
     * if the user had fatal injuries.
     *
     * @return int
     */
    protected function condMedicalCareNotYou()
    {
        if ($this->currCivIsCreator()) {
            return 0;
        }
        return -1;
    }
    
    /**
     * Checks whether or not the complaint involved
     * property seizure and/or damage.
     *
     * @return int
     */
    protected function condProperty()
    {
        $loopItemID = $GLOBALS["SL"]->closestLoop["itemID"];
        $search = $this->sessData->getChildRow('event_sequence', $loopItemID, 'searches');
        if ((isset($search->srch_seized) && trim($search->srch_seized) == 'Y')
            || (isset($search->srch_damage) && trim($search->srch_damage) == 'Y')) {
            return 1;
        }
        return 0;
    }

    /**
     * Retrieves a count of how many allegations are
     * included in this complaint.
     *
     * @return int
     */
    protected function cntAllegations()
    {
        $allegs = $GLOBALS["SL"]->mexplode(',', $this->commaAllegationList());
        return sizeof($allegs);
    }
    
    /**
     * Checks whether or not this complaint has 
     * "too many" allegations selected.
     *
     * @return boolean
     */
    protected function hasTooManyAllegations()
    {
        return ($this->cntAllegations() > 5);
    }
    
    /**
     * Checks whether or not this complaint has 
     * no allegations selected.
     *
     * @return boolean
     */
    protected function hasNoAllegations()
    {
        $this->simpleAllegationList();
        return (sizeof($this->allegations) == 0);
        //return ($this->cntAllegations() == 0);
    }
    
    /**
     * Checks whether or not this complaint passes an audit.
     * If not they should be alerted to fix things.
     *
     * @return int
     */
    protected function condNeedsAudit()
    {
        if ($this->hasTooManyAllegations() || $this->hasNoAllegations()) {
            return 1;
        }
        return 0;
    }
    
    /**
     * Checks whether or not this user has had a
     * confirmationed email send to them today.
     *
     * @return int
     */
    protected function condEmailConfirmSentToday()
    {
        if (isset($this->v["user"]) && isset($this->v["user"]->id)) {
            $cutoff = mktime(date("H"), date("i"), date("s"), 
                date("n"), date("j")-1, date("Y"));
            $cutoff = date("Y-m-d H:i:s", $cutoff);
            $chk = SLEmailed::where('emailed_email_id', 1)
                ->where('emailed_from_user', $this->v["user"]->id)
                ->where('created_at', '>', $cutoff)
                ->get();
             if ($chk->isNotEmpty()) {
                 return 1;                
             }
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint has any uploaded
     * photos, documents, or linked videos.
     *
     * @return int
     */
    protected function complaintHasUploads()
    {
        $uploads = $this->getUploadsMultNodes(
            $this->cmplntUpNodes, 
            $this->isStaffOrAdmin(), 
            $this->v["isOwner"]
        );
        if ($uploads && sizeof($uploads) > 0) {
            return 1;
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint's uploads
     * should be shown to the current user.
     *
     * @param  App\Models\OPComplaints $complaint
     * @return int
     */
    protected function complaintShowUploads($complaint = null)
    {
        if ((!$complaint || $complaint === null)
            && isset($this->sessData->dataSets["complaints"]) 
            && isset($this->sessData->dataSets["complaints"][0])) {
            $complaint = $this->sessData->dataSets["complaints"][0];
        }
        if ($this->complaintHasUploads() == 0) {
            return 0;
        }
        if ($this->isStaffOrAdmin()
            || (isset($this->v["isOwner"]) && $this->v["isOwner"])) {
            if (!$GLOBALS["SL"]->REQ->has('publicView')) {
                return 1;
            }
        }
        if ($this->v["uID"] > 0 && $this->v["user"]->hasRole('oversight')) {
            // needs more strength here

            return 1;
        }
        if ($this->canPrintFullReportByRecordSpecs($complaint)) {
            return 1;
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint's uploads
     * can be editted by the current user.
     *
     * @return int
     */
    protected function complaintCanEditUploads()
    {
        if ($this->isStaffOrAdmin()) {
            return 1;
        }
        return -1;
    }
    
    /**
     * Checks whether or not this complaint should only
     * be printed with anonymized data.
     *
     * @param  App\Models\OPComplaints $complaint
     * @return int
     */
    protected function condPrintAnonOnly($complaint)
    {
        if (!$this->isTypeComplaint($complaint)) {
            return 1;
        }
        $unPub = $this->getUnPublishedStatusList();
        $types = [ 'public', 'pdf' ];
        if (isset($GLOBALS["SL"]->pageView) 
            && in_array($GLOBALS["SL"]->pageView, $types)
            && isset($complaint->com_status)
            && in_array($complaint->com_status, $unPub)) {
            return 1;
        }
        if (isset($GLOBALS["SL"]->pageView) 
            && in_array($GLOBALS["SL"]->pageView, $types)
            && !$this->isPublic()) {
            return 1;
        }
        return 0;
    }

    /**
     * Checks whether or not the incident record indicates 
     * that the location should be printed.
     *
     * @return int
     */
    protected function canPrintIncidentLocation()
    {
        return (isset($this->sessData->dataSets["incidents"]) 
            && isset($this->sessData->dataSets["incidents"][0])
            && isset($this->sessData->dataSets["incidents"][0]->inc_public)
            && intVal($this->sessData->dataSets["incidents"][0]->inc_public) == 1
            && in_array($this->sessData->dataSets["complaints"][0]->com_status, 
                [200, 201, 202, 203, 204]));
    }

    /**
     * Checks whether or not the incident location
     * should be printed for the current page load.
     *
     * @return int
     */
    protected function condPrintIncidentLocation()
    {
        if ($this->canPrintIncidentLocation()) {
            return 1;
        }
        if ($this->isStaffOrAdmin()
            || (isset($this->v["isOwner"]) && $this->v["isOwner"])) {
            if (!$GLOBALS["SL"]->REQ->has('publicView')) {
                return 1;
            }
        }
        return 0;
    }

    /**
     * Checks whether or not the current Civilian's name
     * should be printed for the current page load.
     *
     * @return int
     */
    protected function condPrintCivilianName()
    {
        return $this->condPrintName('user');
    }

    /**
     * Checks whether or not the current Officer's name
     * should be printed for the current page load.
     *
     * @return int
     */
    protected function condPrintOfficerName()
    {
        return $this->condPrintName('officer');
    }

    /**
     * Checks whether or not the current Officer or Civilian 
     * name should be printed for the current page load.
     *
     * @return int
     */
    protected function condPrintName($type = 'user')
    {
        if ($this->isStaffOrAdmin()
            || (isset($this->v["isOwner"]) && $this->v["isOwner"])) {
            if (!$GLOBALS["SL"]->REQ->has('publicView')) {
                return 1;
            }
        }
        if (isset($this->sessData->dataSets["complaints"])
            && isset($this->sessData->dataSets["complaints"][0])) {
            $com = $this->sessData->dataSets["complaints"][0];
            if (isset($com->{ 'com_publish_' . $type . '_name' })
                && intVal($com->{ 'com_publish_' . $type . '_name' }) == 1
                && in_array($com->com_status, [200, 201, 203, 204])) {
                if (!$GLOBALS["SL"]->REQ->has('publicView')) {
                    return 1;
                }
            }
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint should only
     * be printed in full — though still not sensitive data.
     *
     * @return int
     */
    protected function condPrintFullReport()
    {
        if ($this->canPrintFullReport()) {
            if (!$GLOBALS["SL"]->REQ->has('publicView')) {
                return 1;
            }
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint should only
     * be printed in full including sensitive data.
     *
     * @return int
     */
    protected function condPrintSensitiveReport()
    {
        if (isset($GLOBALS["SL"]->pageView) 
            && in_array($GLOBALS["SL"]->pageView, ['full', 'full-pdf'])) {
            if (!$GLOBALS["SL"]->REQ->has('publicView')) {
                return 1;
            }
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint's 
     * publishing is on hold.
     *
     * @param  App\Models\OPComplaints $complaint
     * @return int
     */
    protected function condPrintPublishingOnHold($complaint = null)
    {
        if ((!$complaint || $complaint === null)
            && isset($this->sessData->dataSets["complaints"]) 
            && isset($this->sessData->dataSets["complaints"][0])) {
            $complaint = $this->sessData->dataSets["complaints"][0];
        }
        if ($this->isStaffOrAdmin() || $this->v["isOwner"]) {
            return 0;
        }
        if (!$this->isTypeComplaint($complaint)) {
            return 1;
        }
        if (isset($complaint->com_status)) {
            $status = $complaint->com_status;
            if (in_array($status, $this->getUnPublishedStatusList())) {
                return 1;
            }
        }
        return 0;
    }
    
    /**
     * Checks whether or not the current user is a
     * partner investigative agency.
     *
     * @return int
     */
    protected function condIsOversightAgency()
    {
        if ($this->v["uID"] > 0 
            && $this->v["user"]->hasRole('oversight')) {
            return 1;
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complaint is complete,
     * or the current user has staff permissions of higher.
     *
     * @param  App\Models\OPComplaints $complaint
     * @return int
     */
    protected function condComplaintNotIncompleteOrCurrIsStaff($complaint)
    {
        $defSet = 'Complaint Status';
        $incDef = $GLOBALS["SL"]->def->getID($defSet, 'Incomplete');
        if (isset($complaint->com_status) 
            && intVal($complaint->com_status) != $incDef) {
            return 1;
        }
        if ($this->v["uID"] > 0 && $this->isStaffOrAdmin()) {
            return 1;
        }
        return 0;
    }
    
    /**
     * Checks whether or not this partner only helps users 
     * of OpenPolice.org via in-person clinics.
     *
     * @param  int $nID
     * @return int
     */
    protected function condPartnerDoesOnlyClinics($nID)
    {
        $capDef = $GLOBALS["SL"]->def->getID(
            'Organization Capabilities', 
            'Hosts Clinics for Using OpenPolice.org'
        );
        $caps = [];
        if (isset($this->sessData->dataSets["partner_capac"])) {
            $caps = $this->sessData->dataSets["partner_capac"];
        } elseif (isset($this->sessData->dataSets["partners"])
            && isset($this->sessData->dataSets["partners"][0])) {
            $partID = $this->sessData->dataSets["partners"][0]->part_id;
            $caps = OPPartnerCapac::where('prt_cap_part_id', $partID)
                ->get();
        }
        if (sizeof($caps) == 1 
            && $caps[0]->prt_cap_capacity == $capDef) {
            return 1;
        }
        return 0;
    }
    
    /**
     * Checks whether or not this partner 
     * is actively working with OpenPolice.org, 
     * or the current URL has the ?test=1 parameter.
     *
     * @return int
     */
    protected function condPartnerActiveOrTestLink()
    {
        if (isset($this->sessData->dataSets["partners"])
            && isset($this->sessData->dataSets["partners"][0])) {
            $partner = $this->sessData->dataSets["partners"][0];
            if (isset($partner->part_status) 
                && intVal($partner->part_status) == 1) {
                return 1;
            }
        }
        if ($GLOBALS["SL"]->REQ->has('test')) {
            return 1;
        }
        return 0;
    }
    
    /**
     * Checks whether or not this complainant is unsure 
     * about one or more department involved in this incident.
     *
     * @return int
     */
    protected function condNotSureAboutDepartment()
    {
        if (isset($this->sessData->dataSets["departments"]) 
            && sizeof($this->sessData->dataSets["departments"]) > 0) {
            foreach ($this->sessData->dataSets["departments"] as $dept) {
                if (isset($dept->dept_name)
                    && strpos($dept->dept_name, 'Not sure') !== false) {
                    return 1;
                }
            }
        }
        return 0;
    }


}