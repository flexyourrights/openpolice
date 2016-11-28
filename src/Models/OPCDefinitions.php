<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCDefinitions extends Model
{
	protected $table 		= 'OPC_Definitions';
	protected $primaryKey 	= 'DefID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'DefSet', 
		'DefSubset', 
		'DefIsActive', 
		'DefOrder', 
		'DefValue', 
		'DefDescription', 
	];
}