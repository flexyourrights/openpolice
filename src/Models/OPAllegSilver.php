<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPAllegSilver extends Model
{
    protected $table      = 'OP_AllegSilver';
    protected $primaryKey = 'AlleSilID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'AlleSilComplaintID', 
		'AlleSilStopYN', 
		'AlleSilStopWrongful', 
		'AlleSilOfficerID', 
		'AlleSilOfficerRefuseID', 
		'AlleSilSearchYN', 
		'AlleSilSearchWrongful', 
		'AlleSilWrongfulEntry', 
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
		'AlleSilSexualHarass', 
		'AlleSilSexualAssault', 
		'AlleSilIntimidatingWeapon', 
		'AlleSilIntimidatingWeaponType', 
		'AlleSilUnbecoming', 
		'AlleSilDiscourteous', 
		'AlleSilPropertyDamage', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
