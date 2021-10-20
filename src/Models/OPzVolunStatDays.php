<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPzVolunStatDays extends Model
{
	use Cachable;

    protected $table      = 'op_z_volun_stat_days';
    protected $primaryKey = 'volun_stat_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'volun_stat_date', 
		'volun_stat_signups', 
		'volun_stat_logins', 
		'volun_stat_users_unique', 
		'volun_stat_depts_unique', 
		'volun_stat_online_research', 
		'volun_stat_calls_dept', 
		'volun_stat_calls_ia', 
		'volun_stat_calls_tot', 
		'volun_stat_total_edits', 
		'volun_stat_online_research_v', 
		'volun_stat_calls_dept_v', 
		'volun_stat_calls_ia_v', 
		'volun_stat_calls_tot_v', 
		'volun_stat_total_edits_v', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
