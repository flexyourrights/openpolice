<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCivilians extends Model
{
    protected $table      = 'OP_Civilians';
    protected $primaryKey = 'CivID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'CivComplaintID', 
		'CivVictimUseWeapon', 
		'CivVictimHadWeapon', 
		'CivVictimWhatWeapon', 
		'CivUserID', 
		'CivIsCreator', 
		'CivRole', 
		'CivPersonID', 
		'CivPhysDescID', 
		'CivGiveName', 
		'CivGiveContactInfo', 
		'CivGivePhysDesc', 
		'CivHadVehicle', 
		'CivChase', 
		'CivInPreviousVehicle', 
		'CivResident', 
		'CivChaseType', 
		'CivCameraRecord', 
		'CivUsedProfanity', 
		'CivOccupation', 
		'CivGivenCitation', 
		'CivGivenWarning', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
