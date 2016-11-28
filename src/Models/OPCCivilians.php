<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCCivilians extends Model
{
	protected $table 		= 'OPC_Civilians';
	protected $primaryKey 	= 'CivID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'CivComplaintID', 
		'CivUserID', 
		'CivIsCreator', 
		'CivRole', 
		'CivPersonID', 
		'CivPhysDescID', 
		'CivGiveName', 
		'CivGiveContactInfo', 
		'CivGivePhysDesc', 
		'CivHadVehicle', 
		'CivInPreviousVehicle', 
		'CivResident', 
		'CivCameraRecord', 
		'CivOccupation', 
	];
}