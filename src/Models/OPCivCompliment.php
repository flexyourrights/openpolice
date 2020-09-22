<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCivCompliment extends Model
{
    protected $table      = 'op_civ_compliment';
    protected $primaryKey = 'civ_comp_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'civ_comp_compliment_id', 
		'civ_comp_user_id', 
		'civ_comp_is_creator', 
		'civ_comp_role', 
		'civ_comp_person_id', 
		'civ_comp_phys_desc_id', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
