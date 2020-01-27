<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCharges extends Model
{
    protected $table      = 'op_charges';
    protected $primaryKey = 'chrg_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'chrg_civ_id', 
		'chrg_charges', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
