<?php

namespace OpenPolice\Models;

use Illuminate\Database\Eloquent\Model;

class OPCzVolunEditsDepts extends Model
{
	protected $table = 'OPC_zVolunEditsDepts';
	protected $primaryKey = 'EditDeptID';
	public $timestamps = true;
	
	protected $fillable = [
		'EditDeptName', 'EditDeptSlug', 'EditDeptType', 'EditDeptStatus', 'EditDeptVerified', 'EditDeptEmail', 'EditDeptPhoneWork', 
		'EditDeptAddress', 'EditDeptAddress2', 'EditDeptAddressCity', 'EditDeptAddressState', 'EditDeptAddressZip', 'EditDeptAddressCounty', 
		'EditDeptScoreOpenness', 'EditDeptTotOfficers', 'EditDeptJurisdictionPopulation', 'EditDeptJurisdictionGPS'
	];
    
    
}
