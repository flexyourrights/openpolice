<?php namespace Storage\App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPOfficers extends Model
{
    protected $table      = 'OP_Officers';
    protected $primaryKey = 'OffID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'OffIsVerified', 
		'OffComplaintID', 
		'OffRole', 
		'OffDeptID', 
		'OffPersonID', 
		'OffPhysDescID', 
		'OffGiveName', 
		'OffHadVehicle', 
		'OffPrecinct', 
		'OffBadgeNumber', 
		'OffIDnumber', 
		'OffOfficerRank', 
		'OffDashCam', 
		'OffBodyCam', 
		'OffDutyStatus', 
		'OffUniform', 
		'OffUsedProfanity', 
		'OffAdditionalDetails', 
		'OffGaveCompliment', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
