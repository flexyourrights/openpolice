<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCAllegations extends Model
{
	protected $table 		= 'OPC_Allegations';
	protected $primaryKey 	= 'AlleID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'AlleComplaintID', 
		'AlleType', 
		'AlleEventSequenceID', 
		'AlleDescription', 
		'AlleFindings', 
	];
}