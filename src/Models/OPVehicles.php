<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPVehicles extends Model
{
    protected $table      = 'op_vehicles';
    protected $primaryKey = 'vehic_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'vehic_complaint_id', 
		'vehic_is_civilian', 
		'vehic_transportation', 
		'vehic_unmarked', 
		'vehic_vehicle_make', 
		'vehic_vehicle_model', 
		'vehic_vehicle_desc', 
		'vehic_vehicle_licence', 
		'vehic_vehicle_number', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
