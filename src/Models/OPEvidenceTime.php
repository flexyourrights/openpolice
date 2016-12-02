<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPEvidenceTime extends Model
{
	protected $table 		= 'OP_EvidenceTime';
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