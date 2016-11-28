<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCBodyParts extends Model
{
	protected $table 		= 'OPC_BodyParts';
	protected $primaryKey 	= 'BodyID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'BodyForceID', 
		'BodyInjuryID', 
		'BodyPart', 
	];
}