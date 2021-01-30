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

class OpenPoliceGlobals
{

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

    public function printComplaintStatusAbbr($defID, $type = 0)
    {
        return str_replace(
            'Investigative Agency', 
            'IA',
            $this->printComplaintStatus($defID, $type)
        );
    }

}
