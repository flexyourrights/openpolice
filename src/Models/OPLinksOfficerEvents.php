<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksOfficerEvents extends Model
{
    protected $table      = 'op_links_officer_events';
    protected $primaryKey = 'lnk_off_eve_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'lnk_off_eve_off_id', 
		'lnk_off_eve_eve_id', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
