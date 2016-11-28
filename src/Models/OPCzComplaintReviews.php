<?php

namespace OpenPolice\Models;

use Illuminate\Database\Eloquent\Model;

class OPCzComplaintReviews extends Model
{
	protected $table = 'OPC_zComplaintReviews';
	protected $primaryKey = 'ComRevID';
	
	protected $fillable = [
		'ComRevComplaint', 'ComRevUser', 'ComRevDate', 'ComRevType', 'ComRevStatus', 
		'ComRevNotAnon', 'ComRevOneIncident', 'ComRevCivilianContact', 'ComRevOneOfficer', 'ComRevOneAllegation', 'ComRevEvidenceUpload', 
		'ComRevEnglishSkill', 'ComRevReadability', 'ComRevConsistency', 'ComRevRealistic', 'ComRevOutrage', 
		'ComRevMakeFeatured', 'ComRevExplicitLang', 'ComRevGraphicContent', 
		'ComRevComplaintType', 'ComRevNextAction', 
		'ComRevNote', 
	];
    
    
}
