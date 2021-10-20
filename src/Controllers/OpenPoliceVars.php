<?php
/**
  * OpenPoliceVars is the bottom-level class extending the main Survloop trunk,
  * and initializing
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.2.25
  */
namespace FlexYourRights\OpenPolice\Controllers;

use RockHopSoft\Survloop\Controllers\Tree\TreeSurvForm;

class OpenPoliceVars extends TreeSurvForm
{
    public $classExtension = 'OpenPoliceVars';
    public $treeID         = 1;

    protected $allCivs     = [];
    protected $allegations = [];

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