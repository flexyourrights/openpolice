<?php namespace OpenPolice\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPPartnerStates extends Model
{
    protected $table      = 'OP_PartnerStates';
    protected $primaryKey = 'PrtStaID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'PrtStaPartID', 
		'PrtStaState', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
