<?php
/**
  * OpenComplaintStatsDemo is a helper class which manages
  * aggregate calculations related to complaints.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.3.1
  */
namespace FlexYourRights\OpenPolice\Controllers\Stats;

use DB;
use DateTime;
use App\Models\OPComplaints;
use RockHopSoft\Survloop\Controllers\Stats\SurvStatsGraph;
use RockHopSoft\Survloop\Controllers\Stats\SurvStatsTbl;
use FlexYourRights\OpenPolice\Controllers\Stats\OpenComplaintStatsRecord;
use FlexYourRights\OpenPolice\Controllers\Stats\OpenComplaintStats;

class OpenComplaintStatsDemo extends OpenComplaintStats
{
    /**
     * Prints first demographic report of complaint data.
     *
     * @return string
     */
    public function printComplaintStatsDemo()
    {
        set_time_limit(300);
        $this->v["statTbls"] = [
            'statRaces', 'statRaceAges', 'statRaces2', 'statRace2Ages',
            'statAges', 'statGends', 'statChargeAll', 'statForceAll',
            'statForceAges', 'statForceRaces', 'statForceRaces2', 'statForceGends'
        ];
        $this->loadComplaintDeets();
        $this->printComplaintStatsDemoInit();
        $this->statsLoadMaps();
        if (sizeof($this->v["civsData"]) > 0) {
            foreach ($this->v["civsData"] as $i => $civ) {
                $this->printComplaintStatsDemoAddRow($civ);
            }
        }
        $this->statsRunCalcs();
        $GLOBALS["SL"]->x["needsCharts"] = true;
        return view(
            'vendor.openpolice.nodes.2013-report-demo-stats',
            $this->v
        )->render();
    }

    /**
     * Initialize the reports data tables.
     *
     * @return boolean
     */
    private function printComplaintStatsDemoInit()
    {
        $this->v["statAges"] = new SurvStatsGraph;
        $this->v["statAges"]->addFiltArr('age', 'Age Groups', $this->v["fltAges"]);
        $this->v["statAges"]->addFiltArr('chrg', 'Unresolved Charges', $this->v["fltCharges"]);
        $this->v["statAges"]->addFiltArr('force', 'Unreasonable Use of Force', $this->v["fltForce"]);

        $this->v["statRaces"] = new SurvStatsGraph;
        $this->v["statRaces"]->addFiltArr('race', 'Races', $this->v["fltRaces"]);
        $this->v["statRaces"]->addFiltArr('chrg', 'Unresolved Charges', $this->v["fltCharges"]);
        $this->v["statRaces"]->addFiltArr('force', 'Unreasonable Use of Force', $this->v["fltForce"]);

        $this->v["statRaces2"] = new SurvStatsGraph;
        $this->v["statRaces2"]->addFiltArr('race2', 'Races', $this->v["fltRaces2"]);
        $this->v["statRaces2"]->addFiltArr('chrg', 'Unresolved Charges', $this->v["fltCharges"]);
        $this->v["statRaces2"]->addFiltArr('force', 'Unreasonable Use of Force', $this->v["fltForce"]);

        $this->v["statRaceAges"] = new SurvStatsGraph;
        $this->v["statRaceAges"]->addFiltArr('race', 'Races', $this->v["fltRaces"]);
        $this->v["statRaceAges"]->addFiltArr('age', 'Age Groups', $this->v["fltAges"]);
        $this->v["statRaceAges"]->addFiltArr('gend', 'Genders', $this->v["fltGenders"]);
        //$this->v["statRaceAges"]->addFiltArr('chrg', 'Unresolved Charges', $this->v["fltCharges"]);
        $this->v["statRaceAges"]->addFiltArr('force', 'Unreasonable Use of Force', $this->v["fltForce"]);

        $this->v["statRace2Ages"] = new SurvStatsGraph;
        $this->v["statRace2Ages"]->addFiltArr('race2', 'Races', $this->v["fltRaces2"]);
        $this->v["statRace2Ages"]->addFiltArr('age', 'Age Groups', $this->v["fltAges"]);
        $this->v["statRace2Ages"]->addFiltArr('gend', 'Genders', $this->v["fltGenders"]);
        //$this->v["statRace2Ages"]->addFiltArr('chrg', 'Unresolved Charges', $this->v["fltCharges"]);
        $this->v["statRace2Ages"]->addFiltArr('force', 'Unreasonable Use of Force', $this->v["fltForce"]);

        $this->v["statGends"] = new SurvStatsGraph;
        $this->v["statGends"]->addFiltArr('gend', 'Genders', $this->v["fltGenders"]);
        $this->v["statGends"]->addFiltArr('chrg', 'Unresolved Charges', $this->v["fltCharges"]);
        $this->v["statGends"]->addFiltArr('force', 'Unreasonable Use of Force', $this->v["fltForce"]);

        $this->v["statForceAges"] = new SurvStatsGraph;
        $this->v["statForceAges"]->addFiltArr('force', 'Unreasonable Use of Force', $this->v["fltForce"]);
        $this->v["statForceAges"]->addFiltArr('age', 'Age Groups', $this->v["fltAges"]);

        $this->v["statForceRaces"] = new SurvStatsGraph;
        $this->v["statForceRaces"]->addFiltArr('force', 'Unreasonable Use of Force', $this->v["fltForce"]);
        $this->v["statForceRaces"]->addFiltArr('race', 'Races', $this->v["fltRaces"]);

        $this->v["statForceRaces2"] = new SurvStatsGraph;
        $this->v["statForceRaces2"]->addFiltArr('force', 'Unreasonable Use of Force', $this->v["fltForce"]);
        $this->v["statForceRaces2"]->addFiltArr('race2', 'Races', $this->v["fltRaces2"]);

        $this->v["statForceGends"] = new SurvStatsGraph;
        $this->v["statForceGends"]->addFiltArr('force', 'Unreasonable Use of Force', $this->v["fltForce"]);
        $this->v["statForceGends"]->addFiltArr('gend', 'Genders', $this->v["fltGenders"]);

        $this->v["statForceAll"] = new SurvStatsGraph;
        $this->v["statForceAll"]->addFiltArr('force', 'Unreasonable Use of Force', $this->v["fltForce"]);

        $this->v["statChargeAll"] = new SurvStatsGraph;
        $this->v["statChargeAll"]->addFiltArr('chrg', 'Unresolved Charges', $this->v["fltCharges"]);

        return true;
    }

    /**
     * Adds one civilian record to all of this report's tables.
     *
     * @param  FlexYourRights\OpenPolice\Controllers\OpenComplaintStatsRecord $civ
     * @return boolean
     */
    private function printComplaintStatsDemoAddRow($civ)
    {
        if (isset($civ->age) && $civ->age > 0) {
            $this->v["statAges"]->resetRecFilt();
            $this->v["statAges"]->addRecFilt('age', $civ->age, $civ->civID);
            $this->v["statAges"]->addRecFilt('chrg', $civ->chrg, $civ->civID);
            $this->v["statAges"]->addRecFilt('force', $civ->force, $civ->civID);
            $this->v["statAges"]->addRecDat('date', $civ->date, $civ->civID);

            $this->v["statForceAges"]->resetRecFilt();
            $this->v["statForceAges"]->addRecFilt('force', $civ->force, $civ->civID);
            $this->v["statForceAges"]->addRecFilt('age', $civ->age, $civ->civID);
            $this->v["statForceAges"]->addRecDat('date', $civ->date, $civ->civID);
        }
        if (isset($civ->race) && $civ->race > 0 && $civ->race != 325) {
            $this->v["statRaces"]->resetRecFilt();
            $this->v["statRaces"]->addRecFilt('race', $civ->race, $civ->civID);
            $this->v["statRaces"]->addRecFilt('chrg', $civ->chrg, $civ->civID);
            $this->v["statRaces"]->addRecDat('date', $civ->date, $civ->civID);

            $this->v["statRaces2"]->resetRecFilt();
            $this->v["statRaces2"]->addRecFilt('race2', $civ->race2, $civ->civID);
            $this->v["statRaces2"]->addRecFilt('chrg', $civ->chrg, $civ->civID);
            $this->v["statRaces2"]->addRecDat('date', $civ->date, $civ->civID);

            $this->v["statForceRaces"]->resetRecFilt();
            $this->v["statForceRaces"]->addRecFilt('force', $civ->force, $civ->civID);
            $this->v["statForceRaces"]->addRecFilt('race', $civ->race, $civ->civID);
            $this->v["statForceRaces"]->addRecDat('date', $civ->date, $civ->civID);

            $this->v["statForceRaces2"]->resetRecFilt();
            $this->v["statForceRaces2"]->addRecFilt('force', $civ->force, $civ->civID);
            $this->v["statForceRaces2"]->addRecFilt('race2', $civ->race2, $civ->civID);
            $this->v["statForceRaces2"]->addRecDat('date', $civ->date, $civ->civID);
        }
        if (isset($civ->gend) && trim($civ->gend) != '') {
            $this->v["statGends"]->resetRecFilt();
            $this->v["statGends"]->addRecFilt('gend', $civ->gend, $civ->civID);
            $this->v["statGends"]->addRecFilt('chrg', $civ->chrg, $civ->civID);
            $this->v["statGends"]->addRecFilt('force', $civ->force, $civ->civID);
            $this->v["statGends"]->addRecDat('date', $civ->date, $civ->civID);

            $this->v["statForceGends"]->resetRecFilt();
            $this->v["statForceGends"]->addRecFilt('force', $civ->force, $civ->civID);
            $this->v["statForceGends"]->addRecFilt('gend', $civ->gend, $civ->civID);
            $this->v["statForceGends"]->addRecDat('date', $civ->date, $civ->civID);
        }
        if (isset($civ->race)
            && $civ->race > 0
            && $civ->race != 325
            && isset($civ->age)
            && $civ->age > 0) {
            $this->v["statRaceAges"]->resetRecFilt();
            $this->v["statRaceAges"]->addRecFilt('race', $civ->race, $civ->civID);
            $this->v["statRaceAges"]->addRecFilt('age', $civ->age, $civ->civID);
            $this->v["statRaceAges"]->addRecFilt('chrg', $civ->chrg, $civ->civID);
            $this->v["statRaceAges"]->addRecDat('date', $civ->date, $civ->civID);

            $this->v["statRace2Ages"]->resetRecFilt();
            $this->v["statRace2Ages"]->addRecFilt('race2', $civ->race2, $civ->civID);
            $this->v["statRace2Ages"]->addRecFilt('age', $civ->age, $civ->civID);
            $this->v["statRace2Ages"]->addRecFilt('chrg', $civ->chrg, $civ->civID);
            $this->v["statRace2Ages"]->addRecDat('date', $civ->date, $civ->civID);
        }

        $this->v["statForceAll"]->resetRecFilt();
        $this->v["statForceAll"]->addRecFilt('force', $civ->force, $civ->civID);
        $this->v["statForceAll"]->addRecDat('date', $civ->date, $civ->civID);

        $this->v["statChargeAll"]->resetRecFilt();
        $this->v["statChargeAll"]->addRecFilt('chrg', $civ->chrg, $civ->civID);
        $this->v["statChargeAll"]->addRecDat('date', $civ->date, $civ->civID);
        return true;
    }

}