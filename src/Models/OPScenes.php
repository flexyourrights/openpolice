<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPScenes extends Model
{
	protected $table 		= 'OP_Scenes';
	protected $primaryKey 	= 'ScnID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'ScnComplaintID', 
		'ScnIsVehicle', 
		'ScnType', 
		'ScnDescription', 
		'ScnForcibleEntry', 
		'ScnCCTV', 
		'ScnCCTVDesc', 
	];
}