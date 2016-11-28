<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCInjuryCare extends Model
{
	protected $table 		= 'OPC_InjuryCare';
	protected $primaryKey 	= 'InjCareID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'InjCareSubjectID', 
		'InjCareResultInDeath', 
		'InjCareTimeOfDeath', 
		'InjCareGotMedical', 
		'InjCareHospitalTreated', 
		'InjCareDoctorNameFirst', 
		'InjCareDoctorNameLast', 
		'InjCareDoctorEmail', 
		'InjCareDoctorPhone', 
		'InjCareEmergencyOnScene', 
		'InjCareEmergencyNameFirst', 
		'InjCareEmergencyNameLast', 
		'InjCareEmergencyIDnumber', 
		'InjCareEmergencyVehicleNumber', 
		'InjCareEmergencyLicenceNumber', 
		'InjCareEmergencyDeptName', 
	];
}