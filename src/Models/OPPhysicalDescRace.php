<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPPhysicalDescRace extends Model
{
    use Cachable;

    protected $table      = 'op_physical_desc_race';
    protected $primaryKey = 'phys_race_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'phys_race_phys_desc_id', 
		'phys_race_race', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
