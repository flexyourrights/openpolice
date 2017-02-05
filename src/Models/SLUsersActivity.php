<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLUsersActivity extends Model
{
    protected $table      = 'SL_UsersActivity';
    protected $primaryKey = 'UserActID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'UserActUser', 
		'UserActCurrPage', 
		'UserActVal', 
    ];
}
