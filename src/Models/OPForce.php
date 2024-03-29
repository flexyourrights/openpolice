<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPForce extends Model
{
	use Cachable;

    protected $table      = 'op_force';
    protected $primaryKey = 'for_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
        'for_com_id',
		'for_event_sequence_id', 
		'for_against_animal', 
		'for_animal_desc', 
		'for_type', 
		'for_type_other', 
		'for_gun_ammo_type', 
		'for_gun_desc', 
		'for_how_many_times', 
		'for_orders_before_force', 
		'for_orders_subject_response', 
		'for_while_handcuffed', 
		'for_while_held_down', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
