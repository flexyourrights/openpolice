<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLSessLoops extends Model
{
    protected $table      = 'SL_SessLoops';
    protected $primaryKey = 'SessLoopID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'SessLoopSessID', 
		'SessLoopName', 
		'SessLoopItemID', 
    ];
}
