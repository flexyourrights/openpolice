<?php

namespace OpenPolice\Models;

use Illuminate\Database\Eloquent\Model;

class OPCzVolunEditsOvers extends Model
{
	protected $table = 'OPC_zVolunEditsOvers';
	protected $primaryKey = 'EditOverID';
	public $timestamps = true;
	
	protected $fillable = [
		'EditOverEditDeptID', 
		'EditOverOnlineResearch', 
		'EditOverMadeDeptCall', 
		'EditOverMadeIACall', 
		'EditOverNotes', 
		'EditOverType', 
		'EditOverCivModel', 
		'EditOverUser', 
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
