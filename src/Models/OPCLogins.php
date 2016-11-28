<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCLogins extends Model
{
	protected $table 		= 'OPC_Logins';
	protected $primaryKey 	= 'LogLogID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'LogLogUserID', 
		'LogLogTimestamp', 
		'LogLogAction', 
		'LogLogTotAttempts', 
		'LogLogIP', 
	];
}