<?php namespace Storage\App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPEvidenceTime extends Model
{
    protected $table      = 'OP_EvidenceTime';
    protected $primaryKey = 'EviTimID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'EviTimAllegationID', 
		'EviTimEvidenceID', 
		'EviTimTimestamp', 
		'EviTimDescription', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
