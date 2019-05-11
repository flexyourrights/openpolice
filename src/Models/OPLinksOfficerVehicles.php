<?php namespace OpenPolice\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksOfficerVehicles extends Model
{
    protected $table      = 'OP_LinksOfficerVehicles';
    protected $primaryKey = 'LnkOffVehicID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'LnkOffVehicOffID', 
		'LnkOffVehicVehicID', 
		'LnkOffVehicRole', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
