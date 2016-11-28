<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCSearchSeize extends Model
{
	protected $table 		= 'OPC_SearchSeize';
	protected $primaryKey 	= 'SrchSeizID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'SrchSeizSearchID', 
		'SrchSeizType', 
	];
}