<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPPartners extends Model
{
    protected $table      = 'OP_Partners';
    protected $primaryKey = 'PartID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'PartType', 
		'PartStatus', 
		'PartUserID', 
		'PartPersonID', 
		'PartBio', 
		'PartSlug', 
		'PartCompanyName', 
		'PartTitle', 
		'PartCompanyWebsite', 
		'PartBioUrl', 
		'PartPhotoUrl', 
		'PartAlerts', 
		'PartVersionAB', 
		'PartSubmissionProgress', 
		'PartIPaddy', 
		'PartTreeVersion', 
		'PartUniqueStr', 
		'PartIsMobile', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
