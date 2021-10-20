<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPPrivilegeProfiles extends Model
{
    use Cachable;

    protected $table      = 'op_privilege_profiles';
    protected $primaryKey = 'priv_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'priv_user_id', 
		'priv_complaint_id', 
		'priv_dept_id', 
		'priv_access_level', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
