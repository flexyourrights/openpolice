<?php
namespace OpenPolice\Controllers;

use DB;
use Auth;
use OpenPolice\Models\OPDepartments;
use OpenPolice\Models\OPZeditDepartments;
use OpenPolice\Models\OPZeditOversight;
use OpenPolice\Models\OPzVolunTmp;
use OpenPolice\Models\OPOversight;
use OpenPolice\Controllers\DepartmentScores;
use OpenPolice\Controllers\OpenDepts;

use OpenPolice\Controllers\VolunteerLeaderboard;

class OpenReport extends OpenDepts
{
    protected function reportAllegsWhyDeets($nID = -3)
    {
        $deets = [];
        if (isset($this->sessData->dataSets["AllegSilver"]) && $this->sessData->dataSets["AllegSilver"][0]
            && isset($this->sessData->dataSets["Allegations"]) && sizeof($this->sessData->dataSets["Allegations"]) > 0){
            foreach ($this->worstAllegations as $i => $alleg) {
                if (isset($this->sessData->dataSets["AllegSilver"][0]->{ $alleg[2] })
                    && trim($this->sessData->dataSets["AllegSilver"][0]->{ $alleg[2] }) == 'Y') {
                    $alle = '';
                    if ($GLOBALS["SL"]->x["dataPerms"] != 'public') {
                        $foundWhy = false;
                        $alle .= '<b class="fPerc125">' . $alleg[1] . '</b><br />';
                        foreach ($this->sessData->dataSets["Allegations"] as $j => $all) {
                            if (!$foundWhy && $all && isset($all->AlleType) && $all->AlleType == $alleg[0]
                                && trim($all->AlleDescription) != '') {
                                $alle .= $all->AlleDescription . '<br />';
                                $foundWhy = true;
                            }
                        }
                    } else {
                        $alle .= '<b class="fPerc125">' . $alleg[1] . '</b>';
                    }
                    $deets[] = [$alle];
                }
            }
        }
        return $deets;
    }

    protected function reportAllegsWhy($nID = -3)
    {
        return $this->printReportDeetsBlock($this->reportAllegsWhyDeets($nID), 
            'Allegations</h3><div class="mTn10 slGrey">Including comments from the complainant</div>'
            . '<h3 class="disNon">');
    }

    protected function reportCivAddy($nID)
    {
        if ($nID > 0 && isset($this->allNodes[$nID]) && $this->checkFldDataPerms($this->allNodes[$nID]->getFldRow()) 
            && $this->checkViewDataPerms($this->allNodes[$nID]->getFldRow())) {
            $addy = $GLOBALS["SL"]->printRowAddy($this->sessData->getLatestDataBranchRow(), 'Prsn');
            if (trim($addy) != '') return [ 'Address', $addy ];
        }
        return [];
    }

    protected function reportStory($nID)
    {
        $ret = '';
        if ($nID > 0 && isset($this->allNodes[$nID]) && $this->checkFldDataPerms($this->allNodes[$nID]->getFldRow()) 
            && $this->checkViewDataPerms($this->allNodes[$nID]->getFldRow())) {
            if (!in_array($GLOBALS["SL"]->x["pageView"], ['pdf', 'full-pdf'])) {
                $previewMax = 1800;
                if (strlen($this->sessData->dataSets["Complaints"][0]->ComSummary) > $previewMax) {
                    $brkPos = strpos($this->sessData->dataSets["Complaints"][0]->ComSummary, ' ', $previewMax-200);
                    if ($brkPos > 0) {
                        $ret = '<div id="hidivStoryLess" class="' 
                            . (($GLOBALS["SL"]->REQ->has('read') && $GLOBALS["SL"]->REQ->get('read') == 'more')
                                ? 'disNon' : 'disBlo') . '">' . str_replace("\n", '<br />', 
                            substr($this->sessData->dataSets["Complaints"][0]->ComSummary, 0, $brkPos+1)) . ' ...<br />'
                            . '<a id="hidivBtnStoryMore" class="btn btn-primary mT20" href="javascript:;">Read More</a>'
                            . '</div>'
                            . '<div id="hidivStoryMore" class="' 
                            . (($GLOBALS["SL"]->REQ->has('read') && $GLOBALS["SL"]->REQ->get('read') == 'more')
                                ? 'disBlo' : 'disNon') . '">' . str_replace("\n", '<br />', 
                            $this->sessData->dataSets["Complaints"][0]->ComSummary, $brkPos) 
                            . '<br /><a id="hidivBtnStryLessBtn" class="btn btn-primary mT20" href="javascript:;">'
                            . 'Read Less</a></div>';
                        $GLOBALS["SL"]->pageAJAX .= '$(document).on("click", "#hidivBtnStoryMore", function() { '
                            . 'document.getElementById("hidivStoryMore").style.display="block"; setTimeout(function() { '
                            . 'document.getElementById("hidivStoryLess").style.display="none"; }, 5); });'
                            . '$(document).on("click", "#hidivBtnStryLessBtn", function() { '
                            . 'document.getElementById("hidivStoryLess").style.display="block"; setTimeout(function() { '
                            . 'document.getElementById("hidivStoryMore").style.display="none"; }, 5); });';
                    }
                }
            }
            if (trim($ret) == '') {
                $ret = str_replace("\n", '<br />', $this->sessData->dataSets["Complaints"][0]->ComSummary);
            }
        }
        return '<h3 class="slBlueDark mT0">Story</h3><p>' . $ret . '</p>';
        
    }
    
    protected function queuePeopleSubsets($id, $type = 'Civilians')
    {
        $prsn = $this->sessData->getChildRow($type, $id, 'PersonContact');
        $phys = $this->sessData->getChildRow($type, $id, 'PhysicalDesc');
        return [$prsn, $phys];
    }
    
    protected function chkGetReportDept($overLnkID)
    {
        if (!isset($this->v["reportDepts"])) {
            $this->v["reportDepts"] = [];
        }
        $overLnk = $this->sessData->getRowById('LinksComplaintOversight', $overLnkID);
        if ($overLnk && isset($overLnk->LnkComOverDeptID) && intVal($overLnk->LnkComOverDeptID) > 0
            && !in_array($overLnk->LnkComOverDeptID, $this->v["reportDepts"])) {
            $this->v["reportDepts"][] = $overLnk->LnkComOverDeptID;
            return '';
        }
        return '<!-- skipping overLnk #' . $overLnkID . ' -->';
    }
    
    protected function getReportDept($deptID)
    {
        $dept = $this->sessData->getRowById('Departments', $deptID);
        if ($dept && isset($dept->DeptName)) {
            return '<h3 class="mT0 mB5"><a href="/dept/' . $dept->DeptSlug . '" class="slBlueDark">' 
                 . $dept->DeptName . '</a></h3><div class="mB10">Complaint #' 
                . $this->sessData->dataSets["Complaints"][0]->ComPublicID . ': <b>' 
                . $GLOBALS["SL"]->def->getVal('Complaint Status', 
                    $this->sessData->dataSets["Complaints"][0]->ComStatus) 
                . '</b></div>';
        }
        $this->v["reportDepts"][] = $deptID;
        return '';
    }
    
    protected function getReportByLine()
    {
        $ret = '';
        if ($this->sessData->dataSets['Complaints'][0]->ComPrivacy 
            == $GLOBALS["SL"]->def->getID('Privacy Types', 'Completely Anonymous')) {
            $ret = 'Anonymous';
        } elseif (isset($this->sessData->dataSets["Civilians"]) 
            && isset($this->sessData->dataSets["Civilians"][0]->CivID) 
            && (in_array($GLOBALS["SL"]->x["pageView"], ['full', 'full-pdf'])
            || ($this->isPublished('Complaints', $this->coreID, $this->sessData->dataSets["Complaints"][0])
                && $this->sessData->dataSets['Complaints'][0]->ComPrivacy 
                == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')))) {
            $ret = $this->getCivReportName($this->sessData->dataSets["Civilians"][0]->CivID);
        }
        if (trim($ret) != '') {
            return ['Submitted By', $ret];
        }
        return [];
    }
    
    protected function getReportWhenLine()
    {
        $date = '';
        if ($this->v["isOwner"] || $this->v["isAdmin"] || ($GLOBALS["SL"]->x["pageView"] != 'public' 
            && $this->sessData->dataSets['Complaints'][0]->ComPrivacy
            == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly'))) {
            $date = date('n/j/Y', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeStart));
            $timeStart = $timeEnd = '';
            if ($this->sessData->dataSets["Incidents"][0]->IncTimeEnd !== null) {
                $timeEnd = date('g:ia', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeEnd));
            }
            if ($this->sessData->dataSets["Incidents"][0]->IncTimeStart !== null) {
                $timeStart = date('g:ia', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeStart));
                if ($timeStart != '' && ($timeStart != '12:00am' || $timeStart != $timeEnd)) {
                    $date .= ' <nobr>at ' . $timeStart . '</nobr>';
                    if ($timeEnd != '' && $timeStart != $timeEnd) {
                        $date .= ' <nobr>until ' . $timeEnd . '</nobr>';
                    }
                }
            }
        } elseif (isset($this->sessData->dataSets["Incidents"][0])) {
            $date = date('F Y', strtotime($this->sessData->dataSets["Incidents"][0]->IncTimeStart));
        }
        return ['When', $date];
    }
    
    protected function chkPrintWhereLine($nID = -3)
    {
        $show = false;
        if ($nID > 0 && isset($this->allNodes[$nID]) && $this->checkFldDataPerms($this->allNodes[$nID]->getFldRow()) 
            && $this->checkViewDataPerms($this->allNodes[$nID]->getFldRow())) {
            if ($GLOBALS["SL"]->x["pageView"] == 'full') {
                $show = true;
            } elseif (isset($this->sessData->dataSets["Incidents"][0]->IncPublic) 
                && $this->sessData->dataSets["Incidents"][0]->IncPublic == 'Y'
                && $this->isPublished('Complaints', $this->coreID, $this->sessData->dataSets["Complaints"][0])) {
                $show = true;
            }
        }
        return $show;
    }
    
    protected function getReportWhereLine($nID = -3)
    {
        if (isset($this->sessData->dataSets["Incidents"])) {
            $addy = $GLOBALS["SL"]->printRowAddy($this->sessData->dataSets["Incidents"][0], 'Inc');
            if ($this->chkPrintWhereLine($nID) && trim($addy) != '') {
                return ['Where', $addy];
            }
            if (isset($this->sessData->dataSets["Incidents"][0]->IncAddressState)) {
                $c = '';
                $state = $this->sessData->dataSets["Incidents"][0]->IncAddressState;
                if (isset($this->sessData->dataSets["Incidents"][0]->IncAddressZip)) {
                    $c = $GLOBALS["SL"]->getZipProperty($this->sessData->dataSets["Incidents"][0]->IncAddressZip,
                        'County');
                } elseif (isset($this->sessData->dataSets["Incidents"][0]->IncAddressCity)) {
                    $c = $GLOBALS["SL"]->getCityCounty($this->sessData->dataSets["Incidents"][0]->IncAddressZip,
                        $state);
                }
                if (trim($c) != '') {
                    return ['Where', $GLOBALS["SL"]->allCapsToUp1stChars($c) . ' County, ' . $state];
                }
            }
        }
        return [];
    }
    
    protected function getReportPrivacy($nID)
    {
        switch ($this->sessData->dataSets["Complaints"][0]->ComPrivacy) {
            case $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly'): 
                return 'Full Transparency';
            case $GLOBALS["SL"]->def->getID('Privacy Types', 'Names Visible to Police but not Public'): 
                return 'No Names Public';
            case $GLOBALS["SL"]->def->getID('Privacy Types', 'Completely Anonymous'): 
                return 'Anonymous';
        }
        return '';
    }
    
    protected function getCivReportNameHeader($nID)
    {
        return '<h3 class="slBlueDark" style="margin: 0px 0px 18px 0px;">' 
            . $this->getCivReportName($this->sessData->getLatestDataBranchID()) . '</h3>';
    }
    
    protected function getCivReportName($civID, $ind = 0, $type = 'Subject', $prsn = NULL)
    {
        if (!isset($this->v["civNames"])) {
            $this->v["civNames"] = [];
        }
        if (!isset($this->v["civNames"][$civID]) || trim($this->v["civNames"][$civID]) == '') {
            if (!$prsn) {
                list($prsn, $phys) = $this->queuePeopleSubsets($civID);
            }
            $name = '';
            if ($this->canPrintFullReport()) {
                if ( $civID == $this->sessData->dataSets["Civilians"][0]->CivID 
                    && (trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) == ''
                    || $this->sessData->dataSets["Complaints"][0]->ComPrivacy == 306) ) {
                    $name = '<span style="color: #2b3493;" title="This complainant did not provide their name to '
                        . 'investigators.">Complainant</span>';
                } elseif (trim($prsn->PrsnNameFirst . $prsn->PrsnNameLast) != '' 
                    && ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 304 
                    || $GLOBALS["SL"]->x["pageView"] == 'full')) {
                    if (trim($prsn->PrsnNickname) != '') {
                        $name = trim($prsn->PrsnNickname);
                    } else {
                        $name = '<span style="color: #2b3493;" title="This complainant wanted to publicly provide '
                            . 'their name.">' . $prsn->PrsnNameFirst . ' ' . $prsn->PrsnNameLast . '</span>';
                    } // ' . $prsn->PrsnNameMiddle . '
                }
            }
            $label = 'Complainant';
            $civRow = $this->sessData->getRowById('Civilians', $civID);
            if ($this->sessData->dataSets["Civilians"][0]->CivID != $civID) {
                if ($civRow && isset($civRow->CivRole) && $civRow->CivRole == 'Victim') {
                    $label = 'Victim #' . (1+$this->sessData->getLoopIndFromID('Victims', $civID));
                } else {
                    $label = 'Witness #' . (1+$this->sessData->getLoopIndFromID('Witnesses', $civID));
                }
            } elseif ($this->sessData->dataSets["Civilians"][0]->CivRole == 'Victim') {
                $label = 'Victim #' . (1+$this->sessData->getLoopIndFromID('Victims', $civID));
            } elseif ($this->sessData->dataSets["Civilians"][0]->CivRole == 'Witness') {
                $label = 'Witness #' . (1+$this->sessData->getLoopIndFromID('Witnesses', $civID));
            }
            $this->v["civNames"][$civID] = $label . ((trim($name) != '') ? ': ' . $name : '');
        }
        return $this->v["civNames"][$civID];
    }
    
    protected function getOffReportNameHeader($nID)
    {
        list($itemInd, $itemID) = $this->sessData->currSessDataPosBranchOnly('Officers');
        return '<h3 class="slBlueDark" style="margin: 0px 0px 18px 0px;">' 
            . $this->getOffReportName($this->sessData->getRowById('Officers', $this->sessData->getLatestDataBranchID()),
                $itemInd)
            . '</h3>';
    }
    
    protected function getOffReportName($off, $ind = 0, $prsn = NULL)
    {
        if (!isset($this->v["offNames"])) $this->v["offNames"] = [];
        if ($off && isset($off->OffID)) {
            if (sizeof($this->v["offNames"]) == 0 || !isset($this->v["offNames"][$off->OffID]) 
                || trim($this->v["offNames"][$off->OffID]) == '') {
                if (!$prsn) list($prsn, $phys) = $this->queuePeopleSubsets($off->OffID, 'Officers');
                $name = ' ';
                if ($GLOBALS["SL"]->x["pageView"] != 'public') {
                    if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy == 304 
                        || $GLOBALS["SL"]->x["pageView"] == 'full') {
                        if (trim($prsn->PrsnNickname) != '') {
                            $name = trim($prsn->PrsnNickname);
                        } else {
                            $name = trim($prsn->PrsnNameFirst . ' ' . $prsn->PrsnNameMiddle . ' ' . $prsn->PrsnNameLast);
                            if (trim($name) == '' && trim($off->OffBadgeNumber) != '' 
                                && trim($off->OffBadgeNumber) != '0') {
                                $name = 'Badge #' . $off->OffBadgeNumber;
                            }
                        }
                    }
                }
                $this->v["offNames"][$off->OffID] = 'Officer #' . (1+$ind) . ((trim($name) != '') ? ': ' . $name : '');
            }
            return $this->v["offNames"][$off->OffID];
        }
        return '';
    }
    
    protected function getCivSnstvFldsNotPrinted($civID)
    {
        $info = '';
        $prsn = $this->sessData->getChildRow('Civilians', $civID, 'PersonContact');
        if ((((isset($prsn->PrsnNameFirst) && trim($prsn->PrsnNameFirst) != '') 
            || (isset($prsn->PrsnNameLast) && $prsn->PrsnNameLast != '')) ? ', Name' : '')
            && $this->sessData->dataSets["Complaints"][0]->ComPrivacy != 304) {
            $info .= ', Name';
        }
        if (isset($prsn->PrsnAddress) && trim($prsn->PrsnAddress) != '')   $info .= ', Address';
        if (isset($prsn->PrsnPhoneHome) && trim($prsn->PrsnPhoneHome) != '') $info .= ', Phone Number'; 
        if (isset($prsn->PrsnEmail) && trim($prsn->PrsnEmail) != '')     $info .= ', Email'; 
        if (isset($prsn->PrsnFacebook) && trim($prsn->PrsnFacebook) != '')  $info .= ', Facebook';
        if (isset($prsn->PrsnBirthday) && trim($prsn->PrsnBirthday) != '' && trim($prsn->PrsnBirthday) != '0000-00-00' 
            && trim($prsn->PrsnBirthday) != '1970-01-01') {
            $info .= ', Birthday';
        }
        if (($civID != $this->sessData->dataSets["Civilians"][0]->CivID 
            || $this->sessData->dataSets["Complaints"][0]->ComPrivacy != 306) && trim($info) != '') {
            return '<i class="slGrey">Not public: ' . substr($info, 1) . '</i>';
        }
        return '';
    }
    
    protected function getOffSnstvFldsNotPrinted($offID)
    {
        $off = $this->sessData->getRowById('Officers', $offID);
        $prsn = $this->sessData->getChildRow('Officers', $offID, 'PersonContact');
        $info = (((isset($prsn->PrsnNameFirst) && trim($prsn->PrsnNameFirst) != '') 
            || (isset($prsn->PrsnNameLast) && $prsn->PrsnNameLast != '')) ? ', Name' : '')
            . ((isset($off->OffBadgeNumber) && intVal($off->OffBadgeNumber) > 0) ? ', Badge Number' : '');
        if (trim($info) != '') return '<i class="slGrey">Not public: ' . substr($info, 1) . '</i>';
        return '';
    }
    
    protected function getOffProfan()
    {
        $cnt = 0;
        $profanity = '';
        if (isset($this->sessData->dataSets["Officers"]) && sizeof($this->sessData->dataSets["Officers"]) > 0) {
            foreach ($this->sessData->dataSets["Officers"] as $i => $off) {
                if ($off->OffUsedProfanity == 'Y') {
                    $cnt++;
                    $profanity .= ', ' . $this->getOffReportName($off);
                }
            }
        }
        if (trim($profanity) != '') {
            return ['Officer' . (($cnt > 1) ? 's' : '') . ' used profanity?', substr($profanity, 1)];
        }
        return [];
    }
    
    protected function getCivProfan()
    {
        $cnt = 0;
        $profanity = '';
        if (isset($this->sessData->dataSets["Civilians"]) && sizeof($this->sessData->dataSets["Civilians"]) > 0) {
            foreach ($this->sessData->dataSets["Civilians"] as $i => $civ) {
                if ($civ->CivUsedProfanity == 'Y') {
                    $cnt++;
                    $profanity .= ', ' . $this->getCivReportName($civ->getKey());
                }
            }
        }
        if (trim($profanity) != '') {
            return ['Civilian' . (($cnt > 1) ? 's' : '') . ' used profanity?', substr($profanity, 1)];
        }
        return [];
    }
    
    protected function getReportOffAge($nID)
    {
        $phys = $this->sessData->getLatestDataBranchRow();
        if ($phys && isset($phys->PhysAge) && intVal($phys->PhysAge) > 0) {
            return ['<span class="slGrey">Age Range</span>', 
                $GLOBALS["SL"]->def->getVal('Age Ranges Officers', $phys->PhysAge), $nID];
        }
        return [];
    }
    
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
    
    protected function printReportShare()
    {
        return view('vendor.openpolice.nodes.1710-report-inc-share', [
            "pubID"     => $this->sessData->dataSets["Complaints"][0]->ComPublicID,
            "emojiTags" => $this->printEmojiTags(),
            "published" => $this->isPublished('Complaints', $this->coreID, $this->sessData->dataSets["Complaints"][0]),
            "viewPrfx"  => (($GLOBALS["SL"]->x["pageView"] == 'full') ? 'full-' : '')
            ])->render();
    }
    
    protected function fillGlossary()
    {
        $this->v["glossaryList"] = [];
        if ((in_array($this->treeID, [1, 42]) || $GLOBALS["SL"]->getReportTreeID() == 1)
            && isset($this->sessData->dataSets["Complaints"])) {
            $prvLnk = '<a href="/complaint-privacy-options" target="_blank">Privacy Setting</a>: ';
            if ($this->sessData->dataSets["Complaints"][0]->ComPrivacy 
                == $GLOBALS["SL"]->def->getID('Privacy Types', 'Submit Publicly')) {
                $this->v["glossaryList"][] = ['<b>Full Transparency</b>', 
                    $prvLnk . 'User opts to publish the names of civilians and police officers on this website.'];
            } elseif ($this->sessData->dataSets["Complaints"][0]->ComPrivacy 
                == $GLOBALS["SL"]->def->getID('Privacy Types', 'Names Visible to Police but not Public')) {
                $this->v["glossaryList"][] = ['<b>No Names Public</b>', 
                    $prvLnk . 'User doesn\'t want to publish any names on this website. 
                    This includes police officers\' names and badge numbers too.'];
            } elseif ($this->sessData->dataSets["Complaints"][0]->ComPrivacy 
                == $GLOBALS["SL"]->def->getID('Privacy Types', 'Completely Anonymous')) {
                $this->v["glossaryList"][] = ['<b>Anonymous</b> ', 
                    $prvLnk . 'User needs complaint to be completely anonymous, even though it will be harder to '
                        . 'investigate. No names will be published on this website. Neither OPC staff nor investigators'
                        . ' will be able to contact them. Any details that could be used for personal identification '
                        . 'may be deleted from the database.'];
            }
            if ($this->sessData->dataSets["Complaints"][0]->ComAwardMedallion == 'Gold') {
                $this->v["glossaryList"][] = ['<b>Gold-Level Complaint</b>', 
                    '<a href="/go-gold-make-your-complaint-strong">Optional</a>: This user opted '
                        . 'to share more complete details about their police experience than a Basic Complaint.'];
            }
            $this->simpleAllegationList();
            if (sizeof($this->allegations) > 0) {
                foreach ($this->allegations as $i => $a) {
                    $this->v["glossaryList"][] = [
                        '<b>' . $a[0] . '</b>', 
                        '<a href="/allegations" target="_blank">Allegation</a>: ' 
                            . $GLOBALS["SL"]->def->getDesc('Allegation Type', $a[0])
                    ];
                }
            }
        }
        return true;
    }
    
    protected function printFlexArts()
    {
        $this->loadRelatedArticles();
        return view('vendor.openpolice.nodes.1708-report-flex-articles', [
            "allUrls" => $this->v["allUrls"] ])->render();
    }
    
    protected function printFlexVids()
    {
        $this->loadRelatedArticles();
        return view('vendor.openpolice.nodes.1753-report-flex-videos', [
            "allUrls" => $this->v["allUrls"] ])->render();
    }
    
    protected function printValCustom($nID, $val) {
        if (in_array($nID, [1486, 1528])) {
            return $GLOBALS["SL"]->printHeight(intVal($val));
        }
        return $val;
    }
    
}