<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPEventSequence extends Model
{
	protected $table 		= 'OP_EventSequence';
	protected $primaryKey 	= 'EveID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'EveComplaintID', 
		'EveOrder', 
		'EveType', 
		'EveUserFinished', 
	];
}