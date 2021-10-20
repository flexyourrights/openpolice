<?php
/**
  * OpenPoliceUserStatCalcs totals up multiple user stats.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.3.2
  */
namespace FlexYourRights\OpenPolice\Controllers\Stats;

class OpenPoliceUserStatCalcs
{
    public $title       = '';
    public $comIDs      = [];
    public $comCats     = [];
    public $catsDur     = [];
    public $deptIDs     = [];

    public $sessDur     = 0;
    public $deptDur     = 0;
    public $origDur     = 0;

    public $cntOnline   = 0;
    public $cntCallDept = 0;
    public $cntCallIA   = 0;
    public $orig        = [];

    public function __construct($title = '')
    {
        $this->title = $title;
    }

    public function getAvgMinutesPerDept()
    {
        if (sizeof($this->deptIDs) > 0) {
            return ($this->deptDur/60)/sizeof($this->deptIDs);
        }
        return 0;
    }

    public function getMinutesPerNewDept()
    {
        if (sizeof($this->orig) > 0) {
            return ($this->origDur/60)/sizeof($this->orig);
        }
        return 0;
    }

    public function getMinutesPerPastDept()
    {
        $pastHrs = ($this->deptDur-$this->origDur)/60;
        $pastCnt = sizeof($this->deptIDs)-sizeof($this->orig);
        if ($pastCnt > 0) {
            return $pastHrs/$pastCnt;
        }
        return 0;
    }
}

