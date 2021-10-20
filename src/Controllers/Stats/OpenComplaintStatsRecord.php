<?php
/**
  * OpenComplaintStatsRecord stores only the key complaint data needed
  * for the most common aggregate analysis.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.3.1
  */
namespace FlexYourRights\OpenPolice\Controllers\Stats;

use DB;
use DateTime;
use App\Models\OPAllegSilver;
use App\Models\OPComplaints;
use App\Models\OPLinksComplaintDept;
use App\Models\OPPhysicalDescRace;
use App\Models\OPzEditOversight;

class OpenComplaintStatsRecord
{
	public $comID   = 0;
    public $date    = 0;
	public $chrg    = '';
	public $state   = '';
    public $zip     = '';

	public $civID   = 0;
	public $age     = 0;
	public $gend    = 0;
	public $gendOth = '';
	public $race    = 0;
	public $race2   = 0;

    public $allegs  = [];
    public $events  = [];
    public $force   = '';
    public $stop    = '';
    public $gold    = '';

    public $statusPub = '';
    public $transpar  = '';
    public $weeks2    = '';
    public $newDept   = '';

    /**
     * Load object with core data
     *
     * @param  App\Models\OPComplaints $comID
     * @param  App\Models\OPCivilians $civ
     */
	public function __construct($com, $civ = null)
	{
		$this->comID = $com->com_id;
		$this->date  = strtotime($com->inc_time_start);
		$this->state = $com->inc_address_state;
		if (isset($com->inc_address_zip)) {
			$this->zip = $com->inc_address_zip;
		}
		$this->loadComplaintCharges($com);
		$this->loadComplaintAllegations();
        $this->loadComplaintPublishing($com);
		if ($civ && isset($civ->civ_id)) {
			$this->civID = $civ->civ_id;
			$this->loadComplaintAges($civ);
			$this->loadComplaintRaces($civ);
			$this->loadComplaintGenders($civ);
		}
	}

    /**
     * Standardizes civilian records into groups by age.
     *
     * @param  App\Models\OPComplaints $com
     * @return boolean
     */
	private function loadComplaintCharges($com)
	{
		if (isset($com->com_anyone_charged)) {
			if (in_array(trim($com->com_anyone_charged), ['Y', '?'])
				&& isset($com->com_all_charges_resolved)
				&& trim($com->com_all_charges_resolved) != 'Y') {
				$this->chrg = 'Y';
			} else {
				$this->chrg = 'N';
			}
		}
        $this->gold = 'N';
        if (isset($com->com_award_medallion)
            && trim($com->com_award_medallion) == 'Gold') {
            $this->gold = 'Y';
        }
		return true;
	}

    /**
     * Standardizes civilian records into groups by age.
     *
     * @param  App\Models\OPCivilians $civ
     * @return boolean
     */
	private function loadComplaintAges($civ)
	{
		if (isset($civ->phys_age)
			&& intVal($civ->phys_age) > 0) {
			$this->age = intVal($civ->phys_age);
			if (in_array($this->age, [108, 109])) {
				$this->age = 75; // 75 and Over
			}
		} elseif (isset($civ->prsn_birthday)
			&& trim($civ->prsn_birthday) != '') {
			$now = new DateTime();
			$dob = new DateTime($civ->prsn_birthday);
			$difference = $now->diff($dob);
			$age = $difference->y;
			if ($age <= 15) {
				$this->age = 101;
			} elseif (16 <= $age && $age <= 24) {
				$this->age = 102;
			} elseif (25 <= $age && $age <= 34) {
				$this->age = 103;
			} elseif (35 <= $age && $age <= 44) {
				$this->age = 104;
			} elseif (45 <= $age && $age <= 54) {
				$this->age = 105;
			} elseif (55 <= $age && $age <= 64) {
				$this->age = 106;
			} elseif (65 <= $age && $age <= 74) {
				$this->age = 107;
			} elseif (75 <= $age) {
				$this->age = 75;
			}
		}
		return true;
	}

    /**
     * Standardizes civilian records into groups by gender.
     *
     * @param  App\Models\OPCivilians $civ
     * @return boolean
     */
	private function loadComplaintGenders($civ)
	{
		$this->gend = $this->gendOth = '';
		if (isset($civ->phys_gender_other) && trim($civ->phys_gender_other) != '') {
			$this->gendOth = trim($civ->phys_gender_other);
		}
		if (isset($civ->phys_gender) && trim($civ->phys_gender) == 'O') {
			$this->gend = 'O';
		} elseif (isset($civ->phys_gender) && trim($civ->phys_gender) == 'M') {
			$this->gend = 'M';
		} elseif (isset($civ->phys_gender) && trim($civ->phys_gender) == 'F') {
			$this->gend = 'F';
		} elseif ($this->gendOth != '') {
			$this->gend = 'O';
		}
		return true;
	}

    /**
     * Logs this civilian's races.
     *
     * @param  App\Models\OPCivilians $civ
     * @return boolean
     */
	private function loadComplaintRaces($civ)
	{
		$this->race = 0;
		$this->race2 = 0;
		if (isset($civ->phys_id)) {
			$raceChk = OPPhysicalDescRace::select('phys_race_race')
				->where('phys_race_phys_desc_id', $civ->phys_id)
				->where('phys_race_race', '>', 0)
				->where('phys_race_race', 'NOT LIKE', 325)
				->get();
			if ($raceChk->isNotEmpty()) {
				$races = [];
				foreach ($raceChk as $race) {
					if (isset($race->phys_race_race) && intVal($race->phys_race_race) > 0) {
						$races[] = intVal($race->phys_race_race);
					}
				}
				if (sizeof($races) == 1) {
					$this->race = $races[0];
					if (in_array($races[0], [319, 320, 323])) {
						$this->race2 = $races[0];
					} else {
						$this->race2 = 324;
					}
				} elseif (sizeof($races) > 1) {
					$this->race = 2;
					$this->race2 = 324;
				}
			}
		}
		return true;
	}

    /**
     * Logs this complaint's allegations.
     *
     * @return boolean
     */
	private function loadComplaintAllegations()
	{
		$this->allegs = $this->events = [];
		$this->force = $this->stop = 'N';
		$allegSilv = OPAllegSilver::where('alle_sil_complaint_id', $this->comID)
			->first();
		$allegations = $GLOBALS["CUST"]->getAllegations($allegSilv);
		if (sizeof($allegations) > 0) {
			foreach ($allegations as $alleg) {
				$this->allegs[] = $alleg->defID;
				if ($alleg->name == 'Unreasonable Force') {
					$this->force = 'Y';
				}
			}
		}
        $events = $GLOBALS["CUST"]->getComplaintEventTypes($allegSilv);
        if (sizeof($events) > 0) {
            foreach ($events as $event) {
                $this->events[] = $event;
                if ($event == 'stop') {
                    $this->stop = 'Y';
                }
            }
        }
        return true;
    }

    /**
     * Logs this complaint's publishing situations.
     *
     * @return boolean
     */
    private function loadComplaintPublishing($com)
    {
        $this->statusPub = $this->transpar = $this->newDept = 'N';
        $this->weeks2 = '';
        if (isset($com->com_publish_user_name)
            && isset($com->com_publish_officer_name)
            && intVal($com->com_publish_user_name) == 1
            && intVal($com->com_publish_officer_name) == 1) {
            $this->transpar = 'Y';
        }
        $filedDefs = [
            'Submitted to Oversight',
            'Received by Oversight',
            'Declined To Investigate (Closed)',
            'Investigated (Closed)'
        ];
        if (isset($com->com_status)) {
            $status = $GLOBALS["SL"]->def->getVal('Complaint Status', $com->com_status);
            if ($status == 'OK to Submit to Oversight') {
                $this->statusPub = 'O';
            } elseif (in_array($status, $filedDefs)) {
                $this->statusPub = 'Y';
            }
        }

        $cutoff = mktime(date("H"), date("i"), date("s"), date("n"), date("j")-14, date("Y"));
        if (isset($com->com_record_submitted)
            && strtotime($com->com_record_submitted) >= $cutoff) {
            $this->weeks2 = '2';
        } else {
            $cutoff = mktime(date("H"), date("i"), date("s"), date("n")-2, date("j")-14, date("Y"));
            if (isset($com->com_record_submitted)
                && strtotime($com->com_record_submitted) >= $cutoff) {
                $this->weeks2 = '10';
            }
        }

        $foundNew = false;
        $depts = OPLinksComplaintDept::where('lnk_com_dept_complaint_id', $com->com_id)
            ->whereNotIn('lnk_com_dept_dept_id', [18124]) // not sure
            ->get();
        if ($depts->isNotEmpty()) {
            foreach ($depts as $dept) {
                $chk = OPzEditOversight::where('zed_over_over_type', 303)
                    ->where('zed_over_zed_dept_id', $dept->lnk_com_dept_dept_id)
                    ->where(function($query) {
                        $query->where('zed_over_online_research',  '>', 0)
                              ->orWhere('zed_over_made_dept_call', '>', 0)
                              ->orWhere('zed_over_made_ia_call',   '>', 0);
                    })
                    ->where('created_at', '<=', $com->com_record_submitted)
                    ->first();
                if (!$chk || !isset($chk->created_at)) {
                    $foundNew = true;
                }
            }
        }
        if ($foundNew) {
            $this->newDept = 'Y';
        }
		return true;
	}



}
