<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCVehiclePersons extends Model
{
	protected $table 		= 'OPC_VehiclePersons';
	protected $primaryKey 	= 'VehicPersID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'VehicPersVehicID', 
		'VehicPersCivID', 
		'VehicPersOffID', 
		'VehicPersRole', 
	];
}