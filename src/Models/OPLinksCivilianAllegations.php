<?php namespace OpenPolice\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksCivilianAllegations extends Model
{
    protected $table      = 'OP_LinksCivilianAllegations';
    protected $primaryKey = 'LnkCivAlleID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'LnkCivAlleCivID', 
		'LnkCivAlleAlleID', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
