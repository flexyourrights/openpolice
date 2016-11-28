<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCLinksComplaintOversight extends Model
{
	protected $table 		= 'OPC_LinksComplaintOversight';
	protected $primaryKey 	= 'LnkComOverID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'LnkComOverComplaintID', 
		'LnkComOverOverID', 
		'LnkComOverSubmitted', 
		'LnkComOverStillNoResponse', 
		'LnkComOverReceived', 
		'LnkComOverInvestigating', 
		'LnkComOverReportDate', 
		'LnkComOverOversightReportEvidenceID', 
		'LnkComOverAgencyComplaintNumber', 
	];
}