<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCForce extends Model
{
	protected $table 		= 'OPC_Force';
	protected $primaryKey 	= 'ForID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'ForEventSequenceID', 
		'ForAgainstAnimal', 
		'ForAnimalDesc', 
		'ForType', 
		'ForTypeOther', 
		'ForGunAmmoType', 
		'ForGunDesc', 
		'ForHowManyTimes', 
		'ForOrdersBeforeForce', 
		'ForOrdersSubjectResponse', 
		'ForWhileHandcuffed', 
		'ForWhileHeldDown', 
		'ForOfficerInjured', 
		'ForOfficerInjuredDesc', 
		'ForAllegUnreasonable', 
	];
}