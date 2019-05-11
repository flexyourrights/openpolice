<?php namespace Storage\App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPInjuries extends Model
{
    protected $table      = 'OP_Injuries';
    protected $primaryKey = 'InjID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'InjSubjectID', 
		'InjType', 
		'InjHowManyTimes', 
		'InjDescription', 
		'InjDone', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
