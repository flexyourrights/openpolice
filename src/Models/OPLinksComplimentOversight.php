<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksComplimentOversight extends Model
{
    protected $table      = 'op_links_compliment_oversight';
    protected $primaryKey = 'lnk_compli_over_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'lnk_compli_over_compliment_id', 
		'lnk_compli_over_dept_id', 
		'lnk_compli_over_over_id', 
		'lnk_compli_over_submitted', 
		'lnk_compli_over_received', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
