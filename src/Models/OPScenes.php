<?php namespace Storage\App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPScenes extends Model
{
    protected $table      = 'OP_Scenes';
    protected $primaryKey = 'ScnID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'ScnIsVehicle', 
		'ScnType', 
		'ScnDescription', 
		'ScnForcibleEntry', 
		'ScnCCTV', 
		'ScnCCTVDesc', 
		'ScnIsVehicleAccident', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
