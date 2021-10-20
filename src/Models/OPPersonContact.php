<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPPersonContact extends Model
{
	use Cachable;

    protected $table      = 'op_person_contact';
    protected $primaryKey = 'prsn_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'prsn_name_prefix', 
		'prsn_name_first', 
		'prsn_nickname', 
		'prsn_name_middle', 
		'prsn_name_last', 
		'prsn_name_suffix', 
		'prsn_email', 
		'prsn_phone_home', 
		'prsn_phone_work', 
		'prsn_phone_mobile', 
		'prsn_address', 
		'prsn_address2', 
		'prsn_address_city', 
		'prsn_address_state', 
		'prsn_address_zip', 
		'prsn_birthday', 
		'prsn_facebook', 
		'prsn_user_id', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
