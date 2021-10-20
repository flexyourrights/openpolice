<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPzVolunEditsOvers extends Model
{
    use Cachable;

    protected $table = 'op_z_VolunEditsOvers';
    protected $primaryKey = 'EditOverID';
    public $timestamps = true;
    
    protected $fillable = [
        'EditOver_user', 
        'EditOver_edit_dept_id', 
        'EditOverOnlineResearch', 
        'EditOverMadeDeptCall', 
        'EditOverMadeIACall', 
        'EditOverNotes', 
        'EditOver_over_id', 
        'EditOverType', 
        'EditOverCivModel', 
        'EditOveruser_id', 
        'EditOver_dept_id', 
        'EditOverAgncName', 
        'EditOverVerified', 
        'EditOverNamePrefix', 
        'EditOverNameFirst', 
        'EditOverNickname', 
        'EditOverNameMiddle', 
        'EditOverNameLast', 
        'EditOverNameSuffix', 
        'EditOverTitle', 
        'EditOverIDnumber', 
        'EditOverWebsite', 
        'EditOverFacebook', 
        'EditOverTwitter', 
        'EditOverYouTube', 
        'EditOverHomepageComplaintLink', 
        'EditOverWebComplaintInfo', 
        'EditOverComplaintPDF', 
        'EditOverComplaintWebForm', 
        'EditOverEmail', 
        'EditOverKeepEmailPrivate', 
        'EditOverPhoneWork', 
        'EditOverAddress', 
        'EditOverAddress2', 
        'EditOverAddressCity', 
        'EditOverAddressState', 
        'EditOverAddressZip', 
        'EditOverSubmitDeadline', 
        'EditOverOfficialFormNotReq', 
        'EditOverOfficialAnon', 
        'EditOverWaySubOnline', 
        'EditOverWaySubEmail', 
        'EditOverWaySubVerbalPhone', 
        'EditOverWaySubPaperMail', 
        'EditOverWaySubPaperInPerson', 
        'EditOverWaySubNotary', 
    ];
    
}
