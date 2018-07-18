<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPInjuryBodyParts extends Model
{
    protected $table      = 'OP_InjuryBodyParts';
    protected $primaryKey = 'InjBdyID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'InjBdyInjuryID', 
		'InjBdyPart', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
