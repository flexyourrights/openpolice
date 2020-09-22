<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPDepartments extends Model
{
    protected $table      = 'op_departments';
    protected $primaryKey = 'dept_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'dept_name', 
		'dept_slug', 
		'dept_type', 
		'dept_status', 
		'dept_verified', 
		'dept_email', 
		'dept_phone_work', 
		'dept_address', 
		'dept_address2', 
		'dept_address_city', 
		'dept_address_state', 
		'dept_address_zip', 
		'dept_address_county', 
		'dept_score_openness', 
		'dept_tot_officers', 
		'dept_jurisdiction_population', 
		'dept_jurisdiction_gps', 
		'dept_version_ab', 
		'dept_submission_progress', 
		'dept_ip_addy', 
		'dept_tree_version', 
		'dept_unique_str', 
		'dept_user_id', 
		'dept_is_mobile', 
		'dept_address_lat', 
		'dept_address_lng', 
        'dept_op_compliant', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
