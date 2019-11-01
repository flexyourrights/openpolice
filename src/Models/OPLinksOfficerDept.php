<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksOfficerDept extends Model
{
    protected $table      = 'OP_LinksOfficerDept';
    protected $primaryKey = 'LnkOffDeptID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'LnkOffDeptOfficerID', 
		'LnkOffDeptDeptID', 
		'LnkOffDeptDateVerified', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
