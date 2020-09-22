<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksCivilianEvents extends Model
{
    protected $table      = 'op_links_civilian_events';
    protected $primaryKey = 'lnk_civ_eve_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'lnk_civ_eve_civ_id', 
		'lnk_civ_eve_eve_id', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
