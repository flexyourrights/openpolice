<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPEvidenceTime extends Model
{
    protected $table      = 'op_evidence_time';
    protected $primaryKey = 'evi_tim_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'evi_tim_allegation_id', 
		'evi_tim_evidence_id', 
		'evi_tim_timestamp', 
		'evi_tim_description', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
