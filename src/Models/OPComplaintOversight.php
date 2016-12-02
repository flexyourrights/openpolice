<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPComplaintOversight extends Model
{
	protected $table 		= 'OP_ComplaintOversight';
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