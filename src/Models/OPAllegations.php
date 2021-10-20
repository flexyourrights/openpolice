<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPAllegations extends Model
{
    use Cachable;

    protected $table      = 'op_allegations';
    protected $primaryKey = 'alle_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'alle_complaint_id', 
		'alle_type', 
		'alle_event_sequence_id', 
		'alle_description', 
		'alle_findings', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
