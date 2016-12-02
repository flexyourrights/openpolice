<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPPeopleEventLinks extends Model
{
	protected $table 		= 'OP_PeopleEventLinks';
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