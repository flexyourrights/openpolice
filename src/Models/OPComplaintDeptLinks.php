<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPComplaintDeptLinks extends Model
{
    protected $table         = 'OP_ComplaintDeptLinks';
    protected $primaryKey     = 'CompDeptLinksID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'CompDeptLinksComplaintID', 
        'CompDeptLinksDeptID', 
    ];
}