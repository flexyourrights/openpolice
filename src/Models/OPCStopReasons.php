<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCStopReasons extends Model
{
	protected $table 		= 'OPC_StopReasons';
	protected $primaryKey 	= 'StopReasID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'StopReasStopID', 
		'StopReasReason', 
	];
}