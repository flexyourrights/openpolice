<?php
/**
  * OpenPoliceSearcher extends the SurvLoop Searcher for some hard-coded overrides.
  *
  * Open Police Complaints
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since 0.0
  */
namespace OpenPolice\Controllers;

use Storage\App\Models\OPComplaints;
use Storage\App\Models\OPLinksComplaintDept;
use SurvLoop\Controllers\Searcher;

class OpenPoliceSearcher extends Searcher
{
    protected function processSearchFilt($key, $val)
    {
        if ($key == 'd') {
            $deptComs = $both = [];
            $chk = OPLinksComplaintDept::whereIn('LnkComDeptDeptID', $val)
                ->get();
            if ($chk->isNotEmpty()) {
                foreach ($chk as $com) {
                    $deptComs[] = $com->LnkComDeptComplaintID;
                }
                $chk = OPComplaints::whereIn('ComID', $deptComs)
                    ->select('ComID')
                    ->get();
                $deptComs = [];
                if ($chk->isNotEmpty()) {
                    foreach ($chk as $com) {
                        if (in_array($com->ComID, $this->allPublicFiltIDs)) {
                            $both[] = $com->ComID;
                        }
                    }
                }
            }
            $this->allPublicFiltIDs = $both;
        }
        return true;
    }
    
    
    
}