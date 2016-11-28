<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCPeopleEventLinks extends Model
{
	protected $table 		= 'OPC_PeopleEventLinks';
	protected $primaryKey 	= 'PplEvtLnkID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'PplEvtLnkComplaintID', 
		'PplEvtLnkOfficerID', 
		'PplEvtLnkCivilianID', 
		'PplEvtLnkAllegationID', 
		'PplEvtLnkEventSequenceID', 
		'PplEvtLnkOrderID', 
	];
}