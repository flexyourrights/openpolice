<?php namespace OpenPolice\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPAllegations extends Model
{
    protected $table      = 'OP_Allegations';
    protected $primaryKey = 'AlleID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'AlleComplaintID', 
		'AlleType', 
		'AlleEventSequenceID', 
		'AlleDescription', 
		'AlleFindings', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
