<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLUsersRoles extends Model
{
    protected $table      = 'SL_UsersRoles';
    protected $primaryKey = 'RoleUserID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'RoleUserUID', 
		'RoleUserRID', 
    ];
}
