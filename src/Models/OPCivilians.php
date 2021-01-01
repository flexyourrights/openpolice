<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCivilians extends Model
{
    protected $table      = 'op_civilians';
    protected $primaryKey = 'civ_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'civ_complaint_id', 
		'civ_user_id', 
		'civ_is_creator', 
		'civ_role', 
		'civ_person_id', 
		'civ_phys_desc_id', 
		'civ_give_name', 
		'civ_give_contact_info', 
		'civ_resident', 
		'civ_occupation', 
		'civ_had_vehicle', 
		'civ_chase', 
		'civ_chase_type', 
		'civ_victim_what_weapon', 
		'civ_victim_use_weapon', 
		'civ_camera_record', 
		'civ_used_profanity', 
		'civ_has_injury', 
		'civ_has_injury_care', 
		'civ_given_citation', 
		'civ_given_warning', 
		'civ_citation_number', 
		'civ_charges_other',
        'civ_no_charges_filed', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
