<?php
/**
  * OpenPoliceSearcher extends the Survloop Searcher for some hard-coded overrides.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.12
  */
namespace FlexYourRights\OpenPolice\Controllers;

use App\Models\OPComplaints;
use App\Models\OPLinksComplaintDept;
use RockHopSoft\Survloop\Controllers\Searcher;

class OpenPoliceSearcher extends Searcher
{
    protected function processSearchFilt($key, $val)
    {
        if ($key == 'd') {
            $deptComs = $both = [];
            $chk = OPLinksComplaintDept::whereIn('lnk_com_dept_dept_id', $val)
                ->get();
            if ($chk->isNotEmpty()) {
                foreach ($chk as $com) {
                    $deptComs[] = $com->lnk_com_dept_complaint_id;
                }
                $chk = OPComplaints::whereIn('com_id', $deptComs)
                    ->select('com_id')
                    ->get();
                $deptComs = [];
                if ($chk->isNotEmpty()) {
                    foreach ($chk as $com) {
                        if (in_array($com->com_id, $this->allPublicFiltIDs)) {
                            $both[] = $com->com_id;
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
        $eval = "\$this->v['allcomplaints'] = " 
            . $GLOBALS["SL"]->modelPath('Complaints') . "::where('com_type', " 
            . $GLOBALS["SL"]->def->getID('Complaint Type', 'Police Complaint') 
            . ")->" . $xtra . "orderBy('" . $this->v["sort"][0] 
            . "', '" . $this->v["sort"][1] . "')->get();";
        eval($eval);
//echo '<br /><br /><br />' . $eval . '<br />loadAllComplaintsPublic( ' . $this->v["allcomplaints"]->count() . '<br />';
        return true;
    }
    
    public function getSearchFiltQryStatus()
    {
        $eval = "";
        if (sizeof($this->searchFilts["comstatus"]) > 0) {
            foreach ($this->searchFilts["comstatus"] as $i => $status) {
                if (!$GLOBALS["SL"]->x["isPublicList"] 
                    || in_array($status, [200, 201, 202, 203, 204])) {
                    if ($status == $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete')) { // 194
                        $eval .= "->orWhere(function (\$query" . $i .") { \$query" . $i ."->where('com_type', '"
                            . $GLOBALS["SL"]->def->getID('Complaint Type', 'Unverified') 
                            . "')->where('com_status', '" . $status . "'); })";
                    } elseif (in_array($status, [195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 627])) {
                        $eval .= "->orWhere(function (\$query" . $i .") { \$query" . $i ."->where('com_type', '"
                            . $GLOBALS["SL"]->def->getID('Complaint Type', 'Police Complaint') 
                            . "')->where('com_status', '" . $status . "'); })";
                    } else {
                        $eval .= "->orWhere(function (\$query" . $i .") { \$query" . $i ."->where('com_type', '" 
                            . $status . "')->where('com_status', 'NOT LIKE', '"
                            . $GLOBALS["SL"]->def->getID('Complaint Status', 'Incomplete') . "'); })";
                    }
                }
            }
        }
        if (trim($eval) != '') {
            $eval = "->where(function (\$query) { \$query->where" . substr($eval, 9) . "; })";
        } elseif ($GLOBALS["SL"]->x["isPublicList"]) {
            $eval = "->where('com_type', '"
                . $GLOBALS["SL"]->def->getID('Complaint Type', 'Police Complaint') . "')
                ->whereIn('com_status', [200, 201, 202, 203, 204])";
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
                $eval .= "'op_incidents.inc_address_city', '" . $dir . "'";     
                break;
            case 'first-name': 
                $eval .= "'op_person_contact.prsn_name_first', '" . $dir . "'"; 
                break;
            case 'last-name':  
                $eval .= "'op_person_contact.prsn_name_last', '" . $dir . "'";  
                break;
            case 'date':
            default:           
                $eval .= "'op_complaints.com_record_submitted', '" . $dir . "'";  
                break;
        }
        return $eval . ")";
    }

    public function getSearchFiltDescStatus()
    {
        $ret = '';
        if (sizeof($this->searchFilts["comstatus"]) > 0) {
            foreach ($this->searchFilts["comstatus"] as $i => $status) {
                if (!$GLOBALS["SL"]->x["isPublicList"] 
                    || in_array($status, [200, 201, 202, 203, 204])) {
                    $ret .= (($i > 0) ? ', ' : '') . $GLOBALS["SL"]->def->getValById($status);
                }
            }
        }
        if ($ret == 'Unverified, Not Sure, Police Complaint') {
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
            if (isset($this->searchFilts[$type[1] . "gend"])
                && sizeof($this->searchFilts[$type[1] . "gend"]) > 0) {
                $filtDescTmp = '';
                foreach ($this->searchFilts[$type[1] . "gend"] as $gend) {
                    switch ($gend) {
                        case 'M': $filtDescTmp .= ', Male'; break;
                        case 'F': $filtDescTmp .= ', Female'; break;
                        case 'T': 
                        case 'O': $filtDescTmp .= ', Transgender/Other'; break;
                    }
                }
                $ret .= ' & ' . $type[0] . ' is ' . substr($filtDescTmp, 2);
            }
            if (isset($this->searchFilts[$type[1] . "race"])
                && sizeof($this->searchFilts[$type[1] . "race"]) > 0) {
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