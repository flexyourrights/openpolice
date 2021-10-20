<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPEventSequence extends Model
{
    use Cachable;

    protected $table      = 'op_event_sequence';
    protected $primaryKey = 'eve_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'eve_complaint_id', 
		'eve_type', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
