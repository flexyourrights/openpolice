<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPDeptStats extends Model
{
    protected $table      = 'op_dept_stats';
    protected $primaryKey = 'dept_stat_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'dept_stat_dept_id', 
		'dept_stat_submitted_op', 
		'dept_stat_attorneys', 
        'dept_stat_ok_to_file',
		'dept_stat_investigate_submitted', 
		'dept_stat_investigate_received', 
		'dept_stat_investigate_no_response', 
		'dept_stat_investgated', 
		'dept_stat_investigate_declined', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
