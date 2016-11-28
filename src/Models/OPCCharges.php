<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCCharges extends Model
{
	protected $table 		= 'OPC_Charges';
	protected $primaryKey 	= 'ChrgID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'ChrgArrestID', 
		'ChrgStopID', 
		'ChrgCharges', 
	];
}