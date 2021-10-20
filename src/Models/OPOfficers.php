<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPOfficers extends Model
{
	use Cachable;

    protected $table      = 'op_officers';
    protected $primaryKey = 'off_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'off_verified_id', 
		'off_complaint_id', 
		'off_role', 
		'off_dept_id', 
		'off_person_id', 
		'off_phys_desc_id', 
		'off_give_name', 
		'off_had_vehicle', 
		'off_precinct', 
		'off_badge_number', 
		'off_id_number', 
		'off_officer_rank', 
		'off_dash_cam', 
		'off_body_cam', 
		'off_duty_status', 
		'off_uniform', 
		'off_used_profanity', 
		'off_additional_details', 
		'off_gave_compliment', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
