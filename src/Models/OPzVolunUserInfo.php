<?php namespace OpenPolice\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPzVolunUserInfo extends Model
{
    protected $table      = 'OP_zVolunUserInfo';
    protected $primaryKey = 'UserInfoID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'UserInfoUserID', 
		'UserInfoPersonContactID', 
		'UserInfoStars', 
		'UserInfoStars1', 
		'UserInfoStars2', 
		'UserInfoStars3', 
		'UserInfoDepts', 
		'UserInfoAvgTimeDept', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
