<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPComplaintDeptLinks extends Model
{
    use Cachable;

    protected $table         = 'op_complaint_dept_links';
    protected $primaryKey     = 'comp_dept_links_id';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'comp_dept_links_complaint_id', 
        'comp_dept_links_dept_id', 
    ];
}