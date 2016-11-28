<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCComplaintOversight extends Model
{
	protected $table 		= 'OPC_ComplaintOversight';
	protected $primaryKey 	= 'CompOversightID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'CompOversightComplaintID', 
		'CompOversightOverID', 
		'CompOversightSubmitted', 
		'CompOversightStillNoResponse', 
		'CompOversightReceived', 
		'CompOversightInvestigating', 
		'CompOversightReportDate', 
		'CompOversightOversightReportEvidenceID', 
		'CompOversightAgencyComplaintNumber', 
	];
}