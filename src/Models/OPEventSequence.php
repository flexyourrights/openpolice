<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPEventSequence extends Model
{
    protected $table      = 'op_event_sequence';
    protected $primaryKey = 'eve_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'eve_complaint_id', 
		'eve_type', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
