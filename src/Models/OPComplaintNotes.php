<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPComplaintNotes extends Model
{
    protected $table      = 'op_complaint_notes';
    protected $primaryKey = 'note_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'note_complaint_id', 
		'note_user_id', 
		'note_content', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
