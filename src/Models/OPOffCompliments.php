<?php namespace OpenPolice\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPOffCompliments extends Model
{
    protected $table      = 'OP_OffCompliments';
    protected $primaryKey = 'OffCompID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'OffCompOffID', 
		'OffCompValor', 
		'OffCompLifesaving', 
		'OffCompDeescalation', 
		'OffCompProfessionalism', 
		'OffCompFairness', 
		'OffCompConstitutional', 
		'OffCompCompassion', 
		'OffCompCommunity', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
