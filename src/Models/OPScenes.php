<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPScenes extends Model
{
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
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
