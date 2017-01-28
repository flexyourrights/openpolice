<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPPersonContact extends Model
{
    protected $table      = 'OP_PersonContact';
    protected $primaryKey = 'PrsnID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'PrsnNamePrefix', 
		'PrsnNameFirst', 
		'PrsnNickname', 
		'PrsnNameMiddle', 
		'PrsnNameLast', 
		'PrsnNameSuffix', 
		'PrsnEmail', 
		'PrsnPhoneHome', 
		'PrsnPhoneWork', 
		'PrsnPhoneMobile', 
		'PrsnAddress', 
		'PrsnAddress2', 
		'PrsnAddressCity', 
		'PrsnAddressState', 
		'PrsnAddressZip', 
		'PrsnBirthday', 
		'PrsnFacebook', 
    ];
}
