<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCLinksCivilianEvents extends Model
{
	protected $table 		= 'OPC_LinksCivilianEvents';
	protected $primaryKey 	= 'LnkCivEveID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'LnkCivEveCivID', 
		'LnkCivEveEveID', 
	];
}