<?php namespace OpenPolice\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPZeditDepartments extends Model
{
    protected $table      = 'OP_Zedit_Departments';
    protected $primaryKey = 'ZedDeptID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'ZedDeptUserID', 
		'ZedDeptDuration', 
		'ZedDeptDeptID', 
		'ZedDeptDeptName', 
		'ZedDeptDeptSlug', 
		'ZedDeptDeptType', 
		'ZedDeptDeptStatus', 
		'ZedDeptDeptVerified', 
		'ZedDeptDeptEmail', 
		'ZedDeptDeptPhoneWork', 
		'ZedDeptDeptAddress', 
		'ZedDeptDeptAddress2', 
		'ZedDeptDeptAddressCity', 
		'ZedDeptDeptAddressState', 
		'ZedDeptDeptAddressZip', 
		'ZedDeptDeptAddressCounty', 
		'ZedDeptDeptScoreOpenness', 
		'ZedDeptDeptTotOfficers', 
		'ZedDeptDeptJurisdictionPopulation', 
		'ZedDeptDeptJurisdictionGPS', 
		'ZedDeptDeptUserID', 
		'ZedDeptDeptSubmissionProgress', 
		'ZedDeptDeptTreeVersion', 
		'ZedDeptDeptVersionAB', 
		'ZedDeptDeptUniqueStr', 
		'ZedDeptDeptIPaddy', 
		'ZedDeptDeptIsMobile', 
		'ZedDeptDeptAddressLat', 
		'ZedDeptDeptAddressLng', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
