<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPAdminActions extends Model
{
    protected $table      = 'op_admin_actions';
    protected $primaryKey = 'adm_act_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'adm_act_user_id', 
		'adm_act_table', 
		'adm_act_record_id', 
		'adm_act_old_data', 
		'adm_act_new_data', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
