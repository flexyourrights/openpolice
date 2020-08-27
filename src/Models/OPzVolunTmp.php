<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OPzVolunTmp extends Model
{
    protected $table = 'op_z_volun_tmp';
    protected $primaryKey = 'TmpID';
    public $timestamps = true;
    
    protected $fillable = [
        'TmpUser', 
        'TmpType', 
        'TmpVal', 
        'TmpDate'
    ];
    
    
}
