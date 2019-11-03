<?php
/**
  * OpenPoliceVars is the bottom-level class extending the main SurvLoop trunk,
  * and initializing 
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.2.25
  */
namespace OpenPolice\Controllers;

use SurvLoop\Controllers\Tree\TreeSurvForm;

class OpenPoliceVars extends TreeSurvForm
{
    public $classExtension     = 'OpenPoliceVars';
    public $treeID             = 1;
    
    protected $allCivs         = [];
    protected $allegations     = [];
    
    public $worstAllegations   = [ // Allegations in descending order of severity, Definition IDs
        [126, 'Sexual Assault',                 'AlleSilSexualAssault'],
        [115, 'Unreasonable Force',             'AlleSilForceUnreason'],
        [116, 'Wrongful Arrest',                'AlleSilArrestWrongful'], 
        [480, 'Sexual Harassment',              'AlleSilSexualHarass'], 
        [120, 'Wrongful Property Seizure',      'AlleSilPropertyWrongful'],
        [496, 'Wrongful Property Damage',       'AlleSilPropertyDamage'],
        [125, 'Intimidating Display of Weapon', 'AlleSilIntimidatingWeapon'], 
        [119, 'Wrongful Search',                'AlleSilSearchWrongful'],
        [118, 'Wrongful Entry',                 'AlleSilWrongfulEntry'],
        [117, 'Wrongful Detention',             'AlleSilStopWrongful'], 
        [121, 'Bias-Based Policing',            'AlleSilBias'],
        [122, 'Excessive Arrest Charges',       'AlleSilArrestRetaliatory'],
        [124, 'Excessive Citation',             'AlleSilCitationExcessive'],
        [608, 'Repeat Harassment',              'AlleSilRepeatHarass'],
        [128, 'Conduct Unbecoming an Officer',  'AlleSilUnbecoming'],
        [129, 'Discourtesy',                    'AlleSilDiscourteous'],
        [127, 'Neglect of Duty',                'AlleSilNeglectDuty'], 
        [127, 'Policy or Procedure Violation',  'AlleSilProcedure'], 
        //[131, 'Miranda Rights',                 'AlleSilArrestMiranda'],
        [130, 'Officer Refused To Provide ID',  'AlleSilOfficerRefuseID']
    ];

    public $eventTypes = [
        'Stops',
        'Searches',
        'Force',
        'Arrests'
    ];

    public $eveTypIDs = [
        252 => 'Stops',
        253 => 'Searches',
        254 => 'Force',
        255 => 'Arrests'
    ];

    protected $eventTypeLabel = [
        'Stops'    => 'Stop/Questioning',
        'Searches' => 'Search/Seizure',
        'Force'    => 'Use of Force',
        'Arrests'  => 'Arrest'
    ];

    protected $eventGoldAllegs = [
        'Stops'    => [117, 118],
        'Searches' => [119, 120, 496],
        'Force'    => [115],
        'Arrests'  => [116, 122]
    ];

    protected $cmplntUpNodes   = [
        280,
        324,
        317,
        413,
        371
    ];

    protected $eventTypeLookup = []; // $eveSeqID => 'Event Type'
    protected $eventCivLookup  = []; // array('Event Type' => array($civID, $civID, $civID))

}