<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPOversight extends Model
{
    protected $table      = 'OP_Oversight';
    protected $primaryKey = 'OverID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'OverType', 
		'OverUserID', 
		'OverDeptID', 
		'OverAgncName', 
		'OverVerified', 
		'OverNamePrefix', 
		'OverNameFirst', 
		'OverNickname', 
		'OverNameMiddle', 
		'OverNameLast', 
		'OverNameSuffix', 
		'OverTitle', 
		'OverIDnumber', 
		'OverWebsite', 
		'OverFacebook', 
		'OverTwitter', 
		'OverYouTube', 
		'OverHomepageComplaintLink', 
		'OverWebComplaintInfo', 
		'OverComplaintPDF', 
		'OverComplaintWebForm', 
		'OverEmail', 
		'OverPhoneWork', 
		'OverAddress', 
		'OverAddress2', 
		'OverAddressCity', 
		'OverAddressState', 
		'OverAddressZip', 
		'OverSubmitDeadline', 
		'OverOfficialFormNotReq', 
		'OverOfficialAnon', 
		'OverWaySubOnline', 
		'OverWaySubEmail', 
		'OverWaySubVerbalPhone', 
		'OverWaySubPaperMail', 
		'OverWaySubPaperInPerson', 
		'OverWaySubNotary', 
		'OverKeepEmailPrivate', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
