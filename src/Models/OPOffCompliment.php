<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPOffCompliment extends Model
{
    protected $table      = 'OP_OffCompliment';
    protected $primaryKey = 'OffCompID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'OffCompComplimentID', 
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
