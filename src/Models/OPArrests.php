<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPArrests extends Model
{
    protected $table      = 'op_arrests';
    protected $primaryKey = 'arst_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'arst_event_sequence_id', 
		'arst_charges_filed', 
		'arst_stated_reason', 
		'arst_stated_reason_desc', 
		'arst_miranda', 
		'arst_sita', 
		'arst_no_charges_filed', 
		'arst_strip', 
		'arst_strip_search_desc', 
		'arst_charges_other', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
