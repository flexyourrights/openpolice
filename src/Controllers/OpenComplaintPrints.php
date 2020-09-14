<?php
/**
  * OpenComplaintPrints is a mid-level class which handles custom 
  * printing of data, especially functions which override SurvLoop defaults.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.0.12
  */
namespace OpenPolice\Controllers;

use DB;
use Auth;
use App\Models\OPAllegations;
use App\Models\OPAllegSilver;
use OpenPolice\Controllers\OpenComplaintEmails;

class OpenComplaintPrints extends OpenComplaintEmails
{
    /**
     * 
     *
     * @return boolean
     */
    protected function initBlnkAllegsSilv()
    {
        if (!isset($this->sessData->dataSets["alleg_silver"])) {
            $allegSilv = new OPAllegSilver;
            $allegSilv->alle_sil_complaint_id = $this->coreID;
            $allegSilv->save();
            $this->sessData->dataSets["alleg_silver"] = [ $allegSilv ];
        }
        foreach ($this->worstAllegations as $alle) {
            $found = false;
            if (isset($this->sessData->dataSets["allegations"])) {
                $all = $this->sessData->dataSets["allegations"];
                if (sizeof($all) > 0) {
                    foreach ($all as $i => $alleRow) {
                        if ($alle[0] == $alleRow->alle_type) {
                            $found = true;
                        }
                    }
                }
            }
            if (!$found) {
                $new = new OPAllegations;
                $new->alle_complaint_id = $this->coreID;
                $new->alle_type = $alle[0];
                $new->save();
            }
        }
        return true;
    }
    
    protected function printAllegAudit()
    {
        return $this->commaAllegationList(true);
    }
    
    protected function printEndOfComplaintRedirect($nID)
    {
        $url = '';
        $defNew = $GLOBALS["SL"]->def->getID('Complaint Status', 'New');
        if ($nID == 270) {
            $this->sessData->currSessDataTblFld(
                $nID, 
                'complaints', 
                'com_status', 
                'update', 
                $defNew
            );
            $this->sessData->currSessDataTblFld(
                $nID, 
                'complaints', 
                'com_record_submitted', 
                'update', 
                date("Y-m-d H:i:s")
            );
            $this->sessData->currSessDataTblFld(
                $nID, 
                'complaints', 
                'com_alleg_list', 
                'update', 
                $this->commaAllegationList()
            );
            $this->sessData->dataSets["complaints"][0]->update([
                'com_public_id' => $GLOBALS["SL"]->genNewCorePubID() 
            ]);
            $url = '/complaint/read-' . $this->sessData
                ->dataSets["complaints"][0]->com_public_id;
        } else {
            $this->sessData->currSessDataTblFld(
                $nID, 
                'compliments', 
                'compli_status', 
                'update', 
                $defNew
            );
            $this->sessData->currSessDataTblFld(
                $nID, 
                'compliments', 
                'compli_record_submitted', 
                'update', 
                date("Y-m-d H:i:s")
            );
            //$this->sessData->currSessDataTblFld($nID, 'compliments', 'compli_alleg_list', 'update', 
            //    $this->commaAllegationList());
            $this->sessData->dataSets["compliments"][0]->update([ 
                'compli_public_id' => $GLOBALS["SL"]->genNewCorePubID()
            ]);
            $url = '/compliment/read-' 
                . $this->sessData->dataSets["compliments"][0]->compli_public_id;
        }
        $spin = $GLOBALS["SL"]->sysOpts["spinner-code"];
        //$this->restartSess($GLOBALS["SL"]->REQ);
        return '<br /><br /><center><h1>All Done!<br />'
            . 'Taking you to <a href="' . $url . '">your finished ' 
            . (($nID == 270) ? 'complaint' : 'compliment') 
            . '</a>...</h1>' . $spin . '</center>'
            . '<script id="noExtract" type="text/javascript"> '
            . 'setTimeout("window.location=\'' . $url . '\'", 1500); '
            . '</script><style> '
            . '#nodeSubBtns, #sessMgmt, #dontWorry { display: none; } '
            . '</style>';
    }
    
    protected function customLabels($curr, $str = '')
    {
        if (in_array($this->treeID, [1, 5])) {
            $event = [];
            if (sizeof($this->sessData->dataBranches) > 1 
                && $this->sessData->dataBranches[1]["branch"] == 'event_sequence') {
                $event = $this->getEventSequence($this->sessData->dataBranches[1]["itemID"]);
            }
            if (strpos($str, '[ForceType]') !== false) {
                $row = $this->sessData->getLatestDataBranchRow();
//echo 'customLabels(' . $nIDtxt . ', row: <pre>'; print_r($row); echo '</pre>'; exit;
                $forceDesc = $GLOBALS["SL"]->def->getVal('Force Type', $row->for_type);
                if ($forceDesc == 'Other') {
                    $forceDesc = $row->for_type_other;
                }
                /* if (strpos($nIDtxt, '343') !== false) {
                    $eveSeq = $this->sessData->getDataBranchRow('event_sequence');
                    if ($eveSeq && isset($eveSeq->eve_id)) {
                        $forceDesc .= ' used on ' . $this->getCivNamesFromEvent($eveSeq->eve_id);
                    }
                } */
                $str = str_replace(
                    '[ForceType]', 
                    '<span class="slBlueDark"><b>' . $forceDesc .'</b></span>',
                    $str
                );
            } elseif (isset($event[0]) && isset($event[0]["EveID"])) {
                if (strpos($str, '[LoopItemLabel]') !== false) {
                    $civName = '';
                    if (isset($event[0]["Civilians"]) 
                        && sizeof($event[0]["Civilians"]) > 0) {
                        $civName = $this->getCivilianNameFromID($event[0]["Civilians"][0]);
                    }
                    $name = '<span class="slBlueDark"><b>' . $civName . '</b></span>';
                    $str = str_replace('[LoopItemLabel]', $name, $str);
                }
            } elseif (strpos($str, '[LoopItemLabel]') !== false) {
                $row = $this->sessData->getLatestDataBranchRow();
                if (isset($row->civ_id)) {
                    $str = str_replace('[LoopItemLabel]', $this->getCivName('Victims', $row), $str);
                } elseif (isset($row->inj_care_subject_id)) {
                    $civ = $this->sessData->getRowById('civilians', $row->inj_care_subject_id);
                    $str = str_replace('[LoopItemLabel]', $this->getCivName('Victims', $civ), $str);
                } elseif (isset($row->off_id)) {
                    $str = str_replace('[LoopItemLabel]', $this->getOfficerNameFromID($row->off_id), $str);
                }
            }
            if (strpos($str, '[[List of Allegations]]') !== false) {
                $str = str_replace('[[List of Allegations]]', $this->commaAllegationList(), $str);
            }
            if (strpos($str, '[[List of Events and Allegations]]') !== false) {
                $str = str_replace(
                    '[[List of Events and Allegations]]', 
                    $this->basicAllegationList(true), 
                    $str
                );
            }
            if (strpos($str, '[[List of Compliments]]') !== false) {
                $str = str_replace(
                    '[[List of Compliments]]', 
                    $this->commaComplimentList(), 
                    $str
                );
            }
            if (isset($this->sessData->dataSets["civilians"])) {
                $complainantVic = (isset($this->sessData->dataSets["civilians"][0])
                    && $this->sessData->dataSets["civilians"][0]->civ_role == 'Victim');
                $multipleVic = (sizeof($this->sessData->getLoopRows('Victims')) > 1);
                $nodes = ['209', '212', '852', '248', '222', '227', '234', '243'];
                if (in_array($curr->nIDtxt, $nodes)) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody', 'you', $str);
                    } elseif ($complainantVic && $multipleVic) {
                        $str = str_replace('anybody', 'you or anybody else', $str);
                    }
                } elseif (in_array($curr->nIDtxt, ['204'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody', 'you', $str);
                    } elseif ($complainantVic && $multipleVic) {
                        $str = str_replace('anybody', 'you (or anybody else)', $str);
                    }
                } elseif (in_array($curr->nIDtxt, ['205', '213'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody was', 'you were', $str);
                    } elseif ($complainantVic && $multipleVic) {
                        $str = str_replace('anybody was', 'anybody was', $str);
                    }
                } elseif (in_array($curr->nIDtxt, ['591'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody', 'you', $str);
                    }
                } elseif (in_array($curr->nIDtxt, ['228'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody was', 'you were', $str);
                    }
                }
                $str = str_replace(
                    'Did you who was not arrested get a ticket or citation?', 
                    'Did you get a ticket or citation?', 
                    $str
                );
            }
        }
        
        if (strpos($str, '[[PartnerUrl]]') !== false 
            && isset($this->sessData->dataSets["partners"])
            && isset($this->sessData->dataSets["partners"][0]->part_slug)) {
            $url = $GLOBALS["SL"]->sysOpts["app-url"] . '/';
            switch ($this->sessData->dataSets["partners"][0]->part_type) {
                case $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'): 
                    $url .= 'attorney/';
                    break;
                case $GLOBALS["SL"]->def->getID('Partner Types', 'Organization'): 
                    $url .= 'org/';
                    break;
            }
            $url .= $this->sessData->dataSets["partners"][0]->part_slug;
            $str = str_replace('[[PartnerUrl]]', $GLOBALS["SL"]->swapURLwrap($url, false), $str);
        }
        return $str;
    }
    
    protected function getLoopItemLabelCustom($loop, $itemRow = null, $itemInd = -3)
    {
        //if ($itemIndex < 0) return '';
        if (!$itemRow) {
            return '';
        }
        if (in_array($loop, ['Victims', 'Witnesses'])) {
            return $this->getCivName($loop, $itemRow, $itemInd);
        } elseif ($loop == 'Civilians') {
            if (isset($itemRow->civ_id)) {
                return $this->getCivilianNameFromID($itemRow->civ_id);
            }
        } elseif (in_array($loop, ['Officers', 'Excellent Officers'])) {
            return $this->getOfficerName($itemRow, $itemInd);
        } elseif ($loop == 'Departments') {
            return $this->getDeptName($itemRow, $itemInd);
        } elseif ($loop == 'Citations') { // why isn't this working?!
            if (isset($itemRow->stop_event_sequence_id) 
                && intVal($itemRow->stop_event_sequence_id) > 0) {
                $eveID = $itemRow->stop_event_sequence_id;
                $EveSeq = $this->getEventSequence($eveID);
                if (sizeof($EveSeq[0]["Civilians"]) == 1) {
                    return $this->getCivilianNameFromID($EveSeq[0]["Civilians"][0]);
                }
                $civList = '';
                foreach ($EveSeq[0]["Civilians"] as $civID) {
                    $civList .= ', ' . $this->getCivilianNameFromID($civID);
                }
                return substr($civList, 1);
            }
        }
        return '';
    }
    
    protected function getDeptName($dept = [], $itemIndex = -3)
    {
    //(($itemIndex > 0) ? '<span class="fPerc66 slGrey">(#'.$itemIndex.')</span>' : '');
        $name = ''; 
        if (isset($dept->dept_name) && trim($dept->dept_name) != '') {
            $name = $dept->dept_name . ' ' . $name;
        }
        return trim($name);
    }
    
    protected function getDeptNameByID($deptID)
    {
        $dept = $this->sessData->getRowById('departments', $deptID);
        if ($dept) {
            return $this->getDeptName($dept);
        }
        return '';
    }
    
    protected function printSetLoopNavRowCustom($nID, $loopItem, $setIndex) 
    {
        if (in_array($nID, [143, 917]) && $loopItem) { 
            // $tbl == 'Departments'
            $tbl = (($this->treeID == 5) ? 'links_compliment_dept' : 'links_complaint_dept');
            $child = $this->sessData->getChildRow($tbl, $loopItem->getKey(), 'departments');
            return view(
                'vendor.openpolice.nodes.143-dept-loop-custom-row', 
                [
                    "loopItem" => $child, 
                    "setIndex" => $setIndex, 
                    "itemID"   => $loopItem->getKey()
                ]
            )->render();
        }
        return '';
    }
    
    protected function getTableRecLabelCustom($tbl, $rec = [], $ind = -3)
    {
        if ($tbl == 'vehicles' && isset($rec->vehic_transportation)) {
            $ret = $GLOBALS["SL"]->def->getValById($rec->vehic_transportation);
            if (isset($rec->vehic_unmarked) && $rec->vehic_unmarked == 'Y') {
                $ret .= ' Unmarked';
            }
            if (isset($rec->vehic_vehicle_make) && trim($rec->vehic_vehicle_make) != '') {
                $ret .= ' ' . $rec->vehic_vehicle_make;
            }
            if (isset($rec->vehic_vehicle_model) && trim($rec->vehic_vehicle_model) != '') {
                $ret .= ' ' . $rec->vehic_vehicle_model;
            }
            return $ret . ' (Vehicle #' . (1+$ind) . ')';
        } elseif ($tbl == 'civilians' && isset($rec->civ_id)) {
            return $this->getCivilianNameFromID($rec->civ_id);
        } elseif ($tbl == 'officers' && isset($rec->off_id)) {
            return $this->getOfficerNameFromID($rec->off_id);
        }
        return '';
    }
    
    protected function getReportUploads($nID)
    {
        $ret = $this->reportUploadsMultNodes(
            $this->cmplntUpNodes, 
            $this->isStaffOrAdmin(), 
            $this->v["isOwner"]
        );
        $cnt = $this->v["uploadPrintMap"]["img"]
            +$this->v["uploadPrintMap"]["vid"]
            +$this->v["uploadPrintMap"]["fil"];
        if ($GLOBALS["SL"]->isPdfView()) {
            return '<h4 class="mT0 slBlueDark">' 
                . (($cnt > 1) ? 'Uploads' : 'Upload') . '</h4>'
                . '<div class="w100">' . $ret . '</div>';
        }
        $GLOBALS["SL"]->pageAJAX .= ' setTimeout(function() { '
            . 'document.getElementById("uploadDelayed").innerHTML="' 
            . $GLOBALS["SL"]->addSlashLines($ret) . '"; }, 1500); ';
        return '<h4 class="mT0 slBlueDark">' 
            . (($cnt > 1) ? 'Uploads' : 'Upload') . '</h4>'
            . '<div id="uploadDelayed" class="w100"><div class="w100 taC">'
            . $GLOBALS["SL"]->sysOpts["spinner-code"] . '</div></div>';
    }
    
    /* Double-Checking [For Now] */
    protected function canShowUpload($upDeets, $isAdmin = false, $isOwner = false)
    {
        if ($isAdmin || $isOwner || $this->isStaffOrAdmin() || $this->v["isOwner"]) {
            return true;
        }
        if (isset($this->sessData->dataSets["complaints"])) {
            $com = $this->sessData->dataSets["complaints"][0];
            if (isset($com->com_status)
                && in_array($com->com_status, $this->getPublishedStatusList())
                && $this->canPrintFullReportByRecordSpecs()
                && $this->isTypeComplaint($com)) {
                if ($upDeets["privacy"] == 'Block') {
                    return false;
                }
                return true;
            }
        }
        return false;
    }
    
    protected function loadUpDeetPrivacy($upRow = NULL)
    {
        if ($this->isStaffOrAdmin() || $this->v["isOwner"]) {
            return 'Public';
        }
        if ($upRow && isset($upRow->up_privacy)) {
            if ($upRow->up_tree_id == 1) {
                if ($upRow->up_privacy == 'Private') {
                    $opts = ['sensitive', 'internal'];
                    if (in_array($GLOBALS["SL"]->pageView, $opts)) {
                        return 'Public';
                    }
                    return 'Block';
                }
                if ($GLOBALS["SL"]->dataPerms == 'public'
                    && !$this->canPrintFullReportByRecordSpecs()) {
                    return 'Block';
                }
                if ($GLOBALS["SL"]->pageView == 'public'
                    && $this->canPrintFullReportByRecordSpecs()) {
                    return 'Public';
                }
            }
            return $upRow->up_privacy;
        }
        return 'Block';
    }
    
}