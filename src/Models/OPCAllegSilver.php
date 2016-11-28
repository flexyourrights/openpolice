<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCAllegSilver extends Model
{
	protected $table 		= 'OPC_AllegSilver';
	protected $primaryKey 	= 'AlleSilID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'AlleSilComplaintID', 
		'AlleSilStopYN', 
		'AlleSilStopWrongful', 
		'AlleSilOfficerID', 
		'AlleSilOfficerRefuseID', 
		'AlleSilSearchYN', 
		'AlleSilSearchWrongful', 
		'AlleSilForceYN', 
		'AlleSilForceUnreason', 
		'AlleSilPropertyYN', 
		'AlleSilPropertyWrongful', 
		'AlleSilArrestYN', 
		'AlleSilArrestWrongful', 
		'AlleSilArrestRetaliatory', 
		'AlleSilArrestMiranda', 
		'AlleSilCitationYN', 
		'AlleSilCitationExcessive', 
		'AlleSilProcedure', 
		'AlleSilNeglectDuty', 
		'AlleSilBias', 
		'AlleSilSexualAssault', 
		'AlleSilIntimidatingWeapon', 
		'AlleSilIntimidatingWeaponType', 
		'AlleSilRetaliation', 
		'AlleSilUnbecoming', 
		'AlleSilDiscourteous', 
	];
}