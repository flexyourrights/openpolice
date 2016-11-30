<?php
namespace OpenPolice\Controllers;

use OpenPolice\Models\OPBodyParts;

use OpenPolice\Controllers\OpenPolice;

class OpenPoliceReport extends OpenPolice
{
	
	public $classExtension 	= 'OpenPoliceReport';
	public $treeID 			= 1;
	protected $isReport 	= true;
	
	protected $subjects 	= array();
	protected $witnesses 	= array();
	protected $whatHaps 	= array();
	protected $charges 		= array();
	
	public function printFullReport($reportType = '', $isAdmin = false)
	{
		if (in_array($this->sessData->dataSets["Complaints"][0]->ComStatus, 
			[$GLOBALS["DB"]->getDefID('Complaint Status', 'Hold'), $GLOBALS["DB"]->getDefID('Complaint Status', 'Pending Attorney')]))
		{
			return '<h1>Sorry, this complaint is not public.</h1>';
		}
		
		$ComSlug = $this->sessData->dataSets["Complaints"][0]->ComSlug;
		if (trim($ComSlug) == '')
		{
			$ComSlug = '/' . $this->sessData->dataSets["Complaints"][0]->ComID;
		}
		
		$this->v["isOwner"] = false;
		$this->v["view"] = 'Public';
		if ($this->v["user"] && isset($this->v["user"]->id))
		{
			if ($this->v["user"]->id == $this->sessData->dataSets["Civilians"][0]->CivUserID)
			{
				$this->v["isOwner"] = true;
				if (!$this->REQ->has('publicView') 
					&& ($reportType != 'Public' || $reportType == 'Investigate'))
				{
					$this->v["view"] = 'Investigate';
				}
			}
			elseif ($this->v["user"]->hasRole('administrator|staff'))
			{
				$this->v["view"] = 'Investigate';
			}
		}
		$this->v["civNames"] = $this->v["offNames"] = array();
		$this->whatHaps = $this->getEventSequence();
		$this->findCharges();
		$this->groupInjuries();
		
		$metaDeptList = "";
		if (isset($this->sessData->dataSets["Departments"]) 
			&& sizeof($this->sessData->dataSets["Departments"]) > 0)
		{
			foreach ($this->sessData->dataSets["Departments"] as $dept)
			{
				$metaDeptList = ", ".$dept->DeptName;
			}
		}
		
		$whoBlocks = [
			"Subjects" 		=> [], 
			"Witnesses" 	=> [], 
			"Officers" 		=> []
		];
		if ($this->sessData->dataSets["Civilians"][0]->CivRole == 'Helper')
		{
			$whoBlocks["Subjects"][] = '<div class="reportSectHead">Complainant:</div>' 
				. $this->printCivilian($this->sessData->dataSets["Civilians"][0]);
		}
		$this->subjects = $this->sessData->getLoopRows('Victims');
		$this->witnesses = $this->sessData->getLoopRows('Witnesses');
		if (sizeof($this->subjects) > 0)
		{
			foreach ($this->subjects as $ind => $civ)
			{
				$this->getCivReportName($civ->CivID, $ind, 'Subject');
				$whoBlocks["Subjects"][] = $this->printCivilian($civ);
			}
		}
		if (sizeof($this->witnesses) > 0)
		{
			foreach ($this->witnesses as $ind => $civ) 
			{
				$this->getCivReportName($civ->CivID, $ind, 'Witness');
				$whoBlocks["Witnesses"][] = $this->printCivilian($civ);
			}
		}
		if (isset($this->sessData->dataSets["Officers"]) 
			&& sizeof($this->sessData->dataSets["Officers"]) > 0)
		{
			foreach ($this->sessData->dataSets["Officers"] as $ind => $off)
			{
				$this->getOffReportName($off, $ind);
				$whoBlocks["Officers"][] = $this->printOfficer($off);
			}
		}
		
		$this->printwhatHaps = '';
		if ($this->isGold())
		{
			if (sizeof($this->whatHaps) > 0)
			{
				foreach ($this->whatHaps as $incEve)
				{
					$this->printwhatHaps .= $this->printHap($incEve);
				}
			}
		}
		
		$complainantNameTop = $this->getCivReportName($this->sessData->dataSets["Civilians"][0]->CivID);
		if ($complainantNameTop == '<span style="color: #000;" title="This complainant did not provide their name to investigators.">Complainant</span>')
		{
			$complainantNameTop = 'Anonymous';
		}
						
		if (!isset($GLOBALS["meta"])) $GLOBALS["meta"] = array();
		if (trim($this->sessData->dataSets["Complaints"][0]->ComHeadline) != '')
		{
			$GLOBALS["meta"]["title"] = $this->sessData->dataSets["Complaints"][0]->ComHeadline 
				. " | Open Police Complaint #".$this->coreID;
		}
		else $GLOBALS["meta"]["title"] =  trim(substr($metaDeptList, 1)) . " | Open Police Complaint #".$this->coreID."";
		$GLOBALS["meta"]["desc"] = "Open Police Complaint #".$this->coreID . $metaDeptList . ", " 
			. date('n/j/Y', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeStart)) 
			. " - " . substr($this->sessData->dataSets["Complaints"][0]->ComSummary, 0, 140) . " ...";
		$GLOBALS["meta"]["keywords"] = $metaDeptList . ", " . $this->simpleAllegationList();
		$GLOBALS["meta"]["img"] = '';
		
		$comDate = date("n/j/Y", strtotime($this->sessData->dataSets["Complaints"][0]->updated_at));
		if (trim($this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted) != '' 
			&& $this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted != '0000-00-00 00:00:00') 
		{
			$comDate = date("n/j/Y", strtotime($this->sessData->dataSets["Complaints"][0]->ComRecordSubmitted));
		}

		return view('vendor.openpolice.complaint-report', [
			"isOwner" 				=> $this->v["isOwner"], 
			"view" 					=> $this->v["view"], 
			"complaintID" 			=> $this->coreID, 
			"comDate" 				=> $comDate, 
			"sessData" 				=> $this->sessData->dataSets, 
			"ComSlug" 				=> $ComSlug, 
			"complainantName" 		=> $complainantNameTop, 
			"civNames" 				=> $this->v["civNames"], 
			"whoBlocks" 			=> $whoBlocks, 
			"printwhatHaps" 		=> $this->printwhatHaps, 
			"injuries" 				=> $this->injuries, 
			"hasMedicalCare" 		=> $this->hasMedicalCare, 
			"basicAllegationList" 	=> $this->basicAllegationList(true),
			"basicAllegationListF" 	=> $this->simpleAllegationList(),
			"featureImg" 			=> $this->getFeatureImg(),
			"isAdmin" 				=> $isAdmin
		]);
	}
	
	protected function getCivReportName($civID, $ind = 0, $type = 'Subject', $prsn = array())
	{
		if (!isset($this->v["civNames"][$civID]) || trim($this->v["civNames"][$civID]) == '')
		{
			if (sizeof($prsn) == 0) list($prsn, $phys, $vehic) = $this->queuePeopleSubsets($civID);
			//echo '<pre>'; print_r($prsn); echo '</pre> civID: ' . $civID . ', privacy: ' . $this->sessData->dataSets["Complaints"][0]->ComPrivacy . '<br />';
			$name = '';
			if ( $civID == $this->sessData->dataSets["Civilians"][0]->CivID 
				&& (trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) == ''
				|| $this->sessData->dataSets["Complaints"][0]->ComPrivacy == 206) )
			{
				$name = '<span style="color: #000;" title="This complainant did not provide their name to investigators.">Complainant</span>';
			}
			elseif (trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) != '' 
				&& ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 204 || $this->v["view"] == 'Investigate'))
			{
				$name = '<span style="color: #000;" title="This complainant wanted to publicly provide their name.">
				' . $prsn->PrsnNameFirst . ' ' . $prsn->PrsnNameMiddle . ' ' . $prsn->PrsnNameLast . '
				</span>';
			}
			else
			{
				if ($type == 'Subject' && $this->sessData->dataSets["Civilians"][0]["CivRole"] == 'Victim') $ind++;
				if ($type == 'Witness' && $this->sessData->dataSets["Civilians"][0]["CivRole"] == 'Witness') $ind++;
				$name = $type . ' #' . (1+$ind);
			}
			$this->v["civNames"][$civID] = $name;
		}
		return $this->v["civNames"][$civID];
	}
	
	protected function getOffReportName($off, $ind = 0, $prsn = array())
	{
		if (!isset($this->v["offNames"][$off->OffID]) || trim($this->v["offNames"][$off->OffID]) == '')
		{
			if (sizeof($prsn) == 0) list($prsn, $phys, $vehic) = $this->queuePeopleSubsets($off->OffID, 'Officers');
			$name = '';
			if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 204 || $this->v["view"] == 'Investigate')
			{
				$name = trim($prsn->PrsnNameFirst . ' ' . $prsn->PrsnNameMiddle . ' ' . $prsn->PrsnNameLast);
				if (trim($name) == '' && trim($off->OffBadgeNumber) != '' && trim($off->OffBadgeNumber) != '0')
				{
					$name = 'Badge #' . $off->OffBadgeNumber;
				}
				if ($name == '') $name = 'Officer #' . (1+$ind);
			}
			else $name = 'Officer #' . (1+$ind);
			$this->v["offNames"][$off->OffID] = $name;
		}
		return $this->v["offNames"][$off->OffID];
	}
	
	protected function findCharges() 
	{
		if (sizeof($this->whatHaps) > 0) 
		{
			$this->charges = [ "Arrests" => [], "Citations" => [], "Warnings" => [] ];
			foreach ($this->whatHaps as $incEve) 
			{
				if (isset($this->sessData->dataSets["Charges"]) && sizeof($this->sessData->dataSets["Charges"]) > 0 
					&& sizeof($incEve["Civilians"]) > 0) 
				{
					if ($incEve["EveType"] == 'Stops') 
					{
						foreach ($this->sessData->dataSets["Charges"] as $charge) 
						{
							if ($charge->ChrgStopID == $incEve["Event"]->StopID) 
							{
								foreach ($incEve["Civilians"] as $civID) 
								{
									if (!isset($this->charges["Citations"][$civID])) $this->charges["Citations"][$civID] = array();
									$chargeName = $GLOBALS["DB"]->getDefValue('Citation Charges', $charge->ChrgCharges);
									if (trim($chargeName) == '') $chargeName = $GLOBALS["DB"]->getDefValue('Citation Charges Pedestrian', $charge->ChrgCharges);
									$this->charges["Citations"][$civID][] = $chargeName;
								}
							}
						}
						if (isset($incEve["Event"]->StopChargesOther) && trim($incEve["Event"]->StopChargesOther) != '') 
						{
							foreach ($incEve["Civilians"] as $civID) 
							{
								if (!isset($this->charges["Citations"][$civID])) $this->charges["Citations"][$civID] = array();
								$this->charges["Citations"][$civID][] = $incEve["Event"]->StopChargesOther;
							}
						}
						if (isset($incEve["Event"]->StopGivenWarning) && trim($incEve["Event"]->StopGivenWarning) == 'Y') 
						{
							foreach ($incEve["Civilians"] as $civID) 
							{
								if (!isset($this->charges["Warnings"][$civID])) $this->charges["Warnings"][$civID] = array();
								$this->charges["Warnings"][$civID][] = 'Yes';
							}
						}
					}
					elseif ($incEve["EveType"] == 'Arrests') 
					{
						foreach ($incEve["Civilians"] as $civID) $this->charges["Arrests"][$civID] = array();
						foreach ($this->sessData->dataSets["Charges"] as $charge) 
						{
							if ($charge->ChrgArrestID == $incEve["Event"]->ArstID) 
							{
								foreach ($incEve["Civilians"] as $civID) 
								{
									$this->charges["Arrests"][$civID][] = $GLOBALS["DB"]->getDefValue('Arrest Charges', $charge->ChrgCharges);
								}
							}
						}
						if (isset($incEve["Event"]->ArstChargesOther) && trim($incEve["Event"]->StopChargesOther) != '') 
						{
							foreach ($incEve["Arrests"] as $civID) 
							{
								$this->charges["Arrests"][$civID][] = $incEve["Event"]->StopChargesOther;
							}
						}
						if ($incEve["Event"]->ArstNoChargesFiled == $GLOBALS["DB"]->getDefID('No Charges Filed', 'ALL Charges Were Dropped Before Release')) 
						{
							foreach ($incEve["Civilians"] as $civID) 
							{
								$this->charges["Arrests"][$civID][] = 'All charges dropped before release';
							}
						}
						elseif ($incEve["Event"]->ArstNoChargesFiled == $GLOBALS["DB"]->getDefID('No Charges Filed', 'No Charges Were Ever Filed')) 
						{
							foreach ($incEve["Civilians"] as $civID) 
							{
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
		$deets = array();
		list($prsn, $phys, $vehic) = $this->queuePeopleSubsets($civ->CivID);
		if (trim($phys->PhysRace) != '') 				$deets[] = '<span>Race:</span></td><td>'.$GLOBALS["DB"]->getDefValue('Races', $phys->PhysRace);
		if (trim($phys->PhysGender) != '') 				$deets[] = '<span>Gender:</span></td><td>'.$this->printMF($phys->PhysGender);
		if ($civ->CivIsCreator == 'Y')
		{ 
			if (trim($prsn->PrsnBirthday) != '' && trim($prsn->PrsnBirthday) != '0000-00-00' && trim($prsn->PrsnBirthday) != '1970-01-01')
			{
				if ($this->v["view"] == 'Investigate') 		$deets[] = '<span>Birthday:</span></td><td>'.date("n/j/Y", strtotime($prsn->PrsnBirthday));
				else $deets[] = '<span>Birthday:</span></td><td>'.date("Y", strtotime($prsn->PrsnBirthday));
			}
		}
		elseif (trim($phys->PhysAge) != '') 			$deets[] = '<span>Age Range:</span></td><td>'.$GLOBALS["DB"]->getDefValue('Age Ranges', $phys->PhysAge);
		if (trim($civ->CivOccupation) != '') 			$deets[] = '<span>Occupation:</span></td><td>'.$civ->CivOccupation;
		if (intVal($phys->PhysHeight) > 0) 				$deets[] = '<span>Height:</span></td><td>'.$this->printHeight($phys->PhysHeight);
		if (intVal($phys->PhysBodyType) > 0) 			$deets[] = '<span>Body Type:</span></td><td>'.$GLOBALS["DB"]->getDefValue('Body Types', $phys->PhysBodyType);
		if (trim($phys->PhysHairDescription) != '') 	$deets[] = '<span>Hair:</span></td><td>'.$phys->PhysHairDescription;
		if (trim($phys->PhysHairFacialDesc) != '') 		$deets[] = '<span>Facial Hair:</span></td><td>'.$phys->PhysHairFacialDesc;
		if (trim($phys->PhysEyes) != '') 				$deets[] = '<span>Eyes:</span></td><td>'.$phys->PhysEyes;
		if (trim($phys->PhysDistinguishingMarksDesc) != '') $deets[] = '<span>Distinguishing Marks:</span></td><td>'.$phys->PhysDistinguishingMarksDesc;
		if (trim($phys->PhysVoiceDesc) != '') 			$deets[] = '<span>Voice:</span></td><td>'.$phys->PhysVoiceDesc;
		if (trim($phys->PhysClothesDesc) != '') 		$deets[] = '<span>Clothes:</span></td><td>'.$phys->PhysClothesDesc;
		if (trim($phys->PhysDisabilitiesDesc) != '') 	$deets[] = '<span>Disabilities:</span></td><td>'.$phys->PhysDisabilitiesDesc;
		if (sizeof($vehic) > 0)
		{
			if (trim($vehic->VehicTransportation) != '') 	$deets[] = '<span>Transportation:</span></td><td>'.$GLOBALS["DB"]->getDefValue('Transportation Civilian', $vehic->VehicTransportation);
			if (trim($vehic->VehicVehicleMake) != '') 		$deets[] = '<span>Make:</span></td><td>'.$vehic->VehicVehicleMake;
			if (trim($vehic->VehicVehicleModel) != '') 		$deets[] = '<span>Model:</span></td><td>'.$vehic->VehicVehicleModel;
			if (trim($vehic->VehicVehicleDesc) != '') 		$deets[] = '<span>Description:</span></td><td>'.$vehic->VehicVehicleDesc;
			if (trim($vehic->VehicVehicleLicence) != '') 	$deets[] = '<span>License:</span></td><td>'.$vehic->VehicVehicleLicence;
		}
		if (trim($civ->CivCameraRecord) != '') 			$deets[] = '<span>Recorded Incident:</span></td><td>'.$this->printYN($civ->CivCameraRecord);
		
		$contactInfo = $this->printCivContact($prsn, $civ->CivID);
		$name = $this->getCivReportName($civ->CivID);
		if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 206 
			&& $civ->CivID == $this->sessData->dataSets["Civilians"][0]->CivID)
		{
			$name .= ' <span class="f12 nobld">(Anonymous)</span>';
		}
		$retVal = '
		<div class="reportBlock">
			<div class="row">
				<div class="col-md-4">
					<div class="f20 pB10"><b>' . $name . '</b></div>' . "\n";
					if (sizeof($this->charges) > 0)
					{
						foreach (['Arrests', 'Citations', 'Warnings'] as $type)
						{
							if (sizeof($this->charges[$type]) > 0)
							{
								foreach ($this->charges[$type] as $civID => $charges)
								{
									if ($civID == $civ->CivID)
									{
										if ($type == 'Warnings') $retVal .= '<div class="pB10 f16">Written Warning</div>';
										else
										{
											if ($type == 'Arrests') $retVal .= '<span>Arrest Charges:</span><div class="pL10 pB10 f16">';
											else $retVal .= '<span>Citation Charges:</span><div class="pL10 pB10 f16">';
											foreach ($charges as $charge)
											{
												$retVal .= $charge . '<br />';
											}
											$retVal .= '</div>';
										}
									}
								}
							}
						}
					}
					if (isset($this->injuries[$civ->CivID]))
					{
						$retVal .= '<span>Injuries:</span>';
						foreach ($this->injuries[$civ->CivID][1] as $inj)
						{
							$retVal .= '<div class="pB10 pL10">
								<div class="f16">' . $GLOBALS["DB"]->getDefValue('Injury Types', $inj[0]->InjType) . '</div>
								<div class="pL10 gry6">';
									if (sizeof($inj[1]) > 0)
									{
										$retVal .= implode(', ', $inj[1]) . '<br />';
									}
									if (trim($inj[0]->InjDescription) != '')
									{
										$retVal .= $inj[0]->InjDescription;
									}
								$retVal .= '</div>
							</div>';
						}
					}
					$retVal .= '<div class="pT5 gry4">' . $contactInfo . '</div>
				</div><div class="col-md-4"><table class="table"><tr class="disNon"></tr>' . "\n";
					for ($i=0; $i<floor(sizeof($deets)/2); $i++)
					{
						$retVal .= '<tr><td>' . $deets[$i] . '</td></tr>' . "\n";
					}
					$retVal .= '</table></div>
				<div class="col-md-4"><table class="table"><tr class="disNon"></tr>' . "\n";
					for ($i=floor(sizeof($deets)/2); $i<sizeof($deets); $i++)
					{
						$retVal .= '<tr><td>' . $deets[$i] . '</td></tr>' . "\n";
					}
				$retVal .= '</table>
				</div>
			</div>
		</div>';
		return $retVal;
	}
	
	protected function printCivContact($prsn, $civID)
	{
		if ($civID == $this->sessData->dataSets["Civilians"][0]->CivID && $this->sessData->dataSets["Complaints"][0]->ComPrivacy == 206) return ' ';
		if ($this->v["view"] == 'Public')
		{
			$info = ((trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) != '' && $this->sessData->dataSets["Complaints"][0]->ComPrivacy != 204) 	? ', Name' : '')
				. ((trim($prsn->PrsnAddress) != '') 		? ', Address' : '')
				. ((trim($prsn->PrsnPhoneHome) != '') 		? ', Phone Number' : '') 
				. ((trim($prsn->PrsnEmail) != '') 			? ', Email' : '') 
				. ((trim($prsn->PrsnFacebook) != '') 		? ', Facebook' : '');
			if (($civID != $this->sessData->dataSets["Civilians"][0]->CivID || $this->sessData->dataSets["Complaints"][0]->ComPrivacy != 206)
				&& trim($info) != '')
			{
				return '<i class="gryA">Not public: ' . substr($info, 1) . '</i>';
			}
			return ' ';
		}
		elseif ($this->v["view"] == 'Investigate')
		{
			return ((trim($prsn->PrsnEmail) != '') 			? $prsn->PrsnEmail.'<br />' : '') 
				. ((trim($prsn->PrsnFacebook) != '') 		? $prsn->PrsnFacebook.'<br />' : '') 
				. ((trim($prsn->PrsnPhoneHome) != '') 		? $prsn->PrsnPhoneHome.'<br />' : '') 
				. ((trim($prsn->PrsnAddress) != '') 		? $prsn->PrsnAddress.((trim($prsn->PrsnAddress2) != '') ? '<br />'.$prsn->PrsnAddress2 : '').'<br />' : '') 
				. ((trim($prsn->PrsnAddressCity) != '') 	? $prsn->PrsnAddressCity.', ' : '') 
				. ((trim($prsn->PrsnAddressState) != '') 	? $prsn->PrsnAddressState.' ' : '') 
				. ((trim($prsn->PrsnAddressZip) != '') 		? $prsn->PrsnAddressZip.' ' : '');
		}
		return '';
	}

	protected function printOfficer($off)
	{
		$deets = $deets0 = array();
		list($prsn, $phys, $vehic) = $this->queuePeopleSubsets($off->OffID, 'Officers');
		
		$name = '<div class="f20 pB10"><b>' . $this->getOffReportName($off) . '</b></div>';
		
		if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 204 || $this->v["view"] == 'Investigate') 
		{ 	// then name or badge number (if provided) should appear in official name
			if (trim($prsn->PrsnNameFirst . ' ' . $prsn->PrsnNameMiddle . ' ' . $prsn->PrsnNameLast) != ''
				&& trim($off->OffBadgeNumber) != '' && intVal($off->OffBadgeNumber) > 0)
			{
				$deets0[] = '<span>Badge Number:</span></td><td>'.$off->OffBadgeNumber;
			}
		}
		else
		{
			$info = ((trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) != '') 	? ', Name' : '')
				. ((trim($off->OffBadgeNumber) != '' && intVal($off->OffBadgeNumber) > 0) ? ', Badge Number' : '');
			if (trim($info) != '')
			{
				$name .= '<div class="pB10 mTn10 gryA"><i>Not public: ' . substr($info, 1) . '</i></div>';
			}
		}
		
		if (sizeof($this->sessData->dataSets["Departments"]) > 1 && intVal($off->OffDeptID) > 0)
		{
			$deets[] = '<span>Department:</span></td><td>'.$this->getDeptNameByID($off->OffDeptID);
		}
		if (trim($off->OffRole) != '') 				$deets0[] = '<span>Role:</span></td><td>'.$off->OffRole;
		//if (more than one department) if (trim($off->OffDeptID) != '') $deets0[] = '<span>Department:</span></td><td>'.$off->OffDeptID;
		if (trim($off->OffPrecinct) != '') 			$deets0[] = '<span>Precint:</span></td><td>'.$off->OffPrecinct;
		
		if (trim($off->OffOfficerRank) != '') 		$deets0[] = '<span>Rank:</span></td><td>'.$off->OffOfficerRank;
		if (trim($phys->PhysRace) != '') 			$deets0[] = '<span>Race:</span></td><td>'.$GLOBALS["DB"]->getDefValue('Races', $phys->PhysRace);
		if (trim($phys->PhysGender) != '') 			$deets0[] = '<span>Gender:</span></td><td>'.$this->printMF($phys->PhysGender);
		if (intVal($phys->PhysHeight) > 0) 			$deets[] = '<span>Height:</span></td><td>'.$this->printHeight($phys->PhysHeight);
		if (intVal($phys->PhysBodyType) > 0) 		$deets[] = '<span>Body Type:</span></td><td>'.$GLOBALS["DB"]->getDefValue('Body Types', $phys->PhysBodyType);
		if (trim($phys->PhysHairDescription) != '') $deets[] = '<span>Hair:</span></td><td>'.$phys->PhysHairDescription;
		if (trim($phys->PhysHairFacialDesc) != '') 	$deets[] = '<span>Facial Hair:</span></td><td>'.$phys->PhysHairFacialDesc;
		if (trim($phys->PhysEyes) != '') 			$deets[] = '<span>Eyes:</span></td><td>'.$phys->PhysEyes;
		if (trim($phys->PhysDistinguishingMarksDesc) != '') $deets[] = '<span>Distinguishing Marks:</span></td><td>'.$phys->PhysDistinguishingMarksDesc;
		if (trim($phys->PhysVoiceDesc) != '') 		$deets[] = '<span>Voice:</span></td><td>'.$phys->PhysVoiceDesc;
		if (trim($phys->PhysClothesDesc) != '') 	$deets[] = '<span>Uniform:</span></td><td>'.$phys->PhysClothesDesc;
		if (trim($off->OffBodyCam) != '') 			$deets[] = '<span>Had Body Camera:</span></td><td>'.$this->printYN($off->OffBodyCam);
		if (sizeof($vehic) > 0)
		{
			if (trim($vehic->VehicTransportation) != '')$deets[] = '<span>Transportation:</span></td><td>'.$GLOBALS["DB"]->getDefValue('Transportation Officer', $vehic->VehicTransportation);
			if (trim($vehic->VehicVehicleDesc) != '') 	$deets[] = '<span>Vehicle Description:</span></td><td>'.$vehic->VehicVehicleDesc;
			if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 204 || $this->v["view"] == 'Investigate') 
			{
				if (trim($vehic->VehicVehicleNumber) != '') $deets[] = '<span>Vehicle Number:</span></td><td>'.$vehic->VehicVehicleNumber;
				if (trim($vehic->VehicVehicleLicence) != '')$deets[] = '<span>License Plate:</span></td><td>'.$vehic->VehicVehicleLicence;
			}
			else
			{
				if (trim($vehic->VehicVehicleNumber) != '') $deets[] = '<span>Vehicle Number:</span></td><td><i class="gry6">Not public</i>';
				if (trim($vehic->VehicVehicleLicence) != '')$deets[] = '<span>License Plate:</span></td><td><i class="gry6">Not public</i>';
			}
		}
		
		$retVal = '
		<div class="reportBlock">
			<div class="row">
				<div class="col-md-4">
					' . $name . '
					<table class="table"><tr class="disNon"></tr>';
					for ($i=0; $i<sizeof($deets0); $i++)
					{
						$retVal .= '<tr><td>' . $deets0[$i] . '</td></tr>' . "\n";
					}
					$retVal .= '</table></div>
					<div class="col-md-4"><table class="table"><tr class="disNon"></tr>' . "\n";
					for ($i=0; $i<floor(sizeof($deets)/2); $i++)
					{
						$retVal .= '<tr><td>' . $deets[$i] . '</td></tr>' . "\n";
					}
					$retVal .= '</table></div>
					<div class="col-md-4"><table class="table"><tr class="disNon"></tr>' . "\n";
					for ($i=floor(sizeof($deets)/2); $i<sizeof($deets); $i++)
					{
						$retVal .= '<tr><td>' . $deets[$i] . '</td></tr>' . "\n";
					}
					$retVal .= '</table>
				</div>
			</div>
		</div>' . "\n";
		return $retVal;
	}
	

	
	protected function printHap($incEve)
	{
		$deets = array();
		switch ($incEve["EveType"])
		{
			case 'Stops': 	$deets = $this->printHapStop($incEve["Event"]); break;
			case 'Searches':$deets = $this->printHapSearch($incEve["Event"]); break;
			case 'Force': 	$deets = $this->printHapForce($incEve["Event"]); break;
			case 'Arrests': $deets = $this->printHapArrest($incEve["Event"]); break;
		}
		$allegs = $this->eventAllegations($incEve["EveID"]);
		$orders = $this->getEventOrders($incEve["EveID"], $incEve["EveType"]);
		if (sizeof($orders) > 0)
		{
			if ($incEve["EveType"] == 'Force' && (trim($incEve["Event"]->ForOrdersBeforeForce) != '' 
				|| trim($incEve["Event"]->ForOrdersSubjectResponse) != ''))
			{
				if (trim($incEve["Event"]->ForOrdersBeforeForce) != '')
				{
					$deets[] = '<span>Orders Before Force:</span></td><td>' . $incEve["Event"]->ForOrdersBeforeForce;
				}
				if (trim($incEve["Event"]->ForOrdersSubjectResponse) != '')
				{
					$deets[] = '<span>Subject Response To Orders:</span></td><td>' . $incEve["Event"]->ForOrdersSubjectResponse;
				}
			}
			foreach ($orders as $i => $ord)
			{
				$deets[] = $this->printOrder($ord);
				if (in_array(trim($ord->OrdTroubleUnderYN), array('Y', '?')))
				{
					$deets[] = '<span>Trouble Hearing Order?</span></td><td>' . $this->printYN($ord->OrdTroubleUnderYN)
						. ((trim($ord->OrdTroubleUnderstading) != '') ? '<br />'.$ord->OrdTroubleUnderstading : '');
				}
			}
		}
		return view( 'vendor.openpolice.complaint-report-what-hap', [ "allegs" => $allegs, "deets" => $deets, 
			"eventDesc" => $this->reportEventTitle($incEve) ] )->render();
	}
	
	// similar to printEventSequenceLine($eveSeq), but with Report-driven names
	protected function reportEventTitle($eveSeq)
	{
		//if ($this->debugOn) { echo 'printEventSequenceLine:<pre>'; print_r($eveSeq); echo '</pre>'; }
		if (!isset($eveSeq["EveType"]) || sizeof($eveSeq["Event"]) <= 0) return '';
		$retVal = '';
		if ($eveSeq["EveType"] == 'Force' && isset($eveSeq["Event"]->ForType) && trim($eveSeq["Event"]->ForType) != '')
		{
			$retVal .= (($eveSeq["Event"]->ForType == 'Other') ? $eveSeq["Event"]->ForTypeOther 
							: $GLOBALS["DB"]->getDefValue('Force Type', $eveSeq["Event"]->ForType)) . ' Force ';
		}
		elseif (isset($this->eventTypeLabel[$eveSeq["EveType"]])) $retVal .= $this->eventTypeLabel[$eveSeq["EveType"]] . ' ';
		if ($eveSeq["EveType"] == 'Force' && $eveSeq["Event"]->ForAgainstAnimal == 'Y')
		{
			$retVal .= '<span class="gry9">on</span> ' . $eveSeq["Event"]->ForAnimalDesc;
		}
		else {
			$civNames = $offNames = '';
			if ($this->moreThan1Victim())
			{
				foreach ($eveSeq["Civilians"] as $civ) $civNames .= ', '.$this->v["civNames"][$civ];
				if (trim($civNames) != '') $retVal .= '<div class="pL10 f18"><span class="gry9 mR5">'.(($eveSeq["EveType"] == 'Force') ? 'on ' : 'of ').'</span>' . substr($civNames, 1) . '</div>';
			}
			if ($this->moreThan1Officer())
			{
				foreach ($eveSeq["Officers"] as $off) $offNames .= ', '.$this->v["offNames"][$off];
				if (trim($offNames) != '') $retVal .= '<div class="pL10 f16"><span class="gry9 mR5">by</span> ' . substr($offNames, 1) . '</div>';
			}
		}
		$retVal .= '';
		return $retVal;
	}
	
	protected function printHapStop($event)
	{              
		$deets = array();
		$StopReasons = '';
		if (sizeof($this->sessData->dataSets["StopReasons"]) > 0)
		{
			foreach ($this->sessData->dataSets["StopReasons"] as $reas)
			{
				if ($event->StopID == $reas->StopReasStopID)
				{
					$stop = $GLOBALS["DB"]->getDefValue('Reason for Pedestrian Stop', $reas->StopReasReason);
					if ($stop == '') $stop = $GLOBALS["DB"]->getDefValue('Reason for Vehicle Stop', $reas->StopReasReason);
					$StopReasons .= ', ' . $stop;
				}
			}
		}
		if (trim($StopReasons) != '') 							$deets[] = '<span>Reason Given:</span></td><td>'.substr($StopReasons, 1);
		if (trim($event->StopStatedReasonDesc) != '') 			$deets[] = '<span>Reason Description:</span></td><td>'.$event->StopStatedReasonDesc;
		if (trim($event->StopSubjectAskedToLeave) != '') 		$deets[] = '<span>Subject Ask To Leave?</span></td><td>'.$this->printYN($event->StopSubjectAskedToLeave);
		if (trim($event->StopSubjectStatementsDesc) != '') 		$deets[] = '<span>Subject Asked:</span></td><td>'.$event->StopSubjectStatementsDesc;
		if (trim($event->StopEnterPrivateProperty) != '') 		$deets[] = '<span>Enter Private Property?</span></td><td>'.$this->printYN($event->StopEnterPrivateProperty);
		if (trim($event->StopEnterPrivatePropertyDesc) != '') 	$deets[] = '<span>Entry Description:</span></td><td>'.$event->StopEnterPrivatePropertyDesc;
		if (trim($event->StopPermissionEnter) != '') 			$deets[] = '<span>Officer Request Entry Permission?</span></td><td>'.$this->printYN($event->StopPermissionEnter);
		if (trim($event->StopPermissionEnterGranted) != '') 	$deets[] = '<span>Subject Grant Permission?</span></td><td>'.$this->printYN($event->StopPermissionEnterGranted);
		if (trim($event->StopRequestID) != '') 					$deets[] = '<span>Officer Request Subject ID?</span></td><td>'.$this->printYN($event->StopRequestID);
		if (trim($event->StopRefuseID) != '') 					$deets[] = '<span>Subject Refuse To Give ID?</span></td><td>'.$this->printYN($event->StopRefuseID);
		if (trim($event->StopRequestOfficerID) != '') 			$deets[] = '<span>Subject Request Officer ID?</span></td><td>'.$this->printYN($event->StopRequestOfficerID);
		if (trim($event->StopOfficerRefuseID) != '') 			$deets[] = '<span>Officer Refuse To Give ID?</span></td><td>'.$this->printYN($event->StopOfficerRefuseID);
		if (trim($event->StopSubjectFrisk) != '') 				$deets[] = '<span>Subject Frisked?</span></td><td>'.$this->printYN($event->StopSubjectFrisk);
		if (trim($event->StopSubjectHandcuffed) != '') 			$deets[] = '<span>Subject Handcuffed?</span></td><td>'.$this->printYN($event->StopSubjectHandcuffed);
		if (intVal($event->StopSubjectHandcuffInjury) > 0)
		{
			$desc = '';
			/*
			if (sizeof($this->sessData->dataSets["Injuries"]["Handcuff"]) > 0) {
				foreach ($this->sessData->dataSets["Injuries"]["Handcuff"] as $inj) {
					if ($inj->InjID == $event->StopSubjectHandcuffInjury && trim($inj->InjDescription) != '') $desc .= ', ' . $inj->InjDescription;
				}
			}
			*/
			$deets[] = '<span>Handcuff Injury?</span> Yes' . $desc;
		}
		return $deets;
	}
	
	protected function printHapSearch($event)
	{
		$deets = array();
		if (trim($event->SrchStatedReason) != '') 			$deets[] = '<span>Reason Given:</span></td><td>'.$this->printYN($event->SrchStatedReason);
		if (trim($event->SrchStatedReasonDesc) != '') 		$deets[] = '<span>Reason Description:</span></td><td>'.$event->SrchStatedReasonDesc;
		if (trim($event->SrchOfficerRequest) != '') 		$deets[] = '<span>Officer Request Permission?</span></td><td>'.$this->printYN($event->SrchOfficerRequest);
		if (trim($event->SrchOfficerRequestDesc) != '') 	$deets[] = '<span>Request Description:</span></td><td>'.$event->SrchOfficerRequestDesc;
		if (trim($event->SrchSubjectConsent) != '') 		$deets[] = '<span>Subject Consent?</span></td><td>'.$this->printYN($event->SrchSubjectConsent);
		if (trim($event->SrchSubjectSay) != '') 			$deets[] = '<span>Consent Description:</span></td><td>'.$event->SrchSubjectSay;
		if (trim($event->SrchOfficerThreats) != '') 		$deets[] = '<span>Threats/Lies for Consent?</span></td><td>'.$this->printYN($event->SrchOfficerThreats);
		if (trim($event->SrchOfficerThreatsDesc) != '') 	$deets[] = '<span>Threats/Lies Description:</span></td><td>'.$event->SrchOfficerThreatsDesc;
		if (trim($event->SrchStrip) != '') 					$deets[] = '<span>Strip Searched?</span></td><td>'.$this->printYN($event->SrchStrip);
		if (trim($event->SrchStripSearchDesc) != '') 		$deets[] = '<span>Strip Description:</span></td><td>'.$event->SrchStripSearchDesc;
		if (trim($event->SrchK9sniff) != '') 				$deets[] = '<span>K-9 sniff?</span></td><td>'.$this->printYN($event->SrchK9sniff);
		if (trim($event->SrchContrabandDiscovered) != '') 	$deets[] = '<span>Contraband Discovered?</span></td><td>'.$this->printYN($event->SrchContrabandDiscovered);
		$SearchContra = '';
		if (isset($this->sessData->dataSets["SearchContra"]) && sizeof($this->sessData->dataSets["SearchContra"]) > 0)
		{
			foreach ($this->sessData->dataSets["SearchContra"] as $srch)
			{
				if ($event->SrchID == $srch->SrchConSearchID) $SearchContra .= ', ' . $GLOBALS["DB"]->getDefValue('Contraband Types', $srch->SrchConType);
			}
		}
		if (trim($SearchContra) != '') 						$deets[] = '<span>Contraband Types:</span></td><td>'.substr($SearchContra, 1);
		if (trim($event->SrchContrabandTypes) != '') 		$deets[] = '<span>Contraband Types:</span></td><td>'.$this->printValList($event->SrchContrabandTypes);
		if (trim($event->SrchOfficerWarrant) != '') 		$deets[] = '<span>Have A Warrant?</span></td><td>'.$this->printYN($event->SrchOfficerWarrant);
		if (trim($event->SrchOfficerWarrantSay) != '') 		$deets[] = '<span>Warrant Description:</span></td><td>'.$event->SrchOfficerWarrantSay;
		if (trim($event->SrchSeized) != '') 				$deets[] = '<span>Property Seized?</span></td><td>'.$this->printYN($event->SrchSeized);
		$SearchSeized = '';
		if (isset($this->sessData->dataSets["SearchSeize"]) && sizeof($this->sessData->dataSets["SearchSeize"]) > 0)
		{
			foreach ($this->sessData->dataSets["SearchSeize"] as $srch)
			{
				if ($event->SrchID == $srch->SrchSeizSearchID)
				{
					$SearchSeized .= ', ' . $GLOBALS["DB"]->getDefValue('Property Seized Types', $srch->SrchSeizType);
				}
			}
		}
		if (trim($SearchSeized) != '') 						$deets[] = '<span>Seized Types:</span></td><td>'.substr($SearchSeized, 1);
		if (trim($event->SrchSeizedDesc) != '') 			$deets[] = '<span>Seized Description:</span></td><td>'.$event->SrchSeizedDesc;
		if (trim($event->SrchDamage) != '') 				$deets[] = '<span>Property Damaged?</span></td><td>'.$this->printYN($event->SrchDamage);
		if (trim($event->SrchDamageDesc) != '') 			$deets[] = '<span>Damaged Description:</span></td><td>'.$event->SrchDamageDesc;
		return $deets;
	}
	
	protected function printHapForce($event)
	{
		$deets = array();
		$forceType = $GLOBALS["DB"]->getDefValue('Force Type', $event->ForType);
		if ($forceType == 'Other') 						$deets[] = '<span>Type of Force:</span></td><td>'.$event->ForTypeOther;
		if (in_array($forceType, ['Control Hold', 'Body Weapons', 'Takedown']))
		{
			$subType = '';
			if (sizeof($this->sessData->dataSets["ForceSubType"]) > 0)
			{
				foreach ($this->sessData->dataSets["ForceSubType"] as $forceSubType)
				{
					if ($event->ForID == $forceSubType->ForceSubForceID)
					{
						$subType .= ', ' . $GLOBALS["DB"]->getDefValue('Force Type - '.$forceType, $forceSubType->ForceSubType);
					}
				}
			}
			if (trim($subType) != '') $deets[] = '<span>' . $forceType . ':</span></td><td>' . substr($subType, 1);
		}
		if (trim($event->ForGunAmmoType) != '') 		$deets[] = '<span>Gun Ammo:</span></td><td>'.$GLOBALS["DB"]->getDefValue('Gun Ammo Types', $event->ForGunAmmoType);
		if (trim($event->ForGunDesc) != '') 			$deets[] = '<span>Gun Description:</span></td><td>'.$event->ForGunDesc;
		if (trim($event->ForHowManyTimes) != '') 		$deets[] = '<span># of Times Struck?</span></td><td>'.$event->ForHowManyTimes;
		$BodyParts = '';
		if (isset($this->sessData->dataSets["BodyParts"]) && sizeof($this->sessData->dataSets["BodyParts"]) > 0)
		{
			foreach ($this->sessData->dataSets["BodyParts"] as $part)
			{
				if ($event->ForID == $part->BodyForceID) $BodyParts .= ', ' . $GLOBALS["DB"]->getDefValue('Body Part', $part->BodyPart);
			}
		}
		if (trim($BodyParts) != '') 					$deets[] = '<span>Body Parts:</span></td><td>'.substr($BodyParts, 1);
		if (trim($event->ForWhileHandcuffed) != '') 	$deets[] = '<span>Struck While Handcuffed?</span></td><td>'.$this->printYN($event->ForWhileHandcuffed);
		if (trim($event->ForWhileHeldDown) != '') 		$deets[] = '<span>Struck While Held Down?</span></td><td>'.$this->printYN($event->ForWhileHeldDown);
		return $deets;
	}
	
	protected function printHapArrest($event)
	{
		$deets = array();
		if (trim($event->ArstStatedReason) != '') 		$deets[] = '<span>Reason Given:</span></td><td>'.$this->printYN($event->ArstStatedReason);
		if (trim($event->ArstStatedReasonDesc) != '') 	$deets[] = '<span>Reason Description:</span></td><td>'.$event->ArstStatedReasonDesc;
		if (trim($event->ArstMiranda) != '') 			$deets[] = '<span>Miranda Rights Read?</span></td><td>'.$this->printYN($event->ArstMiranda);
		if (trim($event->ArstSITA) != '') 				$deets[] = '<span>Search Incident To Arrest?</span></td><td>'.$this->printYN($event->ArstSITA);
		if (!in_array(intVal($event->ArstNoChargesFiled), array(0, 188))) $deets[] = $GLOBALS["DB"]->getDefValue('No Charges Filed', $event->ArstNoChargesFiled);
		return $deets;
	}
	
	protected function eventAllegations($incEveID)
	{
		$allegs = array();
		if (isset($this->sessData->dataSets["Allegations"]) && sizeof($this->sessData->dataSets["Allegations"]) > 0)
		{
			foreach ($this->sessData->dataSets["Allegations"] as $alleg)
			{
				if ($alleg->AlleEventSequenceID == $incEveID)
				{
					$desc = $alleg->AlleDescription;
					if ($alleg->AlleType == 'Intimidating Display Of Weapon')
					{
						$desc = $alleg->AlleIntimidatingWeapon . ', ' . $alleg->AlleIntimidatingWeaponType . ', ' . $desc;
					}
					$allegs[] = array($alleg->AlleID, $GLOBALS["DB"]->getDefValue('Allegation Type', $alleg->AlleType), $desc);
				}
			}
		}
		return $allegs;
	}
	
	protected function printOrder($ord)
	{
		$retVal = '<span>Order From Officer'; /* (<span style="color: #000;">' . "\n";
		$offs = $this->getLinkedToEvent('Officer', -3, -3, $ord->OrdID);
		if (sizeof($offs) > 0) {
			foreach ($offs as $i => $offID) {
				$retVal .= (($i > 0) ? ', ' : '') . $this->getCivilianNameFromID($offID);
			}
		}
		$retVal .= '</span> to <span style="color: #000;">' . "\n";
		$civs = $this->getLinkedToEvent('Civilian', -3, -3, $ord->OrdID);
		if (sizeof($civs) > 0) {
			foreach ($civs as $i => $civID) {
				$retVal .= (($i > 0) ? ', ' : '') . $this->getCivilianNameFromID($civID);
			}
		}
		$retVal .= '</span>): */ $retVal .= '</span></td><td>' . $ord->OrdDescription;
		return $retVal;
	}
	
	
	
	protected function groupInjuries()
	{
		$this->hasMedicalCare = false;
		$this->injuries = array();
		if (isset($this->sessData->dataSets["Injuries"]) && sizeof($this->sessData->dataSets["Injuries"]) > 0)
		{
			foreach ($this->sessData->dataSets["Injuries"] as $inj)
			{
				if (!isset($this->injuries[$inj->InjSubjectID]))
				{
					$name = $this->getCivReportName($inj->InjSubjectID);
					if (strpos($name, '>Anonymous<') !== false) $name = 'Complainant';
					$this->injuries[$inj->InjSubjectID] = [$name, [], []];
				}
				$this->injuries[$inj->InjSubjectID][1][] = [$inj, $this->getInjBodyParts($inj)];
			}
			if (isset($this->sessData->dataSets["InjuryCare"]) && sizeof($this->sessData->dataSets["InjuryCare"]) > 0)
			{
				$this->hasMedicalCare = true;
				foreach ($this->injuries as $civID => $inj)
				{
					foreach ($this->sessData->dataSets["InjuryCare"] as $i => $injCare)
					{
						if ($civID == $injCare->InjCareSubjectID) $this->injuries[$civID][2] = $this->injCareDeets($injCare);
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
		if ($parts && sizeof($parts) > 0)
		{
			foreach ($parts as $part)
			{
				$bodyParts[] = $GLOBALS["DB"]->getDefValue('Body Part', $part->BodyPart);
			}
		}
		if (sizeof($bodyParts) == 1 && $bodyParts[0] == 'Unknown')
		{
			$bodyParts[0] = 'Body Part: Unknown';
		}
		return $bodyParts;
	}
	
	protected function injCareDeets($injCare)
	{
		$deets0 = $deets1 = $deets2 = array();
		if (trim($injCare->InjCareHospitalTreated) != '') 	$deets0[] = '<span>Hospital Treated:</span></td><td>'.$injCare->InjCareHospitalTreated;
		if (trim($injCare->InjCareResultInDeath) != '') 	$deets0[] = '<span>Resulted In Death?</span></td><td>'.$this->printYN($injCare->InjCareResultInDeath);
		if (trim($injCare->InjCareTimeOfDeath) != '' && trim($injCare->InjCareResultInDeath) == 'Y') $deets0[] = '<span>Time of Death:</span></td><td>'.date("n/j/y g:ia", strtotime($injCare->InjCareTimeOfDeath));
		
		if (trim($injCare->InjCareDoctorNameFirst) != '' || trim($injCare->InjCareDoctorNameLast) != '' 
			|| trim($injCare->InjCareDoctorEmail) != '' || trim($injCare->InjCareDoctorPhone) != '')
		{
			$deets1[] = '<i>Doctor Information...</i>';
			$name = trim($injCare->InjCareDoctorNameFirst . ' ' . $injCare->InjCareDoctorNameLast);
			if ($this->v["view"] == 'Public' && in_array($this->sessData->dataSets["Complaints"][0]->ComPrivacy, [205, 206, 207]))
			{
				if ($name != '') $deets1[] = '<span>Name:</span></td><td><i class="gry6">Not public</i>';
				if (trim($injCare->InjCareDoctorEmail) != '') 		$deets1[] = '<span>Email:</span></td><td><i class="gry6">Not public</i>';
				if (trim($injCare->InjCareDoctorPhone) != '') 		$deets1[] = '<span>Phone Number:</span></td><td><i class="gry6">Not public</i>';
			}
			else
			{
				if (trim($injCare->InjCareDoctorNameFirst) != '') 	$deets1[] = '<span>First Name:</span></td><td>'.$injCare->InjCareDoctorNameFirst;
				if (trim($injCare->InjCareDoctorNameLast) != '') 	$deets1[] = '<span>Last Name:</span></td><td>'.$injCare->InjCareDoctorNameLast;
				if (trim($injCare->InjCareDoctorEmail) != '') 		$deets1[] = '<span>Email Address:</span></td><td>'.$injCare->InjCareDoctorEmail;
				if (trim($injCare->InjCareDoctorPhone) != '') 		$deets1[] = '<span>Phone Number:</span></td><td>'.$injCare->InjCareDoctorPhone;
			}
		}
		
		if (trim($injCare->InjCareEmergencyOnScene) == 'Y')
		{
			$deets2[] = '<i>Emergency Medical Staff...</i>';
			if (trim($injCare->InjCareEmergencyDeptName) != '') 	$deets2[] = '<span>Department Name:</span></td><td>'.$injCare->InjCareEmergencyDeptName;
			$name = trim($injCare->InjCareEmergencyNameFirst . ' ' . $injCare->InjCareEmergencyNameLast);
			if ($this->v["view"] == 'Public' && in_array($this->sessData->dataSets["Complaints"][0]->ComPrivacy, [205, 206, 207]))
			{
				if ($name != '') $deets2[] = '<span>Name:</span></td><td><i class="gry6">Not public</i>';
				if (trim($injCare->InjCareEmergencyIDnumber) != '') 	$deets2[] = '<span>ID#:</span></td><td><i class="gry6">Not public</i>';
				if (trim($injCare->InjCareEmergencyVehicleNumber) != '') $deets2[] = '<span>Vehicle Number:</span></td><td><i class="gry6">Not public</i>';
				if (trim($injCare->InjCareEmergencyLicenceNumber) != '') $deets2[] = '<span>License Plate:</span></td><td><i class="gry6">Not public</i>';
			}
			else
			{
				if (trim($injCare->InjCareEmergencyNameFirst) != '') 	$deets2[] = '<span>First Name:</span></td><td>'.$injCare->InjCareEmergencyNameFirst;
				if (trim($injCare->InjCareEmergencyNameLast) != '') 	$deets2[] = '<span>Last Name:</span></td><td>'.$injCare->InjCareEmergencyNameLast;
				if (trim($injCare->InjCareEmergencyIDnumber) != '') 	$deets2[] = '<span>ID#:</span></td><td>'.$injCare->InjCareEmergencyIDnumber;
				if (trim($injCare->InjCareEmergencyVehicleNumber) != '') $deets2[] = '<span>Vehicle Number:</span></td><td>'.$injCare->InjCareEmergencyVehicleNumber;
				if (trim($injCare->InjCareEmergencyLicenceNumber) != '') $deets2[] = '<span>License Plate:</span></td><td>'.$injCare->InjCareEmergencyLicenceNumber;
			}
		}
		return [$deets0, $deets1, $deets2];
	}
	
}


?>