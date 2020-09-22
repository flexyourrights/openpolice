<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPVisitors extends Model
{
    protected $table      = 'op_visitors';
    protected $primaryKey = 'vis_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'vis_version_ab', 
		'vis_submission_progress', 
		'vis_ip_addy', 
		'vis_tree_version', 
		'vis_unique_str', 
		'vis_user_id', 
		'vis_is_mobile', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
