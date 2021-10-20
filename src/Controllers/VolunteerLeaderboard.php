<?php
/**
  * OpenVolunteers is a side-class with functions to
  * manage and print volunteers rankings.
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since v0.0.1
  */
namespace FlexYourRights\OpenPolice\Controllers;

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
        // First ensure all volunteers and staff have a corresponding op_zvolun_user_info record
        $volunteers = User::whereIn('id', function($query){
            $query->select('role_user_uid')
                ->from('sl_users_roles')
                ->get();
        })->get();
        if ($volunteers->isNotEmpty()) {
            foreach ($volunteers as $u) {
                $chk = OPzVolunUserInfo::where('user_info_user_id', $u->id)
                	->first();
                if (!$chk) {
                    $tmp = new OPzVolunUserInfo;
                    $tmp->user_info_user_id = $u->id;
                    $tmp->save();
                }
            }
        }

        // Now update all editing totals
        $tally = $userTots = $uniqueDepts = [];
        $edits = DB::table('op_z_edit_oversight')
            ->leftJoin('op_z_edit_departments', 'op_z_edit_oversight.zed_over_zed_dept_id',
                '=', 'op_z_edit_departments.zed_dept_id')
            ->select('op_z_edit_departments.zed_dept_duration',
                'op_z_edit_departments.zed_dept_user_id',
                'op_z_edit_oversight.zed_over_over_dept_id',
                'op_z_edit_oversight.zed_over_online_research',
                'op_z_edit_oversight.zed_over_made_dept_call',
                'op_z_edit_oversight.zed_over_made_ia_call')
            ->where('op_z_edit_oversight.zed_over_over_type', 303)
            ->get();
        if ($edits->isNotEmpty()) {
            foreach ($edits as $edit) {
                if (!isset($tally[$edit->zed_dept_user_id])) {
                    $userTots[$edit->zed_dept_user_id] = [0, 0, 0, 0, 0];
                    $tally[$edit->zed_dept_user_id] = [];
                }
                if (!isset($tally[$edit->zed_dept_user_id][$edit->zed_over_over_dept_id])) {
                    $tally[$edit->zed_dept_user_id][$edit->zed_over_over_dept_id] = [0, 0, 0, 0];
                }
                if ($edit->zed_over_online_research && intVal($edit->zed_over_online_research) > 0) {
                    $tally[$edit->zed_dept_user_id][$edit->zed_over_over_dept_id][0] = 1;
                }
                if ($edit->zed_over_made_dept_call && intVal($edit->zed_over_made_dept_call) > 0) {
                    $tally[$edit->zed_dept_user_id][$edit->zed_over_over_dept_id][1] = 1;
                }
                if ($edit->zed_over_made_ia_call && intVal($edit->zed_over_made_ia_call) > 0) {
                    $tally[$edit->zed_dept_user_id][$edit->zed_over_over_dept_id][2] = 1;
                }
                if ($edit->zed_dept_duration && intVal($edit->zed_dept_duration) > 0) {
                    $tally[$edit->zed_dept_user_id][$edit->zed_over_over_dept_id][3]
                        += intVal($edit->zed_dept_duration);
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
                DB::table('op_zvolun_user_info')
                    ->where('user_info_user_id', $uID)
                    ->update([
                        'user_info_stars1'        => $userTots[$uID][0],
                        'user_info_stars2'        => $userTots[$uID][1],
                        'user_info_stars3'        => $userTots[$uID][2],
                        'user_info_stars'         => ($userTots[$uID][0]+(3*$userTots[$uID][1])+(3*$userTots[$uID][2])),
                        'user_info_depts'         => $userTots[$uID][3],
                        'user_info_avg_time_dept' => $userTots[$uID][4]
                    ]);
            }
        }
        return true;
    }

    public function loadUserInfoStars()
    {
        $chk = Users::select('id')
            ->get();
        $userIDs = $GLOBALS["SL"]->resToArrIds($chk, 'id');
        OPzVolunUserInfo::whereNotIn('user_info_user_id', $userIDs)
            ->limit(1000)
            ->delete();
        $this->UserInfoStars = DB::table('op_zvolun_user_info')
            ->join('users', 'op_zvolun_user_info.user_info_user_id', '=', 'users.id')
            ->leftJoin('op_person_contact', 'op_zvolun_user_info.user_info_person_contact_id',
                '=', 'op_person_contact.prsn_id')
            ->select('users.name', 'op_zvolun_user_info.*', 'op_person_contact.prsn_address_state')
            ->orderBy('op_zvolun_user_info.user_info_stars', 'desc')
            ->orderBy('op_zvolun_user_info.user_info_depts', 'desc')
            ->get();
        return true;
    }


}
