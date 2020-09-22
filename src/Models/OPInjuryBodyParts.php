<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPInjuryBodyParts extends Model
{
    protected $table      = 'op_injury_body_parts';
    protected $primaryKey = 'inj_bdy_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'inj_bdy_injury_id', 
		'inj_bdy_part', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
