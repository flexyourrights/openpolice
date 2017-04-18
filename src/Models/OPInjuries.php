<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPInjuries extends Model
{
    protected $table      = 'OP_Injuries';
    protected $primaryKey = 'InjID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
        'InjComplaintID', 
		'InjSubjectID', 
		'InjType', 
		'InjHowManyTimes', 
		'InjDescription', 
    ];
}
