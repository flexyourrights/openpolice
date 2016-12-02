<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPPhysicalDescRace extends Model
{
	protected $table 		= 'OP_PhysicalDescRace';
	protected $primaryKey 	= 'PhysRaceID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'PhysRacePhysDescID', 
		'PhysRaceRace', 
	];
}