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
		'CivUserID', 
		'CivIsCreator', 
		'CivRole', 
		'CivPersonID', 
		'CivPhysDescID', 
		'CivGiveName', 
		'CivGiveContactInfo', 
		'CivResident', 
		'CivOccupation', 
		'CivHadVehicle', 
		'CivChase', 
		'CivChaseType', 
		'CivVictimWhatWeapon', 
		'CivVictimUseWeapon', 
		'CivCameraRecord', 
		'CivUsedProfanity', 
		'CivHasInjury', 
		'CivHasInjuryCare', 
		'CivGivenCitation', 
		'CivGivenWarning', 
		'CivCitationNumber', 
		'CivChargesOther', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
