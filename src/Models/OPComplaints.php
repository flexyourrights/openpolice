<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPComplaints extends Model
{
	protected $table 		= 'OP_Complaints';
	protected $primaryKey 	= 'ComID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'ComStatus', 
		'ComType', 
		'ComIsCompliment', 
		'ComSubmissionProgress', 
		'ComTreeVersion', 
		'ComABtests', 
		'ComIncidentID', 
		'ComAdminID', 
		'ComPrivacy', 
		'ComAwardMedallion', 
		'ComSummary', 
		'ComSummaryPublic', 
		'ComHeadline', 
		'ComTriedOtherWays', 
		'ComTriedOtherWaysDesc', 
		'ComAttorneyHas', 
		'ComAttorneyOKedOPC', 
		'ComAttorneyWant', 
		'ComAnyoneCharged', 
		'ComAllChargesResolved', 
		'ComUnresolvedChargesActions', 
		'ComLegalAidHelp', 
		'ComGovtInvestigation', 
		'ComGovtInvestigationWhyNot', 
		'ComHowHear', 
		'ComSlug', 
		'ComOfficerDisciplined', 
		'ComOfficerDisciplineType', 
		'ComMediaLinks', 
		'ComAttID', 
		'ComNotes', 
		'ComRecordSubmitted', 
		'ComNumberOfSessions', 
		'ComSessionSeconds', 
		'ComHoneyPot', 
		'ComUniqueStr', 
		'ComIPaddy', 
	];
}