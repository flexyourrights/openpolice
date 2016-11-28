<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCLinksOfficerAllegations extends Model
{
	protected $table 		= 'OPC_LinksOfficerAllegations';
	protected $primaryKey 	= 'LnkOffAlleID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'LnkOffAlleOffID', 
		'LnkOffAlleAlleID', 
	];
}