<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCInjuries extends Model
{
	protected $table 		= 'OPC_Injuries';
	protected $primaryKey 	= 'InjID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'InjSubjectID', 
		'InjType', 
		'InjHowManyTimes', 
		'InjDescription', 
	];
}