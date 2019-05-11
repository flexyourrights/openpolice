<?php namespace OpenPolice\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPForceCivBody extends Model
{
    protected $table      = 'OP_ForceCivBody';
    protected $primaryKey = 'ForceCivID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'ForceCivForceID', 
		'ForceCivBodyWeapon', 
    ];
}
