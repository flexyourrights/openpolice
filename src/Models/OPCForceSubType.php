<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCForceSubType extends Model
{
	protected $table 		= 'OPC_ForceSubType';
	protected $primaryKey 	= 'ForceSubID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'ForceSubForceID', 
		'ForceSubType', 
	];
}