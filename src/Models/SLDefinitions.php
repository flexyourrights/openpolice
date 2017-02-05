<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLDefinitions extends Model
{
    protected $table      = 'SL_Definitions';
    protected $primaryKey = 'DefID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'DefDatabase', 
		'DefSet', 
		'DefSubset', 
		'DefOrder', 
		'DefIsActive', 
		'DefValue', 
		'DefDescription', 
    ];
}
