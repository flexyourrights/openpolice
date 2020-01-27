<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPStopReasons extends Model
{
    protected $table      = 'op_stop_reasons';
    protected $primaryKey = 'stop_reas_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'stop_reas_stop_id', 
		'stop_reas_reason', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
