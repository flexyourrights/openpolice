<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksCivilianVehicles extends Model
{
	protected $table 		= 'OP_LinksCivilianVehicles';
	protected $primaryKey 	= 'LnkCivVehicID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'LnkCivVehicCivID', 
		'LnkCivVehicVehicID', 
		'LnkCivVehicRole', 
	];
}