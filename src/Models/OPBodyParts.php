<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPBodyParts extends Model
{
    protected $table      = 'op_body_parts';
    protected $primaryKey = 'body_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'body_force_id', 
		'body_injury_id', 
		'body_part', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
