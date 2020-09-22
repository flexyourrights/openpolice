<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPInjuryCare extends Model
{
    protected $table      = 'op_injury_care';
    protected $primaryKey = 'inj_care_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'inj_care_subject_id', 
		'inj_care_result_in_death', 
		'inj_care_time_of_death', 
		'inj_care_got_medical', 
		'inj_care_hospital_treated', 
		'inj_care_doctor_name_first', 
		'inj_care_doctor_name_last', 
		'inj_care_doctor_email', 
		'inj_care_doctor_phone', 
		'inj_care_emergency_on_scene', 
		'inj_care_emergency_name_first', 
		'inj_care_emergency_name_last', 
		'inj_care_emergency_id_number', 
		'inj_care_emergency_vehicle_number', 
		'inj_care_emergency_licence_number', 
		'inj_care_emergency_dept_name', 
		'inj_care_done', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
