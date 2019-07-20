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

use App\Models\OPComplaints;
use App\Models\OPLinksComplaintDept;
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
    
    public function loadAllComplaintsPublic($xtra)
    {
        $eval = "\$this->v['allcomplaints'] = " . $GLOBALS["SL"]->modelPath('Complaints') 
            . "::where('ComType', "
            . $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Police Complaint') 
            . ")->" . $xtra . "orderBy('" . $this->v["sort"][0] . "', '" . $this->v["sort"][1]
            . "')->get();";
        eval($eval);
//echo '<br /><br /><br />' . $eval . '<br />loadAllComplaintsPublic( ' . $this->v["allcomplaints"]->count() . '<br />';
        return true;
    }
    
    public function getSearchFiltQryStatus()
    {
        $eval = "";
        if (sizeof($this->searchFilts["comstatus"]) > 0) {
            foreach ($this->searchFilts["comstatus"] as $i => $status) {
                if ($status == $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) { // 194
                    $eval .= "->orWhere(function (\$query" . $i .") { \$query" . $i ."->where('ComType', '"
                        . $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Unreviewed') 
                        . "')->where('ComStatus', '" . $status . "'); })";
                } elseif (in_array($status, [195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 627])) {
                    $eval .= "->orWhere(function (\$query" . $i .") { \$query" . $i ."->where('ComType', '"
                        . $GLOBALS["SL"]->def->getID('OPC Staff/Internal Complaint Type', 'Police Complaint') 
                        . "')->where('ComStatus', '" . $status . "'); })";
                } else {
                    $eval .= "->orWhere(function (\$query" . $i .") { \$query" . $i ."->where('ComType', '" 
                        . $status . "')->where('ComStatus', 'NOT LIKE', '"
                        . $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete') . "'); })";
                }
            }
        }
        if (trim($eval) != '') {
            $eval = "->where(function (\$query) { \$query->where" . substr($eval, 9) . "; })";
        }
//echo 'eval' . str_replace("})", "<br />})", str_replace("->", "<br />->", $eval)) . '<br />comstatus: <pre>'; print_r($this->searchFilts["comstatus"]); echo '</pre>'; exit;
        return $eval;
    }
    
    public function getSearchFiltQryOrderBy()
    {
        $dir = "asc";
        if (isset($this->v["sortDir"]) && $this->v["sortDir"] == 'desc') {
            $dir = "desc";
        }
        $eval = "->orderBy(";
        switch ($this->v["sortLab"]) {
            case 'city':
                $eval .= "'OP_Incidents.IncAddressCity', '" . $dir . "'"; break;
            case 'first-name':
                $eval .= "'OP_PersonContact.PrsnNameFirst', '" . $dir . "'"; break;
            case 'last-name':
                $eval .= "'OP_PersonContact.PrsnNameLast', '" . $dir . "'"; break;
            case 'date':
            default: 
                $eval .= "'OP_Complaints.ComRecordSubmitted', '" . $dir . "'"; break;
        }
        return $eval . ")";
    }

    public function getSearchFiltDescStatus()
    {
        $ret = '';
        if (sizeof($this->searchFilts["comstatus"]) > 0) {
            foreach ($this->searchFilts["comstatus"] as $i => $status) {
                $ret .= (($i > 0) ? ', ' : '') . $GLOBALS["SL"]->def->getValById($status);
            }
        }
        if ($ret == 'Unreviewed, Not Sure, Police Complaint') {
            $ret = 'Active Complaints';
        }
        if (trim($ret) != '') {
            $ret = ' & ' . $ret;
        }
        return $ret;
    }

    public function getSearchFiltDescPeeps()
    {
        $ret = '';
        $physTypes = [
            ['Victim',  'vict'],
            ['Officer', 'off' ]
        ];
        foreach ($physTypes as $type) {
            if (sizeof($this->searchFilts[$type[1] . "gend"]) > 0) {
                $filtDescTmp = '';
                foreach ($this->searchFilts[$type[1] . "gend"] as $gend) {
                    switch ($gend) {
                        case 'M': $filtDescTmp .= ', Male'; break;
                        case 'F': $filtDescTmp .= ', Female'; break;
                        case 'T': $filtDescTmp .= ', Transgender/Other'; break;
                    }
                }
                $ret .= ' & ' . $type[0] . ' is ' . substr($filtDescTmp, 2);
            }
            if (sizeof($this->searchFilts[$type[1] . "race"]) > 0) {
                $filtDescTmp = '';
                foreach ($this->searchFilts[$type[1] . "race"] as $race) {
                    $filtDescTmp .= ', ' . $GLOBALS["SL"]->def->getVal('Races', $race);
                }
                $ret .= ' & ' . $type[0] . ' is ' . substr($filtDescTmp, 2);
            }
        }
        return $ret;
    }
    
}