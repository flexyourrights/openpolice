<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLDataSubsets extends Model
{
    protected $table         = 'SL_DataSubsets';
    protected $primaryKey     = 'DataSubID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'DataSubTree', 
        'DataSubTbl', 
        'DataSubTblLnk', 
        'DataSubSubTbl', 
        'DataSubSubLnk', 
        'DataSubAutoGen', 
    ];
}
