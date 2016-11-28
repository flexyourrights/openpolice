<?php
namespace OpenPolice\Controllers;

use DB;

use SurvLoop\Models\User;

use OpenPolice\Models\OPCzVolunUserInfo;

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
		// First ensure all volunteers and staff have a corresponding OPC_zVolunUserInfo record
		$volunteers = User::whereIn('id', function($query){
			$query->select('RoleUserUID')
					->from('SL_UsersRoles')
					->get();            
		})->get();
		if ($volunteers && sizeof($volunteers) > 0)
		{
			foreach ($volunteers as $u)
			{
				$chk = OPCzVolunUserInfo::find($u->id);
				if (!$chk || sizeof($chk) == 0)
				{
					$tmp = new OPCzVolunUserInfo;
					$tmp->UserInfoUserID = $u->id;
					$tmp->save();
				}
			}
		}
		
		// Now update all editing totals
		$tally = $userTots = $uniqueDepts = array();
		$edits = DB::table('OPC_zVolunEditsOvers')
            ->leftJoin('OPC_zVolunEditsDepts', 'OPC_zVolunEditsOvers.EditOverEditDeptID', 
            	'=', 'OPC_zVolunEditsDepts.EditDeptID')
            ->select('OPC_zVolunEditsDepts.EditDeptPageTime', 
            	'OPC_zVolunEditsOvers.EditOverUser', 
            	'OPC_zVolunEditsOvers.EditOverDeptID', 
            	'OPC_zVolunEditsOvers.EditOverOnlineResearch', 
            	'OPC_zVolunEditsOvers.EditOverMadeDeptCall', 
            	'OPC_zVolunEditsOvers.EditOverMadeIACall')
			->where('OPC_zVolunEditsOvers.EditOverType', 169)
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
				DB::table('OPC_zVolunUserInfo')
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
		DB::raw("DELETE FROM `OPC_zVolunUserInfo` WHERE `UserInfoUserID` NOT IN (SELECT `id` FROM `users`)");
		$this->UserInfoStars = DB::table('OPC_zVolunUserInfo')
            ->join('users', 'OPC_zVolunUserInfo.UserInfoUserID', '=', 'users.id')
            ->leftJoin('OPC_PersonContact', 'OPC_zVolunUserInfo.UserInfoPersonContactID', 
            	'=', 'OPC_PersonContact.PrsnID')
            ->select('users.name', 'OPC_zVolunUserInfo.*', 'OPC_PersonContact.PrsnAddressState')
            ->orderBy('OPC_zVolunUserInfo.UserInfoStars', 'desc')
            ->orderBy('OPC_zVolunUserInfo.UserInfoDepts', 'desc')
            ->get();
		return true;
    }
    
    
}

?>