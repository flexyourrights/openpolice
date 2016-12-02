<?php
namespace OpenPolice\Controllers;

use DB;

use App\Models\User;

use App\Models\OPzVolunUserInfo;

class VolunteerLeaderboard
{
	public $UserInfoStars = array();
	
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
		if ($volunteers && sizeof($volunteers) > 0)
		{
			foreach ($volunteers as $u)
			{
				$chk = OPzVolunUserInfo::find($u->id);
				if (!$chk || sizeof($chk) == 0)
				{
					$tmp = new OPzVolunUserInfo;
					$tmp->UserInfoUserID = $u->id;
					$tmp->save();
				}
			}
		}
		
		// Now update all editing totals
		$tally = $userTots = $uniqueDepts = array();
		$edits = DB::table('OP_zVolunEditsOvers')
            ->leftJoin('OP_zVolunEditsDepts', 'OP_zVolunEditsOvers.EditOverEditDeptID', 
            	'=', 'OP_zVolunEditsDepts.EditDeptID')
            ->select('OP_zVolunEditsDepts.EditDeptPageTime', 
            	'OP_zVolunEditsOvers.EditOverUser', 
            	'OP_zVolunEditsOvers.EditOverDeptID', 
            	'OP_zVolunEditsOvers.EditOverOnlineResearch', 
            	'OP_zVolunEditsOvers.EditOverMadeDeptCall', 
            	'OP_zVolunEditsOvers.EditOverMadeIACall')
			->where('OP_zVolunEditsOvers.EditOverType', 303)
            ->get();
		if ($edits && sizeof($edits) > 0)
		{
			foreach ($edits as $edit)
			{
				if (!isset($tally[$edit->EditOverUser]))
				{
					$userTots[$edit->EditOverUser] = array(0, 0, 0, 0, 0);
					$tally[$edit->EditOverUser] = array();
				}
				if (!isset($tally[$edit->EditOverUser][$edit->EditOverDeptID])) 					$tally[$edit->EditOverUser][$edit->EditOverDeptID] = array(0, 0, 0, 0);
				if ($edit->EditOverOnlineResearch && intVal($edit->EditOverOnlineResearch) > 0) 	$tally[$edit->EditOverUser][$edit->EditOverDeptID][0] = 1;
				if ($edit->EditOverMadeDeptCall && intVal($edit->EditOverMadeDeptCall) > 0) 		$tally[$edit->EditOverUser][$edit->EditOverDeptID][1] = 1;
				if ($edit->EditOverMadeIACall && intVal($edit->EditOverMadeIACall) > 0) 			$tally[$edit->EditOverUser][$edit->EditOverDeptID][2] = 1;
				if ($edit->EditDeptPageTime && intVal($edit->EditDeptPageTime) > 0) 				$tally[$edit->EditOverUser][$edit->EditOverDeptID][3] += intVal($edit->EditDeptPageTime);
			}
		}
		if (sizeof($tally) > 0)
		{
			foreach ($tally as $uID => $depts)
			{
				$userTots[$uID][3] = sizeof($depts);
				$totDeptTime = 0;
				if (sizeof($depts) > 0)
				{
					foreach ($depts as $deptID => $t)
					{
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
						'UserInfoStars1' 		=> $userTots[$uID][0], 
						'UserInfoStars2' 		=> $userTots[$uID][1], 
						'UserInfoStars3' 		=> $userTots[$uID][2], 
						'UserInfoStars' 		=> ($userTots[$uID][0]+(3*$userTots[$uID][1])+(3*$userTots[$uID][2])), 
						'UserInfoDepts' 		=> $userTots[$uID][3], 
						'UserInfoAvgTimeDept' 	=> $userTots[$uID][4]
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

?>