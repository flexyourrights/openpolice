<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPAdministrators extends Model
{
    protected $table      = 'op_administrators';
    protected $primaryKey = 'adm_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'adm_user_id', 
		'adm_person_id', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
