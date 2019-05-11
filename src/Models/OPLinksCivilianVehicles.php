<?php namespace Storage\App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksCivilianVehicles extends Model
{
    protected $table      = 'OP_LinksCivilianVehicles';
    protected $primaryKey = 'LnkCivVehicID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'LnkCivVehicCivID', 
		'LnkCivVehicVehicID', 
		'LnkCivVehicRole', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
