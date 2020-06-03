<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPzVolunUserInfo extends Model
{
    protected $table      = 'op_z_volun_user_info';
    protected $primaryKey = 'user_info_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'user_info_user_id', 
		'user_info_person_contact_id', 
		'user_info_stars', 
		'user_info_stars1', 
		'user_info_stars2', 
		'user_info_stars3', 
		'user_info_depts', 
		'user_info_avg_time_dept', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
