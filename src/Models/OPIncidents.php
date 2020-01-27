<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPIncidents extends Model
{
    protected $table      = 'op_incidents';
    protected $primaryKey = 'inc_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'inc_complaint_id', 
		'inc_address', 
		'inc_address2', 
		'inc_address_city', 
		'inc_address_state', 
		'inc_address_zip', 
		'inc_address_lat', 
		'inc_address_lng', 
		'inc_landmarks', 
		'inc_time_start', 
		'inc_time_end', 
		'inc_duration', 
		'inc_public', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
