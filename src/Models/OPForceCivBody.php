<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPForceCivBody extends Model
{
    protected $table      = 'op_force_civ_body';
    protected $primaryKey = 'force_civ_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'force_civ_force_id', 
		'force_civ_body_weapon', 
    ];
}
