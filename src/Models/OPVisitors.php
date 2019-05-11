<?php namespace Storage\App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPVisitors extends Model
{
    protected $table      = 'OP_Visitors';
    protected $primaryKey = 'VisID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'VisVersionAB', 
		'VisSubmissionProgress', 
		'VisIPaddy', 
		'VisTreeVersion', 
		'VisUniqueStr', 
		'VisUserID', 
		'VisIsMobile', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
