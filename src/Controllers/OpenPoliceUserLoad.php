<?php
/**
  * OpenPoliceUserLoad is a class which inserts behavior
  * within Survloop's loading of a user's top-right corner of the UX.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.2.29
  */
namespace FlexYourRights\OpenPolice\Controllers;

use Auth;
use App\Models\OPPersonContact;
use RockHopSoft\Survloop\Controllers\SurvloopUserLoad;

class OpenPoliceUserLoad extends SurvloopUserLoad
{
    public function __construct()
    {
        if (Auth::user() && Auth::user()->id) {
            if (Auth::user()->hasRole('staff|partner')
                && !in_array(Auth::user()->id, [863, 2253])) {
                $contact = OPPersonContact::whereNotNull('prsn_address_state')
                    ->where('prsn_user_id', Auth::user()->id)
                    ->first();
                if ($contact && trim($contact->prsn_address_state) != '') {
                    $st = $contact->prsn_address_state;
                    $link = '/dash/all-complete-complaints?states=' . $st;
                    $this->addNavMenuTweak(2, 0, 0, $link);
                    $link = '/dash/volunteer?state=' . $st;
                    $this->addNavMenuTweak(3, 0, 0, $link);
                }
            }
        }
    }
    
}
