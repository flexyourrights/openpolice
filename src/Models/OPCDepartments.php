<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCDepartments extends Model
{
	protected $table 		= 'OPC_Departments';
	protected $primaryKey 	= 'DeptID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'DeptName', 
		'DeptSlug', 
		'DeptType', 
		'DeptStatus', 
		'DeptVerified', 
		'DeptEmail', 
		'DeptPhoneWork', 
		'DeptAddress', 
		'DeptAddress2', 
		'DeptAddressCity', 
		'DeptAddressState', 
		'DeptAddressZip', 
		'DeptAddressCounty', 
		'DeptScoreOpenness', 
		'DeptTotOfficers', 
		'DeptJurisdictionPopulation', 
		'DeptJurisdictionGPS', 
	];
}