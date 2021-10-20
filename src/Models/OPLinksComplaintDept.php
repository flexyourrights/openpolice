<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPLinksComplaintDept extends Model
{
    use Cachable;

    protected $table      = 'op_links_complaint_dept';
    protected $primaryKey = 'lnk_com_dept_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'lnk_com_dept_complaint_id', 
		'lnk_com_dept_dept_id', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
