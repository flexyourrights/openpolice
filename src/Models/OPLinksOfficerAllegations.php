<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksOfficerAllegations extends Model
{
	protected $table 		= 'OP_LinksOfficerAllegations';
	protected $primaryKey 	= 'LnkOffAlleID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'LnkOffAlleOffID', 
		'LnkOffAlleAlleID', 
	];
}