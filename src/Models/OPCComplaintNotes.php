<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCComplaintNotes extends Model
{
	protected $table 		= 'OPC_ComplaintNotes';
	protected $primaryKey 	= 'NoteID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'NoteComplaintID', 
		'NoteUserID', 
		'NoteTimestamp', 
		'NoteContent', 
	];
}