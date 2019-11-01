<?php
/**
  * OpenPoliceAllegations is a mid-level class for functions handling
  * records listing, sorting, and describing allegations of misconduct.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.2.5
  */
namespace OpenPolice\Controllers;

use OpenPolice\Controllers\OpenPolicePeople;

class OpenPoliceAllegations extends OpenPolicePeople
{
    public function simpleAllegationList()
    {
        if (sizeof($this->allegations) == 0 
            && isset($this->sessData->dataSets["AllegSilver"]) 
            && isset($this->sessData->dataSets["AllegSilver"][0])) {
            $allegSilv = $this->sessData->dataSets["AllegSilver"][0];
            foreach ($this->worstAllegations as $i => $alleg) {
                $allegInfo = [$alleg[1], '', -3, [], []]; // Alleg Name, Alleg Why, Alleg ID, Civs, Offs
                switch ($alleg[1]) {
                    case 'Sexual Assault':
                    case 'Unreasonable Force':
                    case 'Wrongful Arrest':
                    case 'Wrongful Property Seizure':
                    case 'Wrongful Search':
                    case 'Wrongful Detention':
                    case 'Bias-Based Policing':
                    case 'Repeat Harassment':
                    case 'Excessive Arrest Charges':
                    case 'Conduct Unbecoming an Officer':
                    case 'Discourtesy':
                    case 'Neglect of Duty':
                    case 'Policy or Procedure Violation':
                    case 'Excessive Citation':
                        $allegInfo[1] .= $this->chkSilvAlleg($alleg[2], $alleg[1], $alleg[0]);
                        break;
                    case 'Intimidating Display of Weapon':
                        if ($this->checkAllegIntimidWeaponSilver($allegSilv)) {
                            $allegInfo[1] .= ', ' . $this->getAllegDesc($allegName, $allegID);
                        }
                    case 'Wrongful Entry':
                        if (isset($this->sessData->dataSets["Stops"]) 
                            && sizeof($this->sessData->dataSets["Stops"]) > 0) {
                            foreach ($this->sessData->dataSets["Stops"] as $stop) {
                                if (isset($stop->StopAllegWrongfulEntry) 
                                    && $stop->StopAllegWrongfulEntry == 'Y') {
                                    $allegRec = $this->getAllegGoldRec($alleg[1], $alleg[0]);
                                    $allegInfo[1] .= ', ' 
                                        . $this->getAllegDesc($alleg[1], $alleg[0], $allegRec);
                                }
                            }
                        }
                        break;
                    case 'Miranda Rights':
                        if (isset($allegSilv->AlleSilArrestMiranda)
                            && $allegSilv->AlleSilArrestMiranda == 'Y') {
                            $allegInfo[1] .= ' ';
                        }
                        break;
                    case 'Officer Refused To Provide ID':
                        if (isset($allegSilv->AlleSilOfficerRefuseID)
                            && $allegSilv->AlleSilOfficerRefuseID == 'Y') {
                            $allegInfo[1] .= ' ';
                        }
                        break;
                }
                if ($allegInfo[1] != '') {
                    $this->allegations[] = $allegInfo;
                }
            }
        }
        return $this->allegations;
    }
    
    public function commaAllegationList($ulist = false)
    {
        $ret = '';
        $this->simpleAllegationList();
        if (sizeof($this->allegations) > 0) {
            foreach ($this->allegations as $i => $alleg) {
                if ($ulist) {
                    $ret .= '<li>' . $alleg[0] . '</li>';
                } else {
                    $ret .= (($i > 0) ? ', ' : '') . $alleg[0];
                }
            }
        }
        return $ret;
    }
    
    public function commaAllegationListSplit()
    {
        $allegs = ['', ''];
        $this->simpleAllegationList();
        if (sizeof($this->allegations) > 0) { // special printing...
            foreach ($this->allegations as $i => $alleg) {
                if ($i > 0) {
                    $allegs[1] .= (($i > 1) ? ', ' : '') . $alleg[0];
                } else {
                    $allegs[0] .= $alleg[0];
                }
            }
        }
        return $allegs;
    }
    
    public function commaTopThreeAllegationList()
    {
        $ret = '';
        $this->simpleAllegationList();
        if (sizeof($this->allegations) > 0) {
            if (sizeof($this->allegations) == 1) {
                return $this->allegations[0][0];
            }
            if (sizeof($this->allegations) == 2) {
                return $this->allegations[0][0] . ' and ' . $this->allegations[1][0];
            }
            return $this->allegations[0][0] . ', ' . $this->allegations[1][0] 
                . ', and ' . $this->allegations[2][0];
        }
        return $ret;
    }
    
    protected function commaComplimentList()
    {
        $types = [];
        if (isset($this->sessData->dataSets["OffCompliments"]) 
            && sizeof($this->sessData->dataSets["OffCompliments"]) > 0) {
            foreach ($this->sessData->dataSets["OffCompliments"] as $off) {
                if (isset($off->OffCompValor) 
                    && trim($off->OffCompValor) == 'Y') {
                    $types[] = 'Valor';
                }
                if (isset($off->OffCompLifesaving) 
                    && trim($off->OffCompLifesaving) == 'Y') {
                    $types[] = 'Lifesaving';
                }
                if (isset($off->OffCompDeescalation) 
                    && trim($off->OffCompDeescalation) == 'Y') {
                    $types[] = 'De-escalation';
                }
                if (isset($off->OffCompProfessionalism) 
                    && trim($off->OffCompProfessionalism) == 'Y') {
                    $types[] = 'Professionalism';
                }
                if (isset($off->OffCompFairness) 
                    && trim($off->OffCompFairness) == 'Y') {
                    $types[] = 'Fairness';
                }
                if (isset($off->OffCompConstitutional) 
                    && trim($off->OffCompConstitutional) == 'Y') {
                    $types[] = 'Constitutional';
                }
                if (isset($off->OffCompCompassion) 
                    && trim($off->OffCompCompassion) == 'Y') {
                    $types[] = 'Compassion';
                }
                if (isset($off->OffCompCommunity) 
                    && trim($off->OffCompCommunity) == 'Y') {
                    $types[] = 'Community';
                }
            }
        }
        $ret = '';
        if (in_array('Valor', $types)) {
            $ret .= ', Valor';
        }
        if (in_array('Lifesaving', $types)) {
            $ret .= ', Lifesaving';
        }
        if (in_array('De-escalation', $types)) {
            $ret .= ', De-escalation';
        }
        if (in_array('Professionalism', $types)) {
            $ret .= ', Professionalism';
        }
        if (in_array('Fairness', $types)) {
            $ret .= ', Fairness';
        }
        if (in_array('Constitutional', $types)) {
            $ret .= ', Constitutional Policing';
        }
        if (in_array('Compassion', $types)) {
            $ret .= ', Compassion';
        }
        if (in_array('Community', $types)) {
            $ret .= ', Community Service';
        }
        if (trim($ret) != '') {
            $ret = trim(substr($ret, 1));
        }
        return $ret;
    }
    
    protected function intimidWeaponNos()
    {
        return [
            $GLOBALS["SL"]->def->getID('Intimidating Displays Of Weapon', 'No'),
            $GLOBALS["SL"]->def->getID('Intimidating Displays Of Weapon', 'Not sure')
        ];
    }
    
    protected function checkAllegIntimidWeaponSilver($allegSilv)
    {
        if (isset($allegSilv->AlleSilIntimidatingWeapon) 
            && in_array(intVal($allegSilv->AlleSilIntimidatingWeapon), $this->intimidWeaponNos())) {

        }

    }
    
    protected function checkAllegIntimidWeapon($alleg)
    {
        $def = $GLOBALS["SL"]->def->getID('Allegation Type', 'Intimidating Display of Weapon');
        return ($alleg->AlleType != $def 
            || !in_array($alleg->AlleIntimidatingWeapon, $this->intimidWeaponNos()));
    }
    
    protected function getAllegID($allegName)
    {
        if (sizeof($this->worstAllegations) > 0) {
            foreach ($this->worstAllegations as $a) {
                if ($a[1] == $allegName) {
                    return $a[0];
                }
            }
        }
        return -3;
    }
    
    protected function getAllegFldName($allegID)
    {
        if ($allegID > 0 && sizeof($this->worstAllegations) > 0) {
            foreach ($this->worstAllegations as $a) {
                if ($a[0] == $allegID) {
                    return $a[2];
                }
            }
        }
        return '';
    }
    
    protected function getAllegSilvRec($allegName, $allegID = -3)
    {
        if ($allegID <= 0) {
            $allegID = $this->getAllegID($allegName);
        }
        if (isset($this->sessData->dataSets["Allegations"]) 
            && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
            foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                if ($alleg->AlleType == $allegID 
                    && (!isset($alleg->AlleEventSequenceID) 
                        || intVal($alleg->AlleEventSequenceID) <= 0)) {
                    return $alleg;
                }
            }
        }
        return [];
    }
    
    protected function getAllegGoldRec($allegName, $allegID = -3)
    {
        if ($allegID <= 0) {
            $allegID = $this->getAllegID($allegName);
        }
        if (isset($this->sessData->dataSets["Allegations"]) 
            && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
            foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                if ($alleg->AlleType == $allegID 
                    && isset($alleg->AlleEventSequenceID) 
                    && intVal($alleg->AlleEventSequenceID) > 0) {
                    return $alleg;
                }
            }
        }
        return [];
    }
    
    protected function getAllegDesc($allegName, $allegID = -3, $allegRec = [])
    {
        if (!$allegRec || !isset($allegRec->AlleDescription)) {
            $allegRec = $this->getAllegSilvRec($allegName, $allegID);
        }
        if ($allegRec && isset($allegRec->AlleDescription)) {
            return trim($allegRec->AlleDescription);
        }
        return '';
    }
    
    protected function chkSilvAlleg($fldName, $allegName, $allegID = -3)
    {
        if (isset($this->sessData->dataSets["AllegSilver"][0]->{ $fldName })
            && trim($this->sessData->dataSets["AllegSilver"][0]->{ $fldName }) == 'Y') {
            return ', ' . $this->getAllegDesc($allegName, $allegID);
        }
        return '';
    }    
    
    
    protected function basicAllegationList($showWhy = false, $isAnon = false)
    {
        $ret = '';
        if (isset($this->sessData->dataSets["Allegations"]) 
            && sizeof($this->sessData->dataSets["Allegations"]) > 0) {
            $printedOfficers = false;
            $allegOffs = [];
            // if there's only one Officer on the Complaint, then it is associated with all Allegations
            if (!$isAnon && isset($this->sessData->dataSets["Officers"]) 
                && sizeof($this->sessData->dataSets["Officers"]) == 1) {
                /*
                $ret .= '<div class="pL5 pB10">Officer '
                    . $this->getOfficerNameFromID($this->sessData->dataSets["Officers"][0]->OffID) . '</div>';
                */
                $printedOfficers = true;
            } else { // Load Officer names for each Allegation
                foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                    if ($this->checkAllegIntimidWeapon($alleg)) {
                        $allegOffs[$alleg->AlleID] = '';
                        $offs = $this->getLinkedToEvent('Officer', $alleg->AlleID);
                        if (sizeof($offs) > 0) {
                            foreach ($offs as $off) {
                                $allegOffs[$alleg->AlleID] .= ', '
                                    . $this->getOfficerNameFromID($off);
                            }
                        }
                        if (trim($allegOffs[$alleg->AlleID]) != '') {
                            $allegOffs[$alleg->AlleID] = substr($allegOffs[$alleg->AlleID], 1); 
                            // 'Officer'.((sizeof($offs) > 1) ? 's' : '').
                        }
                    }
                }
                // now let's check if all allegations are against the same officers, so we only print them once
                $allOfficersSame = true; $prevAllegOff = '*START*';
                foreach ($allegOffs as $allegOff) {
                    if ($prevAllegOff == '*START*') {
                    
                    } elseif ($prevAllegOff != $allegOff) {
                        $allOfficersSame = false;
                    }
                    $prevAllegOff = $allegOff;
                }
                if (!$isAnon && $allOfficersSame) { // all the same, so print once at the top
                    $ret .= '<div class="pL5 pB10 fPerc125">' 
                        . $allegOffs[$this->sessData->dataSets["Allegations"][0]->AlleID] 
                        . '</div>';
                    $printedOfficers = true;
                }
            }
            foreach ($this->worstAllegations as $allegType) { // printing Allegations in order of severity...
                foreach ($this->sessData->dataSets["Allegations"] as $alleg) {
                    if ($alleg->AlleType 
                        == $GLOBALS["SL"]->def->getID('Allegation Type', $allegType)) {
                        if ($this->checkAllegIntimidWeapon($alleg)) {
                            $ret .= '<div class="fPerc125">' . $allegType;
                            if (!$isAnon && !$printedOfficers && isset($allegOffs[$alleg->AlleID])) {
                                $ret .= ' <span class="mL20 slGrey">' 
                                    . $allegOffs[$alleg->AlleID] . '</span>';
                            }
                            $ret .= '</div>' . (($showWhy) ? '<div class="slGrey mTn10 pL20">' 
                                . $alleg->AlleDescription . '</div>' : '') 
                                . '<div class="p5"></div>';
                        }
                    }
                }
            }
        } else {
            $ret = '<i>No allegations found.</i>';
        }
        return $ret;
    }
    

}