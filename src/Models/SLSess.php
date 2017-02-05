<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLSess extends Model
{
    protected $table      = 'SL_Sess';
    protected $primaryKey = 'SessID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'SessUserID', 
		'SessTree', 
		'SessCoreID', 
		'SessCurrNode', 
		'SessLoopRootJustLeft', 
		'SessAfterJumpTo', 
		'SessZoomPref', 
		'SessIsMobile', 
		'SessBrowser', 
    ];
}
