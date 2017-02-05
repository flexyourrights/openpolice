<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLConditions extends Model
{
    protected $table      = 'SL_Conditions';
    protected $primaryKey = 'CondID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'CondDatabase', 
		'CondTag', 
		'CondDesc', 
		'CondOperator', 
		'CondOperDeet', 
		'CondField', 
		'CondTable', 
		'CondLoop', 
		'CondOpts', 
    ];
}
