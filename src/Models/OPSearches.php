<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPSearches extends Model
{
    protected $table      = 'op_searches';
    protected $primaryKey = 'srch_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'srch_event_sequence_id', 
		'srch_stated_reason', 
		'srch_stated_reason_desc', 
		'srch_officer_request', 
		'srch_officer_request_desc', 
		'srch_subject_consent', 
		'srch_subject_say', 
		'srch_officer_threats', 
		'srch_officer_threats_desc', 
		'srch_strip', 
		'srch_strip_search_desc', 
		'srch_k9_sniff', 
		'srch_contraband_discovered', 
		'srch_officer_warrant', 
		'srch_officer_warrant_say', 
		'srch_seized', 
		'srch_seized_desc', 
		'srch_damage', 
		'srch_damage_desc', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
