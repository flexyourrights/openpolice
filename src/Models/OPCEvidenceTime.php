<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCEvidenceTime extends Model
{
	protected $table 		= 'OPC_EvidenceTime';
	protected $primaryKey 	= 'EviTimID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'EviTimAllegationID', 
		'EviTimEvidenceID', 
		'EviTimTimestamp', 
		'EviTimDescription', 
	];
}