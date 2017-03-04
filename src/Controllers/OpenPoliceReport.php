<?php
namespace OpenPolice\Controllers;

use App\Models\OPBodyParts;

use OpenPolice\Controllers\OpenPolice;

class OpenPoliceReport extends OpenPolice
{
    
    public $classExtension  = 'OpenPoliceReport';
    public $treeID          = 1;
    protected $isReport     = true;
    public $hideDisclaim    = false;
    
    protected $subjects     = [];
    protected $witnesses    = [];
    protected $whoBlocks    = [];
    protected $whatHaps     = [];
    protected $charges      = [];
    protected $injuries     = [];
    
    public function printFullReport($reportType = '', $isAdmin = false)
    {
        if (in_array($this->sessData->dataSets["Complaints"][0]->ComStatus, [
            $GLOBALS["SL"]->getDefID('Complaint Status', 'Hold'), 
            $GLOBALS["SL"]->getDefID('Complaint Status', 'Pending Attorney')
            ])) {
            return '<h1>Sorry, this complaint is not public.</h1>';
        }
        
        $ComSlug = $this->sessData->dataSets["Complaints"][0]->ComSlug;
        if (trim($ComSlug) == '') {
            $ComSlug = '/' . $this->sessData->dataSets["Complaints"][0]->ComID;
        }
        
        $this->v["isOwner"] = false;
        $this->v["view"] = 'Public';
        if ($this->v["user"] && isset($this->v["user"]->id)) {
            if ($this->v["user"]->id == $this->sessData->dataSets["Civilians"][0]->CivUserID) {
                $this->v["isOwner"] = true;
                if (!$GLOBALS["SL"]->REQ->has('publicView') 
                    && ($reportType != 'Public' || $reportType == 'Investigate')) {
                    $this->v["view"] = 'Investigate';
                }
            } elseif ($this->v["user"]->hasRole('administrator|staff')) {
                $this->v["view"] = 'Investigate';
            }
        }
        $this->v["civNames"] = $this->v["offNames"] = [];
        $this->whatHaps = $this->getEventSequence();
        $this->findCharges();
        $this->groupInjuries();
        
        $metaDeptList = "";
        if (isset($this->sessData->dataSets["Departments"]) && sizeof($this->sessData->dataSets["Departments"]) > 0) {
            foreach ($this->sessData->dataSets["Departments"] as $dept) {
                $metaDeptList = ", ".$dept->DeptName;
            }
        }
        
        $this->whoBlocks = [
            "Subjects"  => [], 
            "Witnesses" => [], 
            "Officers"  => []
        ];
        if ($this->sessData->dataSets["Civilians"][0]->CivRole == 'Helper') {
            $this->whoBlocks["Subjects"][] = $this->printCivilian($this->sessData->dataSets["Civilians"][0], 0);
        }
        $this->subjects = $this->sessData->getLoopRows('Victims');
        $this->witnesses = $this->sessData->getLoopRows('Witnesses');
        if (sizeof($this->subjects) > 0) {
            foreach ($this->subjects as $ind => $civ) {
                $this->getCivReportName($civ->CivID, $ind, 'Subject');
                $this->whoBlocks["Subjects"][] = $this->printCivilian($civ, $ind);
            }
        }
        if (sizeof($this->witnesses) > 0) {
            foreach ($this->witnesses as $ind => $civ) {
                $this->getCivReportName($civ->CivID, $ind, 'Witness');
                $this->whoBlocks["Witnesses"][] = $this->printCivilian($civ, $ind);
            }
        }
        if (isset($this->sessData->dataSets["Officers"]) && sizeof($this->sessData->dataSets["Officers"]) > 0) {
            foreach ($this->sessData->dataSets["Officers"] as $ind => $off) {
                $this->getOffReportName($off, $ind);
                $this->whoBlocks["Officers"][] = $this->printOfficer($off, $ind);
            }
        }
        
        $this->printwhatHaps = '<div class="reportSectHead2">What Happened...</div>';
        if ($this->isGold()) {
            if (sizeof($this->whatHaps) > 0) {
                foreach ($this->whatHaps as $incEve) {
                    $this->printwhatHaps .= $this->printHap($incEve);
                }
            }
        }
        
        $complainantNameTop = $this->getCivReportName($this->sessData->dataSets["Civilians"][0]->CivID);
        if ($complainantNameTop == $this->getNameTopAnon()) $complainantNameTop = 'Anonymous';
        
        if (!isset($GLOBALS["meta"])) $GLOBALS["meta"] = [];
        if (trim($this->sessData->dataSets["Complaints"][0]->ComHeadline) != '') {
            $GLOBALS["meta"]["title"] = $this->sessData->dataSets["Complaints"][0]->ComHeadline 
                . " | Open Police Complaint #".$this->coreID;
        } else {
            $GLOBALS["meta"]["title"] =  trim(substr($metaDeptList, 1)) 
                . " | Open Police Complaint #".$this->coreID."";
        }
        $GLOBALS["meta"]["desc"] = "Open Police Complaint #".$this->coreID . $metaDeptList . ", " 
            . date('n/j/Y', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeStart)) 
            . " - " . substr($this->sessData->dataSets["Complaints"][0]->ComSummary, 0, 140) . " ...";
        $GLOBALS["meta"]["keywords"] = $metaDeptList . ", " . $this->commaAllegationList();
        $GLOBALS["meta"]["img"] = '';
        
        $comDate = date("n/j/Y", strtotime($this->sessData->dataSets["Complaints"][0]->updated_at));
        if (trim($this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted) != '' 
            && $this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted != '0000-00-00 00:00:00') {
            $comDate = date("n/j/Y", strtotime($this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted));
        }

        return view('vendor.openpolice.complaint-report', [
            "isOwner"              => $this->v["isOwner"], 
            "view"                 => $this->v["view"], 
            "complaintID"          => $this->coreID, 
            "comDate"              => $comDate, 
            "sessData"             => $this->sessData->dataSets, 
            "ComSlug"              => $ComSlug, 
            "complainantName"      => $complainantNameTop, 
            "civNames"             => $this->v["civNames"], 
            "civBlocks"            => $this->printCivBlocks(), 
            "offBlocks"            => $this->printOffBlocks(), 
            "printwhatHaps"        => $this->printwhatHaps, 
            "fullAllegations"      => $this->printFullAllegs(),
            "injuries"             => $this->injuries, 
            "hasMedicalCare"       => $this->hasMedicalCare, 
            "basicAllegationList"  => $this->basicAllegationList(true),
            "basicAllegationListF" => $this->commaAllegationList(),
            "featureImg"           => $this->getFeatureImg(),
            "hideDisclaim"         => $this->hideDisclaim,
            "isAdmin"              => $isAdmin
        ]);
    }
    
    protected function getCivReportName($civID, $ind = 0, $type = 'Subject', $prsn = array())
    {
        if (!isset($this->v["civNames"][$civID]) || $this->v["civNames"][$civID] == '') {
            if (sizeof($prsn) == 0) list($prsn, $phys, $vehic) = $this->queuePeopleSubsets($civID);
            $name = '';
            if ( $civID == $this->sessData->dataSets["Civilians"][0]->CivID 
                && (trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) == ''
                || $this->sessData->dataSets["Complaints"][0]->ComPrivacy == 306) ) {
                $name = $this->getNameTopAnon();
            } elseif (trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) != '' 
                && ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 304 
                || $this->v["view"] == 'Investigate')) {
                if (trim($prsn->PrsnNickname) != '') {
                    $name = trim($prsn->PrsnNickname);
                } else {
                    $name = '<span style="color: #2b3493;" title="This complainant wanted to publicly provide their '
                        . 'name.">' . $prsn->PrsnNameFirst . ' ' . $prsn->PrsnNameLast 
                        . '</span>'; // ' . $prsn->PrsnNameMiddle . ' 
                }
            }
            /* if ($type == 'Subject' && $this->sessData->dataSets["Civilians"][0]->CivID != $civID
                && $this->sessData->dataSets["Civilians"][0]["CivRole"] == 'Victim') $ind++;
            if ($type == 'Witness' && $this->sessData->dataSets["Civilians"][0]->CivID != $civID
                && $this->sessData->dataSets["Civilians"][0]["CivRole"] == 'Witness') $ind++; */
            $this->v["civNames"][$civID] = $type . ' #' . (1+$ind) . ': ' . $name;
        }
        return $this->v["civNames"][$civID];
    }
    
    protected function getOffReportName($off, $ind = 0, $prsn = array())
    {
        if (!isset($this->v["offNames"][$off->OffID]) || trim($this->v["offNames"][$off->OffID]) == '') {
            if (sizeof($prsn) == 0) list($prsn, $phys, $vehic) = $this->queuePeopleSubsets($off->OffID, 'Officers');
            $name = ' ';
            if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 304 || $this->v["view"] == 'Investigate') {
                if (trim($prsn->PrsnNickname) != '') {
                    $name = trim($prsn->PrsnNickname);
                } else {
                    $name = trim($prsn->PrsnNameFirst . ' ' . $prsn->PrsnNameMiddle . ' ' . $prsn->PrsnNameLast);
                    if (trim($name) == '' && trim($off->OffBadgeNumber) != '' && trim($off->OffBadgeNumber) != '0') {
                        $name = 'Badge #' . $off->OffBadgeNumber;
                    }
                }
            }
            $this->v["offNames"][$off->OffID] = $name;
        }
        return $this->v["offNames"][$off->OffID];
    }
    
    protected function findCharges() 
    {
        if (sizeof($this->whatHaps) > 0) {
            $this->charges = [ "Arrests" => [], "Citations" => [], "Warnings" => [] ];
            foreach ($this->whatHaps as $incEve) {
                if (isset($this->sessData->dataSets["Charges"]) && sizeof($this->sessData->dataSets["Charges"]) > 0 
                    && sizeof($incEve["Civilians"]) > 0) {
                    if ($incEve["EveType"] == 'Stops') {
                        foreach ($this->sessData->dataSets["Charges"] as $charge) {
                            if ($charge->ChrgStopID == $incEve["Event"]->StopID) {
                                foreach ($incEve["Civilians"] as $civID) {
                                    if (!isset($this->charges["Citations"][$civID])) {
                                        $this->charges["Citations"][$civID] = [];
                                    }
                                    $chargeName = $GLOBALS["SL"]->getDefValue('Citation Charges', $charge->ChrgCharges);
                                    if (trim($chargeName) == '') {
                                        $chargeName = $GLOBALS["SL"]->getDefValue('Citation Charges Pedestrian', 
                                            $charge->ChrgCharges);
                                    }
                                    $this->charges["Citations"][$civID][] = $chargeName;
                                }
                            }
                        }
                        if (isset($incEve["Event"]->StopChargesOther) 
                            && trim($incEve["Event"]->StopChargesOther) != '') {
                            foreach ($incEve["Civilians"] as $civID) {
                                if (!isset($this->charges["Citations"][$civID])) {
                                    $this->charges["Citations"][$civID] = [];
                                }
                                $this->charges["Citations"][$civID][] = $incEve["Event"]->StopChargesOther;
                            }
                        }
                        if (isset($incEve["Event"]->StopGivenWarning) 
                            && trim($incEve["Event"]->StopGivenWarning) == 'Y') {
                            foreach ($incEve["Civilians"] as $civID)  {
                                if (!isset($this->charges["Warnings"][$civID])) {
                                    $this->charges["Warnings"][$civID] = [];
                                }
                                $this->charges["Warnings"][$civID][] = 'Yes';
                            }
                        }
                    }
                    elseif ($incEve["EveType"] == 'Arrests') {
                        foreach ($incEve["Civilians"] as $civID) {
                            $this->charges["Arrests"][$civID] = [];
                        }
                        foreach ($this->sessData->dataSets["Charges"] as $charge) {
                            if ($charge->ChrgArrestID == $incEve["Event"]->ArstID) {
                                foreach ($incEve["Civilians"] as $civID) {
                                    $this->charges["Arrests"][$civID][] 
                                        = $GLOBALS["SL"]->getDefValue('Arrest Charges', $charge->ChrgCharges);
                                }
                            }
                        }
                        if (isset($incEve["Event"]->ArstChargesOther) 
                            && trim($incEve["Event"]->StopChargesOther) != '') {
                            foreach ($incEve["Arrests"] as $civID) {
                                $this->charges["Arrests"][$civID][] = $incEve["Event"]->StopChargesOther;
                            }
                        }
                        if ($incEve["Event"]->ArstNoChargesFiled 
                            == $GLOBALS["SL"]->getDefID('No Charges Filed', 'ALL Charges Were Dropped Before Release')) {
                            foreach ($incEve["Civilians"] as $civID) {
                                $this->charges["Arrests"][$civID][] = 'All charges dropped before release';
                            }
                        } elseif ($incEve["Event"]->ArstNoChargesFiled 
                            == $GLOBALS["SL"]->getDefID('No Charges Filed', 'No Charges Were Ever Filed')) {
                            foreach ($incEve["Civilians"] as $civID) {
                                $this->charges["Arrests"][$civID][] = 'No charges were ever filed';
                            }
                        }
                    }
                }
            }
        }
        return true;
    }
    
    protected function getFeatureImg()
    {
        return ''; // to-do
    }
    
    protected function queuePeopleSubsets($id, $type = 'Civilians')
    {
        $prsn = $this->sessData->getChildRow($type, $id, 'PersonContact');
        $phys = $this->sessData->getChildRow($type, $id, 'PhysicalDesc');
        $vehic = $this->sessData->getChildRow($type, $id, 'Vehicles');
        return [$prsn, $phys, $vehic];
    }
    
    protected function printCivilian($civ, $ind = 0)
    {
        $deets = [];
        list($prsn, $phys, $vehic) = $this->queuePeopleSubsets($civ->CivID);
        if (trim($phys->PhysRace) != '') $deets[] = ['Race', $GLOBALS["SL"]->getDefValue('Races', $phys->PhysRace)];
        if (trim($phys->PhysGender) != '') $deets[] = ['Gender', $this->printMF($phys->PhysGender)];
        if ($civ->CivIsCreator == 'Y') { 
            if (trim($prsn->PrsnBirthday) != '' && trim($prsn->PrsnBirthday) != '0000-00-00' 
                && trim($prsn->PrsnBirthday) != '1970-01-01') {
                if ($this->v["view"] == 'Investigate') {
                    $deets[] = ['Birthday', date("n/j/Y", strtotime($prsn->PrsnBirthday))];
                }
                else $deets[] = ['Birthday', date("Y", strtotime($prsn->PrsnBirthday))];
            }
        } elseif (trim($phys->PhysAge) != '') {
            $deets[] = ['Age Range', $GLOBALS["SL"]->getDefValue('Age Ranges', $phys->PhysAge)];
        }
        if (trim($civ->CivOccupation) != '') $deets[] = ['Occupation', $civ->CivOccupation];
        if (intVal($phys->PhysHeight) > 0) $deets[] = ['Height', $this->printHeight($phys->PhysHeight)];
        if (intVal($phys->PhysBodyType) > 0) $deets[] 
            = ['Body Type', $GLOBALS["SL"]->getDefValue('Body Types', $phys->PhysBodyType)];
        if (trim($phys->PhysHairDescription) != '') $deets[] = ['Hair', $phys->PhysHairDescription];
        if (trim($phys->PhysHairFacialDesc) != '') $deets[] = ['Facial Hair', $phys->PhysHairFacialDesc];
        if (trim($phys->PhysEyes) != '') $deets[] = ['Eyes', $phys->PhysEyes];
        if (trim($phys->PhysDistinguishingMarksDesc) != '') $deets[] 
            = ['Distinguishing Marks', $phys->PhysDistinguishingMarksDesc];
        if (trim($phys->PhysVoiceDesc) != '') $deets[] = ['Voice', $phys->PhysVoiceDesc];
        if (trim($phys->PhysClothesDesc) != '') $deets[] = ['Clothes', $phys->PhysClothesDesc];
        if (trim($phys->PhysDisabilitiesDesc) != '') $deets[] = ['Disabilities', $phys->PhysDisabilitiesDesc];
        if (sizeof($vehic) > 0) {
            if (intVal($vehic->VehicTransportation) > 0) $deets[] = ['Transportation', 
                $GLOBALS["SL"]->getDefValue('Transportation Civilian', $vehic->VehicTransportation)];
            if (trim($vehic->VehicVehicleMake) != '') $deets[] = ['Make', $vehic->VehicVehicleMake];
            if (trim($vehic->VehicVehicleModel) != '') $deets[] = ['Model', $vehic->VehicVehicleModel];
            if (trim($vehic->VehicVehicleDesc) != '') $deets[] = ['Description', $vehic->VehicVehicleDesc];
            if (trim($vehic->VehicVehicleLicence) != '') $deets[] = ['License', $vehic->VehicVehicleLicence];
        }
        if (trim($civ->CivCameraRecord) != '') $deets[] = ['Recorded Incident', $this->printYN($civ->CivCameraRecord)];
        if (isset($this->injuries[$civ->CivID])) {
            $injTxt = '';
            foreach ($this->injuries[$civ->CivID][1] as $i => $inj) {
                $injTxt .= ', ' . $GLOBALS["SL"]->getDefValue('Injury Types', $inj[0]->InjType) . ', '
                    . ((sizeof($inj[1]) > 0) ? strtolower(implode(', ', $inj[1])) . ', ' : '')
                    . ((trim($inj[0]->InjDescription) != '') ? $inj[0]->InjDescription : '') 
                    . ((isset($this->injuries[$civ->CivID][1][($i+1)]) > 1) ? ';' : '');
            }
            if (sizeof($this->injuries[$civ->CivID][1]) == 1) $deets[] = ['<span>Injury:</span> ' . substr($injTxt, 1)];
            else $deets[] = ['<span>Injuries:</span> ' . substr($injTxt, 1)];
        }
        
        $contactInfo = $this->printCivContact($prsn, $civ->CivID);
        $name = $this->getCivReportName($civ->CivID);
        if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 306 
            && $civ->CivID == $this->sessData->dataSets["Civilians"][0]->CivID) {
            $name .= ' <span class="nobld">(Anonymous)</span>';
        }
        $ret = '';
        if (sizeof($this->charges) > 0) {
            foreach (['Arrests', 'Citations', 'Warnings'] as $type) {
                if (sizeof($this->charges[$type]) > 0) {
                    foreach ($this->charges[$type] as $civID => $charges) {
                        if ($civID == $civ->CivID) {
                            if ($type == 'Warnings') {
                                $ret .= '<div class="pB10 f16">Written Warning</div>';
                            } else {
                                if ($type == 'Arrests') {
                                    $ret .= '<span>Arrest Charges:</span><div class="pL10 pB10 f16">';
                                } else {
                                    $ret .= '<span>Citation Charges:</span><div class="pL10 pB10 f16">';
                                }
                                foreach ($charges as $charge) $ret .= $charge . '<br />';
                                $ret .= '</div>';
                            }
                        }
                    }
                }
            }
        }
        return $this->printReportDeetsBlock($deets, $name) . $ret;
    }
    
    protected function printCivContact($prsn, $civID)
    {
        if ($civID == $this->sessData->dataSets["Civilians"][0]->CivID 
            && $this->sessData->dataSets["Complaints"][0]->ComPrivacy == 306) {
            return ' ';
        }
        if ($this->v["view"] == 'Public') {
            $info = '';
            if (trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) != '' 
                && $this->sessData->dataSets["Complaints"][0]->ComPrivacy != 304) {
                $info .= ', Name';
            }
            if (trim($prsn->PrsnAddress) != '')   $info .= ', Address';
            if (trim($prsn->PrsnPhoneHome) != '') $info .= ', Phone Number'; 
            if (trim($prsn->PrsnEmail) != '')     $info .= ', Email'; 
            if (trim($prsn->PrsnFacebook) != '')  $info .= ', Facebook';
            if (($civID != $this->sessData->dataSets["Civilians"][0]->CivID 
                || $this->sessData->dataSets["Complaints"][0]->ComPrivacy != 306) && trim($info) != '') {
                return '<i class="gryA">Not public: ' . substr($info, 1) . '</i>';
            }
            return ' ';
        } elseif ($this->v["view"] == 'Investigate') {
            $info = '';
            if (trim($prsn->PrsnEmail) != '')        $info .= $prsn->PrsnEmail . '<br />';
            if (trim($prsn->PrsnFacebook) != '')     $info .= $prsn->PrsnFacebook . '<br />';
            if (trim($prsn->PrsnPhoneHome) != '')    $info .= $prsn->PrsnPhoneHome . '<br />';
            if (trim($prsn->PrsnAddress) != '')      $info .= $prsn->PrsnAddress . '<br />';
            if (trim($prsn->PrsnAddress2) != '')     $info .= $prsn->PrsnAddress2 . '<br />';
            if (trim($prsn->PrsnAddressCity) != '')  $info .= $prsn->PrsnAddressCity . ', ';
            if (trim($prsn->PrsnAddressState) != '') $info .= $prsn->PrsnAddressState . ' ';
            if (trim($prsn->PrsnAddressZip) != '')   $info .= $prsn->PrsnAddressZip . ' ';
            return $info;
        }
        return '';
    }

    protected function printOfficer($off, $ind)
    {
        $deets = [];
        list($prsn, $phys, $vehic) = $this->queuePeopleSubsets($off->OffID, 'Officers');
        $name = $this->getOffReportName($off);
        $name = 'Officer #' . (1+$ind) . ': ' . $name . '';
        
        if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 304 || $this->v["view"] == 'Investigate') {
            // then name or badge number (if provided) should appear in official name
            if (trim($prsn->PrsnNameFirst . ' ' . $prsn->PrsnNameMiddle . ' ' . $prsn->PrsnNameLast) != ''
                && trim($off->OffBadgeNumber) != '' && intVal($off->OffBadgeNumber) > 0) {
                $deets[] = ['Badge Number', $off->OffBadgeNumber];
            }
        } else {
            $info = ((trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) != '') ? ', Name' : '')
                . ((trim($off->OffBadgeNumber) != '' && intVal($off->OffBadgeNumber) > 0) ? ', Badge Number' : '');
            if (trim($info) != '') $deets[] = ['<span>Not public:</span>' . substr($info, 1)];
        }
        
        if (sizeof($this->sessData->dataSets["Departments"]) > 1 && intVal($off->OffDeptID) > 0) $deets[] 
            = ['Department', $this->getDeptNameByID($off->OffDeptID)];
        if (trim($off->OffRole) != '') $deets[] = ['Role', $off->OffRole];
        //if (more than one department) 
        //if (trim($off->OffDeptID) != '') $deets[] = ['Department', $off->OffDeptID;
        if (trim($off->OffPrecinct) != '') $deets[] = ['Precint', $off->OffPrecinct];
        if (trim($off->OffOfficerRank) != '') $deets[] = ['Rank', $off->OffOfficerRank];
        if (trim($phys->PhysRace) != '') $deets[] = ['Race', $GLOBALS["SL"]->getDefValue('Races', $phys->PhysRace)];
        if (trim($phys->PhysGender) != '') $deets[] = ['Gender', $this->printMF($phys->PhysGender)];
        if (intVal($phys->PhysHeight) > 0) $deets[] = ['Height', $this->printHeight($phys->PhysHeight)];
        if (intVal($phys->PhysBodyType) > 0) $deets[] 
            = ['Body Type', $GLOBALS["SL"]->getDefValue('Body Types', $phys->PhysBodyType)];
        if (trim($phys->PhysHairDescription) != '') $deets[] = ['Hair', $phys->PhysHairDescription];
        if (trim($phys->PhysHairFacialDesc) != '') $deets[] = ['Facial Hair', $phys->PhysHairFacialDesc];
        if (trim($phys->PhysEyes) != '') $deets[] = ['Eyes', $phys->PhysEyes];
        if (trim($phys->PhysDistinguishingMarksDesc) != '') $deets[] 
            = ['Distinguishing Marks', $phys->PhysDistinguishingMarksDesc];
        if (trim($phys->PhysVoiceDesc) != '') $deets[] = ['Voice', $phys->PhysVoiceDesc];
        if (trim($phys->PhysClothesDesc) != '') $deets[] = ['Uniform', $phys->PhysClothesDesc];
        if (trim($off->OffBodyCam) != '') $deets[] = ['Had Body Camera', $this->printYN($off->OffBodyCam)];
        if (sizeof($vehic) > 0) {
            if (intVal($vehic->VehicTransportation) > 0) $deets[] = ['Transportation', 
                $GLOBALS["SL"]->getDefValue('Transportation Officer', $vehic->VehicTransportation)];
            if (trim($vehic->VehicVehicleDesc) != '') $deets[] = ['Vehicle Description', $vehic->VehicVehicleDesc];
            if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 304 || $this->v["view"] == 'Investigate') {
                if (trim($vehic->VehicVehicleNumber) != '') $deets[] = ['Vehicle Number', $vehic->VehicVehicleNumber];
                if (trim($vehic->VehicVehicleLicence) != '') $deets[] = ['License Plate', $vehic->VehicVehicleLicence];
            } else {
                if (trim($vehic->VehicVehicleNumber) != '') $deets[] 
                    = ['Vehicle Number', '<i class="gry6">Not public</i>'];
                if (trim($vehic->VehicVehicleLicence) != '') $deets[] 
                    = ['License Plate', '<i class="gry6">Not public</i>'];
            }
        }
        return $this->printReportDeetsBlock($deets, $name);
    }
    
    protected function printCivBlocks()
    {
        $ret = '';
        if (sizeof($this->whoBlocks["Subjects"]) > 0) {
            foreach ($this->whoBlocks["Subjects"] as $who) $ret .= $who;
        }
        if (sizeof($this->whoBlocks["Witnesses"]) > 0) {
            foreach ($this->whoBlocks["Witnesses"] as $who) $ret .= $who;
        }
        return $ret;
    }
    
    protected function printOffBlocks()
    {
        $ret = '';
        if (sizeof($this->whoBlocks["Officers"]) > 0) {
            foreach ($this->whoBlocks["Officers"] as $who) $ret .= $who;
        }
        return $ret;
    }
    

    
    protected function printHap($incEve)
    {
        $deets = [];
        switch ($incEve["EveType"]) {
            case 'Stops':     $deets = $this->printHapStop($incEve["Event"]);   break;
            case 'Searches':  $deets = $this->printHapSearch($incEve["Event"]); break;
            case 'Force':     $deets = $this->printHapForce($incEve["Event"]);  break;
            case 'Arrests':   $deets = $this->printHapArrest($incEve["Event"]); break;
        }
        $allegs = $this->eventAllegations($incEve["EveID"]);
        /* $orders = $this->getEventOrders($incEve["EveID"], $incEve["EveType"]);
        if (sizeof($orders) > 0) {
            if ($incEve["EveType"] == 'Force' && (trim($incEve["Event"]->ForOrdersBeforeForce) != '' 
                || trim($incEve["Event"]->ForOrdersSubjectResponse) != '')) {
                if (trim($incEve["Event"]->ForOrdersBeforeForce) != '') {
                    $deets[] = ['Orders Before Force', $incEve["Event"]->ForOrdersBeforeForce;
                }
                if (trim($incEve["Event"]->ForOrdersSubjectResponse) != '') {
                    $deets[] = ['Subject Response To Orders', '' 
                        . $incEve["Event"]->ForOrdersSubjectResponse;
                }
            }
            foreach ($orders as $i => $ord) {
                $deets[] = $this->printOrder($ord);
                if (in_array(trim($ord->OrdTroubleUnderYN), array('Y', '?'))) {
                    $deets[] = ['Trouble Hearing Order?</span></td><td>' 
                        . $this->printYN($ord->OrdTroubleUnderYN)
                        . ((trim($ord->OrdTroubleUnderstading) != '') ? '<br />' . $ord->OrdTroubleUnderstading : '');
                }
            }
        } */
        $ret = $this->printReportDeetsBlock($deets, $this->reportEventTitle($incEve));
        if (isset($allegs) && sizeof($allegs) > 0) {
            foreach ($allegs as $alleg) {
                $ret .= '<b>' . $alleg[1] . '</b><br />';
                if (!in_array($alleg[1], ['Miranda Rights', 'Officer Refused To Provide ID'])) {
                    $ret .= '<div class="pL10"><span class="mR5">Why?</span> ' . $alleg[2] . '</div>';
                }
            }
        }
        return $ret;
    }
    
    // similar to printEventSequenceLine($eveSeq), but with Report-driven names
    protected function reportEventTitle($eveSeq)
    {
        //if ($this->debugOn) { echo 'printEventSequenceLine:<pre>'; print_r($eveSeq); echo '</pre>'; }
        if (!isset($eveSeq["EveType"]) || sizeof($eveSeq["Event"]) <= 0) return '';
        $ret = '';
        if ($eveSeq["EveType"] == 'Force' && isset($eveSeq["Event"]->ForType) 
            && trim($eveSeq["Event"]->ForType) != '') {
            $ret .= (($eveSeq["Event"]->ForType == 'Other') ? $eveSeq["Event"]->ForTypeOther 
                            : $GLOBALS["SL"]->getDefValue('Force Type', $eveSeq["Event"]->ForType)) . ' Force ';
        }
        elseif (isset($this->eventTypeLabel[$eveSeq["EveType"]])) {
            $ret .= $this->eventTypeLabel[$eveSeq["EveType"]] . ' ';
        }
        if ($eveSeq["EveType"] == 'Force' && $eveSeq["Event"]->ForAgainstAnimal == 'Y') {
            $ret .= '<span class="gry9">on</span> ' . $eveSeq["Event"]->ForAnimalDesc;
        } else {
            $civNames = $offNames = '';
            if ($this->moreThan1Victim()) {
                foreach ($eveSeq["Civilians"] as $civ) $civNames .= ', '.$this->v["civNames"][$civ];
                if (trim($civNames) != '') {
                    $ret .= '<div class="pL10 f18"><span class="gry9 mR5">' . (($eveSeq["EveType"] == 'Force') 
                        ? 'on ' : 'of ') . '</span>' . substr($civNames, 1) . '</div>';
                }
            }
            if ($this->moreThan1Officer()) {
                foreach ($eveSeq["Officers"] as $off) $offNames .= ', '.$this->v["offNames"][$off];
                if (trim($offNames) != '') {
                    $ret .= '<div class="pL10 f16"><span class="gry9 mR5">by</span> ' 
                        . substr($offNames, 1) . '</div>';
                }
            }
        }
        $ret .= '';
        return $ret;
    }
    
    protected function printHapStop($event)
    {              
        $deets = [];
        $StopReasons = '';
        if (isset($this->sessData->dataSets["StopReasons"]) && sizeof($this->sessData->dataSets["StopReasons"]) > 0) {
            foreach ($this->sessData->dataSets["StopReasons"] as $reas) {
                if ($event->StopID == $reas->StopReasStopID) {
                    $stop = $GLOBALS["SL"]->getDefValue('Reason for Pedestrian Stop', $reas->StopReasReason);
                    if ($stop == '') {
                        $stop = $GLOBALS["SL"]->getDefValue('Reason for Vehicle Stop', $reas->StopReasReason);
                    }
                    $StopReasons .= ', ' . $stop;
                }
            }
        }
        if (trim($StopReasons) != '') $deets[] = ['<span>Reason Given:</span> ' . substr($StopReasons, 1)];
        if (trim($event->StopStatedReasonDesc) != '') $deets[] 
            = ['<span>Reason Description:</span> ' . $event->StopStatedReasonDesc];
        if (trim($event->StopSubjectAskedToLeave) != '') $deets[] 
            = ['Subject Ask To Leave?', $this->printYN($event->StopSubjectAskedToLeave)];
        if (trim($event->StopSubjectStatementsDesc) != '') $deets[] 
            = ['Subject Asked', $event->StopSubjectStatementsDesc];
        if (trim($event->StopEnterPrivateProperty) != '') $deets[] 
            = ['Enter Private Property?', $this->printYN($event->StopEnterPrivateProperty)];
        if (trim($event->StopEnterPrivatePropertyDesc) != '') $deets[] 
            = ['<span>Entry Description:</span> ' . $event->StopEnterPrivatePropertyDesc];
        if (trim($event->StopPermissionEnter) != '') $deets[] 
            = ['Officer Request Entry Permission?,', $this->printYN($event->StopPermissionEnter)];
        if (trim($event->StopPermissionEnterGranted) != '') $deets[] 
            = ['Subject Grant Permission?', $this->printYN($event->StopPermissionEnterGranted)];
        if (trim($event->StopRequestID) != '') $deets[] 
            = ['Officer Request Subject ID?', $this->printYN($event->StopRequestID)];
        if (trim($event->StopRefuseID) != '') $deets[] 
            = ['Subject Refuse To Give ID?', $this->printYN($event->StopRefuseID)];
        if (trim($event->StopRequestOfficerID) != '') $deets[] 
            = ['Subject Request Officer ID?', $this->printYN($event->StopRequestOfficerID)];
        if (trim($event->StopOfficerRefuseID) != '') $deets[] 
            = ['Officer Refuse To Give ID?', $this->printYN($event->StopOfficerRefuseID)];
        if (trim($event->StopSubjectFrisk) != '') $deets[] 
            = ['Subject Frisked?', $this->printYN($event->StopSubjectFrisk)];
        if (trim($event->StopSubjectHandcuffed) != '') $deets[] 
            = ['Subject Handcuffed?', $this->printYN($event->StopSubjectHandcuffed)];
        if (intVal($event->StopSubjectHandcuffInjury) > 0) {
            $desc = '';
            /*
            if (sizeof($this->sessData->dataSets["Injuries"]["Handcuff"]) > 0) {
                foreach ($this->sessData->dataSets["Injuries"]["Handcuff"] as $inj) {
                    if ($inj->InjID == $event->StopSubjectHandcuffInjury && trim($inj->InjDescription) != '') {
                        $desc .= ', ' . $inj->InjDescription;
                    }
                }
            }
            */
            $deets[] = ['Handcuff Injury?', 'Yes' . $desc];
        }
        return $deets;
    }
    
    protected function printHapSearch($event)
    {
        $deets = [];
        if (trim($event->SrchStatedReason) != '') $deets[] = ['Reason Given', $this->printYN($event->SrchStatedReason)];
        if (trim($event->SrchStatedReasonDesc) != '') $deets[] 
            = ['<span>Reason Description:</span> ' . $event->SrchStatedReasonDesc];
        if (trim($event->SrchOfficerRequest) != '') $deets[] 
            = ['Officer Request Permission?', $this->printYN($event->SrchOfficerRequest)];
        if (trim($event->SrchOfficerRequestDesc) != '') $deets[] 
            = ['<span>Request Description:</span> ' . $event->SrchOfficerRequestDesc];
        if (trim($event->SrchSubjectConsent) != '') $deets[] 
            = ['Subject Consent?', $this->printYN($event->SrchSubjectConsent)];
        if (trim($event->SrchSubjectSay) != '') $deets[] 
            = ['<span>Consent Description:</span> ' . $event->SrchSubjectSay];
        if (trim($event->SrchOfficerThreats) != '') $deets[] 
            = ['Threats/Lies for Consent?', $this->printYN($event->SrchOfficerThreats)];
        if (trim($event->SrchOfficerThreatsDesc) != '') $deets[] 
            = ['<span>Threats/Lies Description:</span> ' . $event->SrchOfficerThreatsDesc];
        if (trim($event->SrchStrip) != '') $deets[] = ['Strip Searched?', $this->printYN($event->SrchStrip)];
        if (trim($event->SrchStripSearchDesc) != '') $deets[] 
            = ['<span>Strip Description:</span> ' . $event->SrchStripSearchDesc];
        if (trim($event->SrchK9sniff) != '') $deets[] = ['K-9 sniff?', $this->printYN($event->SrchK9sniff)];
        if (trim($event->SrchContrabandDiscovered) != '') $deets[] 
            = ['Contraband Discovered?', $this->printYN($event->SrchContrabandDiscovered)];
        $SearchContra = '';
        if (isset($this->sessData->dataSets["SearchContra"]) 
            && sizeof($this->sessData->dataSets["SearchContra"]) > 0) {
            foreach ($this->sessData->dataSets["SearchContra"] as $srch) {
                if ($event->SrchID == $srch->SrchConSearchID) {
                    $SearchContra .= ', ' . $GLOBALS["SL"]->getDefValue('Contraband Types', $srch->SrchConType);
                }
            }
        }
        if (trim($SearchContra) != '') $deets[] = ['<span>Contraband Types:</span> ' . substr($SearchContra, 1)];
        //if (trim($event->SrchContrabandTypes) != '') $deets[] 
        //    = ['Contraband Types', $this->printValList($event->SrchContrabandTypes)];
        if (trim($event->SrchOfficerWarrant) != '') $deets[] 
            = ['Have A Warrant?', $this->printYN($event->SrchOfficerWarrant)];
        if (trim($event->SrchOfficerWarrantSay) != '') $deets[] 
            = ['<span>Warrant Description:</span> ' . $event->SrchOfficerWarrantSay];
        if (trim($event->SrchSeized) != '') $deets[] = ['Property Seized?', $this->printYN($event->SrchSeized)];
        $SearchSeized = '';
        if (isset($this->sessData->dataSets["SearchSeize"]) && sizeof($this->sessData->dataSets["SearchSeize"]) > 0) {
            foreach ($this->sessData->dataSets["SearchSeize"] as $srch) {
                if ($event->SrchID == $srch->SrchSeizSearchID) {
                    $SearchSeized .= ', ' . $GLOBALS["SL"]->getDefValue('Property Seized Types', $srch->SrchSeizType);
                }
            }                                                                                  
        }
        if (trim($SearchSeized) != '') $deets[] = ['<span>Seized Types:</span> ' . substr($SearchSeized, 1)];
        if (trim($event->SrchSeizedDesc) != '') $deets[] 
            = ['<span>Seized Description:</span> ' . $event->SrchSeizedDesc];
        if (trim($event->SrchDamage) != '') $deets[] = ['Property Damaged?', $this->printYN($event->SrchDamage)];
        if (trim($event->SrchDamageDesc) != '') $deets[] 
            = ['<span>Damaged Description:</span> ' . $event->SrchDamageDesc];
        return $deets;
    }
    
    protected function printHapForce($event)
    {
        $deets = [];
        $forceType = $GLOBALS["SL"]->getDefValue('Force Type', $event->ForType);
        if ($forceType == 'Other') $deets[] = ['Type of Force', $event->ForTypeOther];
        if (in_array($forceType, ['Control Hold', 'Body Weapons', 'Takedown'])) {
            $subType = '';
            if (sizeof($this->sessData->dataSets["ForceSubType"]) > 0) {
                foreach ($this->sessData->dataSets["ForceSubType"] as $forceSubType) {
                    if ($event->ForID == $forceSubType->ForceSubForceID) {
                        $subType .= ', ' 
                            . $GLOBALS["SL"]->getDefValue('Force Type - ' . $forceType, $forceSubType->ForceSubType);
                    }
                }
            }
            if (trim($subType) != '') $deets[] = [$forceType, substr($subType, 1)];
        }
        if (trim($event->ForGunAmmoType) != '') $deets[] 
            = ['Gun Ammo', $GLOBALS["SL"]->getDefValue('Gun Ammo Types', $event->ForGunAmmoType)];
        if (trim($event->ForGunDesc) != '') $deets[] = ['Gun Description', $event->ForGunDesc];
        if (trim($event->ForHowManyTimes) != '') $deets[] = ['# of Times Struck?', $event->ForHowManyTimes];
        $BodyParts = '';
        if (isset($this->sessData->dataSets["BodyParts"]) && sizeof($this->sessData->dataSets["BodyParts"]) > 0) {
            foreach ($this->sessData->dataSets["BodyParts"] as $part) {
                if ($event->ForID == $part->BodyForceID) {
                    $BodyParts .= ', ' . $GLOBALS["SL"]->getDefValue('Body Part', $part->BodyPart);
                }
            }
        }
        if (trim($BodyParts) != '') $deets[] = ['Body Parts', substr($BodyParts, 1)];
        if (trim($event->ForWhileHandcuffed) != '') $deets[] 
            = ['Struck While Handcuffed?', $this->printYN($event->ForWhileHandcuffed)];
        if (trim($event->ForWhileHeldDown) != '') $deets[] 
            = ['Struck While Held Down?', $this->printYN($event->ForWhileHeldDown)];
        return $deets;
    }
    
    protected function printHapArrest($event)
    {
        $deets = [];
        if (trim($event->ArstStatedReason) != '') $deets[] = ['Reason Given', $this->printYN($event->ArstStatedReason)];
        if (trim($event->ArstStatedReasonDesc) != '') $deets[] 
            = ['<span>Reason Description:</span> ' . $event->ArstStatedReasonDesc];
        if (trim($event->ArstMiranda) != '') $deets[] = ['Miranda Rights Read?', $this->printYN($event->ArstMiranda)];
        if (trim($event->ArstSITA) != '') $deets[] = ['Search Incident To Arrest?', $this->printYN($event->ArstSITA)];
        if (!in_array(intVal($event->ArstNoChargesFiled), array(0, 188))) $deets[] 
            = [$GLOBALS["SL"]->getDefValue('No Charges Filed', $event->ArstNoChargesFiled)];
        return $deets;
    }
    
    protected function printHapMedical()
    {
        $ret = '';
        if ($this->hasMedicalCare) {
            $ret .= '<div class="reportSectHead">Medical Care:</div>';
            if (sizeof($this->injuries) > 0) {
                foreach ($this->injuries as $civID => $inj) {
                    if (sizeof($inj[2]) > 0) {
                        $ret .= $this->printReportDeetsBlock($deets, $inj[0]);
                    }
                }
            }
        }
        return $ret;
    }
    
    protected function printFullAllegs()
    {
        $ret = '<div class="reportSectHead2">Allegations...</div>'; // . $this->basicAllegationList(true);
        $this->simpleAllegationList();
        if (isset($this->allegations) && sizeof($this->allegations) > 0) {
            foreach ($this->allegations as $alleg) {
                $deets = [];
                if (isset($this->sessData->dataSets["LinksCivilianAllegations"]) 
                    && sizeof($this->sessData->dataSets["LinksCivilianAllegations"]) > 0) {
                    foreach ($this->sessData->dataSets["LinksCivilianAllegations"] as $civ) {
                        //if ($civ->LnkCivAlleAlleID == ) 
                    }
                }
                if (!in_array($alleg[0], ['Miranda Rights', 'Officer Refused To Provide ID']) 
                    && trim($alleg[1]) != '') {
                    $deets[] = [substr($alleg[1], 1)];
                }
                $ret .= $this->printReportDeetsBlock($deets, $alleg[0]);
            }
        }
        $med = $this->printHapMedical();
        if (trim($med) != '') {
            $ret .= '<div class="reportSectHead">Medical Care...</div>' . $med;
        }
        return $ret;
    }
    
    protected function eventAllegations($incEveID)
    {
        $allegs = [];
        if (isset($this->sessData->dataSets["Allegations"]) && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
            foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                if ($alleg->AlleEventSequenceID == $incEveID) {
                    $desc = $alleg->AlleDescription;
                    if ($alleg->AlleType == 'Intimidating Display of Weapon') {
                        $desc = $alleg->AlleIntimidatingWeapon . ', ' 
                            . $alleg->AlleIntimidatingWeaponType . ', ' . $desc;
                    }
                    $allegs[] = [
                        $alleg->AlleID, 
                        $GLOBALS["SL"]->getDefValue('Allegation Type', $alleg->AlleType), 
                        $desc
                    ];
                }
            }
        }
        return $allegs;
    }
    
    protected function printOrder($ord)
    {
        $ret = '';
        /* $ret = '<span>Order From Officer (<span style="color: #000;">' . "\n";
        $offs = $this->getLinkedToEvent('Officer', -3, -3, $ord->OrdID);
        if (sizeof($offs) > 0) {
            foreach ($offs as $i => $offID) {
                $ret .= (($i > 0) ? ', ' : '') . $this->getCivilianNameFromID($offID);
            }
        }
        $ret .= '</span> to <span style="color: #000;">' . "\n";
        $civs = $this->getLinkedToEvent('Civilian', -3, -3, $ord->OrdID);
        if (sizeof($civs) > 0) {
            foreach ($civs as $i => $civID) {
                $ret .= (($i > 0) ? ', ' : '') . $this->getCivilianNameFromID($civID);
            }
        }
        $ret .= '</span>)', $ord->OrdDescription; */
        return $ret;
    }
    
    
    
    protected function groupInjuries()
    {
        $this->hasMedicalCare = false;
        $this->injuries = [];
        if (isset($this->sessData->dataSets["Injuries"]) && sizeof($this->sessData->dataSets["Injuries"]) > 0) {
            foreach ($this->sessData->dataSets["Injuries"] as $inj) {
                if (!isset($this->injuries[$inj->InjSubjectID])) {
                    $name = $this->getCivReportName($inj->InjSubjectID);
                    if (strpos($name, '>Anonymous<') !== false) $name = 'Complainant';
                    $this->injuries[$inj->InjSubjectID] = [$name, [], []];
                }
                $this->injuries[$inj->InjSubjectID][1][] = [$inj, $this->getInjBodyParts($inj)];
            }
            if (isset($this->sessData->dataSets["InjuryCare"]) 
                && sizeof($this->sessData->dataSets["InjuryCare"]) > 0) {
                $this->hasMedicalCare = true;
                foreach ($this->injuries as $civID => $inj) {
                    foreach ($this->sessData->dataSets["InjuryCare"] as $i => $injCare) {
                        if ($civID == $injCare->InjCareSubjectID) {
                            $this->injuries[$civID][2] = $this->injCareDeets($injCare);
                        }
                    }
                }
            }
        }
        return $this->injuries;
    }
    
    protected function getInjBodyParts($inj)
    {
        $bodyParts = [];
        $parts = OPBodyParts::where('BodyInjuryID', $inj->InjID)
            ->orderBy('BodyPart', 'asc')
            ->get();
        if ($parts && sizeof($parts) > 0) {
            foreach ($parts as $part) {
                $bodyParts[] = $GLOBALS["SL"]->getDefValue('Body Part', $part->BodyPart);
            }
        }
        if (sizeof($bodyParts) == 1 && $bodyParts[0] == 'Unknown') {
            $bodyParts[0] = 'Body Part: Unknown';
        }
        return $bodyParts;
    }
    
    protected function injCareDeets($injCare)
    {
        $deets = [];
        if (trim($injCare->InjCareHospitalTreated) != '') {
            $deets[] = ['Hospital Treated', $injCare->InjCareHospitalTreated];
        }
        if (trim($injCare->InjCareResultInDeath) != '') {
            $deets[] = ['Resulted In Death?', $this->printYN($injCare->InjCareResultInDeath)];
        }
        if (trim($injCare->InjCareTimeOfDeath) != '' && trim($injCare->InjCareResultInDeath) == 'Y') {
            $deets[] = ['Time of Death', date("n/j/y g:ia", strtotime($injCare->InjCareTimeOfDeath))];
        }
        if (trim($injCare->InjCareDoctorNameFirst) != '' 
            || trim($injCare->InjCareDoctorNameLast) != '' 
            || trim($injCare->InjCareDoctorEmail) != '' 
            || trim($injCare->InjCareDoctorPhone) != '') {
            $deets[] = ['<span><i>Doctor Information...</i></span>'];
            $name = trim($injCare->InjCareDoctorNameFirst . ' ' . $injCare->InjCareDoctorNameLast);
            if ($this->v["view"] == 'Public' 
                && in_array($this->sessData->dataSets["Complaints"][0]->ComPrivacy, [305, 306, 207])) {
                if ($name != '') {
                    $deets[] = ['Name', '<i class="gry6">Not public</i>'];
                }
                if (trim($injCare->InjCareDoctorEmail) != '') {
                    $deets[] = ['Email', '<i class="gry6">Not public</i>'];
                }
                if (trim($injCare->InjCareDoctorPhone) != '') {
                    $deets[] = ['Phone Number', '<i class="gry6">Not public</i>'];
                }
            } else {
                if (trim($injCare->InjCareDoctorNameFirst) != '') {
                    $deets[] = ['First Name', $injCare->InjCareDoctorNameFirst];
                }
                if (trim($injCare->InjCareDoctorNameLast) != '') {
                    $deets[] = ['Last Name', $injCare->InjCareDoctorNameLast];
                }
                if (trim($injCare->InjCareDoctorEmail) != '') {
                    $deets[] = ['Email Address', $injCare->InjCareDoctorEmail];
                }
                if (trim($injCare->InjCareDoctorPhone) != '') {
                    $deets[] = ['Phone Number', $injCare->InjCareDoctorPhone];
                }
            }
        }
        
        if (trim($injCare->InjCareEmergencyOnScene) == 'Y') {
            $deets[] = ['<span><i>Emergency Medical Staff...</i></span>'];
            if (trim($injCare->InjCareEmergencyDeptName) != '') {
                $deets[] = ['<span>Department Name:</span> ' . $injCare->InjCareEmergencyDeptName];
            }
            $name = trim($injCare->InjCareEmergencyNameFirst . ' ' . $injCare->InjCareEmergencyNameLast);
            if ($this->v["view"] == 'Public' 
                && in_array($this->sessData->dataSets["Complaints"][0]->ComPrivacy, [305, 306, 207])) {
                if ($name != '') {
                    $deets[] = ['Name', '<i class="gry6">Not public</i>'];
                }
                if (trim($injCare->InjCareEmergencyIDnumber) != '') {
                    $deets[] = ['ID#', '<i class="gry6">Not public</i>'];
                }
                if (trim($injCare->InjCareEmergencyVehicleNumber) != '') {
                    $deets[] = ['Vehicle Number', '<i class="gry6">Not public</i>'];
                }
                if (trim($injCare->InjCareEmergencyLicenceNumber) != '') {
                    $deets[] = ['License Plate', '<i class="gry6">Not public</i>'];
                }
            } else {
                if (trim($injCare->InjCareEmergencyNameFirst) != '') {
                    $deets[] = ['First Name', $injCare->InjCareEmergencyNameFirst];
                }
                if (trim($injCare->InjCareEmergencyNameLast) != '') {
                    $deets[] = ['Last Name', $injCare->InjCareEmergencyNameLast];
                }
                if (trim($injCare->InjCareEmergencyIDnumber) != '') {
                    $deets[] = ['ID#', $injCare->InjCareEmergencyIDnumber];
                }
                if (trim($injCare->InjCareEmergencyVehicleNumber) != '') {
                    $deets[] = ['Vehicle Number', $injCare->InjCareEmergencyVehicleNumber];
                }
                if (trim($injCare->InjCareEmergencyLicenceNumber) != '') {
                    $deets[] = ['License Plate', $injCare->InjCareEmergencyLicenceNumber];
                }
            }
        }
        return $deets;
    }
    
    protected function getNameTopAnon()
    {
        return '<span style="color: #2b3493;" title="This complainant did not provide their name to investigators.">'
            . 'Complainant</span>';
    }
    
}
