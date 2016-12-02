<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPComplaintNotes extends Model
{
	protected $table 		= 'OP_ComplaintNotes';
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