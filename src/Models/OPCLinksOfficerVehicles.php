<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCLinksOfficerVehicles extends Model
{
	protected $table 		= 'OPC_LinksOfficerVehicles';
	protected $primaryKey 	= 'LnkOffVehicID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'LnkOffVehicOffID', 
		'LnkOffVehicVehicID', 
		'LnkOffVehicRole', 
	];
}