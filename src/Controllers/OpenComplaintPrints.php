<?php
/**
  * OpenComplaintPrints is a mid-level class which handles custom 
  * printing of data, especially functions which override SurvLoop defaults.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
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
        if (!isset($this->sessData->dataSets["AllegSilver"])) {
            $allegSilv = new OPAllegSilver;
            $allegSilv->AlleSilComplaintID = $this->coreID;
            $allegSilv->save();
            $this->sessData->dataSets["AllegSilver"] = [
                $allegSilv
            ];
        }
        foreach ($this->worstAllegations as $alle) {
            $found = false;
            if (isset($this->sessData->dataSets["Allegations"])) {
                $all = $this->sessData->dataSets["Allegations"];
                if (sizeof($all) > 0) {
                    foreach ($all as $i => $alleRow) {
                        if ($alle[0] == $alleRow->AlleType) {
                            $found = true;
                        }
                    }
                }
            }
            if (!$found) {
                $new = new OPAllegations;
                $new->AlleComplaintID = $this->coreID;
                $new->AlleType = $alle[0];
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
        $defNew = $GLOBALS["SL"]->def
            ->getID('Complaint Status', 'New');
        if ($nID == 270) {
            $this->sessData->currSessData(
                $nID, 
                'Complaints', 
                'ComStatus', 
                'update', 
                $defNew
            );
            $this->sessData->currSessData(
                $nID, 
                'Complaints', 
                'ComRecordSubmitted', 
                'update', 
                date("Y-m-d H:i:s")
            );
            $this->sessData->currSessData(
                $nID, 
                'Complaints', 
                'ComAllegList', 
                'update', 
                $this->commaAllegationList()
            );
            $this->sessData->dataSets["Complaints"][0]->update([
                'ComPublicID' => $GLOBALS["SL"]->genNewCorePubID() 
            ]);
            $url = '/complaint/read-' . $this->sessData
                ->dataSets["Complaints"][0]->ComPublicID;
        } else {
            $this->sessData->currSessData(
                $nID, 
                'Compliments', 
                'CompliStatus', 
                'update', 
                $defNew
            );
            $this->sessData->currSessData(
                $nID, 
                'Compliments', 
                'CompliRecordSubmitted', 
                'update', 
                date("Y-m-d H:i:s")
            );
            //$this->sessData->currSessData($nID, 'Compliments', 'CompliAllegList', 'update', 
            //    $this->commaAllegationList());
            $this->sessData->dataSets["Compliments"][0]->update([ 
                'CompliPublicID' => $GLOBALS["SL"]->genNewCorePubID()
            ]);
            $url = '/compliment/read-' . $this->sessData
                ->dataSets["Compliments"][0]->ComPublicID;
        }
        $spin = $GLOBALS["SL"]->sysOpts["spinner-code"];
        $this->restartSess($GLOBALS["SL"]->REQ);
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
    
    protected function customLabels($nIDtxt = '', $str = '')
    {
        if (in_array($this->treeID, [1, 5])) {
            $event = [];
            if (sizeof($this->sessData->dataBranches) > 1 
                && $this->sessData->dataBranches[1]["branch"] 
                    == 'EventSequence') {
                $event = $this->getEventSequence(
                    $this->sessData->dataBranches[1]["itemID"]
                );
            }
            if (isset($event[0]) && isset($event[0]["EveID"])) {
                if (strpos($str, '[LoopItemLabel]') !== false) {
                    $civName = $this->isEventAnimalForce(
                        $event[0]["EveID"], 
                        $event[0]["Event"]
                    );
                    if (trim($civName) == '' 
                        && isset($event[0]["Civilians"]) 
                        && sizeof($event[0]["Civilians"]) > 0) {
                        $civName = $this->getCivilianNameFromID(
                            $event[0]["Civilians"][0]
                        );
                    }
                    $name = '<span class="slBlueDark"><b>' 
                        . $civName . '</b></span>';
                    $str = str_replace(
                        '[LoopItemLabel]', 
                        $name, 
                        $str
                    );
                }
                if (strpos($str, '[ForceType]') !== false) {
                    $forceDesc = $GLOBALS["SL"]->def->getVal(
                        'Force Type', 
                        $event[0]["Event"]->ForType
                    );
                    if ($forceDesc == 'Other') {
                        $forceDesc = $event[0]["Event"]->ForTypeOther;
                    }
                    if (strpos($nIDtxt, '343') !== false) {
                        $eveSeq = $this->sessData
                            ->getDataBranchRow('EventSequence');
                        if ($eveSeq && isset($eveSeq->EveID)) {
                            $forceDesc .= ' used on ' 
                                . $this->getCivNamesFromEvent(
                                    $eveSeq->EveID);
                        }
                    }
                    $str = str_replace('[ForceType]', 
                        '<span class="slBlueDark"><b>' 
                            . $forceDesc .'</b></span>',
                        $str);
                }
            } elseif (strpos($str, '[LoopItemLabel]') !== false) {
                $row = $this->sessData->getLatestDataBranchRow();
                if (isset($row->CivID)) {
                    $str = str_replace(
                        '[LoopItemLabel]', 
                        $this->getCivName('Victims', $row), 
                        $str
                    );
                } elseif (isset($row->InjCareSubjectID)) {
                    $civ = $this->sessData->getRowById(
                        'Civilians', 
                        $row->InjCareSubjectID
                    );
                    $str = str_replace(
                        '[LoopItemLabel]', 
                        $this->getCivName('Victims', $civ), 
                        $str
                    );
                } elseif (isset($row->OffID)) {
                    $str = str_replace(
                        '[LoopItemLabel]', 
                        $this->getOfficerNameFromID($row->OffID), 
                        $str
                    );
                }
            }
            if (strpos($str, '[[List of Allegations]]') !== false) {
                $str = str_replace(
                    '[[List of Allegations]]', 
                    $this->commaAllegationList(), 
                    $str
                );
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
            if (isset($this->sessData->dataSets["Civilians"])) {
                $complainantVic = (isset($this->sessData->dataSets["Civilians"][0])
                    && $this->sessData->dataSets["Civilians"][0]->CivRole == 'Victim');
                $multipleVic = (sizeof($this->sessData->getLoopRows('Victims')) > 1);
                $nodes = ['209', '212', '852', '248', '222', '227', '234', '243'];
                if (in_array($nIDtxt, $nodes)) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody', 'you', $str);
                    } elseif ($complainantVic && $multipleVic) {
                        $str = str_replace('anybody', 'you or anybody else', $str);
                    }
                } elseif (in_array($nIDtxt, ['204'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody', 'you', $str);
                    } elseif ($complainantVic && $multipleVic) {
                        $str = str_replace('anybody', 'you (or anybody else)', $str);
                    }
                } elseif (in_array($nIDtxt, ['205', '213'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody was', 'you were', $str);
                    } elseif ($complainantVic && $multipleVic) {
                        $str = str_replace('anybody was', 'anybody was', $str);
                    }
                } elseif (in_array($nIDtxt, ['591'])) {
                    if ($complainantVic && !$multipleVic) {
                        $str = str_replace('anybody', 'you', $str);
                    }
                } elseif (in_array($nIDtxt, ['228'])) {
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
            && isset($this->sessData->dataSets["Partners"])
            && isset($this->sessData->dataSets["Partners"][0]->PartSlug)) {
            $url = $GLOBALS["SL"]->sysOpts["app-url"] . '/';
            switch ($this->sessData->dataSets["Partners"][0]->PartType) {
                case $GLOBALS["SL"]->def->getID('Partner Types', 'Attorney'): 
                    $url .= 'attorney/';
                    break;
                case $GLOBALS["SL"]->def->getID('Partner Types', 'Organization'): 
                    $url .= 'org/';
                    break;
            }
            $url .= $this->sessData->dataSets["Partners"][0]->PartSlug;
            $str = str_replace(
                '[[PartnerUrl]]', 
                $GLOBALS["SL"]->swapURLwrap($url, false), 
                $str
            );
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
            if (isset($itemRow->CivID)) {
                return $this->getCivilianNameFromID($itemRow->CivID);
            }
        } elseif (in_array($loop, ['Officers', 'Excellent Officers'])) {
            return $this->getOfficerName($itemRow, $itemInd);
        } elseif ($loop == 'Departments') {
            return $this->getDeptName($itemRow, $itemInd);
        } elseif ($loop == 'Events') {
            if (isset($itemRow->EveID)) {
                return $this->getEventLabel($itemRow->EveID);
            }
        } elseif ($loop == 'Citations') { // why isn't this working?!
            if (isset($itemRow->StopEventSequenceID) 
                && intVal($itemRow->StopEventSequenceID) > 0) {
                $eveID = $itemRow->StopEventSequenceID;
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
        if (isset($dept->DeptName) && trim($dept->DeptName) != '') {
            $name = $dept->DeptName . ' ' . $name;
        }
        return trim($name);
    }
    
    protected function getDeptNameByID($deptID)
    {
        $dept = $this->sessData->getRowById('Departments', $deptID);
        if ($dept) {
            return $this->getDeptName($dept);
        }
        return '';
    }
    
    protected function printSetLoopNavRowCustom($nID, $loopItem, $setIndex) 
    {
        if (in_array($nID, [143, 917]) && $loopItem) { 
            // $tbl == 'Departments'
            $tbl = (($this->treeID == 5) ? 'LinksComplimentDept' 
                : 'LinksComplaintDept');
            $child = $this->sessData->getChildRow(
                $tbl, 
                $loopItem->getKey(), 
                'Departments'
            );
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
        if ($tbl == 'Vehicles' 
            && isset($rec->VehicTransportation)) {
            return $GLOBALS["SL"]->def
                    ->getValById($rec->VehicTransportation)
                . ((isset($rec->VehicUnmarked) 
                    && $rec->VehicUnmarked == 'Y') 
                    ? ' Unmarked' : '')
                . ((isset($rec->VehicVehicleMake) 
                    && trim($rec->VehicVehicleMake) != '') 
                    ? ' ' . $rec->VehicVehicleMake : '')
                . ((isset($rec->VehicVehicleModel) 
                    && trim($rec->VehicVehicleModel) != '') 
                    ? ' ' . $rec->VehicVehicleModel : '') 
                . ' (Vehicle #' . (1+$ind) . ')';
        } elseif ($tbl == 'Civilians' && isset($rec->CivID)) {
            return $this->getCivilianNameFromID($rec->CivID);
        } elseif ($tbl == 'Officers' && isset($rec->OffID)) {
            return $this->getOfficerNameFromID($rec->OffID);
        }
        return '';
    }
    
    protected function getReportUploads($nID)
    {
        $ret = $this->reportUploadsMultNodes(
            $this->cmplntUpNodes, 
            $this->v["isAdmin"], 
            $this->v["isOwner"]
        );
        $cnt = $this->v["uploadPrintMap"]["img"]
            +$this->v["uploadPrintMap"]["vid"]
            +$this->v["uploadPrintMap"]["fil"];
        return '<h3 class="mT0 slBlueDark">' 
            . (($cnt > 1) ? 'Uploads' : 'Upload') 
            . '</h3>' . $ret;
    }
    
    /* Double-Checking [For Now] */
    protected function canShowUpload($nID, $upDeets, $isAdmin = false, $isOwner = false)
    {
        if ($isAdmin || $isOwner) {
            return true;
        }
        if (isset($this->sessData->dataSets["Complaints"])) {
            $com = $this->sessData->dataSets["Complaints"][0];
            if (isset($com->ComStatus)) {
                if (in_array($com->ComStatus, 
                    $this->getPublishedStatusList())
                    && $com->ComPrivacy == $GLOBALS["SL"]->def
                        ->getID('Privacy Types', 'Submit Publicly')) {
                    if ($upDeets["privacy"] == 'Block') {
                        return false;
                    }
                    return true;
                }
            }
        }
        return false;
    }
    
    protected function loadUpDeetPrivacy($upRow = NULL)
    {
        if ($upRow && isset($upRow->UpPrivacy)) {
            if ($upRow->UpTreeID == 1) {
                if ($upRow->UpPrivacy == 'Private') {
                    $opts = ['sensitive', 'internal'];
                    if (in_array($GLOBALS["SL"]->pageView, $opts)) {
                        return 'Public';
                    }
                    return 'Block';
                }
                if ($GLOBALS["SL"]->dataPerms == 'public') {
                    return 'Block';
                }
                if ($GLOBALS["SL"]->pageView == 'public') {
                    return 'Public';
                }
            }
            return $upRow->UpPrivacy;
        }
        return 'Block';
    }
    
}