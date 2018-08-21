<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLNodeSaves extends Model
{
    protected $table         = 'SL_NodeSaves';
    protected $primaryKey     = 'NodeSaveID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'NodeSaveSession', 
        'NodeSaveLoopItemID', 
        'NodeSaveNode', 
        'NodeSaveVersionAB', 
        'NodeSaveTblFld', 
        'NodeSaveNewVal', 
    ];
}
