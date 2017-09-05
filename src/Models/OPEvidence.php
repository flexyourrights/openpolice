<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPEvidence extends Model
{
    protected $table      = 'OP_Evidence';
    protected $primaryKey = 'EvidID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'EvidComplaintID', 
		'EvidComplimentID', 
		'UpType', 
		'UpPrivacy', 
		'EvidDateTime', 
		'UpTitle', 
		'UpDesc', 
		'UpUploadFile', 
		'UpStoredFile', 
		'UpVideoLink', 
		'UpVideoDuration', 
		'EvidCivilianID', 
		'EvidDeptID', 
		'EvidSceneID', 
		'EvidEventSequenceID', 
		'EvidInjuryID', 
		'EvidNoteID', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
