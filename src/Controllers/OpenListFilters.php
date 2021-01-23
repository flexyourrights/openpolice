<?php
/**
  * OpenListing is a mid-level class which handles the printing
  * of listings of conduct reports, mostly in preview.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.2.18
  */
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use App\Models\OPComplaints;
use App\Models\OPPhysicalDescRace;
use App\Models\SLNode;
use App\Models\SLSearchRecDump;
use FlexYourRights\OpenPolice\Controllers\OpenAjax;

class OpenListFilters extends OpenAjax
{
    /**
     * Print all the standard filters used to manage listings
     * of complaints or compliments.
     *
     * @param  int $nID
     * @param  string $view
     * @return string
     */
    protected function printComplaintsFilters($nID, $view = 'list')
    {
        if (!isset($this->searcher->v["sortLab"]) 
            || $this->searcher->v["sortLab"] == '') {
            $this->searcher->v["sortLab"] = 'date';
        }
        if (!isset($this->searcher->v["sortDir"]) 
            || $this->searcher->v["sortDir"] == '') {
            $alpha = ['first-name', 'last-name', 'city'];
            if (in_array($this->searcher->v["sortLab"], $alpha)) {
                $this->searcher->v["sortDir"] = 'asc';
            } else {
                $this->searcher->v["sortDir"] = 'desc';
            }
        }
        if (!isset($this->searcher->searchFilts["comstatus"])) {
            if (!$GLOBALS["SL"]->x["isPublicList"]) {
                $this->searcher->searchFilts["comstatus"] = [ 295, 296 ]; //301
            } else {
                $this->searcher->searchFilts["comstatus"] = [];
            }
        }
        $GLOBALS["SL"]->loadStates();
        if (!isset($this->searcher->searchFilts["states"])) {
            $this->searcher->searchFilts["states"] = [];
        }
        if ((!isset($this->searcher->searchFilts["states"]) 
                || sizeof($this->searcher->searchFilts["states"]) == 0)
            && (isset($this->searcher->searchFilts["state"]) 
            && trim($this->searcher->searchFilts["state"]) != '')) {
            $this->searcher->searchFilts["states"][] 
                = trim($this->searcher->searchFilts["state"]);
        }
        if (!isset($this->searcher->searchFilts["allegs"])) {
            $this->searcher->searchFilts["allegs"] = [];
        }
        if (!isset($this->searcher->searchFilts["victgend"])) {
            $this->searcher->searchFilts["victgend"] = [];
        }
        if (!isset($this->searcher->searchFilts["victrace"])) {
            $this->searcher->searchFilts["victrace"] = [];
        }
        if (!isset($this->searcher->searchFilts["offgend"])) {
            $this->searcher->searchFilts["offgend"] = [];
        }
        if (!isset($this->searcher->searchFilts["offrace"])) {
            $this->searcher->searchFilts["offrace"] = [];
        }
        $statusFilts = $GLOBALS["SL"]->printAccordian(
            'By Complaint Status',
            view(
                'vendor.openpolice.complaint-listing-filters-status', 
                [ "srchFilts"  => $this->searcher->searchFilts ]
            )->render(),
            (sizeof($this->searcher->searchFilts["comstatus"]) > 0)
        );
        $stateFilts = $GLOBALS["SL"]->printAccordian(
            'By State',
            view(
                'vendor.openpolice.complaint-listing-filters-states', 
                [ "srchFilts"  => $this->searcher->searchFilts ]
            )->render(),
            (sizeof($this->searcher->searchFilts["states"]) > 0)
        );
        $allegFilts = $GLOBALS["SL"]->printAccordian(
            'By Allegation',
            view(
                'vendor.openpolice.complaint-listing-filters-allegs', 
                [
                    "allegTypes" => $this->worstAllegations,
                    "srchFilts"  => $this->searcher->searchFilts
                ]
            )->render(),
            (sizeof($this->searcher->searchFilts["allegs"]) > 0)
        );
        $victFilts = $GLOBALS["SL"]->printAccordian(
            'By Victim Description',
            view(
                'vendor.openpolice.complaint-listing-filters-vict', 
                [
                    "races"      => $GLOBALS["SL"]->def->getSet('Races'),
                    "srchFilts"  => $this->searcher->searchFilts
                ]
            )->render(),
            ((isset($this->searcher->searchFilts["victgend"])
                && sizeof($this->searcher->searchFilts["victgend"]) > 0)
                || (isset($this->searcher->searchFilts["victrace"])
                    && sizeof($this->searcher->searchFilts["victrace"]) > 0))
        );
        $offFilts = $GLOBALS["SL"]->printAccordian(
            'By Officer Description',
            view(
                'vendor.openpolice.complaint-listing-filters-off', 
                [
                    "races"      => $GLOBALS["SL"]->def->getSet('Races'),
                    "srchFilts"  => $this->searcher->searchFilts
                ]
            )->render(),
            (sizeof($this->searcher->searchFilts["offgend"]) > 0
                || sizeof($this->searcher->searchFilts["offrace"]) > 0)
        );
        return view(
            'vendor.openpolice.complaint-listing-filters', 
            [
                "nID"         => $nID,
                "view"        => $view,
                "statusFilts" => $statusFilts,
                "stateFilts"  => $stateFilts,
                "allegFilts"  => $allegFilts,
                "victFilts"   => $victFilts,
                "offFilts"    => $offFilts,
                "srchFilts"   => $this->searcher->searchFilts
            ]
        )->render();
    }

    /**
     * First main complaint management listings query.
     *
     * @param  int $nID
     * @return DB
     */
    protected function runComplaintListQueries($nID)
    {
        ini_set('max_execution_time', 180);
        // First run query into $compls1
        $eval = $this->printComplaintListQry1($nID);
        eval($eval);
        // Then run 2nd-round queries
        return $this->printComplaintListQry2($nID, $compls1);
    }

    /**
     * First main complaint management listings query.
     *
     * @param  int $nID
     * @return DB
     */
    protected function printComplaintListQry1($nID)
    {
        $hasStateFilt = (sizeof($this->searcher->searchFilts["states"]) > 0);
        $eval = "\$compls1 = DB::table('op_complaints')
            ->join('op_incidents', function (\$joi) {
                \$joi->on('op_complaints.com_incident_id', '=', 'op_incidents.inc_id')"
                . (($hasStateFilt) ? "->whereIn('op_incidents.inc_address_state', ['"
                        . implode("', '", $this->searcher->searchFilts["states"]) . "'])" 
                    : "") . ";
            })";
        if ($hasStateFilt) {
            $this->v["filtersDesc"] .= ' & ' 
                . implode(', ', $this->searcher->searchFilts["states"]);
        }
        if (isset($this->searcher->searchFilts["allegs"])
            && sizeof($this->searcher->searchFilts["allegs"]) > 0) {
            $filtDescTmp = '';
            $eval .= "->join('op_alleg_silver', function (\$joi) {
                \$joi->on('op_complaints.com_id', 
                    '=', 'op_alleg_silver.alle_sil_complaint_id')";
            foreach ($this->searcher->searchFilts["allegs"] as $i => $allegID) {
                $allegFld = $this->getAllegFldName($allegID);
                if ($allegFld == 'alle_sil_intimidating_weapon') {
                    if ($i > 0) {
                        $eval .= "->orWhereIn";
                    } else {
                        $eval .= "->whereIn";
                    }
                    $eval .= "('op_alleg_silver." . $allegFld 
                        . "', [277, 278, 279, 280])";
                } else {
                    if ($i > 0) {
                        $eval .= "->orWhere";
                    } else {
                        $eval .= "->where";
                    }
                    $eval .= "('op_alleg_silver." . $allegFld . "', 'LIKE', 'Y')";
                }
                $filtDescTmp = ' or ' 
                    . $GLOBALS["SL"]->def->getVal('Allegation Type', $allegID);
            }
            $eval .= "; })";
            $this->v["filtersDesc"] .= ' & ' . substr($filtDescTmp, 3);
        }

        $eval .= "->join('op_civilians', function (\$joi) {
                \$joi->on('op_complaints.com_id', '=', 'op_civilians.civ_complaint_id')
                    ->where('op_civilians.civ_is_creator', 'Y');
            })
            ->join('op_person_contact', 'op_civilians.civ_person_id', 
                '=', 'op_person_contact.prsn_id')";
        
        if (isset($this->v["fltIDs"]) 
            && sizeof($this->v["fltIDs"]) > 0) {
            $fltIDs = '';
            foreach ($this->v["fltIDs"] as $ids) {
                if (sizeof($ids) > 0) {
                    foreach ($ids as $id) {
                        $fltIDs .= ', ' . $id;
                    }
                }
            }
            if (trim($fltIDs) != '') {
                $eval .= "->whereIn('op_complaints.com_id', [" 
                    . substr($fltIDs, 2) . "])";
            }
        }
        if (isset($GLOBALS["SL"]->x["reqPics"]) && $GLOBALS["SL"]->x["reqPics"]) {
            $eval .= "->whereIn('op_complaints.com_user_id', [" 
                . implode(", ", $GLOBALS["SL"]->getUsersWithProfilePics()) . "])";
        }
        $eval .= $this->searcher->getSearchFiltQryStatus()
            . "->select('op_complaints.*', 'op_person_contact.prsn_name_first', 
            'op_person_contact.prsn_name_last', 'op_person_contact.prsn_email', 'op_incidents.*')"
            . $this->searcher->getSearchFiltQryOrderBy() 
            . "->get();";
        return $eval;
    }

    /**
     * Second main complaint management listings query. A separate pass
     * to further filter results, too hairy for the main filter query.
     *
     * @param  int $nID
     * @param  array $compls1
     * @return DB
     */
    protected function printComplaintListQry2($nID, $compls1)
    {
        $GLOBALS["SL"]->xmlTree["coreTbl"] = 'complaints';
        $compls2 = [];
        if ($compls1 && sizeof($compls1) > 0) {
            foreach ($compls1 as $com) {
                $comID = $com->com_id;
                $inFilter = true;
                if ((isset($this->searcher->searchFilts["victgend"])
                    && sizeof($this->searcher->searchFilts["victgend"]) > 0)
                    || (isset($this->searcher->searchFilts["victrace"])
                        && sizeof($this->searcher->searchFilts["victrace"]) > 0)) {
                    $chk = DB::table('op_physical_desc')
                        ->join('op_civilians', function ($joi) use ($comID) {
                            $joi->on('op_physical_desc.phys_id', '=', 'op_civilians.civ_phys_desc_id')
                                ->where('op_civilians.civ_complaint_id', $comID)
                                ->where('op_civilians.civ_role', 'Victim');
                        })
                        ->select('op_physical_desc.phys_id', 'op_physical_desc.phys_gender')
                        ->get();
                    if ($chk->isNotEmpty()) {
                        $flt = $this->searcher->searchFilts["victgend"];
                        if (sizeof($flt) > 0) {
                            if (!$this->chkFiltPhysGend($chk, $flt)) {
                                $inFilter = false;
                            }
                        }
                        $flt = $this->searcher->searchFilts["victrace"];
                        if (sizeof($flt) > 0) {
                            if (!$this->chkFiltPhysRace($chk, $flt)) {
                                $inFilter = false;
                            }
                        }
                    }
                }
                if ($inFilter 
                    && ((isset($this->searcher->searchFilts["offgend"])
                            && sizeof($this->searcher->searchFilts["offgend"]) > 0)
                        || (isset($this->searcher->searchFilts["offrace"])
                            && sizeof($this->searcher->searchFilts["offrace"]) > 0))) {
                    $chk = DB::table('op_physical_desc')
                        ->join('op_officers', function ($joi) use ($comID) {
                            $joi->on('op_physical_desc.phys_id', '=', 'op_officers.off_phys_desc_id')
                                ->where('op_officers.off_complaint_id', $comID);
                        })
                        ->select('op_physical_desc.phys_id', 'op_physical_desc.phys_gender')
                        ->get();
                    if ($chk->isNotEmpty()) {
                        $flt = $this->searcher->searchFilts["offgend"];
                        if (sizeof($flt) > 0) {
                            if (!$this->chkFiltPhysGend($chk, $flt)) {
                                $inFilter = false;
                            }
                        }
                        $flt = $this->searcher->searchFilts["offrace"];
                        if (sizeof($flt) > 0) {
                            if (!$this->chkFiltPhysRace($chk, $flt)) {
                                $inFilter = false;
                            }
                        }
                    }
                }
                if ($inFilter 
                    && trim($this->searcher->searchTxt) != ''
                    && sizeof($this->searcher->searchParse) > 0) {
                    $perms = 'public';
                    if ($this->isStaffOrAdmin()) {
                        $perms = 'sensitive';
                    }
                    $eval = "\$dump = App\\Models\\SLSearchRecDump::where('sch_rec_dmp_tree_id', 1)
                        ->where('sch_rec_dmp_rec_id', " . $comID . ")
                        ->where('sch_rec_dmp_perms', '" . $perms . "')
                        ->where(function(\$query) { \$query";
                    foreach ($this->searcher->searchParse as $w => $word) {
                        $eval .= (($w == 0) ? "->where" : "->orWhere") 
                            . "('sch_rec_dmp_rec_dump', 'LIKE', \"%" 
                            . $word . "%\")";
                    }
                    $eval .= "; })->select('sch_rec_dmp_id')->first();";
                    eval($eval);
                    if (!$dump || !isset($dump->sch_rec_dmp_id)) {
                        $inFilter = false;
                    }
                }
                if ($inFilter) {
                    $compls2[] = $com;
                }
            }
        }
        return $compls2;
    }

    /**
     * Load a written description of the current filters applied to
     * the complaint listings on this page load.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function printComplaintFiltsDesc($nID)
    {
        if (!isset($this->v["filtersDesc"])) {
            $this->v["filtersDesc"] = '';
        }
        $this->v["filtersDesc"] .= $this->searcher->getSearchFiltDescPeeps()
            . $this->searcher->getSearchFiltDescStatus();
        $this->v["filtersDesc"] = str_replace(
            'OK to Submit to Oversight',
            'OK to Submit to Investigative Agency',
            $this->v["filtersDesc"]
        );
        $this->v["filtersDesc"] = str_replace(
            'Submitted to Oversight',
            'Submitted to Investigative Agency',
            $this->v["filtersDesc"]
        );
        $this->v["filtersDesc"] = str_replace(
            'Received by Oversight',
            'Received by Investigative Agency',
            $this->v["filtersDesc"]
        );
        if (trim($this->v["filtersDesc"]) != '') {
            $this->v["filtersDesc"] = substr($this->v["filtersDesc"], 2);
        }
        return true;
    }

    /**
     * Check whether or not the current gender filters match
     * and physical description records passed in.
     *
     * @param  array $chkPhys
     * @param  array $matches
     * @return boolean
     */
    protected function chkFiltPhysGend($chkPhys, $matches = [])
    {
        $inFilterGend = false;
        foreach ($chkPhys as $phys) {
            if (isset($phys->phys_gender) && trim($phys->phys_gender) != '') {
                if (in_array('T', $matches)) {
                    if (!in_array($phys->phys_gender, ['M', 'F'])) {
                        $inFilterGend = true;
                    }
                } elseif (in_array('M', $matches) && $phys->phys_gender == 'M') {
                    $inFilterGend = true;
                } elseif (in_array('F', $matches) && $phys->phys_gender == 'F') {
                    $inFilterGend = true;
                }
            }
        }
        return $inFilterGend;
    }
    
    /**
     * Check whether or not the current race filters match
     * and physical description records passed in.
     *
     * @param  array $chkPhys
     * @param  array $matches
     * @return boolean
     */
    protected function chkFiltPhysRace($chkPhys, $matches = [])
    {
        $inFilterRace = false;
        foreach ($chkPhys as $phys) {
            $chkRace = OPPhysicalDescRace::where('phys_race_phys_desc_id', $phys->phys_id)
                ->whereIn('phys_race_race', $matches)
                ->select('phys_race_id')
                ->get();
            if ($chkRace->isNotEmpty()) {
                $inFilterRace = true;
            }
        }
        return $inFilterRace;
    }

    /**
     * Creates a preview of the search results count for the top of the page.
     *
     * @return string
     */
    protected function printComplaintFiltDescPrev()
    {
        $this->v["complaintFiltDescPrev"] = $found = '';
        if (isset($this->v["complaints"]) 
            && is_array($this->v["complaints"])) {
            $found = number_format(sizeof($this->v["complaints"])) . ' Found: ';
        }
        if (isset($this->searcher->searchTxt) 
            && trim($this->searcher->searchTxt) != '') {
            $srch = '<span class="mR5 slGrey">"' 
                . $this->searcher->searchTxt . '",</span>';
            if (strpos($this->v["filtersDesc"], $srch) === false) {
                $this->v["filtersDesc"] = $srch . $this->v["filtersDesc"];
            }
        }
        if (isset($this->v["filtersDesc"]) 
            && trim($this->v["filtersDesc"]) != '') {
            $this->v["filtersDesc"] = trim($this->v["filtersDesc"]);
            $this->v["complaintFiltDescPrev"] .= '<span class="mL5 slGrey">'
                . $GLOBALS["SL"]->wordLimitDotDotDot($this->v["filtersDesc"], 20)
                . '</span>';
        }
        if (isset($GLOBALS["SL"]->x["isPublicList"])
            && $GLOBALS["SL"]->x["isPublicList"]
            && trim($this->v["complaintFiltDescPrev"]) == '') {
            $this->v["complaintFiltDescPrev"] = '<span class="mR5 slGrey">Published</span>';
        }
        $this->v["complaintFiltDescPrev"] = $found
            . $this->v["complaintFiltDescPrev"];
        return $this->v["complaintFiltDescPrev"];
    }
    
    /**
     * Add complaint to search results for managing complaints.
     *
     * @param  App\Models\OPComplaints $com
     * @return string
     */
    protected function printComplaintListingResultsAdd($com)
    {
        $this->v["comInfo"][$com->com_public_id] = [
            "depts"     => '',
            "submitted" => ''
        ];
        $dChk = DB::table('op_links_complaint_dept')
            ->where('op_links_complaint_dept.lnk_com_dept_complaint_id', 
                $com->com_id)
            ->leftJoin('op_departments', 'op_departments.dept_id', 
                '=', 'op_links_complaint_dept.lnk_com_dept_dept_id')
            ->select('op_departments.dept_name', 'op_departments.dept_slug')
            ->orderBy('op_departments.dept_name', 'asc')
            ->get();
        if ($dChk && sizeof($dChk) > 0) {
            foreach ($dChk as $i => $d) {
                $comma = (($i > 0) ? ', ' : '');
                $this->v["comInfo"][$com->com_public_id]["depts"] .= $comma . $d->dept_name;
            }
        }
        $comTime = strtotime($com->updated_at);
        if (trim($com->com_record_submitted) != '' 
            && $com->com_record_submitted != '0000-00-00 00:00:00') {
            $comTime = strtotime($com->com_record_submitted);
        }
        if (!isset($com->com_status) || intVal($com->com_status) <= 0) {
            $com->com_status = $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete');
            OPComplaints::find($com->com_id)
                ->update([ "com_status" => $com->com_status ]);
        }
        if (!isset($com->com_type) || intVal($com->com_type) <= 0) {
            $com->com_type = $GLOBALS['SL']->def->getID('Complaint Type', 'Unverified');
            OPComplaints::find($com->com_id)
                ->update([ "com_type" => $com->com_type ]);
        }
        $cutoffTime = mktime(date("H"), date("i"), date("s"), 
            date("m"), date("d")-2, date("Y"));
        if ($comTime < $cutoffTime) {
            if (!isset($com->com_summary) || trim($com->com_summary) == '') {
                OPComplaints::find($com->com_id)
                    ->delete();
                $comTime = false;
            }
        }
        if ($comTime !== false) {
            $this->v["comInfo"][$com->com_public_id]["submitted"] = date("n/j/Y", $comTime);
            $incDef = $GLOBALS['SL']->def->getID('Complaint Status', 'Incomplete');
            if ($com->com_status == $incDef) {
                if ($com->com_submission_progress > 0 
                    && !isset($this->v["lastNodes"][$com->com_submission_progress])) {
                    $node = SLNode::find($com->com_submission_progress);
                    if ($node && isset($node->node_prompt_notes)) {
                        $this->v["lastNodes"][$com->com_submission_progress] 
                            = $node->node_prompt_notes;
                    }
                }
            }
            $sortInd = $this->printComplaintListingRank($com, $comTime);
            $this->v["complaints"][$sortInd] = $com;
        }
        if (!isset($com->com_alleg_list) || trim($com->com_alleg_list) == '') {
            $this->v["ajaxRefreshs"][] = $com->com_public_id;
        }
        return true;
    }
    
    /**
     * Get sorting index for this complaint.
     *
     * @param  App\Models\OPComplaints $com
     * @param  int $comTime
     * @return string
     */
    protected function printComplaintListingRank($com, $comTime)
    {
        $sortInd = $comTime;
        $reverseTime = 10000000000-$comTime;
        if ($this->searcher->v["sortLab"] == 'city') {
            $sortInd = trim($com->inc_address_city);
            if ($sortInd == '') {
                $sortInd = 'zzzzzzz';
            }
            $sortInd .= ' ' . trim($com->inc_address_state) . ' ' . $reverseTime;
        } elseif ($this->searcher->v["sortLab"] == 'first-name') {
            $sortInd = trim($com->prsn_name_first);
            if ($sortInd == '') {
                $sortInd = 'zzzzzzz';
            }
            $sortInd .= ' ' . trim($com->prsn_name_last) . ' ' . $reverseTime;
        } elseif ($this->searcher->v["sortLab"] == 'last-name') {
            $sortInd = trim($com->prsn_name_last);
            if ($sortInd == '') {
                $sortInd = 'zzzzzzz';
            }
            $sortInd .= ' ' . trim($com->prsn_name_first) . ' ' . $reverseTime;
        } elseif ($this->searcher->v["sortLab"] == 'urgency') {
            $sortInd = $this->printComplaintListingRankUrgency($com);
        }
        return $sortInd;
    }
    
    /**
     * Get sorting index by status urgency for this complaint.
     *
     * @param  App\Models\OPComplaints $com
     * @return string
     */
    private function printComplaintListingRankUrgency($com)
    {
        $sortInd = intVal($com->com_id);
        $type = $GLOBALS['SL']->def->getVal('Complaint Type', $com->com_type);
        $status = $GLOBALS['SL']->def->getVal('Complaint Status', $com->com_status);
        if ($type == 'Not Sure') {
            $sortInd += 990000000;
        } elseif ($type == 'Unverified') {
            $sortInd += 950000000;
        } elseif ($status == 'New') {
            $sortInd += 900000000;
        } elseif ($status == 'Hold') {
            $sortInd += 850000000;
        } elseif ($status == 'Reviewed') {
            $sortInd += 800000000;
        } elseif ($status == 'Needs More Work') {
            $sortInd += 700000000;
        } elseif ($status == 'OK to Submit to Oversight') {
            $sortInd += 600000000;
        } elseif ($status == 'Wants Attorney') {
            $sortInd += 560000000;
        } elseif ($status == 'Pending Attorney') {
            $sortInd += 540000000;
        } elseif ($status == 'Has Attorney') {
            $sortInd += 500000000;
        } elseif ($status == 'Submitted to Oversight') {
            $sortInd += 450000000;
        } elseif ($status == 'Received by Oversight') {
            $sortInd += 400000000;
        } elseif ($status == 'Declined To Investigate (Closed)') {
            $sortInd += 360000000;
        } elseif ($status == 'Investigated (Closed)') {
            $sortInd += 340000000;
        } elseif ($status == 'Closed') {
            $sortInd += 300000000;
        }
        return $sortInd;
    }


}
