<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCompliments extends Model
{
    protected $table      = 'OP_Compliments';
    protected $primaryKey = 'CompliID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'CompliPublicID', 
		'CompliUserID', 
		'CompliStatus', 
		'CompliType', 
		'CompliIncidentID', 
		'CompliSceneID', 
		'CompliPrivacy', 
		'CompliSummary', 
		'CompliHowHear', 
		'CompliFeedback', 
		'CompliSlug', 
		'CompliNotes', 
		'CompliRecordSubmitted', 
		'CompliSubmissionProgress', 
		'CompliVersionAB', 
		'CompliTreeVersion', 
		'CompliHoneyPot', 
		'CompliIsMobile', 
		'CompliUniqueStr', 
		'CompliIPaddy', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
