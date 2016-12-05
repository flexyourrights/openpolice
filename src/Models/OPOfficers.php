<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPOfficers extends Model
{
    protected $table         = 'OP_Officers';
    protected $primaryKey     = 'OffID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'OffIsVerified', 
        'OffComplaintID', 
        'OffRole', 
        'OffDeptID', 
        'OffPersonID', 
        'OffPhysDescID', 
        'OffGiveName', 
        'OffGivePhysDesc', 
        'OffHadVehicle', 
        'OffInPreviousVehicle', 
        'OffPrecinct', 
        'OffBadgeNumber', 
        'OffIDnumber', 
        'OffOfficerRank', 
        'OffDashCam', 
        'OffBodyCam', 
        'OffDutyStatus', 
        'OffAdditionalDetails', 
    ];
}