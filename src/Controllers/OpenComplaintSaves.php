<?php
/**
  * OpenComplaintSaves is a mid-level class which handles custom overrides 
  * for storing survey form field submissions.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.0.12
  */
namespace FlexYourRights\OpenPolice\Controllers;

use DB;
use Auth;
use App\Models\OPStops;
use App\Models\OPForce;
use App\Models\OPInjuries;
use App\Models\OPLinksCivilianEvents;
use App\Models\OPLinksCivilianForce;
use App\Models\OPLinksCivilianVehicles;
use App\Models\OPLinksOfficerVehicles;
use FlexYourRights\OpenPolice\Controllers\OpenComplaintConditions;

class OpenComplaintSaves extends OpenComplaintConditions
{
    /**
     * Override default behavior for submitting survey forms,
     * delegateing specifc saving procedures for custom nodes.
     * This overrides the postNodePublic function in
     * RockHopSoft\Survloop\Controllers\Tree\TreeSurvInput.
     *
     * @param  TreeNodeSurv $curr
     * @return boolean
     */
    protected function postNodePublicCustom(&$curr)
    {
        $extension = $this->extensionPostNodePublic($curr);
        if ($extension !== false) {
            return $extension;
        }
        $nID = $curr->nID;
        if (empty($tmpSubTier)) {
            $tmpSubTier = $this->loadNodeSubTier($nID);
        }
        if ($this->treeID == 1 && isset($this->sessData->dataSets["complaints"])) {
            $this->sessData->dataSets["complaints"][0]->update([
                "updated_at" => date("Y-m-d H:i:s")
            ]);
        }
        // Main Complaint Survey...
        if (in_array($nID, [16, 17, 2262, 2263])) {
            return $this->saveStartTime($curr);
        } elseif ($nID == 2261) {
            return true;
        } elseif (in_array($nID, [145, 920])) {
            return $this->saveNewDept($nID);
        } elseif ($nID == 234) {
            return $this->saveCitationVictim($nID);
        } elseif ($nID == 237) {
            return $this->saveCitationVictims($nID);
        } elseif ($nID == 671) {
            return $this->saveProfanePersons($nID);
        } elseif ($nID == 674) {
            return $this->saveProfanePerson($nID);
        } elseif ($nID == 670) {
            return $this->saveProfanePersons($nID, 'civ');
        } elseif ($nID == 676) {
            return $this->saveProfanePerson($nID, 'civ');
        } elseif (in_array($nID, [743, 2044])) {
            return $this->saveForceTypes($nID);
        } elseif ($nID == 2824) {
            return $this->saveForceTypeLnkOneVictim($nID);
        } elseif ($nID == 388) {
          return $this->saveArrestChargesDropped($nID);
        } elseif ($nID == 976) {
            return $this->saveStatusCompletion($nID);
            
        // Department Editor Survey ...
        } elseif ($nID == 2232) {
            $date = date('Y-m-d H:i:s');
            $this->sessData->currSessData($curr, 'update', $date);
            return true;
        } elseif ($nID == 1285) {
            return $this->saveDeptSubWays1($nID);
        } elseif ($nID == 1287) {
            return $this->saveDeptSubWays2($nID);
        } elseif ($nID == 1329) {
            return $this->saveEditLog($nID);
            
        // Page Nodes ...
        } elseif ($nID == 1007) {
            return $this->postContactEmail($nID);
        }
        return false; // false to continue standard post processing
    }

    /**
     * Override default behavior for submitting survey forms,
     * delegateing specifc saving procedures for custom nodes.
     * e.g. flexyourrights/openpolice-extension
     *
     * @param  TreeNodeSurv $curr
     * @return boolean
     */
    protected function extensionPostNodePublic(&$curr)
    {
        return false; // false to continue standard post processing
    }
    
    /**
     * Store the start date — and optionally start time — of the incident.
     *
     * @param  int $nID
     * @param  string $tbl
     * @param  string $fld
     * @return boolean
     */
    protected function saveStartTime($curr)
    {
        $dateNode = ((in_array($curr->nID, [16, 17])) ? 15 : 2261);
        $date = $this->getRawFormDate($dateNode);
        if (trim($date) == '') {
            $date = '0000-00-00';
        }
        $date = date("Y-m-d", strtotime($date));
        if (substr($date, 0, 5) == '-0001') {
            $date = '0000-00-00';
        }
        $time = $this->postFormTimeStr($curr->nID);
        if ($time === null || strlen($time) != 8) {
            $date .= ' 00:00:00';
        } else {
            $date .= ' ' . $time;
        }
        if (strpos($date, '0000-') === false && strpos($date, '-00') === false) {
            $this->sessData->currSessData($curr, 'update', $date);
        }
        return true;
    }
    
    /**
     * If there is only one civilian involved, store whether or not
     * they were given any citations.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveCitationVictim($nID)
    {
        if (isset($this->sessData->dataSets["civilians"]) 
            && sizeof($this->sessData->dataSets["civilians"]) == 1) {
            if ($GLOBALS["SL"]->REQ->has('n234fld') 
                && trim($GLOBALS["SL"]->REQ->get('n234fld')) == 'Y') {
                $this->sessData->dataSets["civilians"][0]->update([
                    'civ_given_citation' => 'Y'
                ]);
            } else {
                $this->sessData->dataSets["civilians"][0]->update([
                    'civ_given_citation' => 'N'
                ]);
            }
        }
        return false;
    }
    
    /**
     * Since there are more than one civilian involved, store 
     * which of them were given any citations.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveCitationVictims($nID)
    {
        $nodeFld = 'n' . $nID . 'fld';
        $isEmpty = (!$GLOBALS["SL"]->REQ->has($nodeFld) 
            || !is_array($GLOBALS["SL"]->REQ->get($nodeFld)) 
            || sizeof($GLOBALS["SL"]->REQ->get($nodeFld)) == 0);
        $civs = $this->sessData->getLoopRows('Victims');
        if ($civs && sizeof($civs) > 0) {
            foreach ($civs as $i => $civ) {
                $reqArr = $GLOBALS["SL"]->REQ->get($nodeFld);
                if ($isEmpty || !in_array($civ->civ_id, $reqArr)) {
                    $civ->update([ 'civ_given_citation' => 'N' ]);
                } else {
                    $civ->update([ 'civ_given_citation' => 'Y' ]);
                }
            }
        }
        return true;
    }

    /**
     * If there is only one of a type of person involved, 
     * store whether or not they were given any citations.
     *
     * @param  int $nID
     * @param  string $type
     * @return boolean
     */
    protected function saveProfanePerson($nID, $type = 'off')
    {
        $tbl = (($type == 'off') ? 'officers' : 'civilians');
        $nodeFld = 'n' . $nID . 'fld';
        $profFld = $type . '_used_profanity';
        if ($GLOBALS["SL"]->REQ->has($nodeFld)) {
            $this->sessData->dataSets[$tbl][0]->{ $profFld } = trim($GLOBALS["SL"]->REQ->get($nodeFld));
        } else {
            $this->sessData->dataSets[$tbl][0]->{ $profFld } = '';
        }
        $this->sessData->dataSets[$tbl][0]->save();
        return true;
    }
    
    /**
     * Since there are more than one of a type of person 
     * civilian involved, store which of them used profanity.
     *
     * @param  int $nID
     * @param  string $type
     * @return boolean
     */
    protected function saveProfanePersons($nID, $type = 'off')
    {
        $tbl = (($type == 'off') ? 'officers' : 'civilians');
        $nodeFld = 'n' . $nID . 'fld';
        $profFld = $type . '_used_profanity';
        foreach ($this->sessData->dataSets[$tbl] as $i => $off) {
            if ($GLOBALS["SL"]->REQ->has($nodeFld) 
                && in_array($off->getKey(), $GLOBALS["SL"]->REQ->get($nodeFld))) {
                $this->sessData->dataSets[$tbl][$i]->{ $profFld } = 'Y';
            } else {
                $this->sessData->dataSets[$tbl][$i]->{ $profFld } = '';
            }
            $this->sessData->dataSets[$tbl][$i]->save();
        }
        return true;
    }
    
    /**
     * Store the different types of force used against which civilians.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveForceTypes($nID)
    {
        $GLOBALS["SL"]->def->loadDefs('Force Type');
        $nodeFld = 'n' . $nID . 'fld';
        $forceTypes = $GLOBALS["SL"]->def->defValues["Force Type"];
        $newTypes = $forceRecs = [];
        if (isset($this->sessData->dataSets["force"])) {
            $forceRecs = $this->sessData->dataSets["force"];
        }
        $animType = 'N';
        $animDesc = '';
        if ($nID == 743) {
            $animType = 'Y';
            $nodeFld = 'n744fld';
            if ($GLOBALS["SL"]->REQ->has('n746fld')) {
                $animDesc = trim($GLOBALS["SL"]->REQ->n746fld);
            }
        }
        if ($GLOBALS["SL"]->REQ->has($nodeFld) && is_array($newTypes)) {
            $newTypes = $GLOBALS["SL"]->REQ->get($nodeFld);
        }
        foreach ($forceTypes as $typ) {
            if (in_array($typ->def_id, $newTypes)) {
                $found = false;
                if (sizeof($forceRecs) > 0) {
                    foreach ($forceRecs as $frc) {
                        if ($frc->for_against_animal == $animType
                            && $frc->for_type == $typ->def_id) {
                            $found = true;
                        }
                    }
                }
                if (!$found) {
                    $frc = new OPForce;
                    $frc->for_com_id         = $this->coreID;
                    $frc->for_type           = $typ->def_id;
                    $frc->for_against_animal = $animType;
                    $frc->for_animal_desc    = $animDesc;
                    $frc->save();
                }
            } else {
                if (sizeof($forceRecs) > 0) {
                    foreach ($forceRecs as $frc) {
                        if ($frc->for_against_animal == $animType
                            && $frc->for_type == $typ->def_id) {
                            $frc->delete();
                        }
                    }
                }
            }
        }
        // This delete may just be needed for older complaints:
        OPForce::where('for_com_id', $this->coreID)
            ->whereNull('for_type')
            ->delete();
        if ($nID == 743) {
            $this->sessData->refreshDataSets();
            return false;
        }
        return true;
    }
    
    /**
     * In this case there is only one victim, but here we'll
     * make a linkage between them and this type of force
     * for a more consistent algorithm for finding matches.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveForceTypeLnkOneVictim($nID)
    {

        $force = $this->sessData->getDataBranchRow('force');
        $civs = $this->sessData->getLoopRows('Victims');
        if ($civs && sizeof($civs) == 1 && isset($force->for_id)) {
            if (!isset($this->sessData->dataSets["links_civilian_force"])) {
                $this->sessData->dataSets["links_civilian_force"] = [];
            }
            $found = false;
            if (sizeof($this->sessData->dataSets["links_civilian_force"]) > 0) {
                foreach ($this->sessData->dataSets["links_civilian_force"] as $lnk) {
                    if ($lnk->lnk_civ_frc_force_id == $force->for_id
                        && $lnk->lnk_civ_frc_civ_id == $civs[0]->civ_id) {
                        $found = true;
                    }
                }
            }
            if (!$found) {
                $lnk = new OPLinksCivilianForce;
                $lnk->lnk_civ_frc_force_id = $force->for_id;
                $lnk->lnk_civ_frc_civ_id = $civs[0]->civ_id;
                $lnk->save();
                $this->sessData->dataSets["links_civilian_force"][] = $lnk;
            }
        }
        return true;
    }
    
    /**
     * Check discreprency with previous answer.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveArrestChargesDropped($nID)
    {
        if ((!isset($this->sessData->dataSets["complaints"][0]->com_all_charges_resolved)
                || $this->sessData->dataSets["complaints"][0]->com_all_charges_resolved != 'Y')
            && $GLOBALS["SL"]->REQ->has('n388fld')
            && trim($GLOBALS["SL"]->REQ->n388fld) == 'Y') {
            $this->sessData->dataSets["complaints"][0]->com_all_charges_resolved = 'Y';
            $this->sessData->dataSets["complaints"][0]->save();
        }
        return false;
    }
    
    /**
     * Update the complaint status to 'New', AKA complete.
     *
     * @param  int $nID
     * @return boolean
     */
    protected function saveStatusCompletion($nID)
    {
        if ($GLOBALS["SL"]->REQ->get('step') != 'next') {
            return true;
        }
        $newDef = $GLOBALS["SL"]->def->getID('Complaint Status', 'New');
        $this->sessData->dataSets["complaints"][0]->com_status = $newDef;
        $this->sessData->dataSets["complaints"][0]->save();
        return false;
    }
    
}