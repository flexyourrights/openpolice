<?php namespace OpenPolice\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPComplaintNotes extends Model
{
    protected $table      = 'OP_ComplaintNotes';
    protected $primaryKey = 'NoteID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'NoteComplaintID', 
		'NoteUserID', 
		'NoteTimestamp', 
		'NoteContent', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
