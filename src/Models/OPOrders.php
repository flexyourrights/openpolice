<?php namespace Storage\App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPOrders extends Model
{
    protected $table      = 'OP_Orders';
    protected $primaryKey = 'OrdID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'OrdEventSequenceID', 
		'OrdDescription', 
		'OrdTroubleUnderYN', 
		'OrdTroubleUnderstading', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
