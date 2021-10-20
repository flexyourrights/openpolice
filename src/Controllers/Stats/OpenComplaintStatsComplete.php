<?php
/**
  * OpenComplaintStatsComplete is a helper class which manages
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

class OpenComplaintStatsComplete extends OpenComplaintStats
{
    /**
     * Prints first demographic report of complaint data.
     *
     * @return string
     */
    public function printComplaintStatsBasic()
    {
        set_time_limit(300);
        $this->v["statTbls"] = [ 'statAllegs', 'statEvents' ]; //, 'statPublish', 'statDeptsNew'
        $this->printComplaintStatsBasicInit();
        $this->statsLoadMaps();
        $this->v["comData"] = [];
        if (sizeof($this->v["coreRecs"]) > 0) {
            foreach ($this->v["coreRecs"] as $ind => $com) {
                $comStat = new OpenComplaintStatsRecord($com);
                $this->printComplaintStatsBasicAddRow($comStat);
            }
        }
        $this->statsRunCalcs();

        $this->fillComplaintStatsStatesTbl('statesTbl');
        arsort($this->v["statesCnt"]);
        $this->fillComplaintStatsStatesTbl('statesTbl2');
        $this->fillComplaintStatsPublishTbl('publishTbl');
        $this->fillComplaintStatsNewDeptTbl('newDeptTbl');

//echo '<pre>'; print_r($this->v["statPublish"]); echo '</pre>'; exit;

        $this->v["goldStopCnt"] = $this->v["statEvents"]->dat["aY"]["dat"]["a"]["sum"];
        $this->v["goldStopPerc"] = $this->v["goldStopCnt"]
            /$this->v["statEvents"]->dat["1"]["cnt"];

        $GLOBALS["SL"]->x["needsCharts"] = true;
        return view(
            'vendor.openpolice.nodes.1190-report-basic-stats',
            $this->v
        )->render();
    }

    /**
     * Initialize the reports data tables.
     *
     * @return boolean
     */
    private function printComplaintStatsBasicInit()
    {
        $this->v["statAllegs"] = new SurvStatsGraph;
        $this->v["statAllegs"]->addFiltArr('chrg', 'Unresolved Charges', $this->v["fltCharges"]);
        foreach ($GLOBALS["CUST"]->worstAllegs as $allegType) {
            $this->v["statAllegs"]->addDataType('aleg' . $allegType->defID, $allegType->name);
        }

        $this->v["statEvents"] = new SurvStatsGraph;
        $this->v["statEvents"]->addFiltArr('gold', 'Go Gold', $this->v["fltGold"]);
        $events = $GLOBALS["CUST"]->getEventTypeList();
        if (sizeof($events) > 0) {
            foreach ($events as $event) {
                $eAbbr = 'eve' . $event->abbr;
                $this->v["statEvents"]->addDataType($eAbbr, $event->name);
            }
        }

        // Tally Status and Full Transparency
        $this->v["publishCnts"] = [
            "N" => [ "Y" => 0, "N" => 0 ],
            "O" => [ "Y" => 0, "N" => 0 ],
            "Y" => [ "Y" => 0, "N" => 0 ]
        ];
        // Tally Last Two Weeks and New Department
        $this->v["deptCnts"] = [
            "tot" => 0,
            "2"   => [ "Y" => 0, "N" => 0 ],
            "10"  => [ "Y" => 0, "N" => 0 ]
        ];
        /*
        $this->v["statPublish"] = new SurvStatsGraph;
        $this->v["statPublish"]->addFiltArr('status', 'Status',  $this->v["fltStatusPub"]);
        $this->v["statPublish"]->addFiltArr('transpar', 'Full Transparency',  $this->v["fltTransparent"]);

        $this->v["statDeptsNew"] = new SurvStatsGraph;
        $this->v["statDeptsNew"]->addFiltArr('weeks2',  'Last Two Weeks',  $this->v["fltTwoWeeks"]);
        $this->v["statDeptsNew"]->addFiltArr('newDept', 'New Department',  $this->v["fltNewDept"]);
        */

        $this->v["statesCntTot"] = 0;
        $this->v["statesCnt"] = [];
        foreach ($GLOBALS["SL"]->states->stateList as $abbr => $name) {
            $this->v["statesCnt"][$abbr] = 0;
        }
    }

    /**
     * Adds one civilian record to all of this report's tables.
     *
     * @param  FlexYourRights\OpenPolice\Controllers\OpenComplaintStatsRecord $com
     * @return boolean
     */
    private function printComplaintStatsBasicAddRow($com)
    {
        if (sizeof($com->allegs) > 0) {
            $this->v["statAllegs"]->resetRecFilt();
            $this->v["statAllegs"]->addRecFilt('chrg', $com->chrg, $com->comID);
            if (sizeof($com->allegs) > 0) {
                foreach ($com->allegs as $alleg) {
                    $this->v["statAllegs"]->addRecDat('aleg' . $alleg, 1, $com->comID);
                }
            }
        }

        $this->v["statEvents"]->resetRecFilt();
        $this->v["statEvents"]->addRecFilt('gold', $com->gold, $com->comID);
        $this->v["statEvents"]->addRecDat('date', $com->date, $com->comID);
        if (sizeof($com->events) > 0) {
            if (sizeof($com->events) > 0) {
                foreach ($com->events as $event) {
                    $this->v["statEvents"]->addRecDat('eve' . $event, 1, $com->comID);
                }
            }
        }

//echo 'adding to statPublish — status: ' . $com->statusPub . ',  transpar: ' . $com->transpar . ',  date: ' . $com->date . ', <br />';
        $this->v["publishCnts"][$com->statusPub][$com->transpar]++;
        if ($com->weeks2 != '') {
            $this->v["deptCnts"][$com->weeks2][$com->newDept]++;
            $this->v["deptCnts"]["tot"]++;
        }
        /*
        $this->v["statPublish"]->resetRecFilt();
        $this->v["statPublish"]->addRecFilt('status', $com->statusPub, $com->comID);
        $this->v["statPublish"]->addRecDat('transpar', $com->transpar, $com->comID);
        $this->v["statPublish"]->addRecDat('date', $com->date, $com->comID);

        $this->v["statDeptsNew"]->resetRecFilt();
        $this->v["statDeptsNew"]->addRecFilt('weeks2', $com->weeks2, $com->comID);
        $this->v["statDeptsNew"]->addRecFilt('newDept', $com->newDept, $com->comID);
        $this->v["statDeptsNew"]->addRecDat('date', $com->date, $com->comID);
        */
        if ($com->state && trim($com->state) != '') {
            if (isset($this->v["statesCnt"][$com->state])) {
                $this->v["statesCnt"][$com->state]++;
                $this->v["statesCntTot"]++;
            }
        }
        return true;
    }

    /**
     * Adds one civilian record to all of this report's tables.
     *
     * @param  FlexYourRights\OpenPolice\Controllers\OpenComplaintStatsRecord $com
     * @return boolean
     */
    private function fillComplaintStatsStatesTbl($tblName)
    {
        $this->v[$tblName] = new SurvStatsTbl;
        $this->v[$tblName]->startNewRow('brdBotGrey');
        $this->v[$tblName]->addRowCell();
        $this->v[$tblName]->addHeaderCellSpan('Frequency');

        $this->v[$tblName]->startNewRow('brdBotBlue2');
        $this->v[$tblName]->addHeaderCell('Total Record Count');
        $this->v[$tblName]->addRowCell();
        $this->v[$tblName]->addRowCellNumber($this->v["statesCntTot"], 'slGrey');
        foreach ($this->v["statesCnt"] as $abbr => $cnt) {
            $this->v[$tblName]->startNewRow();
            $this->v[$tblName]->addHeaderCell($GLOBALS["SL"]->getState($abbr));
            $this->v[$tblName]->addRowCellPerc($cnt/$this->v["statesCntTot"]);
            $this->v[$tblName]->addRowCellNumber($cnt, 'slGrey');
        }
    }

    /**
     * Adds one civilian record to all of this report's tables.
     *
     * @param  string tblName
     * @return void
     */
    private function fillComplaintStatsPublishTbl($tblName = 'publishTbl')
    {
        $allTot = sizeof($this->v["coreRecs"]);
        $this->v[$tblName] = new SurvStatsTbl;
        $this->v[$tblName]->startNewRow('brdBotGrey');
        $this->v[$tblName]->addRowCell();
        $this->v[$tblName]->addHeaderCellSpan('Frequency', 'brdRgtBlue2');
        $this->v[$tblName]->addHeaderCellSpan('Full Transparency', 'brdLftGrey');
        $this->v[$tblName]->addHeaderCellSpan('Not Fully Transparent', 'brdLftGrey');

        $this->v[$tblName]->startNewRow('brdBotBlue2');
        $this->v[$tblName]->addHeaderCell('Total Record Count');
        $this->v[$tblName]->addRowCell('');
        $this->v[$tblName]->addRowCellNumber($allTot, 'slGrey brdRgtBlue2');
        foreach (['Y', 'N'] as $transpar) {
            $this->v[$tblName]->addRowCell('', 'brdLftGrey');
            $tot = $this->v["publishCnts"]["Y"][$transpar]
                +$this->v["publishCnts"]["O"][$transpar]
                +$this->v["publishCnts"]["N"][$transpar];
            $this->v[$tblName]->addRowCellNumber($tot, 'slGrey');
        }
        foreach (['Y', 'O', 'N'] as $status) {
            $this->v[$tblName]->startNewRow();
            $this->v[$tblName]->addHeaderCell($this->getStatusPubTitle($status));
            $tot = $this->v["publishCnts"][$status]["Y"]
                +$this->v["publishCnts"][$status]["N"];
            $perc = 0;
            if ($allTot > 0) {
                $perc = $tot/$allTot;
            }
            $this->v[$tblName]->addRowCellPerc($perc);
            $this->v[$tblName]->addRowCellNumber($tot, 'slGrey brdRgtBlue2');
            foreach (['Y', 'N'] as $transpar) {
                $cnt = $this->v["publishCnts"][$status][$transpar];
                $tot = $this->v["publishCnts"]["Y"][$transpar]
                    +$this->v["publishCnts"]["O"][$transpar]
                    +$this->v["publishCnts"]["N"][$transpar];
                $perc = 0;
                if ($tot > 0) {
                    $perc = $cnt/$tot;
                }
                $this->v[$tblName]->addRowCellPerc($perc, 'brdLftGrey');
                $this->v[$tblName]->addRowCellNumber($cnt, 'slGrey');
            }
        }
    }

    /**
     * Adds one civilian record to all of this report's tables.
     *

        $this->v["deptCnts"][$com->weeks2][$com->newDept]++;

     * @param  string tblName
     * @return void
     */
    private function fillComplaintStatsNewDeptTbl($tblName = 'newDeptTbl')
    {
        $allTot = $this->v["deptCnts"]["tot"];
        $this->v[$tblName] = new SurvStatsTbl;
        $this->v[$tblName]->startNewRow('brdBotGrey');
        $this->v[$tblName]->addRowCell();
        $this->v[$tblName]->addHeaderCellSpan('Frequency', 'brdRgtBlue2');
        $this->v[$tblName]->addHeaderCellSpan('Within Last 2 Weeks', 'brdLftGrey');
        $this->v[$tblName]->addHeaderCellSpan('Between Last 2-10 Weeks', 'brdLftGrey');

        $this->v[$tblName]->startNewRow('brdBotBlue2');
        $this->v[$tblName]->addHeaderCell('Total Record Count');
        $this->v[$tblName]->addRowCell('');
        $this->v[$tblName]->addRowCellNumber($allTot, 'slGrey brdRgtBlue2');
        foreach (['2', '10'] as $weeks2) {
            $this->v[$tblName]->addRowCell('', 'brdLftGrey');
            $tot = $this->v["deptCnts"][$weeks2]["Y"]+$this->v["deptCnts"][$weeks2]["N"];
            $this->v[$tblName]->addRowCellNumber($tot, 'slGrey');
        }
        foreach (['Y', 'N'] as $newDept) {
            $this->v[$tblName]->startNewRow();
            $this->v[$tblName]->addHeaderCell($this->getNewDeptTitle($newDept));
            $tot = $this->v["deptCnts"]["2"][$newDept]+$this->v["deptCnts"]["10"][$newDept];
            $perc = 0;
            if ($allTot > 0) {
                $perc = $tot/$allTot;
            }
            $this->v[$tblName]->addRowCellPerc($perc);
            $this->v[$tblName]->addRowCellNumber($tot, 'slGrey brdRgtBlue2');
            foreach (['2', '10'] as $weeks2) {
                $cnt = $this->v["deptCnts"][$weeks2][$newDept];
                $tot = $this->v["deptCnts"][$weeks2]["Y"]+$this->v["deptCnts"][$weeks2]["N"];
                $perc = 0;
                if ($tot > 0) {
                    $perc = $cnt/$tot;
                }
                $this->v[$tblName]->addRowCellPerc($perc, 'brdLftGrey');
                $this->v[$tblName]->addRowCellNumber($cnt, 'slGrey');
            }
        }
    }


}