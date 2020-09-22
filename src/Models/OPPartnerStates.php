<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPPartnerStates extends Model
{
    protected $table      = 'op_partner_states';
    protected $primaryKey = 'prt_sta_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'prt_sta_part_id', 
		'prt_sta_state', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
