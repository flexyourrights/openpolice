<?php namespace OpenPolice\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPzComplaintReviews extends Model
{
    protected $table      = 'OP_zComplaintReviews';
    protected $primaryKey = 'ComRevID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'ComRevComplaint', 
		'ComRevUser', 
		'ComRevDate', 
		'ComRevType', 
		'ComRevComplaintType', 
		'ComRevStatus', 
		'ComRevNext Action', 
		'ComRevNote', 
		'ComRevOneIncident', 
		'ComRevCivilianContact', 
		'ComRevOneOfficer', 
		'ComRevOneAllegation', 
		'ComRevEvidenceUploaded', 
		'ComRevEnglishSkill', 
		'ComRevReadability', 
		'ComRevConsistency', 
		'ComRevRealistic', 
		'ComRevOutrage', 
		'ComRevExplicitLang', 
		'ComRevGraphicContent', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
