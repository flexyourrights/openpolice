<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPInjuries extends Model
{
    protected $table      = 'op_injuries';
    protected $primaryKey = 'inj_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'inj_subject_id', 
		'inj_type', 
		'inj_how_many_times', 
		'inj_description', 
		'inj_done', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
