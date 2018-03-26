<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksComplaintOversight extends Model
{
    protected $table      = 'OP_LinksComplaintOversight';
    protected $primaryKey = 'LnkComOverID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'LnkComOverComplaintID', 
		'LnkComOverOverID', 
		'LnkComOverSubmitted', 
		'LnkComOverStillNoResponse', 
		'LnkComOverReceived', 
		'LnkComOverInvestigated', 
		'LnkComOverReportDate', 
		'LnkComOverOversightReportEvidenceID', 
		'LnkComOverAgencyComplaintNumber', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
