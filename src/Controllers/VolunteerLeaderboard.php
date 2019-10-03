<?php
/**
  * OpenVolunteers is a side-class with functions to
  * manage and print volunteers rankings.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <wikiworldorder@protonmail.com>
  * @since v0.0.1
  */
namespace OpenPolice\Controllers;

use DB;
use App\Models\User;
use App\Models\OPzVolunUserInfo;

class VolunteerLeaderboard
{
    public $UserInfoStars = [];
    
    function __construct()
    {
        $this->checkVolunStats();
        $this->loadUserInfoStars();
        return true;
    }
    
    public function checkVolunStats()
    {
        // First ensure all volunteers and staff have a corresponding OP_zVolunUserInfo record
        $volunteers = User::whereIn('id', function($query){
            $query->select('RoleUserUID')
                ->from('SL_UsersRoles')
                ->get();
        })->get();
        if ($volunteers->isNotEmpty()) {
            foreach ($volunteers as $u) {
                $chk = OPzVolunUserInfo::where('UserInfoUserID', $u->id)
                	->first();
                if (!$chk) {
                    $tmp = new OPzVolunUserInfo;
                    $tmp->UserInfoUserID = $u->id;
                    $tmp->save();
                }
            }
        }
        
        // Now update all editing totals
        $tally = $userTots = $uniqueDepts = [];
        $edits = DB::table('OP_Zedit_Oversight')
            ->leftJoin('OP_Zedit_Departments', 'OP_Zedit_Oversight.ZedOverZedDeptID', 
                '=', 'OP_Zedit_Departments.ZedDeptID')
            ->select('OP_Zedit_Departments.ZedDeptDuration', 
                'OP_Zedit_Departments.ZedDeptUserID', 
                'OP_Zedit_Oversight.ZedOverOverDeptID', 
                'OP_Zedit_Oversight.ZedOverOnlineResearch', 
                'OP_Zedit_Oversight.ZedOverMadeDeptCall', 
                'OP_Zedit_Oversight.ZedOverMadeIACall')
            ->where('OP_Zedit_Oversight.ZedOverOverType', 303)
            ->get();
        if ($edits->isNotEmpty()) {
            foreach ($edits as $edit) {
                if (!isset($tally[$edit->ZedDeptUserID])) {
                    $userTots[$edit->ZedDeptUserID] = array(0, 0, 0, 0, 0);
                    $tally[$edit->ZedDeptUserID] = [];
                }
                if (!isset($tally[$edit->ZedDeptUserID][$edit->ZedOverOverDeptID])) {
                    $tally[$edit->ZedDeptUserID][$edit->ZedOverOverDeptID] = array(0, 0, 0, 0);
                }
                if ($edit->ZedOverOnlineResearch && intVal($edit->ZedOverOnlineResearch) > 0) {
                    $tally[$edit->ZedDeptUserID][$edit->ZedOverOverDeptID][0] = 1;
                }
                if ($edit->ZedOverMadeDeptCall && intVal($edit->ZedOverMadeDeptCall) > 0) {
                    $tally[$edit->ZedDeptUserID][$edit->ZedOverOverDeptID][1] = 1;
                }
                if ($edit->ZedOverMadeIACall && intVal($edit->ZedOverMadeIACall) > 0) {
                    $tally[$edit->ZedDeptUserID][$edit->ZedOverOverDeptID][2] = 1;
                }
                if ($edit->ZedDeptDuration && intVal($edit->ZedDeptDuration) > 0) {
                    $tally[$edit->ZedDeptUserID][$edit->ZedOverOverDeptID][3] += intVal($edit->ZedDeptDuration);
                }
            }
        }
        if (sizeof($tally) > 0) {
            foreach ($tally as $uID => $depts) {
                $userTots[$uID][3] = sizeof($depts);
                $totDeptTime = 0;
                if (sizeof($depts) > 0) {
                    foreach ($depts as $deptID => $t) {
                        $userTots[$uID][0] += $t[0];
                        $userTots[$uID][1] += $t[1];
                        $userTots[$uID][2] += $t[2];
                        $totDeptTime += $t[3];
                    }
                }
                $userTots[$uID][4] = $totDeptTime/sizeof($depts);
                DB::table('OP_zVolunUserInfo')
                    ->where('UserInfoUserID', $uID)
                    ->update([
                        'UserInfoStars1'      => $userTots[$uID][0], 
                        'UserInfoStars2'      => $userTots[$uID][1], 
                        'UserInfoStars3'      => $userTots[$uID][2], 
                        'UserInfoStars'       => ($userTots[$uID][0]+(3*$userTots[$uID][1])+(3*$userTots[$uID][2])), 
                        'UserInfoDepts'       => $userTots[$uID][3], 
                        'UserInfoAvgTimeDept' => $userTots[$uID][4]
                    ]);
            }
        }
        return true;
    }
    
    public function loadUserInfoStars()
    {
        DB::raw("DELETE FROM `OP_zVolunUserInfo` WHERE `UserInfoUserID` NOT IN (SELECT `id` FROM `users`)");
        $this->UserInfoStars = DB::table('OP_zVolunUserInfo')
            ->join('users', 'OP_zVolunUserInfo.UserInfoUserID', '=', 'users.id')
            ->leftJoin('OP_PersonContact', 'OP_zVolunUserInfo.UserInfoPersonContactID', 
                '=', 'OP_PersonContact.PrsnID')
            ->select('users.name', 'OP_zVolunUserInfo.*', 'OP_PersonContact.PrsnAddressState')
            ->orderBy('OP_zVolunUserInfo.UserInfoStars', 'desc')
            ->orderBy('OP_zVolunUserInfo.UserInfoDepts', 'desc')
            ->get();
        return true;
    }
    
    
}
