<?php

namespace OpenPolice\Models;

use Illuminate\Database\Eloquent\Model;

class OPzVolunUserInfo extends Model
{
	protected $table = 'OP_zVolunUserInfo';
	protected $primaryKey = 'UserInfoID';
	public $timestamps = true;
	
	protected $fillable = [
		'UserInfoPersonContactID', 'UserInfoStars', 
		'UserInfoStars1', 'UserInfoStars2', 'UserInfoStars3', 
		'UserInfoDepts', 'UserInfoAvgTimeDept', 
	];
    
}
