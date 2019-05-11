<?php namespace Storage\App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPVehicles extends Model
{
    protected $table      = 'OP_Vehicles';
    protected $primaryKey = 'VehicID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'VehicComplaintID', 
		'VehicIsCivilian', 
		'VehicTransportation', 
		'VehicUnmarked', 
		'VehicVehicleMake', 
		'VehicVehicleModel', 
		'VehicVehicleDesc', 
		'VehicVehicleLicence', 
		'VehicVehicleNumber', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
