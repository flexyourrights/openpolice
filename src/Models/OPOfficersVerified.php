<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPOfficersVerified extends Model
{
    protected $table      = 'op_officers_verified';
    protected $primaryKey = 'off_ver_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'off_ver_status', 
		'off_ver_person_id', 
		'off_ver_cnt_complaints', 
		'off_ver_cnt_allegations', 
		'off_ver_cnt_compliments', 
		'off_ver_cnt_commends', 
		'off_ver_unique_str', 
		'off_ver_submission_progress', 
		'off_ver_version_ab', 
		'off_ver_tree_version', 
		'off_ver_ip_addy', 
		'off_ver_is_mobile', 
		'off_ver_user_id', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
