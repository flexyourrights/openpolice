<?php
/**
  * OpenPoliceAllegations is a mid-level class for functions handling
  * records listing, sorting, and describing allegations of misconduct.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.2.5
  */
namespace FlexYourRights\OpenPolice\Controllers;

use FlexYourRights\OpenPolice\Controllers\OpenPolicePeople;

class OpenPoliceAllegations extends OpenPolicePeople
{
    /**
     * Get an array full of of AllegInfo objects which apply to this complaint.
     *
     * @return array
     */
    public function simpleAllegationList()
    {
        if (sizeof($this->allegations) == 0
            && isset($this->sessData->dataSets["alleg_silver"])
            && isset($this->sessData->dataSets["alleg_silver"][0])) {
            $allegSilv = $this->sessData->dataSets["alleg_silver"][0];
            $allegs = $stops = [];
            if (isset($this->sessData->dataSets["allegations"])) {
                $allegs = $this->sessData->dataSets["allegations"];
            }
            if (isset($this->sessData->dataSets["stops"])) {
                $stops = $this->sessData->dataSets["stops"];
            }
            $this->allegations = $GLOBALS["CUST"]->getAllegations($allegSilv, $allegs, $stops);
        }
        return $this->allegations;
    }

    /**
     * Get written list of allegations which apply to this complaint,
     * either comma separated, or as part of an HTML list.
     *
     * @param  boolean  $ulist
     * @return string
     */
    public function commaAllegationList($ulist = false)
    {
        $ret = '';
        $this->simpleAllegationList();
        if (sizeof($this->allegations) > 0) {
            foreach ($this->allegations as $i => $alleg) {
                if ($ulist) {
                    $ret .= '<li>' . $alleg->name . '</li>';
                } else {
                    $ret .= (($i > 0) ? ', ' : '') . $alleg->name;
                }
            }
        }
        return $ret;
    }

    /**
     * Get an array of two strings. The first lists the first allegation.
     * The second lists the rest.
     *
     * @return array
     */
    public function commaAllegationListSplit()
    {
        $allegs = ['', ''];
        $this->simpleAllegationList();
        if (sizeof($this->allegations) > 0) { // special printing...
            foreach ($this->allegations as $i => $alleg) {
                if ($i > 0) {
                    $allegs[1] .= (($i > 1) ? ', ' : '') . $alleg->name;
                } else {
                    $allegs[0] .= $alleg->name;
                }
            }
        }
        return $allegs;
    }

    /**
     * Get a written list of the worst three allegations tied to this complaint.
     *
     * @return string
     */
    public function commaTopThreeAllegationList()
    {
        $ret = '';
        $this->simpleAllegationList();
        if (sizeof($this->allegations) > 0) {
            if (sizeof($this->allegations) == 1) {
                return $this->allegations[0]->name;
            }
            if (sizeof($this->allegations) == 2) {
                return $this->allegations[0]->name . ' and ' . $this->allegations[1]->name;
            }
            return $this->allegations[0]->name . ', ' . $this->allegations[1]->name
                . ', and ' . $this->allegations[2]->name;
        }
        return $ret;
    }

    /**
     * Get a list of all of this complaint's allegations with more details.
     *
     * @param  boolean  $showWhy
     * @param  boolean  $isAnon
     * @return string
     */
    protected function basicAllegationList($showWhy = false, $isAnon = false)
    {
        $ret = '';
        if (isset($this->sessData->dataSets["allegations"])
            && sizeof($this->sessData->dataSets["allegations"]) > 0) {
            $printedOfficers = false;
            $allegOffs = [];
            // if there's only one Officer on the Complaint, then it is associated with all Allegations
            if (!$isAnon && isset($this->sessData->dataSets["officers"])
                && sizeof($this->sessData->dataSets["officers"]) == 1) {
                /*
                $ret .= '<div class="pL5 pB10">Officer '
                    . $this->getOfficerNameFromID($this->sessData->dataSets["officers"][0]->off_id) . '</div>';
                */
                $printedOfficers = true;
            } else { // Load Officer names for each Allegation
                foreach ($this->sessData->dataSets["allegations"] as $alleg) {
                    if ($GLOBALS["CUST"]->checkAllegIntimidWeapon($alleg)) {
                        $allegOffs[$alleg->alle_id] = '';
                        $offs = $this->getLinkedToEvent('Officer', $alleg->alle_id);
                        if (sizeof($offs) > 0) {
                            foreach ($offs as $off) {
                                $allegOffs[$alleg->alle_id] .= ', ' . $this->getOfficerNameFromID($off);
                            }
                        }
                        if (trim($allegOffs[$alleg->alle_id]) != '') {
                            $allegOffs[$alleg->alle_id] = substr($allegOffs[$alleg->alle_id], 1);
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
                        . $allegOffs[$this->sessData->dataSets["allegations"][0]->alle_id]
                        . '</div>';
                    $printedOfficers = true;
                }
            }
            foreach ($GLOBALS["CUST"]->worstAllegs as $allegType) {
                // printing Allegations in order of severity...
                foreach ($this->sessData->dataSets["allegations"] as $alleg) {
                    if ($alleg->alle_type == $allegType->defID) {
                        if ($GLOBALS["CUST"]->checkAllegIntimidWeapon($alleg)) {
                            $ret .= '<div class="fPerc125">' . $allegType->name;
                            if (!$isAnon && !$printedOfficers && isset($allegOffs[$alleg->alle_id])) {
                                $ret .= ' <span class="mL20 slGrey">' . $allegOffs[$alleg->alle_id]
                                    . '</span>';
                            }
                            $ret .= '</div>' . (($showWhy) ? '<div class="slGrey mTn10 pL20">'
                                . $alleg->alle_description . '</div>' : '')
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


    /**
     * Get an array of all commendations which apply to this compliment.
     *
     * @return array
     */
    protected function arrayComplimentList()
    {
        $types = [];
        if (isset($this->sessData->dataSets["off_compliments"])
            && sizeof($this->sessData->dataSets["off_compliments"]) > 0) {
            foreach ($this->sessData->dataSets["off_compliments"] as $off) {
                if (isset($off->off_comp_valor)
                    && trim($off->off_comp_valor) == 'Y') {
                    $types[] = 'Valor';
                }
                if (isset($off->off_comp_lifesaving)
                    && trim($off->off_comp_lifesaving) == 'Y') {
                    $types[] = 'Lifesaving';
                }
                if (isset($off->off_comp_deescalation)
                    && trim($off->off_comp_deescalation) == 'Y') {
                    $types[] = 'De-escalation';
                }
                if (isset($off->off_comp_professionalism)
                    && trim($off->off_comp_professionalism) == 'Y') {
                    $types[] = 'Professionalism';
                }
                if (isset($off->off_comp_fairness)
                    && trim($off->off_comp_fairness) == 'Y') {
                    $types[] = 'Fairness';
                }
                if (isset($off->off_comp_constitutional)
                    && trim($off->off_comp_constitutional) == 'Y') {
                    $types[] = 'Constitutional';
                }
                if (isset($off->off_comp_compassion)
                    && trim($off->off_comp_compassion) == 'Y') {
                    $types[] = 'Compassion';
                }
                if (isset($off->off_comp_community)
                    && trim($off->off_comp_community) == 'Y') {
                    $types[] = 'Community';
                }
            }
        }
        return $types;
    }

    /**
     * Get a written list of all commendations which apply to this compliment.
     *
     * @return string
     */
    protected function commaComplimentList()
    {
        $ret = '';
        $types = $this->arrayComplimentList();
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

}