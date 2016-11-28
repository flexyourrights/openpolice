<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCEventSequence extends Model
{
	protected $table 		= 'OPC_EventSequence';
	protected $primaryKey 	= 'EveID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'EveComplaintID', 
		'EveOrder', 
		'EveType', 
		'EveUserFinished', 
	];
}