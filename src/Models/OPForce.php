<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPForce extends Model
{
    protected $table      = 'OP_Force';
    protected $primaryKey = 'ForID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'ForVictimUseWeapon', 
		'ForEventSequenceID', 
		'ForVictimHadWeapon', 
		'ForVictimWhatWeapon', 
		'ForAgainstAnimal', 
		'ForAnimalDesc', 
		'ForType', 
		'ForTypeOther', 
		'ForGunAmmoType', 
		'ForGunDesc', 
		'ForHowManyTimes', 
		'ForOrdersBeforeForce', 
		'ForOrdersSubjectResponse', 
		'ForChase', 
		'ForChaseType', 
		'ForWhileHandcuffed', 
		'ForWhileHeldDown', 
		'ForOfficerInjured', 
		'ForOfficerInjuredDesc', 
		'ForAllegUnreasonable', 
    ];
}
