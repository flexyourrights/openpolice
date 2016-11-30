<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPPhysicalDesc extends Model
{
	protected $table 		= 'OP_PhysicalDesc';
	protected $primaryKey 	= 'PhysID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'PhysGender', 
		'PhysGenderOther', 
		'PhysAge', 
		'PhysHeight', 
		'PhysBodyType', 
		'PhysHairDescription', 
		'PhysHairFacialDesc', 
		'PhysEyes', 
		'PhysDistinguishingMarksDesc', 
		'PhysVoiceDesc', 
		'PhysClothesDesc', 
		'PhysDisabilitiesDesc', 
	];
}