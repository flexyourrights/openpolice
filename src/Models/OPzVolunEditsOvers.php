<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OPzVolunEditsOvers extends Model
{
    protected $table = 'OP_zVolunEditsOvers';
    protected $primaryKey = 'EditOverID';
    public $timestamps = true;
    
    protected $fillable = [
        'EditOverUser', 
        'EditOverEditDeptID', 
        'EditOverOnlineResearch', 
        'EditOverMadeDeptCall', 
        'EditOverMadeIACall', 
        'EditOverNotes', 
        'EditOverOverID', 
        'EditOverType', 
        'EditOverCivModel', 
        'EditOverUserID', 
        'EditOverDeptID', 
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
