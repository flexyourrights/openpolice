<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLDataLoop extends Model
{
    protected $table      = 'SL_DataLoop';
    protected $primaryKey = 'DataLoopID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'DataLoopTree', 
		'DataLoopRoot', 
		'DataLoopPlural', 
		'DataLoopSingular', 
		'DataLoopTable', 
		'DataLoopSortFld', 
		'DataLoopDoneFld', 
		'DataLoopMaxLimit', 
		'DataLoopWarnLimit', 
		'DataLoopMinLimit', 
		'DataLoopIsStep', 
		'DataLoopAutoGen', 
    ];
}
