<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OPzVolunEditsDepts extends Model
{
    protected $table = 'op_z_VolunEditsDepts';
    protected $primaryKey = 'EditDeptID';
    public $timestamps = true;
    
    protected $fillable = [
        'EditDeptUser',
        'EditDeptPageTime',
        'EditDept_dept_id', 
        'EditDeptName', 
        'EditDeptSlug', 
        'EditDeptType', 
        'EditDeptStatus', 
        'EditDeptVerified', 
        'EditDeptEmail', 
        'EditDeptPhoneWork', 
        'EditDeptAddress', 
        'EditDeptAddress2', 
        'EditDeptAddressCity', 
        'EditDeptAddressState', 
        'EditDeptAddressZip', 
        'EditDeptAddressCounty', 
        'EditDeptScoreOpenness', 
        'EditDeptTotOfficers', 
        'EditDeptJurisdictionPopulation', 
        'EditDeptJurisdictionGPS'
    ];
    
    
}
