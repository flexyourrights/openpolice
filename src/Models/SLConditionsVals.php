<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLConditionsVals extends Model
{
    protected $table      = 'SL_ConditionsVals';
    protected $primaryKey = 'CondValID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'CondValCondID', 
		'CondValValue', 
    ];
}
