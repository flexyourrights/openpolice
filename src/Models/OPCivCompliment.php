<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCivCompliment extends Model
{
    protected $table      = 'OP_CivCompliment';
    protected $primaryKey = 'CivCompID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'CivCompComplimentID', 
		'CivCompUserID', 
		'CivCompIsCreator', 
		'CivCompRole', 
		'CivCompPersonID', 
		'CivCompPhysDescID', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
