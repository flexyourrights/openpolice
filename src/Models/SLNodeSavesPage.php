<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLNodeSavesPage extends Model
{
    protected $table      = 'SL_NodeSavesPage';
    protected $primaryKey = 'PageSaveID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'PageSaveSession', 
		'PageSaveNode', 
		'PageSaveLoopItemID', 
    ];
}
