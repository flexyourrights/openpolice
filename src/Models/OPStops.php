<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPStops extends Model
{
    protected $table      = 'op_stops';
    protected $primaryKey = 'stop_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'stop_event_sequence_id', 
		'stop_stated_reason_desc', 
		'stop_subject_asked_to_leave', 
		'stop_subject_statements_desc', 
		'stop_enter_private_property', 
		'stop_enter_private_property_desc', 
		'stop_permission_enter', 
		'stop_permission_enter_granted', 
		'stop_request_id', 
		'stop_refuse_id', 
		'stop_request_officer_id', 
		'stop_officer_refuse_id', 
		'stop_subject_frisk', 
		'stop_subject_handcuffed', 
		'stop_stop_subject_handcuff_inj_yn', 
		'stop_subject_handcuff_injury', 
		'stop_duration', 
		'stop_breath_alcohol', 
		'stop_breath_alcohol_failed', 
		'stop_breath_cannabis', 
		'stop_breath_cannabis_failed', 
		'stop_saliva_test', 
		'stop_sobriety_other', 
		'stop_sobriety_other_describe', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
