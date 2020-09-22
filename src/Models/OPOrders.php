<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPOrders extends Model
{
    protected $table      = 'op_Orders';
    protected $primaryKey = 'OrdID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'Ord_event_sequence_id', 
		'OrdDescription', 
		'OrdTroubleUnder_yn', 
		'OrdTroubleUnderstading', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
