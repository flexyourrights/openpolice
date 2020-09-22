<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksComplimentDept extends Model
{
    protected $table      = 'op_links_compliment_dept';
    protected $primaryKey = 'lnk_compli_dept_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'lnk_compli_dept_compliment_id', 
		'lnk_compli_dept_dept_id', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
