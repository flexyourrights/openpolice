<?php
/**
  * OpenReport is mid-level class which manages some of the specific 
  * reporting customizations.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use DB;
use Auth;
use App\Models\OPDepartments;
use App\Models\OPZeditDepartments;
use App\Models\OPZeditOversight;
use App\Models\OPzVolunTmp;
use App\Models\OPOversight;
use OpenPolice\Controllers\OpenDepts;
use OpenPolice\Controllers\VolunteerLeaderboard;

class OpenReport extends OpenDepts
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
        if (isset($this->sessData->dataSets["AllegSilver"]) 
            && isset($this->sessData->dataSets["AllegSilver"][0])) {
            $silv = $this->sessData->dataSets["AllegSilver"][0];
            foreach ($this->worstAllegations as $i => $alleg) {
                if (isset($silv->{ $alleg[2] })) {
                    $show = (trim($silv->{ $alleg[2] }) == 'Y');
                    if ($alleg[1] == 'Intimidating Display of Weapon') {
                        $allegVal = intVal($silv->{ $alleg[2] });
                        if (!in_array($allegVal, 
                            $this->intimidWeaponNos())) {
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
        $alle = '';
        if (!$this->canPrintFullReport()) {
            $alle .= '<b class="fPerc125">' 
                . $alleg[1] . '</b>';
        } else {
            $foundWhy = false;
            $alle .= '<b class="fPerc125">' 
                . $alleg[1] . '</b><br />';
            foreach ($this->sessData->dataSets["Allegations"] 
                as $j => $all) {
                if (!$foundWhy && $all 
                    && isset($all->AlleType) 
                    && $all->AlleType == $alleg[0] 
                    && trim($all->AlleDescription) != '') {
                    $alle .= $all->AlleDescription . '<br />';
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
        $why = $this->reportAllegsWhyDeets($nID);
        $deets = 'Allegations</h3>'
            . (($this->canPrintFullReport()) 
                ? '<div class="slGrey mTn10">Including ' 
                    . 'comments from the complainant</div>'
                : '')
            . '<h3 class="disNon">';
        return $this->printReportDeetsBlock($why, $deets);
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
                $addy = $GLOBALS["SL"]->printRowAddy($prsn, 'Prsn');
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
            $fldRow = $this->allNodes[$nID]->getFldRow();
            if ($this->checkFldDataPerms($fldRow) 
                && $this->checkViewDataPerms($fldRow)) {
                $story = $this->sessData
                    ->dataSets["Complaints"][0]->ComSummary;
                $views = ['pdf', 'full-pdf'];
                if (!in_array($GLOBALS["SL"]->pageView, $views)) {
                    $ret = $this->printStoryPreview($story);
                }
                if (trim($ret) == '') {
                    $ret = $GLOBALS["SL"]->textSaferHtml($story);
                }
            }
        }
        return '<h3 class="slBlueDark mT0 mB10">'
            . 'Story</h3><p>' . $ret . '</p>';
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
            $more = ($GLOBALS["SL"]->REQ->has('read') 
                && $GLOBALS["SL"]->REQ->get('read') 
                    == 'more');
            $brkPos = strpos($story, ' ', $previewMax-200);
            if ($brkPos > 0) {
                $storyLess = substr($story, 0, $brkPos+1);
                $ret = view(
                    'vendor.openpolice.nodes.1373-story-read-more', 
                    [
                        "more"      => $more,
                        "storyLess" => $GLOBALS["SL"]
                            ->textSaferHtml($storyLess),
                        "storyMore" => $GLOBALS["SL"]
                            ->textSaferHtml($story)
                    ]
                )->render();
                $GLOBALS["SL"]->pageAJAX .= view('vendor.openpolice.nodes.1373-story-read-more-ajax'
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
    protected function queuePeopleSubsets($id, $type = 'Civilians')
    {
        $prsn = $this->sessData->getChildRow($type, $id, 'PersonContact');
        $phys = $this->sessData->getChildRow($type, $id, 'PhysicalDesc');
        return [ $prsn, $phys ];
    }
    
    /**
     * Load department IDs responsible for this report.
     *
     * @param  int $overLnkID
     * @return string
     */
    protected function chkGetReportDept($overLnkID)
    {
        if (!isset($this->v["reportDepts"])) {
            $this->v["reportDepts"] = [];
        }
        $overLnk = $this->sessData->getRowById(
            'LinksComplaintOversight', 
            $overLnkID
        );
        if ($overLnk && isset($overLnk->LnkComOverDeptID)) {
            $deptID = intVal($overLnk->LnkComOverDeptID);
            if ($deptID > 0 
                && !in_array($deptID, $this->v["reportDepts"])) {
                $this->v["reportDepts"][] = $deptID;
                return '';
            }
        }
        return '<!-- skipping overLnk #' . $overLnkID . ' -->';
    }
    
    /**
     * Print the report headline a department responsible for this report.
     *
     * @param  int $deptID
     * @return string
     */
    protected function getReportDept($deptID)
    {
        $dept = $this->sessData->getRowById('Departments', $deptID);
        if ($dept && isset($dept->DeptName)) {
            return '<h3 class="mT0 mB5"><a href="/dept/' 
                . $dept->DeptSlug . '" class="slBlueDark">'
                . 'Misconduct Incident Report for ' . $dept->DeptName 
                . '</a></h3><div class="mB10"><b>Complaint #' 
                . $this->sessData->dataSets["Complaints"][0]->ComPublicID 
                . ': ' . $this->printComplaintStatus(
                    $this->sessData->dataSets["Complaints"][0]->ComStatus)
                . '</b></div>';
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
    protected function getReportByLine()
    {
        $ret = '';
        $com = $this->sessData->dataSets['Complaints'][0];
        $anon = $GLOBALS["SL"]->def
            ->getID('Privacy Types', 'Completely Anonymous');
        if ($com->ComPrivacy == $anon) {
            $ret = 'Anonymous';
        } elseif (isset($this->sessData->dataSets["Civilians"]) 
            && isset($this->sessData->dataSets["Civilians"][0]->CivID) 
            && (in_array($GLOBALS["SL"]->pageView, ['full', 'full-pdf'])
            || ($this->isPublished('Complaints', $this->coreID, $com)
                && $com->ComPrivacy 
                == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')))) {
            $ret = $this->getCivReportName($this->sessData
                ->dataSets["Civilians"][0]->CivID);
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
        if (!$complaint && isset($this->sessData->dataSets['Complaints'])) {
            $complaint = $this->sessData->dataSets['Complaints'][0];
        }
        return (($this->v["isOwner"] || $this->v["isAdmin"]) 
            && (!isset($GLOBALS["SL"]->x["isPublicList"]) 
                || !$GLOBALS["SL"]->x["isPublicList"]));
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
        if (isset($this->sessData->dataSets["Incidents"][0])) {
            $inc = $this->sessData->dataSets["Incidents"][0];
            if ($this->shouldPrintFullDate()) {
                $date = date('n/j/Y', strtotime($inc->IncTimeStart));
                $timeStart = $timeEnd = '';
                if ($inc->IncTimeEnd !== null) {
                    $timeEnd = date('g:ia', strtotime($inc->IncTimeEnd));
                }
                if ($inc->IncTimeStart !== null) {
                    $timeStart = date('g:ia', strtotime($inc->IncTimeStart));
                    $date .= $this->printStartEndTimes($timeStart, $timeEnd);
                }
            } else {
                $date = date('F Y', strtotime($inc->IncTimeStart));
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
            && ($timeStart != '12:00am' || $timeStart != $timeEnd)) {
            $ret .= ' <nobr>at ' . $timeStart . '</nobr>';
            if ($timeEnd != '' && $timeStart != $timeEnd) {
                $ret .= ' <nobr>until ' . $timeEnd . '</nobr>';
            }
        }
        return $ret;
    }
    
    /**
     * Check the current permissions on printing the incident's full address.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function chkPrintWhereLine($nID = -3)
    {
        $show = false;
        if ($nID > 0 && isset($this->allNodes[$nID]) 
            && $this->checkFldDataPerms($this->allNodes[$nID]->getFldRow()) 
            && $this->checkViewDataPerms($this->allNodes[$nID]->getFldRow())) {
            if ($GLOBALS["SL"]->pageView == 'full') {
                $show = true;
            } elseif (isset($this->sessData->dataSets["Incidents"][0]->IncPublic) 
                && $this->sessData->dataSets["Incidents"][0]->IncPublic == 'Y'
                && $this->canPrintFullReport()) {
                $show = true;
            }
        }
        return $show;
    }
    
    /**
     * Get the label and value for where an incident occured,
     * depending on the current permissions and available info.
     *
     * @param  int $nID
     * @return array
     */
    protected function getReportWhereLine($nID = -3)
    {
        if (isset($this->sessData->dataSets["Incidents"])) {
            $inc = $this->sessData->dataSets["Incidents"][0];
            $addy = $GLOBALS["SL"]->printRowAddy($inc, 'Inc');
            if ($this->chkPrintWhereLine($nID) 
                && trim($addy) != '') {
                return [ 'Indicent Location', $addy ];
            }
            if (isset($inc->IncAddressState)) {
                $c = '';
                $state = $inc->IncAddressState;
                if (isset($inc->IncAddressZip) 
                    && strlen($inc->IncAddressZip) > 4) {
                    $c = $GLOBALS["SL"]->getZipProperty(
                        $inc->IncAddressZip, 'County');
                }
                if (trim($c) == '' && isset($inc->IncAddressCity)) {
                    $c = $GLOBALS["SL"]->getCityCounty(
                        $inc->IncAddressCity, $state);
                }
                if (trim($c) != '') {
                    $ret = $GLOBALS["SL"]->allCapsToUp1stChars($c) 
                        . ', ' . $state;
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
        $ret = str_replace(
            'District Of Columbia County, DC', 
            'Washington, DC', 
            $str
        );
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
        $set = 'Privacy Types';
        switch ($this->sessData->dataSets["Complaints"][0]->ComPrivacy) {
            case $GLOBALS["SL"]->def->getID($set, 'Submit Publicly'): 
                return 'Full Transparency';
            case $GLOBALS["SL"]->def->getID($set, 
                'Names Visible to Police but not Public'): 
                return 'No Names Public';
            case $GLOBALS["SL"]->def->getID($set, 'Completely Anonymous'): 
                return 'Anonymous';
        }
        return '';
    }
    
    /**
     * Whether or not witness names should be printed on this complaint report.
     *
     * @param  App\Models\OPOversight $civRow
     * @return boolean
     */
    protected function hideWitnessName($civRow)
    {
        $views = ['full', 'full-pdf'];
        return ($civRow->CivRole == 'Witness' 
            && $civRow->CivIsCreator != 'Y'
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
        if (isset($civRow->CivRole) 
            && isset($civRow->CivIsCreator)) {
            if ($nID == 1507) {
                $prsn = $this->sessData->getChildRow(
                    'Civilians', 
                    $civRow->CivID, 
                    'PersonContact'
                );
                if ($prsn && isset($prsn->PrsnNickname)) {
                    $nick = trim(strtolower(
                        $prsn->PrsnNickname));
                    $first = $middle = $last = '';
                    if (isset($prsn->PrsnNameFirst)) {
                        $first = trim(strtolower(
                            $prsn->PrsnNameFirst));
                    }
                    if (isset($prsn->PrsnNameMiddle)) {
                        $middle = trim(strtolower(
                            $prsn->PrsnNameMiddle));
                    }
                    if (isset($prsn->PrsnNameLast)) {
                        $last = trim(strtolower(
                            $prsn->PrsnNameLast));
                    }
                    if ($this->chkDupliNickname(
                        $nick, $first, $middle, $last)) {
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
        return ($first == $nick || $middle == $nick || $last == $nick 
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
        return '<h3 class="slBlueDark" '
            . 'style="margin: 0px 0px 18px 0px;">' 
            . $this->getCivReportName(
                $this->sessData->getLatestDataBranchID()) 
            . '</h3>';
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
            $civRow = $this->sessData->getRowById('Civilians', $civID);
            if (!$prsn) {
                list($prsn, $phys) = $this->queuePeopleSubsets($civID);
            }
            $name = '';
            if ($this->canPrintFullReport()) {
                $setCivID = $this->sessData
                    ->dataSets["Civilians"][0]->CivID;
                $firstLast = trim($prsn->PrsnNameFirst 
                    . $prsn->PrsnNameLast);
                if ($civID ==  $setCivID
                    && ($firstLast == ''
                    || $this->sessData->dataSets["Complaints"][0]
                        ->ComPrivacy == 306) ) {
                    $name = '<span style="color: #2b3493;" title="'
                        . 'This complainant did not provide '
                        . 'their name to investigators.'
                        . '">Complainant</span>';
                } elseif ($firstLast != '' 
                    && $this->canPrintFullReport()
                    && !$this->hideWitnessName($civRow)) {
                    if (trim($prsn->PrsnNickname) != '') {
                        $name = trim($prsn->PrsnNickname);
                    } else {
                        $name = '<span style="color: #2b3493;" title="'
                            . 'This complainant wanted to '
                            . 'publicly provide their name.">' 
                            . $prsn->PrsnNameFirst . ' ' 
                            . $prsn->PrsnNameLast . '</span>';
                    } // ' . $prsn->PrsnNameMiddle . '
                }
            }
            $label = 'Complainant';
            if ($this->sessData->dataSets["Civilians"][0]
                    ->CivID != $civID) {
                if ($civRow && isset($civRow->CivRole) 
                    && $civRow->CivRole == 'Victim') {
                    $label = 'Victim #' . (1+$this->sessData
                        ->getLoopIndFromID('Victims', $civID));
                } else {
                    $label = 'Witness #' . (1+$this->sessData
                        ->getLoopIndFromID('Witnesses', $civID));
                }
            } elseif ($this->sessData->dataSets["Civilians"][0]
                ->CivRole == 'Victim') {
                $label = 'Victim #' . (1+$this->sessData
                    ->getLoopIndFromID('Victims', $civID));
            } elseif ($this->sessData->dataSets["Civilians"][0]
                ->CivRole == 'Witness') {
                $label = 'Witness #' . (1+$this->sessData
                    ->getLoopIndFromID('Witnesses', $civID));
            }
            $this->v["civNames"][$civID] = $label 
                . ((trim($name) != '') ? ': ' . $name : '');
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
        list($itemInd, $itemID) = $this->sessData
            ->currSessDataPosBranchOnly('Officers');
        //$offID = $this->sessData->getLatestDataBranchID();
        $offRow = $this->sessData->getRowById('Officers', $itemID);
        return '<h3 class="slBlueDark" '
            . 'style="margin: 0px 0px 18px 0px;">' 
            . $this->getOffReportName($offRow, $itemInd) 
            . '</h3>';
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
        if ($off && isset($off->OffID)) {
            if (sizeof($this->v["offNames"]) == 0 
                || !isset($this->v["offNames"][$off->OffID]) 
                || trim($this->v["offNames"][$off->OffID]) == '') {
                if (!$prsn) {
                    list($prsn, $phys) = $this
                        ->queuePeopleSubsets($off->OffID, 'Officers');
                }
                $name = ' ';
                if ($this->canPrintFullReport()) {
                    if (trim($prsn->PrsnNickname) != '') {
                        $name = trim(str_ireplace(
                            'Officer ', 
                            '', 
                            $prsn->PrsnNickname
                        ));
                    } else {
                        $name = trim($prsn->PrsnNameFirst . ' ' 
                            . $prsn->PrsnNameMiddle . ' ' 
                            . $prsn->PrsnNameLast);
                        if ($name == '' 
                            && trim($off->OffBadgeNumber) != '' 
                            && trim($off->OffBadgeNumber) != '0') {
                            $name = 'Badge #' . $off->OffBadgeNumber;
                        }
                    }
                }
                $this->v["offNames"][$off->OffID] = 'Officer #' 
                    . (1+$ind) . ((trim($name) != '') 
                        ? ': ' . $name : '');
            }
            return $this->v["offNames"][$off->OffID];
        }
        return '';
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
            'Civilians', 
            $civID, 
            'PersonContact'
        );
        if ((isset($prsn->PrsnNameFirst) 
            && trim($prsn->PrsnNameFirst) != '') 
            || (isset($prsn->PrsnNameLast) 
                && $prsn->PrsnNameLast != '')) {
            //&& $this->sessData->dataSets["Complaints"][0]
            //->ComPrivacy != 304) {
            $info .= ', Name';
        }
        if (isset($prsn->PrsnAddress) 
            && trim($prsn->PrsnAddress) != '')  {
            $info .= ', Address';
        }
        if (isset($prsn->PrsnPhoneHome) 
            && trim($prsn->PrsnPhoneHome) != '') {
            $info .= ', Phone Number'; 
        }
        if (isset($prsn->PrsnEmail) 
            && trim($prsn->PrsnEmail) != '') {
            $info .= ', Email'; 
        }
        if (isset($prsn->PrsnFacebook) 
            && trim($prsn->PrsnFacebook) != '') {
            $info .= ', Facebook';
        }
        if (isset($prsn->PrsnBirthday) 
            && trim($prsn->PrsnBirthday) != '' 
            && trim($prsn->PrsnBirthday) != '0000-00-00' 
            && trim($prsn->PrsnBirthday) != '1970-01-01') {
            $info .= ', Birthday';
        }
        if (trim($info) != '' && ($civID != $this->sessData
                ->dataSets["Civilians"][0]->CivID 
            || $this->sessData->dataSets["Complaints"][0]
                ->ComPrivacy != 306)) {
            return '<i class="slGrey">Not public: ' 
                . substr($info, 1) . '</i>';
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
        $off = $this->sessData->getRowById('Officers', $offID);
        $prsn = $this->sessData->getChildRow(
            'Officers', 
            $offID, 
            'PersonContact'
        );
        $info = (((isset($prsn->PrsnNameFirst) 
                && trim($prsn->PrsnNameFirst) != '') 
            || (isset($prsn->PrsnNameLast) 
                && $prsn->PrsnNameLast != '')) ? ', Name' : '')
            . ((isset($off->OffBadgeNumber) 
                && intVal($off->OffBadgeNumber) > 0) 
                ? ', Badge Number' : '');
        if (trim($info) != '') {
            return '<i class="slGrey">Not public: ' 
                . substr($info, 1) . '</i>';
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
        if (isset($this->sessData->dataSets["Officers"])) {
            $offs = $this->sessData->dataSets["Officers"];
            if (sizeof($offs) > 0) {
                foreach ($offs as $i => $off) {
                    if ($off->OffUsedProfanity == 'Y') {
                        $cnt++;
                        $profanity .= ', ' 
                            . $this->getOffReportName($off);
                    }
                }
            }
        }
        if (trim($profanity) != '') {
            $desc = 'Officer' . (($cnt > 1) ? 's' : '') 
                . ' used profanity?';
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
        if (isset($this->sessData->dataSets["Civilians"])) {
            $civs = $this->sessData->dataSets["Civilians"];
            if (sizeof($civs) > 0) {
                foreach ($civs as $i => $civ) {
                    if ($civ->CivUsedProfanity == 'Y') {
                        $cnt++;
                        $profanity .= ', ' . $this
                            ->getCivReportName($civ->getKey());
                    }
                }
            }
        }
        if (trim($profanity) != '') {
            $desc = 'Civilian' . (($cnt > 1) ? 's' : '') 
                . ' used profanity?';
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
        if ($phys && isset($phys->PhysAge) 
            && intVal($phys->PhysAge) > 0) {
            $defAge = $GLOBALS["SL"]->def
                ->getVal('Age Ranges Officers', $phys->PhysAge);
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
     * Print out sharing and social media section of the report.
     *
     * @return string
     */
    protected function printReportShare()
    {
        $com = $this->sessData->dataSets["Complaints"][0];
        $isPublished = $this->isPublished(
            'Complaints', 
            $this->coreID, 
            $com
        );
        $viewPrfx = (($GLOBALS["SL"]->pageView == 'full') 
            ? 'full-' : '');
        return view('vendor.openpolice.nodes.1710-report-inc-share', [
            "pubID"     => $com->ComPublicID,
            "emojiTags" => $this->printEmojiTags(),
            "published" => $isPublished,
            "viewPrfx"  => $viewPrfx
        ])->render();
    }
    
    /**
     * Fill in the SurvLoop glossary with everything worthy in this report.
     *
     * @return string
     */
    protected function fillGlossary()
    {
        $this->v["glossaryList"] = [];
        if ((in_array($this->treeID, [1, 42]) 
                || $GLOBALS["SL"]->getReportTreeID() == 1)
            && isset($this->sessData->dataSets["Complaints"])) {
            $prvLnk = '<a href="/complaint-privacy-options" '
                . 'target="_blank">Privacy Setting</a>: ';
            $prvType = $GLOBALS["SL"]->def->getVal('Privacy Types', 
                $this->sessData->dataSets["Complaints"][0]->ComPrivacy);
            $prvType = str_replace('Names Visible to Police but not Public', 
                'No Names Public', str_replace('Completely ', '', 
                str_replace('Submit Publicly', 'Full Transparency', 
                    $prvType)));
            $desc = view(
                'vendor.openpolice.report-inc-fill-glossary', 
                [
                    "glossaryType" => $prvType,
                    "prvLnk"       => $prvLnk
                ]
            )->render();
            $this->v["glossaryList"][] = [
                '<b>' . $prvType . '</b>',
                $desc
            ];
            if ($this->sessData->dataSets["Complaints"][0]
                ->ComAwardMedallion == 'Gold') {
                $this->v["glossaryList"][] = [
                    '<b>Gold-Level Complaint</b>', 
                    view(
                        'vendor.openpolice.report-inc-fill-glossary', 
                        [
                            "glossaryType" => 'Gold-Level Complaint'
                        ]
                    )->render()
                ];
            }
            $this->simpleAllegationList();
            if (sizeof($this->allegations) > 0) {
                foreach ($this->allegations as $i => $a) {
                    $this->v["glossaryList"][] = [
                        '<b>' . $a[0] . '</b>', 
                        '<a href="/allegations" target="_blank">'
                            . 'Allegation</a>: ' 
                            . $GLOBALS["SL"]->def
                                ->getDesc('Allegation Type', $a[0])
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
            [
                "allUrls" => $this->v["allUrls"]
            ]
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
        return view('vendor.openpolice.nodes.1753-report-flex-videos', [
            "allUrls" => $this->v["allUrls"]
        ])->render();
    }
    
    /**
     * This SurvLoop function provides customization of values reported
     * in detail blocks.
     *
     * @param  int $nID
     * @param  string $val
     * @return string
     */
    protected function printValCustom($nID, $val)
    {
        if (in_array($nID, [1486, 1528])) {
            return $GLOBALS["SL"]->printHeight(intVal($val));
        }
        return $val;
    }

}