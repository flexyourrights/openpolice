<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCSearchContra extends Model
{
	protected $table 		= 'OPC_SearchContra';
	protected $primaryKey 	= 'SrchConID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'SrchConSearchID', 
		'SrchConType', 
	];
}