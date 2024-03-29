<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPScenes extends Model
{
    use Cachable;

    protected $table      = 'op_scenes';
    protected $primaryKey = 'scn_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'scn_is_vehicle', 
		'scn_type', 
		'scn_description', 
		'scn_forcible_entry', 
		'scn_cctv', 
		'scn_cctv_desc', 
		'scn_is_vehicle_accident', 
        'scn_how_feel', 
        'scn_desires_officers', 
        'scn_desires_officers_other', 
        'scn_desires_depts', 
        'scn_desires_depts_other', 
        'scn_attorney_first_name', 
        'scn_attorney_last_name',
        'scn_attorney_email',
        'scn_why_no_officers', 
        'scn_why_no_officers_other',
    ];
    
    // END Survloop auto-generated portion of Model
    
}
