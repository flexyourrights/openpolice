<?php
/**
  * OpenPoliceVars is the bottom-level class extending the main Survloop trunk,
  * and initializing 
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <rockhoppers@runbox.com>
  * @since v0.2.25
  */
namespace OpenPolice\Controllers;

use Survloop\Controllers\Tree\TreeSurvForm;

class OpenPoliceVars extends TreeSurvForm
{
    public $classExtension     = 'OpenPoliceVars';
    public $treeID             = 1;
    
    protected $allCivs         = [];
    protected $allegations     = [];
    
    public $worstAllegations   = [ // Allegations in descending order of severity, Definition IDs
        [126, 'Sexual Assault',                 'alle_sil_sexual_assault'],
        [115, 'Unreasonable Force',             'alle_sil_force_unreason'],
        [116, 'Wrongful Arrest',                'alle_sil_arrest_wrongful'], 
        [480, 'Sexual Harassment',              'alle_sil_sexual_harass'], 
        [120, 'Wrongful Property Seizure',      'alle_sil_property_wrongful'],
        [496, 'Wrongful Property Damage',       'alle_sil_property_damage'],
        [125, 'Intimidating Display of Weapon', 'alle_sil_intimidating_weapon'], 
        [119, 'Wrongful Search',                'alle_sil_search_wrongful'],
        [118, 'Wrongful Entry',                 'alle_sil_wrongful_entry'],
        [117, 'Wrongful Detention',             'alle_sil_stop_wrongful'], 
        [121, 'Bias-Based Policing',            'alle_sil_bias'],
        [122, 'Excessive Arrest Charges',       'alle_sil_arrest_retaliatory'],
        [124, 'Excessive Citation',             'alle_sil_citation_excessive'],
        [608, 'Repeat Harassment',              'alle_sil_repeat_harass'],
        [128, 'Conduct Unbecoming an Officer',  'alle_sil_unbecoming'],
        [129, 'Discourtesy',                    'alle_sil_discourteous'],
        [127, 'Neglect of Duty',                'alle_sil_neglect_duty'], 
        [127, 'Policy or Procedure Violation',  'alle_sil_procedure'], 
        //[131, 'Miranda Rights',                 'alle_sil_arrest_miranda'],
        [130, 'Officer Refused To Provide ID',  'alle_sil_officer_refuse_id']
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

    protected $cmplntUpNodes   = [ 280 ];

    protected $eventTypeLookup = []; // $eveSeqID => 'Event Type'
    protected $eventCivLookup  = []; // array('Event Type' => array($civID, $civID, $civID))

}