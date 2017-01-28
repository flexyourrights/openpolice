<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPBodyParts extends Model
{
    protected $table      = 'OP_BodyParts';
    protected $primaryKey = 'BodyID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'BodyForceID', 
		'BodyInjuryID', 
		'BodyPart', 
    ];
}
