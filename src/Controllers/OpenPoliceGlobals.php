<?php
/**
  * OpenPoliceGlobals allows the attachment of custom variables and processes
  * in Survloop's main Globals class.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.2.19
  */
namespace FlexYourRights\OpenPolice\Controllers;

use App\Models\OPAllegations;
use App\Models\OPStops;

class OpenPoliceGlobals
{
    public $worstAllegs = [];
    public $eventTypes  = [];

    /**
     * Load the system's Allegations in descending order of severity,
     * with Definition IDs and field names.
     *
     * @return void
     */
    public function __construct()
    {
        $this->worstAllegs = [
            new AllegType( 126, 'Sexual Assault',                 'alle_sil_sexual_assault'      ),
            new AllegType( 115, 'Unreasonable Force',             'alle_sil_force_unreason'      ),
            new AllegType( 116, 'Wrongful Arrest',                'alle_sil_arrest_wrongful'     ),
            new AllegType( 480, 'Sexual Harassment',              'alle_sil_sexual_harass'       ),
            new AllegType( 120, 'Wrongful Property Seizure',      'alle_sil_property_wrongful'   ),
            new AllegType( 496, 'Wrongful Property Damage',       'alle_sil_property_damage'     ),
            new AllegType( 125, 'Intimidating Display of Weapon', 'alle_sil_intimidating_weapon' ),
            new AllegType( 119, 'Wrongful Search',                'alle_sil_search_wrongful'     ),
            new AllegType( 118, 'Wrongful Entry',                 'alle_sil_wrongful_entry'      ),
            new AllegType( 117, 'Wrongful Detention',             'alle_sil_stop_wrongful'       ),
            new AllegType( 121, 'Bias-Based Policing',            'alle_sil_bias'                ),
            new AllegType( 122, 'Excessive Arrest Charges',       'alle_sil_arrest_retaliatory'  ),
            new AllegType( 124, 'Excessive Citation',             'alle_sil_citation_excessive'  ),
            new AllegType( 608, 'Repeat Harassment',              'alle_sil_repeat_harass'       ),
            new AllegType( 128, 'Conduct Unbecoming an Officer',  'alle_sil_unbecoming'          ),
            new AllegType( 129, 'Discourtesy',                    'alle_sil_discourteous'        ),
            new AllegType( 132, 'Neglect of Duty',                'alle_sil_neglect_duty'        ),
            new AllegType( 127, 'Policy or Procedure Violation',  'alle_sil_procedure'           ),
            //new AllegType( 131, 'Miranda Rights',                 'alle_sil_arrest_miranda'    ),
            new AllegType( 130, 'Officer Refused To Provide ID',  'alle_sil_officer_refuse_id'   )
        ];
    }

    /**
     * Get an array of the Definition ID numbers for all Allegations.
     *
     * @param  string  $prefix
     * @return array
     */
    public function getAllegIDs($prefix = '')
    {
        $ret = [];
        foreach ($this->worstAllegs as $allegType) {
            if ($prefix == '') {
                $ret[] = $allegType->defID;
            } else {
                $ret[] = $prefix . $allegType->defID;
            }
        }
        return $ret;
    }

    /**
     * Get the database field name of one Allegation.
     *
     * @param  int  $allegID
     * @return string
     */
    public function getAllegFldName($allegID)
    {
        foreach ($this->worstAllegs as $allegType) {
            if ($allegType->defID == $allegID) {
                return $allegType->field;
            }
        }
        return '';
    }

    /**
     * Get a list of allegations made for one complaint.
     *
     * @param  App\Models\OPAllegSilver  $allegSilv
     * @param  array  $allegRows
     * @param  array  $stops
     * @return array
     */
    public function getAllegations($allegSilv, $allegRows = [], $stops = [])
    {
        $allegations = [];
        if (sizeof($allegRows) == 0) {
            $allegRows = OPAllegations::where('alle_complaint_id', $allegSilv->alle_sil_complaint_id)
                ->get();
        }
        if (sizeof($stops) == 0) {
            $stops = OPStops::where('stop_com_id', $allegSilv->alle_sil_complaint_id)
                ->get();
        }
        foreach ($GLOBALS["CUST"]->worstAllegs as $i => $allegType) {
            $allegInfo = new AllegInfo($allegType->defID, $allegType->name);
            switch ($allegType->name) {
                case 'Sexual Assault':
                case 'Sexual Harassment':
                case 'Unreasonable Force':
                case 'Wrongful Arrest':
                case 'Wrongful Property Damage':
                case 'Wrongful Property Seizure':
                case 'Wrongful Search':
                case 'Wrongful Entry':
                case 'Wrongful Detention':
                case 'Bias-Based Policing':
                case 'Repeat Harassment':
                case 'Excessive Arrest Charges':
                case 'Conduct Unbecoming an Officer':
                case 'Discourtesy':
                case 'Neglect of Duty':
                case 'Policy or Procedure Violation':
                case 'Excessive Citation':
                    $allegInfo->why .= $this->chkSilvAlleg($allegSilv, $allegRows, $allegType);
                    break;
                case 'Intimidating Display of Weapon':
                    if ($this->checkAllegSilvIntimidWeapon($allegSilv)) {
                        $defSet = 'Intimidating Displays Of Weapon';
                        $alleVal = $allegSilv->alle_sil_intimidating_weapon;
                        $allegInfo->why .= ', ' . $GLOBALS["SL"]->def->getVal($defSet, $alleVal);
                    }
                case 'Miranda Rights':
                    if (isset($allegSilv->alle_sil_arrest_miranda)
                        && $allegSilv->alle_sil_arrest_miranda == 'Y') {
                        $allegInfo->why .= ' ';
                    }
                    break;
                case 'Officer Refused To Provide ID':
                    if (isset($allegSilv->alle_sil_officer_refuse_id)
                        && $allegSilv->alle_sil_officer_refuse_id == 'Y') {
                        $allegInfo->why .= ' ';
                    }
                    break;
            }
            if ($allegInfo->why != '') {
                $allegations[] = $allegInfo;
            }
        }
        return $allegations;
    }

    /**
     * Get description of why complaint alleges misconduct.
     *
     * @param  App\Models\OPAllegSilver  $allegSilv
     * @param  array  $allegRows
     * @param  AllegType  $allegType
     * @return string
     */
    private function chkSilvAlleg($allegSilv, $allegRows, $allegType)
    {
        if (isset($allegSilv->{ $allegType->field })
            && trim($allegSilv->{ $allegType->field }) == 'Y') {
            $allegRec = $this->getAllegSilvRec($allegRows, $allegType);
            return ', ' . $this->getAllegDesc($allegRows, $allegType, $allegRec);
        }
        return '';
    }

    /**
     * Check whether or not this complaint has an
     * allegation of intimidation with a weapon.
     *
     * @param  App\Models\OPAllegSilver  $allegSilv
     * @return boolean
     */
    private function checkAllegSilvIntimidWeapon($allegSilv)
    {
        if (isset($allegSilv->alle_sil_intimidating_weapon)) {
            $weapon = intVal($allegSilv->alle_sil_intimidating_weapon);
            return ($weapon > 0 && !in_array($weapon, $this->intimidWeaponNos()));
        }
        return false;
    }

    /**
     * Check whether or not this complaint has an
     * allegation of intimidation with a weapon.
     *
     * @param  App\Models\OPAllegations  $alleg
     * @return boolean
     */
    public function checkAllegIntimidWeapon($alleg)
    {
        $defID = $this->def->getID('Allegation Type', 'Intimidating Display of Weapon');
        $weapon = intVal($alleg->alle_intimidating_weapon);
        return ($alleg->alle_type != $defID || !in_array($weapon, $this->intimidWeaponNos()));
    }

    /**
     * Returns an array with the two Definition IDs which
     * do not indicate the intimidation with a weapon.
     *
     * @return array
     */
    public function intimidWeaponNos()
    {
        return [
            $GLOBALS["SL"]->def->getID('Intimidating Displays Of Weapon', 'No'),
            $GLOBALS["SL"]->def->getID('Intimidating Displays Of Weapon', 'Not sure')
        ];
    }

    /**
     * Get a list of allegations made for one complaint.
     *
     * @param  array  $allegRows
     * @param  AllegType  $allegType
     * @param  array  $allegRec
     * @return string
     */
    private function getAllegDesc($allegRows, $allegType, $allegRec = [])
    {
        if ($allegRec && isset($allegRec->alle_description)) {
            return trim($allegRec->alle_description);
        }
        return '';
    }

    /**
     * Get this complaint's allegation record for a specific type of allegation.
     *
     * @param  array  $allegRows
     * @param  AllegType  $allegType
     * @return array
     */
    private function getAllegSilvRec($allegRows, $allegType)
    {
        if (sizeof($allegRows) > 0) {
            foreach ($allegRows as $alleg) {
                if ($alleg->alle_type == $allegType->defID
                    && (!isset($alleg->alle_event_sequence_id)
                        || intVal($alleg->alle_event_sequence_id) <= 0)) {
                    return $alleg;
                }
            }
        }
        return [];
    }

    /**
     * Get a list of the names and field names for the Yes/No questions
     * regarding the basic events within a police incident.
     *
     * @return array
     */
    public function getEventTypeList()
    {
        if (sizeof($this->eventTypes) == 0) {
            $this->eventTypes = [
                new EventType('stop',   'Stop or Detention',            'alle_sil_stop_yn'       ),
                new EventType('offID',  'Asked for Officer\'s ID',      'alle_sil_officer_id'    ),
                new EventType('search', 'Search of Person or Property', 'alle_sil_search_yn'     ),
                new EventType('prop',   'Property Seized or Damaged',   'alle_sil_property_yn'   ),
                new EventType('force',  'Force Used',                   'alle_sil_force_yn'      ),
                new EventType('arrest', 'Arrest Occured',               'alle_sil_arrest_yn'     ),
                new EventType('ticket', 'Ticket or Citation Given',     'alle_sil_citation_yn'   ),
                new EventType('repeat', 'Ongoing or Repeated Contact',  'alle_sil_repeat_contact')
            ];
        }
        return $this->eventTypes;
    }

    /**
     * Get an array of the incident event abbreviations.
     *
     * @param  string  $prefix
     * @return array
     */
    public function getAllEventAbbrs($prefix = '')
    {
        $ret = [];
        foreach ($this->eventTypes as $event) {
            if ($prefix == '') {
                $ret[] = $event->abbr;
            } else {
                $ret[] = $prefix . $event->abbr;
            }
        }
        return $ret;
    }

    /**
     * Get the name of an type of incident event by it's abbreviation.
     *
     * @return string
     */
    public function getEventTypeName($abbr)
    {
        $this->getEventTypeList();
        foreach ($this->eventTypes as $type) {
            if ($type->abbr == $abbr) {
                return $type->name;
            }
        }
        return '';
    }

    /**
     * Get a list of the names and field names for the Yes/No questions
     * regarding the basic events within a police incident.
     *
     * @param  App\Models\OPAllegSilver  $allegSilv
     * @return array
     */
    public function getComplaintEventTypes($allegSilv)
    {
        $ret = [];
        if ($allegSilv && isset($allegSilv->alle_sil_complaint_id)) {
            $this->getEventTypeList();
            foreach ($this->eventTypes as $type) {
                if (isset($allegSilv->{ $type->field })
                    && $allegSilv->{ $type->field } == 'Y') {
                    $ret[] = $type->abbr;
                }
            }
        }
        return $ret;
    }


    /**
     * Print one complaint status, by its Definition ID.
     *
     * @param  int  $defID
     * @param  int  $type
     * @return string
     */
    public function printComplaintStatus($defID, $type = 0)
    {
        $ret = $GLOBALS["SL"]->def->getVal('Complaint Status', $defID);
        $unverif = $GLOBALS['SL']->def->getID('Complaint Type', 'Unverified');
        if ($ret == 'New' && $type > 0 && $type == $unverif) {
            $ret = 'Unverified';
        }
        $ret = str_replace('Submit to Oversight', 'File with Oversight', $ret);
        $ret = str_replace('Submitted to Oversight', 'Filed with Oversight', $ret);
        $ret = str_replace('Oversight', 'Investigative Agency', $ret);
        $desc = 'Investigative Agency Declined to Investigate (Closed)';
        $ret = str_replace('Declined To Investigate (Closed)', $desc, $ret);
        $desc = 'Investigated by Investigative Agency (Closed)';
        $ret = str_replace('Investigated (Closed)', $desc, $ret);
        return $ret;
    }

    /**
     * Print abbreviated complaint status, by its Definition ID.
     *
     * @param  int  $defID
     * @param  int  $type
     * @return string
     */
    public function printComplaintStatusAbbr($defID, $type = 0)
    {
        $status = $this->printComplaintStatus($defID, $type);
        return str_replace('Investigative Agency', 'IA', $status);
    }

}


class AllegType
{
    public $defID = 0;
    public $name  = '';
    public $field = '';

    public function __construct($defID, $name, $field)
    {
        $this->defID = $defID;
        $this->name  = $name;
        $this->field = $field;
    }

}

class AllegInfo
{
    public $defID = 0;
    public $name  = '';
    public $why   = '';
    public $id    = -3;
    public $civs  = [];
    public $offs  = [];

    public function __construct($defID, $name, $why = '', $id = -3, $civs = [], $offs = [])
    {
        $this->defID = $defID;
        $this->name  = $name;
        $this->why   = $why;
        $this->id    = $id;
        $this->civs  = $civs;
        $this->offs  = $offs;
    }

}

class EventType
{
    public $abbr  = '';
    public $name  = '';
    public $field = '';

    public function __construct($abbr, $name, $field)
    {
        $this->abbr  = $abbr;
        $this->name  = $name;
        $this->field = $field;
    }

}
