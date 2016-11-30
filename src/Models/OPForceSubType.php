<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPForceSubType extends Model
{
	protected $table 		= 'OP_ForceSubType';
	protected $primaryKey 	= 'ForceSubID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'ForceSubForceID', 
		'ForceSubType', 
	];
}