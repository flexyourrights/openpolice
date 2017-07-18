<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPSearches extends Model
{
    protected $table      = 'OP_Searches';
    protected $primaryKey = 'SrchID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'SrchEventSequenceID', 
		'SrchStatedReason', 
		'SrchStatedReasonDesc', 
		'SrchOfficerRequest', 
		'SrchOfficerRequestDesc', 
		'SrchSubjectConsent', 
		'SrchSubjectSay', 
		'SrchOfficerThreats', 
		'SrchOfficerThreatsDesc', 
		'SrchStrip', 
		'SrchStripSearchDesc', 
		'SrchK9sniff', 
		'SrchContrabandDiscovered', 
		'SrchOfficerWarrant', 
		'SrchOfficerWarrantSay', 
		'SrchSeized', 
		'SrchSeizedDesc', 
		'SrchDamage', 
		'SrchDamageDesc', 
		'SrchAllegWrongfulSearch', 
		'SrchAllegWrongfulProperty', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
