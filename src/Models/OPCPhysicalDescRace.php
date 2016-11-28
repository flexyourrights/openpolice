<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCPhysicalDescRace extends Model
{
	protected $table 		= 'OPC_PhysicalDescRace';
	protected $primaryKey 	= 'PhysRaceID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'PhysRacePhysDescID', 
		'PhysRaceRace', 
	];
}