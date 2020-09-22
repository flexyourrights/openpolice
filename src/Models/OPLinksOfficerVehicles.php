<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksOfficerVehicles extends Model
{
    protected $table      = 'op_links_officer_vehicles';
    protected $primaryKey = 'lnk_off_vehic_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'lnk_off_vehic_off_id', 
		'lnk_off_vehic_vehic_id', 
		'lnk_off_vehic_role', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
