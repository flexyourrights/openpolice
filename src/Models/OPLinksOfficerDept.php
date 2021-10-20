<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPLinksOfficerDept extends Model
{
    use Cachable;

    protected $table      = 'op_links_officer_dept';
    protected $primaryKey = 'lnk_off_dept_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'lnk_off_dept_officer_id', 
		'lnk_off_dept_dept_id', 
		'lnk_off_dept_date_verified', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
