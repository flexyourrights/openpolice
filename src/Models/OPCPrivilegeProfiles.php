<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCPrivilegeProfiles extends Model
{
	protected $table 		= 'OPC_PrivilegeProfiles';
	protected $primaryKey 	= 'PrivID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'PrivUserID', 
		'PrivComplaintID', 
		'PrivDeptID', 
		'PrivAccessLevel', 
	];
}