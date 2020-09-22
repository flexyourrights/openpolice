<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPTesterBeta extends Model
{
    protected $table      = 'op_tester_beta';
    protected $primaryKey = 'beta_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'beta_email', 
		'beta_name', 
		'beta_last_name', 
		'beta_year', 
		'beta_narrative', 
		'beta_how_hear', 
		'beta_invited', 
		'beta_user_id', 
		'beta_version_ab', 
		'beta_submission_progress', 
		'beta_ip_addy', 
		'beta_tree_version', 
		'beta_unique_str', 
		'beta_is_mobile', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
