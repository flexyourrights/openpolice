<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPDepartments extends Model
{
    protected $table      = 'OP_Departments';
    protected $primaryKey = 'DeptID';
    public $timestamps    = true;
    protected $fillable   = 
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
