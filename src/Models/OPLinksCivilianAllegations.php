<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksCivilianAllegations extends Model
{
    protected $table      = 'op_links_civilian_allegations';
    protected $primaryKey = 'lnk_civ_alle_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'lnk_civ_alle_civ_id', 
		'lnk_civ_alle_alle_id', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
