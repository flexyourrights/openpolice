<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLDesignTweaks extends Model
{
    protected $table      = 'SL_DesignTweaks';
    protected $primaryKey = 'TweakID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'TweakVersionAB', 
		'TweakSubmissionProgress', 
		'TweakUserID', 
    ];
}
