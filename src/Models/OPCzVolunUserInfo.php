<?php

namespace OpenPolice\Models;

use Illuminate\Database\Eloquent\Model;

class OPCzVolunUserInfo extends Model
{
	protected $table = 'OPC_zVolunUserInfo';
	protected $primaryKey = 'UserInfoID';
	public $timestamps = true;
	
	protected $fillable = [
		'UserInfoPersonContactID', 'UserInfoStars', 
		'UserInfoStars1', 'UserInfoStars2', 'UserInfoStars3', 
		'UserInfoDepts', 'UserInfoAvgTimeDept', 
	];
    
}
