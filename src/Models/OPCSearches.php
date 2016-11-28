<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCSearches extends Model
{
	protected $table 		= 'OPC_Searches';
	protected $primaryKey 	= 'SrchID';
	public $timestamps 		= true;
	protected $fillable 	= 
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
}