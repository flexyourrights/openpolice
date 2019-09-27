<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPComplaints extends Model
{
    protected $table      = 'OP_Complaints';
    protected $primaryKey = 'ComID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'ComUserID', 
		'ComStatus', 
		'ComType', 
		'ComIncidentID', 
		'ComSceneID', 
		'ComPrivacy', 
		'ComAwardMedallion', 
		'ComSummary', 
		'ComAllegList', 
		'ComTriedOtherWays', 
		'ComTriedOtherWaysDesc', 
		'ComOfficerInjured', 
		'ComOfficerInjuredDesc', 
		'ComAttorneyHas', 
		'ComAttorneyOKedOPC', 
		'ComAttorneyWant', 
		'ComAnyoneCharged', 
		'ComAllChargesResolved', 
		'ComUnresolvedChargesActions', 
		'ComFileLawsuit', 
		'ComHowHear', 
		'ComFeedback', 
		'ComOfficerDisciplined', 
		'ComOfficerDisciplineType', 
		'ComMediaLinks', 
		'ComAdminID', 
		'ComAttID', 
		'ComNotes', 
		'ComSlug', 
		'ComRecordSubmitted', 
		'ComSubmissionProgress', 
		'ComVersionAB', 
		'ComTreeVersion', 
		'ComHoneyPot', 
		'ComIsMobile', 
		'ComUniqueStr', 
		'ComIPaddy', 
		'ComPublicID', 
		'ComShareData', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
