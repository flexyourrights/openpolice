<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPAdministrators extends Model
{
	protected $table 		= 'OP_Administrators';
	protected $primaryKey 	= 'AdmID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'AdmUserID', 
		'AdmPersonID', 
		'AdmBio', 
	];
}