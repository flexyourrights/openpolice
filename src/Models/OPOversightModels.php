<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPOversightModels extends Model
{
    use Cachable;

    protected $table      = 'op_oversight_models';
    protected $primaryKey = 'over_mod_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'over_mod_oversight_id', 
		'over_mod_civ_model', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
