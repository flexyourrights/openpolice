<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OPzComplaintReviews extends Model
{
    protected $table = 'OP_zComplaintReviews';
    protected $primaryKey = 'ComRevID';
    
    protected $fillable = [
        'ComRevComplaint', 
        'ComRevUser', 
        'ComRevDate', 
        'ComRevType', 
        'ComRevStatus', 
        'ComRevNotAnon', 
        'ComRevOneIncident', 
        'ComRevCivilianContact', 
        'ComRevOneOfficer', 
        'ComRevOneAllegation', 
        'ComRevEvidenceUpload', 
        'ComRevEnglishSkill', 
        'ComRevReadability', 
        'ComRevConsistency', 
        'ComRevRealistic', 
        'ComRevOutrage', 
        'ComRevMakeFeatured', 
        'ComRevExplicitLang', 
        'ComRevGraphicContent', 
        'ComRevComplaintType', 
        'ComRevNextAction', 
        'ComRevNote', 
    ];
    
    
}
