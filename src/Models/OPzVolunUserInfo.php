<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OPzVolunUserInfo extends Model
{
    protected $table = 'OP_zVolunUserInfo';
    protected $primaryKey = 'UserInfoID';
    public $timestamps = true;
    
    protected $fillable = [
        'UserInfoUserID', 
        'UserInfoPersonContactID', 
        'UserInfoStars', 
        'UserInfoStars1', 
        'UserInfoStars2', 
        'UserInfoStars3', 
        'UserInfoDepts', 
        'UserInfoAvgTimeDept', 
    ];
    
}
