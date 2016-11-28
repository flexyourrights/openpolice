<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCAdministrators extends Model
{
	protected $table 		= 'OPC_Administrators';
	protected $primaryKey 	= 'AdmID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'AdmUserID', 
		'AdmPersonID', 
		'AdmBio', 
	];
}