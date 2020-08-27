<?php
/**
  * OpenReport is mid-level class which manages some of the specific 
  * reporting customizations.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use DB;
use Auth;
use App\Models\OPDepartments;
use App\Models\OPzEditDepartments;
use App\Models\OPzEditOversight;
use App\Models\OPzVolunTmp;
use App\Models\OPOversight;
use OpenPolice\Controllers\OpenDeptStats;

class OpenReport extends OpenDeptStats
{
    /**
     * Load detail blocks for big printing of allegations on complaint reports.
     *
     * @param  int $nID
     * @return array
     */
    protected function reportAllegsWhyDeets($nID = -3)
    {
        $deets = [];
        if (isset($this->sessData->dataSets["alleg_silver"]) 
            && isset($this->sessData->dataSets["alleg_silver"][0])) {
            $silv = $this->sessData->dataSets["alleg_silver"][0];
            foreach ($this->worstAllegations as $i => $alleg) {
                if (isset($silv->{ $alleg[2] })) {
                    $show = (trim($silv->{ $alleg[2] }) == 'Y');
                    if ($alleg[1] == 'Intimidating Display of Weapon') {
                        $allegVal = intVal($silv->{ $alleg[2] });
                        if (!in_array($allegVal, $this->intimidWeaponNos())) {
                            $show = true;
                        }
                    }
                    if ($show) {
                        $deets[] = $this->printAllegsWhyDeet($alleg);
                    }
                }
            }
        }
        return $deets;
    }

    /**
     * Load one allegation detail row for big printing on complaint reports.
     *
     * @param  array $alleg
     * @return array
     */
    protected function printAllegsWhyDeet($alleg)
    {
        $alle = '<div style="font-weight: bold; padding-bottom: 5px;">'
            . $alleg[1] . '</div>';
        if ($this->canPrintFullReport()) {
            $foundWhy = false;
            foreach ($this->sessData->dataSets["allegations"] as $j => $all) {
                if (!$foundWhy 
                    && $all 
                    && isset($all->alle_type) 
                    && $all->alle_type == $alleg[0] 
                    && trim($all->alle_description) != '') {
                    $alle .= $all->alle_description;
                    $foundWhy = true;
                }
            }
        }
        return [ $alle ];
    }

    /**
     * Print out big block of allegations on complaint report.
     *
     * @param  int $nID
     * @return string
     */
    protected function reportAllegsWhy($nID = -3)
    {
        $fullPrint = false;
        $why = $this->reportAllegsWhyDeets($nID);
        $deets = 'Allegations';
        $desc = '';
        if ($this->canPrintFullReport()) {
            $desc = '<div class="slGrey" style="padding-bottom: 15px;">'
                . 'Including comments from the complainant</div>';
            $fullPrint = true;
        }
        if ($fullPrint) {
            return $this->printReportDeetsBlock($why, $deets, $this->allNodes[$nID], $desc);
        }
        if (!$GLOBALS["SL"]->isPdfView()) {
            return $this->printReportDeetsBlockCols($why, $deets, 2, $this->allNodes[$nID], $desc);
        }
        // special printing for PDF...
        $ret = '<h4 class="slBlueDark" style="margin-top: 30px;">Allegations</h4>'
            . '<table class="repDeetsBlock"><tr>';
        $allegsList = $GLOBALS["SL"]->mexplode(',', $this->commaAllegationList());
        foreach ($allegsList as $i => $alleg) {
            $ret .= '<td class="w50"><b>' . $alleg . '</b></td>';
            if ($i%2 == 1) {
                $ret .= '</tr><tr>';
            }
        }
        return $ret . '</tr></table>';
    }

    /**
     * Print out civilian's address. (Only run if already allowed.)
     *
     * @param  int $nID
     * @return array
     */
    protected function reportCivAddy($nID)
    {
        if ($nID > 0 && isset($this->allNodes[$nID])) {
            $fldRow = $this->allNodes[$nID]->getFldRow();
            if ($this->checkFldDataPerms($fldRow)
                && $this->checkViewDataPerms($fldRow)) {
                $prsn = $this->sessData->getLatestDataBranchRow();
                $addy = $GLOBALS["SL"]->printRowAddy($prsn, 'prsn_');
                if (trim($addy) != '') {
                    return [ 'Address', $addy ];
                }
            }
        }
        return [];
    }

    /**
     * Print out report's main narrative story, potentially with read more button.
     *
     * @param  int $nID
     * @return string
     */
    protected function reportStory($nID)
    {
        $ret = '';
        if ($nID > 0 && isset($this->allNodes[$nID])) {
            $story = $this->sessData->dataSets["complaints"][0]->com_summary;
            $views = [ 'pdf', 'full-pdf' ];
            if (!in_array($GLOBALS["SL"]->pageView, $views)) {
                $ret = $this->printStoryPreview($story);
            }
            if (trim($ret) == '') {
                $ret = $GLOBALS["SL"]->textSaferHtml($story);
            }
        }
        return '<h4 class="slBlueDark mT0 mB10">Story</h4><p>' . $ret . '</p>';
    }

    /**
     * Prints story preview with a read more button.
     *
     * @param  string $story
     * @return string
     */
    protected function printStoryPreview($story)
    {
        $ret = '';
        $previewMax = 1800;
        if (strlen($story) > $previewMax) {
            $more = ($GLOBALS["SL"]->REQ->has('read') && $GLOBALS["SL"]->REQ->get('read') == 'more');
            $brkPos = strpos($story, ' ', $previewMax-200);
            if ($brkPos > 0) {
                $storyLess = substr($story, 0, $brkPos+1);
                $ret = view(
                    'vendor.openpolice.nodes.1373-story-read-more', 
                    [
                        "more"      => $more,
                        "storyLess" => $GLOBALS["SL"]->textSaferHtml($storyLess),
                        "storyMore" => $GLOBALS["SL"]->textSaferHtml($story)
                    ]
                )->render();
                $GLOBALS["SL"]->pageAJAX .= view(
                    'vendor.openpolice.nodes.1373-story-read-more-ajax'
                )->render();
            } else {
                $ret = $story;
            }
        }
        return $ret;
    }
    
    /**
     * Load a civlian record's subset records for person contact information
     * and physical description information.
     *
     * @param  int $overLnkID
     * @return string
     */
    protected function queuePeopleSubsets($id, $type = 'civilians')
    {
        $prsn = $this->sessData->getChildRow($type, $id, 'person_contact');
        $phys = $this->sessData->getChildRow($type, $id, 'physical_desc');
        return [ $prsn, $phys ];
    }
    
    /**
     * Load department IDs responsible for this report.
     *
     * @param  int $overLnkID
     * @return string
     */
    protected function chkGetReportDept($deptID)
    {
        if (!isset($this->v["reportDepts"])) {
            $this->v["reportDepts"] = [];
        }
        if ($deptID > 0 && !in_array($deptID, $this->v["reportDepts"])) {
            $this->v["reportDepts"][] = $deptID;
            return '';
        }
        return '<!-- skipping overLnk #' . $deptID . ' -->';
    }
    
    /**
     * Print the report headline a department responsible for this report.
     *
     * @param  int $deptID
     * @return string
     */
    protected function getReportDept($deptID)
    {
        $dept = $this->sessData->getRowById('departments', $deptID);
//echo 'getReportDept(' . $deptID . '<pre>'; print_r($dept); echo '</pre>'; exit;
        if ($dept && isset($dept->dept_name)) {
            $status = $this->sessData->dataSets["complaints"][0]->com_status;
            if ($GLOBALS["SL"]->isPdfView()) {
                return '<h4 style="margin-bottom: 0px; ">'
                    . 'Misconduct Incident Report for ' . $dept->dept_name . '</h4>';
            } else {
                return '<h4 class="mT0 mB5"><a href="/dept/' . $dept->dept_slug 
                    . '" class="slBlueDark">Misconduct Incident Report for ' 
                    . $dept->dept_name . '</a></h4>'
                    . '<div id="complaintReportStatusLine" class="mB10"><b>Complaint #'
                    . $this->sessData->dataSets["complaints"][0]->com_public_id . ': ' 
                    . $this->printComplaintStatus($status) . '</b></div>';
            }
        }
        $this->v["reportDepts"][] = $deptID;
        return '';
    }
    
    /**
     * Get the label and value for who submitted a report,
     * depending on the current permissions and available info.
     *
     * @return array
     */
    protected function getReportPublicID($nID)
    {
        $ret = '';
        $com = $this->sessData->dataSets["complaints"][0];
        if (isset($com->com_public_id) && intVal($com->com_public_id) > 0) {
            return [
                'OpenPolice.org Report ID', 
                'Complaint #' . $com->com_public_id
            ];
        }
        return [];
    }
    
    /**
     * Get the label and value for who submitted a report,
     * depending on the current permissions and available info.
     *
     * @return array
     */
    protected function getReportByLine()
    {
        $ret = '';
        $com = $this->sessData->dataSets["complaints"][0];
        if ($this->isAnonyLogin()) {
            $ret = 'Anonymous';
        } elseif (isset($this->sessData->dataSets["civilians"]) 
            && isset($this->sessData->dataSets["civilians"][0]->civ_id)) {
            if (in_array($GLOBALS["SL"]->pageView, [ 'full', 'full-pdf' ])
                || ($this->isPublished('complaints', $this->coreID, $com) 
                    && $this->isPublic())) {
                $ret = $this->getCivReportName(
                    $this->sessData->dataSets["civilians"][0]->civ_id
                );
            }
        }
        if (trim($ret) != '') {
            return [ 'Submitted By', $ret ];
        }
        return [];
    }
    
    /**
     * Check the current permissions on printing the incident's detailed time.
     *
     * @param  App\Models\OPComplaints $complaint
     * @return boolean
     */
    protected function shouldPrintFullDate($complaint = null)
    {
        if (!$complaint && isset($this->sessData->dataSets["complaints"])) {
            $complaint = $this->sessData->dataSets["complaints"][0];
        }
        return (($this->v["isOwner"] || $this->v["isAdmin"]) 
            && (!isset($GLOBALS["SL"]->x["isPublicList"]) || !$GLOBALS["SL"]->x["isPublicList"]));
    }
    
    /**
     * Get the label and value for when an incident occured,
     * depending on the current permissions and available info.
     *
     * @param  int $nID
     * @return array
     */
    protected function getReportWhenLine()
    {
        $date = '';
        if (isset($this->sessData->dataSets["incidents"][0])) {
            $inc = $this->sessData->dataSets["incidents"][0];
            if ($this->shouldPrintFullDate()) {
                $date = date('n/j/Y', strtotime($inc->inc_time_start));
                $timeStart = $timeEnd = '';
                if ($inc->inc_time_end !== null) {
                    $timeEnd = date('g:i a', strtotime($inc->inc_time_end));
                }
                if ($inc->inc_time_start !== null) {
                    $timeStart = date('g:i a', strtotime($inc->inc_time_start));
                    $date .= $this->printStartEndTimes($timeStart, $timeEnd);
                }
            } else {
                $date = date('F Y', strtotime($inc->inc_time_start));
            }
        }
        return [ 'Indicent Date', $date ];
    }
    
    /**
     * Print starting date and time, and possibly the end
     *
     * @param  string $timeStart
     * @param  string $timeEnd
     * @return string
     */
    protected function printStartEndTimes($timeStart, $timeEnd)
    {
        $ret = '';
        if ($timeStart != '' 
            && ($timeStart != '12:00 am' || $timeStart != $timeEnd)) {
            $ret .= ' <nobr>at ' . $timeStart . '</nobr>';
            if ($timeEnd != '' && $timeStart != $timeEnd) {
                $ret .= ' <nobr>until ' . $timeEnd . '</nobr>';
            }
        }
        return $ret;
    }
    
    /**
     * Get the label and value for where an incident occured,
     * depending on the current permissions and available info.
     *
     * @param  int $nID
     * @return array
     */
    protected function getReportWhereLine($nID = -3, $noAddy = false)
    {
        if (isset($this->sessData->dataSets["incidents"])) {
            $inc = $this->sessData->dataSets["incidents"][0];
            $addy = $GLOBALS["SL"]->printRowAddy($inc, 'inc_');
            if (trim($addy) != ''
                && !$noAddy
                && $this->condPrintIncidentLocation()) {
                return [
                    'Indicent Location', 
                    $addy
                ];
            }
            if (isset($inc->inc_address_state)) {
                $c = '';
                $state = $inc->inc_address_state;
                if (isset($inc->inc_address_zip) && strlen($inc->inc_address_zip) > 4) {
                    $c = $GLOBALS["SL"]->getZipProperty($inc->inc_address_zip, 'county');
                }
                if (trim($c) == '' && isset($inc->inc_address_city)) {
                    $c = $GLOBALS["SL"]->getCityCounty($inc->inc_address_city, $state);
                }
                if (trim($c) != '') {
                    $ret = $GLOBALS["SL"]->allCapsToUp1stChars($c) . ', ' . $state;
                    return [
                        'Indicent Location',
                        $this->fixWhereError($ret)
                    ];
                }
            }
        }
        return [];
    }
    
    /**
     * Check the location description, and correct specific problems.
     *
     * @param  string $str
     * @return string
     */
    protected function fixWhereError($str)
    {
        $ret = str_replace('District Of Columbia County, DC', 'Washington, DC', $str);
        return $ret;
    }
    
    /**
     * Get the english name for this report's privacy setting.
     *
     * @param  int $nID
     * @return string
     */
    protected function getReportPrivacy($nID)
    {
        $ret = '';
        $com = $this->sessData->dataSets["complaints"][0];
        $inc = $this->sessData->dataSets["incidents"][0];
        if (isset($com->com_anon) 
            && intVal($com->com_anon) == 1) {
            $ret .= ', Submitted Anonymously';
        } elseif (isset($com->com_publish_user_name) 
            && intVal($com->com_publish_user_name) == 1) {
            $ret .= ', Publish Complainant\'s Name';
        }
        if (isset($com->com_publish_officer_name) 
            && intVal($com->com_publish_officer_name) == 1) {
            $ret .= ', Publish Officer Names';
        }
        if ($ret == '') {
            $ret .= ', Publish No Names';
        }
        if (isset($inc->inc_public) 
            && intVal($inc->inc_public) == 1) {
            $ret .= ', Publish Incident Address';
        }
        if ($ret != '') {
            return trim(substr($ret, 1));
        }
        return $ret;
    }
    
    /**
     * Whether or not witness names should be printed on this complaint report.
     *
     * @param  App\Models\OPOversight $civRow
     * @return boolean
     */
    protected function hideWitnessName($civRow)
    {
        $views = [ 'full', 'full-pdf' ];
        return (/* isset($civRow->civ_role)
            && $civRow->civ_role == 'Witness' 
            && */
            (!isset($civRow->civ_is_creator)
                || $civRow->civ_is_creator != 'Y')
            && ($GLOBALS["SL"]->dataPerms != 'sensitive' 
                || !in_array($GLOBALS["SL"]->pageView, $views)));
    }
    
    /**
     * Print civilians name for reports, when needed in a row format.
     *
     * @param  int $nID
     * @return array
     */
    protected function getCivReportNameRow($nID)
    {
        $civRow = $this->sessData->getDataBranchRow('Civilians');
        if (isset($civRow->civ_role) && isset($civRow->civ_is_creator)) {
            if ($nID == 1507) {
                $prsn = $this->sessData->getChildRow('civilians', $civRow->civ_id, 'person_contact');
                if ($prsn && isset($prsn->prsn_nickname)) {
                    $nick = trim(strtolower($prsn->prsn_nickname));
                    $first = $middle = $last = '';
                    if (isset($prsn->prsn_name_first)) {
                        $first = trim(strtolower($prsn->prsn_name_first));
                    }
                    if (isset($prsn->prsn_name_middle)) {
                        $middle = trim(strtolower($prsn->prsn_name_middle));
                    }
                    if (isset($prsn->prsn_name_last)) {
                        $last = trim(strtolower($prsn->prsn_name_last));
                    }
                    if ($this->chkDupliNickname($nick, $first, $middle, $last)) {
                        return [ '', '', 0 ];
                    }
                }
            }
            if ($this->hideWitnessName($civRow)) {
                $deetLabel = 'First Name';
                if ($nID == 2637) {
                    $deetLabel = 'Middle Name';
                } elseif ($nID == 1506) {
                    $deetLabel = 'Last Name';
                } elseif ($nID == 1507) {
                    $deetLabel = 'Nickname';
                }
                return [
                    $deetLabel, 
                    '<i class="slGrey">Not public</i>', 
                    $nID 
                ];
            }
        }
        return '';
    }
    
    /**
     * Check if first, middle, or last names are the same as a nickname;
     *
     * @param  string $nick
     * @param  string $first
     * @param  string $middle
     * @param  string $last
     * @return boolean
     */
    protected function chkDupliNickname($nick, $first = '', $middle = '', $last = '')
    {
        return ($first == $nick 
            || $middle == $nick 
            || $last == $nick 
            || ($first . ' ' . $last) == $nick
            || ($first . ' ' . $middle . ' ' . $last) == $nick);
    }
    
    /**
     * Print civilians name for section headers in reports.
     *
     * @param  int $nID
     * @return string
     */
    protected function getCivReportNameHeader($nID)
    {
//echo '<br /><br /><br />getCivReportNameHeader(' . $nID . ', branch: ' . $this->sessData->getLatestDataBranchID() . ', name: ' . $this->getCivReportName($this->sessData->getLatestDataBranchID()) . '<br />'; exit;
        $style = '';
        if (!$GLOBALS["SL"]->isPdfView()) {
            $style = ' style="margin: 0px 0px 18px 0px;"';
        }
        return '<h4 class="slBlueDark"' . $style . '>' 
            . $this->getCivReportName($this->sessData->getLatestDataBranchID()) 
            . '</h4>';
    }
    
    /**
     * Determine the most appropriate name printing for a civilian in reports.
     *
     * @param  int $civID
     * @param  int $ind
     * @param  string $type
     * @param  App\Models\OPPersonContact $prsn
     * @return string
     */
    protected function getCivReportName($civID, $ind = 0, $type = 'Subject', $prsn = NULL)
    {
        if (!isset($this->v["civNames"])) {
            $this->v["civNames"] = [];
        }
        if (!isset($this->v["civNames"][$civID]) 
            || trim($this->v["civNames"][$civID]) == '') {
            $name = '';
            $civRow = $this->sessData->getRowById('civilians', $civID);
            if (!$prsn) {
                list($prsn, $phys) = $this->queuePeopleSubsets($civID);
            }
//echo 'getCivReportName(' . $civID . '<pre>'; print_r($this->sessData->dataSets["civilians"]); print_r($civRow); echo '</pre><pre>'; print_r($prsn); echo '</pre><pre>'; print_r($phys); echo '</pre>';
            $com = $this->sessData->dataSets["complaints"][0];
            $civ1 = $this->sessData->dataSets["civilians"][0];
            if ($this->canPrintFullReport()) {
                $setCivID = $civ1->civ_id;
                $firstLast = '';
                if (isset($prsn->prsn_name_first)) {
                    $firstLast .= trim($prsn->prsn_name_first);
                }
                if (isset($prsn->prsn_name_last)) {
                    $firstLast .= trim($prsn->prsn_name_last);
                }
                if ($civID ==  $setCivID 
                    && ($firstLast == '' || $com->com_privacy == 306)) {
                    // '<span style="color: #2B3493;" title="'
                    // . 'This complainant did not provide their name to investigators.'
                    $name = 'Complainant';
                } elseif ($this->canPrintFullReport()
                    && !$this->hideWitnessName($civRow)) {
                    if ($firstLast != '') {
                        // '<span style="color: #2B3493;" title="'
                        // . 'This complainant wanted to publicly provide their name.">' 
                        $name = $prsn->prsn_name_first . ' ' . $prsn->prsn_name_last;
                        // ' . $prsn->prsn_name_middle . '
                    } elseif (isset($prsn->prsn_nickname) 
                        && trim($prsn->prsn_nickname) != '') {
                        $name = trim($prsn->prsn_nickname);
                    }
                }
            }
            $label = 'Complainant';
            if ($civ1->civ_id != $civID) {
                if ($civRow 
                    && isset($civRow->civ_role) 
                    && $civRow->civ_role == 'Victim') {
                    $label = 'Victim #' 
                        . (1+$this->sessData->getLoopIndFromID('Victims', $civID));
                } else {
                    $label = 'Witness #' 
                        . (1+$this->sessData->getLoopIndFromID('Witnesses', $civID));
                }
            } elseif ($civ1->civ_role == 'Victim') {
                $label = 'Victim #' 
                    . (1+$this->sessData->getLoopIndFromID('Victims', $civID));
            } elseif ($civ1->civ_role == 'Witness') {
                $label = 'Witness #' 
                    . (1+$this->sessData->getLoopIndFromID('Witnesses', $civID));
            }
            if (trim($name) == '') {
                $this->v["civNames"][$civID] = $label;
            } else {
                $this->v["civNames"][$civID] = $name . ' (' . $label . ')';
            }
        }
        return $this->v["civNames"][$civID];
    }
    
    /**
     * Print an officer's name for reports.
     *
     * @param  int $nID
     * @return string
     */
    protected function getOffReportNameHeader($nID)
    {
        list($itemInd, $itemID) = $this->sessData->currSessDataPosBranchOnly('officers');
        //$offID = $this->sessData->getLatestDataBranchID();
        $offRow = $this->sessData->getRowById('officers', $itemID);
        $style = '';
        if (!$GLOBALS["SL"]->isPdfView()) {
            $style = ' style="margin: 0px 0px 18px 0px;"';
        }
        return '<h4 class="slBlueDark"' . $style . '>' 
            . $this->getOffReportName($offRow, $itemInd) . '</h4>';
    }
    
    /**
     * Determine the most appropriate name printing for a officer in reports.
     *
     * @param  App\Models\OPOfficers $off
     * @param  int $ind
     * @param  App\Models\OPPersonContact $prsn
     * @return string
     */
    protected function getOffReportName($off, $ind = 0, $prsn = NULL)
    {
        if (!isset($this->v["offNames"])) {
            $this->v["offNames"] = [];
        }
        if ($off && isset($off->off_id)) {
            if (sizeof($this->v["offNames"]) == 0 
                || !isset($this->v["offNames"][$off->off_id]) 
                || trim($this->v["offNames"][$off->off_id]) == '') {
                if (!$prsn) {
                    list($prsn, $phys) = $this->queuePeopleSubsets($off->off_id, 'officers');
                }
                $name = ' ';
                if ($this->canPrintFullReport()) {
                    if (trim($off->off_officer_rank) != '') {
                        $rank = strtolower(trim(str_replace(' ', '', $off->off_officer_rank)));
                        $ok2print = [
                            'Agent', 'Assistant Chief of Police', 'Assistant Commissioner',
                            'Assistant Sheriff', 'Assistant Superintendent', 'Captain (Capt.)', 
                            'Chief Deputy', 'Chief of Police', 'Colonel (Col.)', 'Commander (Cdr.)', 
                            'Commissioner', 'Corporal (Cpl.)', 'Deputy', 'Deputy Chief of Police', 
                            'Deputy Commissioner', 'Deputy Superintendent', 'Detective (Det.)', 
                            'Director (Dir.)', 'Inspector (Insp.)', 'Investigator', 
                            'Lieutenant (Lt.)', 'Lieutenant Colonel (Lt. Col.)', 'Major (Maj.)', 
                            'Officer (Ofc.)', 'Patrol Officer', 'Police Officer', 
                            'Police Commissioner', 'Sergeant (Sgt.)', 'Sheriff', 
                            'Superintendent (Supt.)', 'Trooper', 'Undersheriff'
                        ];
                        foreach ($ok2print as $tmpRnk) {
                            $lwrRnk = strtolower(str_replace(' ', '', $tmpRnk));



                        }
                    }


                    



                    if (trim($prsn->prsn_nickname) != '') {
                        $name = trim(str_ireplace('Officer ', '', $prsn->prsn_nickname));
                    } else {
                        $name = trim($prsn->prsn_name_first . ' ' . $prsn->prsn_name_middle 
                            . ' ' . $prsn->prsn_name_last);
                        if ($name == '' 
                            && trim($off->off_badge_number) != '' 
                            && trim($off->off_badge_number) != '0') {
                            $name = 'Badge #' . $off->off_badge_number;
                        }
                    }
                }
                $label = 'Officer #' . (1+$ind);
                if (trim($name) == '') {
                    $this->v["offNames"][$off->off_id] = $label;
                } else {
                    $this->v["offNames"][$off->off_id] = $name . ' (' . $label . ')';
                }
            }
            return $this->v["offNames"][$off->off_id];
        }
        return '';
    }
    
    /**
     * Determine the most appropriate name printing for a officer within the survey.
     *
     * @param  App\Models\OPOfficers $off
     * @param  int $ind
     * @param  App\Models\OPPersonContact $prsn
     * @return string
     */
    protected function getOffNickname($off, $ind = 0, $prsn = NULL)
    {
        if (!isset($this->v["offNicknames"])) {
            $this->v["offNicknames"] = [];
        }
        if ($off && isset($off->off_id)) {
            if (sizeof($this->v["offNicknames"]) == 0 
                || !isset($this->v["offNicknames"][$off->off_id]) 
                || trim($this->v["offNicknames"][$off->off_id]) == '') {
                if (!$prsn) {
                    list($prsn, $phys) = $this->queuePeopleSubsets($off->off_id, 'officers');
                }
                $name = ' ';
                if ($this->canPrintFullReport()) {
                    if (trim($prsn->prsn_nickname) != '') {
                        $name = trim(str_ireplace('Officer ', '', $prsn->prsn_nickname));
                    } else {
                        $name = trim($prsn->prsn_name_first . ' ' . $prsn->prsn_name_middle 
                            . ' ' . $prsn->prsn_name_last);
                        if ($name == '' 
                            && trim($off->off_badge_number) != '' 
                            && trim($off->off_badge_number) != '0') {
                            $name = 'Badge #' . $off->off_badge_number;
                        }
                    }
                }
                $label = 'Officer #' . (1+$ind);
                if (trim($name) == '') {
                    $this->v["offNicknames"][$off->off_id] = $label;
                } else {
                    $this->v["offNicknames"][$off->off_id] = $name . ' (' . $label . ')';
                }
            }
            return $this->v["offNicknames"][$off->off_id];
        }
        return '';
    }

    /**
     * Print the Civilian name tied to this Use of Force.
     *
     * @return string
     */
    protected function getForceCivName()
    {
        $ret = '';
        $forceID = $this->sessData->getLatestDataBranchID();
        if ($forceID > 0
            && isset($this->sessData->dataSets["links_civilian_force"])
            && sizeof($this->sessData->dataSets["links_civilian_force"]) > 0) {
            foreach ($this->sessData->dataSets["links_civilian_force"] as $lnk) {
                if (isset($lnk->lnk_civ_frc_force_id)
                    && $lnk->lnk_civ_frc_force_id == $forceID) {
                    $ret .= ', ' . $this->getCivReportName($lnk->lnk_civ_frc_civ_id);
                }
            }
            if ($ret != '') {
                $ret = substr($ret, 1);
            }
        }
        return $ret;
    }

    /**
     * Print the Officer name tied to this Use of Force.
     *
     * @return string
     */
    protected function getForceOffName()
    {
        $ret = '';
        $forceID = $this->sessData->getLatestDataBranchID();
        if ($forceID > 0
            && isset($this->sessData->dataSets["links_officer_force"])
            && sizeof($this->sessData->dataSets["links_officer_force"]) > 0) {
            foreach ($this->sessData->dataSets["links_officer_force"] as $lnk) {
                if (isset($lnk->lnk_off_frc_force_id)
                    && $lnk->lnk_off_frc_force_id == $forceID) {
                    $ret .= ', ' . $this->getOffReportName($lnk->lnk_off_frc_off_id);
                }
            }
            if ($ret != '') {
                $ret = substr($ret, 1);
            }
        }
        return $ret;
    }
    
    /**
     * Create a list of civilian information fields which are not to be printed
     * with current permissions.
     *
     * @param  int $civID
     * @return string
     */
    protected function getCivSnstvFldsNotPrinted($civID)
    {
        $info = '';
        $prsn = $this->sessData->getChildRow(
            'civilians', 
            $civID, 
            'person_contact'
        );
        if ((isset($prsn->prsn_name_first) && trim($prsn->prsn_name_first) != '') 
            || (isset($prsn->prsn_name_last) && $prsn->prsn_name_last != '')) {
            //&& $this->sessData->dataSets["complaints"][0]
            //->com_privacy != 304) {
            $info .= ', Name';
        }
        if (isset($prsn->prsn_address) && trim($prsn->prsn_address) != '')  {
            $info .= ', Address';
        }
        if (isset($prsn->prsn_phone_home) && trim($prsn->prsn_phone_home) != '') {
            $info .= ', Phone Number'; 
        }
        if (isset($prsn->prsn_email) && trim($prsn->prsn_email) != '') {
            $info .= ', Email'; 
        }
        if (isset($prsn->prsn_facebook) && trim($prsn->prsn_facebook) != '') {
            $info .= ', Facebook';
        }
        if (isset($prsn->prsn_birthday) 
            && trim($prsn->prsn_birthday) != '' 
            && trim($prsn->prsn_birthday) != '0000-00-00' 
            && trim($prsn->prsn_birthday) != '1970-01-01') {
            $info .= ', Birthday';
        }
        if (trim($info) != '' 
            && ($civID != $this->sessData->dataSets["civilians"][0]->civ_id 
                || $this->sessData->dataSets["complaints"][0]->com_privacy != 306)) {
            return '<i class="slGrey">Not public: ' . substr($info, 1) . '</i>';
        }
        return '';
    }
    
    /**
     * Create a list of officer information fields which are not to be printed
     * with current permissions.
     *
     * @param  int $offID
     * @return string
     */
    protected function getOffSnstvFldsNotPrinted($offID)
    {
        $off = $this->sessData->getRowById('officers', $offID);
        $prsn = $this->sessData->getChildRow('officers', $offID, 'person_contact');
        $info = '';
        if ((isset($prsn->prsn_name_first) && trim($prsn->prsn_name_first) != '') 
            || (isset($prsn->prsn_name_last) && $prsn->prsn_name_last != '')) {
            $info .= ', Name';
        }
        if (isset($off->off_badge_number) && intVal($off->off_badge_number) > 0) {
            $info .= ', Badge Number';
        }
        if (trim($info) != '') {
            return '<i class="slGrey">Not public: ' . substr($info, 1) . '</i>';
        }
        return '';
    }
    
    /**
     * Create a written list of officer's that used profanity during this incident.
     *
     * @return array
     */
    protected function getOffProfan()
    {
        $cnt = 0;
        $profanity = '';
        if (isset($this->sessData->dataSets["officers"])) {
            $offs = $this->sessData->dataSets["officers"];
            if (sizeof($offs) > 0) {
                foreach ($offs as $i => $off) {
                    if ($off->off_used_profanity == 'Y') {
                        $cnt++;
                        $profanity .= ', ' . $this->getOffReportName($off);
                    }
                }
            }
        }
        if (trim($profanity) != '') {
            $desc = 'Officer' . (($cnt > 1) ? 's' : '') . ' used profanity?';
            return [
                $desc, 
                substr($profanity, 1)
            ];
        }
        return [];
    }
    
    /**
     * Create a written list of civilian's that used profanity during this incident.
     *
     * @return array
     */
    protected function getCivProfan()
    {
        $cnt = 0;
        $profanity = '';
        if (isset($this->sessData->dataSets["civilians"])) {
            $civs = $this->sessData->dataSets["civilians"];
            if (sizeof($civs) > 0) {
                foreach ($civs as $i => $civ) {
                    if ($civ->civ_used_profanity == 'Y') {
                        $cnt++;
                        $profanity .= ', ' . $this->getCivReportName($civ->getKey());
                    }
                }
            }
        }
        if (trim($profanity) != '') {
            $desc = 'Civilian' . (($cnt > 1) ? 's' : '') . ' used profanity?';
            return [
                $desc, 
                substr($profanity, 1)
            ];
        }
        return [];
    }
    
    /**
     * Get report details block for officer's age range.
     *
     * @return array
     */
    protected function getReportOffAge($nID)
    {
        $phys = $this->sessData->getLatestDataBranchRow();
        if ($phys && isset($phys->phys_age) && intVal($phys->phys_age) > 0) {
            $defAge = $GLOBALS["SL"]->def->getVal('Age Ranges Officers', $phys->phys_age);
            return [
                '<span class="slGrey">Age Range</span>', 
                $defAge, 
                $nID
            ];
        }
        return [];
    }
    
    /**
     * Print out report section header for gold event descriptions.
     *
     * @return string
     */
    protected function reportEventTitle($eveID)
    {
        $h3 = '<h3 class="slBlueDark mT0 mB20">';
        switch ($this->getEveSeqRowType($eveID)) {
            case 'Stops':    return $h3 . 'Stop</h3>';
            case 'Searches': return $h3 . 'Search</h3>';
            case 'Force':    return $h3 . 'Use of Force</h3>';
            case 'Arrests':  return $h3 . 'Arrest</h3>';
        }
        return '';
    }
    
    /**
     * Print out desires for departments.
     *
     * @return string
     */
    protected function reportDeptDesires($nID)
    {
        $ret = '';
        $defAll = $GLOBALS["SL"]->def->getID(
            'Desires for Departments',
            'All three of these things'
        );
        if (isset($this->sessData->dataSets["scenes"])
            && isset($this->sessData->dataSets["scenes"][0]->scn_desires_depts)) {
            $ret = intVal($this->sessData->dataSets["scenes"][0]->scn_desires_depts);
            if ($ret == $defAll) {
                $ret = 'New rules limiting police use of force, '
                    . 'Stronger accountability systems for police misconduct, '
                    . 'Better training for officers';
            } else {
                $ret = $GLOBALS["SL"]->def->getVal('Desires for Departments', $ret);
            }
        }
        $label = '<a id="hidivBtn2899" class="hidivBtn slGrey" href="javascript:;"'
            . '>Desires for Department(s) Involved</a>'
            . '<div id="hidiv2899" class="disNon">'
            . '"I want the police department to adopt..."</div>';
        return ['', $ret];
    }
    
    /**
     * Print out sharing and social media section of the report.
     *
     * @return string
     */
    protected function printReportShare()
    {
        $com = $this->sessData->dataSets["complaints"][0];
        $isPublished = $this->isPublished('complaints', $this->coreID, $com);
        $viewPrfx = (($GLOBALS["SL"]->pageView == 'full') ? 'full-' : '');
        return view(
            'vendor.openpolice.nodes.1710-report-inc-share', 
            [
                "pubID"     => $com->com_public_id,
                "emojiTags" => $this->printEmojiTags(),
                "published" => $isPublished,
                "viewPrfx"  => $viewPrfx
            ]
        )->render();
    }
    
    /**
     * Fill in the SurvLoop glossary with everything worthy in this report.
     *
     * @return string
     */
    protected function fillGlossary()
    {
        $this->v["glossaryList"] = [];
        $allegLabel = 'Allegation: ';
        if (!$GLOBALS["SL"]->isPdfView()) {
            $allegLabel = '<a href="/allegations" target="_blank">Allegation</a>: ';
        }
        if ((in_array($this->treeID, [1, 42]) || $GLOBALS["SL"]->getReportTreeID() == 1)
            && isset($this->sessData->dataSets["complaints"])) {
            if ($this->sessData->dataSets["complaints"][0]->com_award_medallion == 'Gold') {
                $this->v["glossaryList"][] = [
                    '<b>Gold-Level Complaint</b>', 
                    view(
                        'vendor.openpolice.report-inc-fill-glossary', 
                        [ "glossaryType" => 'Gold-Level Complaint' ]
                    )->render()
                ];
            }
            $this->simpleAllegationList();
            if (sizeof($this->allegations) > 0) {
                foreach ($this->allegations as $i => $a) {
                    $allegDesc = $allegLabel 
                        . $GLOBALS["SL"]->def->getDesc('Allegation Type', $a[0]);
                    $this->v["glossaryList"][] = [
                        '<b>' . $a[0] . '</b>', 
                        $allegDesc
                    ];
                }
            }
        }
        return true;
    }
    
    /**
     * Print out Flex Your Rights articles associated with responses
     * included in this report.
     *
     * @return string
     */
    protected function printFlexArts()
    {
        $this->loadRelatedArticles();
        return view(
            'vendor.openpolice.nodes.1708-report-flex-articles', 
            [ "allUrls" => $this->v["allUrls"] ]
        )->render();
    }
    
    /**
     * Print out Flex Your Rights videos associated with responses
     * included in this report.
     *
     * @return string
     */
    protected function printFlexVids()
    {
        $this->loadRelatedArticles();
        $ret = view(
            'vendor.openpolice.nodes.1753-report-flex-videos', 
            [ "allUrls" => $this->v["allUrls"] ]
        )->render();
        $GLOBALS["SL"]->pageAJAX .= ' setTimeout(function() { '
            . 'document.getElementById("flexVidsDelayed").innerHTML="' 
            . $GLOBALS["SL"]->addSlashLines($ret) . '"; }, 1700); ';
        return '<div id="flexVidsDelayed" class="w100"><div class="w100 taC">'
            . $GLOBALS["SL"]->sysOpts["spinner-code"] . '</div></div>';
    }
    
    /**
     * This SurvLoop function provides customization of values reported
     * in detail blocks.
     *
     * @param  int $nID
     * @param  string $val
     * @param  App\Models\SLFields $fldRow
     * @return string
     */
    protected function printValCustom($nID, $val, $fldRow)
    {
        if (in_array($nID, [1486, 1528])) {
            return $GLOBALS["SL"]->printHeight(intVal($val));
        } elseif ($nID == 2834) {
            return $this->getForceCivName();
        } elseif ($nID == 2835) {
            return $this->getForceOffName();
        }
        return $val;
    }

}