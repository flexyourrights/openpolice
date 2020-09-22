<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCompliments extends Model
{
    protected $table      = 'op_compliments';
    protected $primaryKey = 'compli_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'compli_user_id', 
		'compli_status', 
		'compli_type', 
		'compli_incident_id', 
		'compli_scene_id', 
		'compli_privacy', 
		'compli_summary', 
		'compli_how_hear', 
		'compli_feedback', 
		'compli_slug', 
		'compli_notes', 
		'compli_record_submitted', 
		'compli_submission_progress', 
		'compli_version_ab', 
		'compli_tree_version', 
		'compli_honey_pot', 
		'compli_is_mobile', 
		'compli_unique_str', 
		'compli_ip_addy', 
		'compli_public_id', 
		'compli_is_demo', 
		'compli_share_data', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
