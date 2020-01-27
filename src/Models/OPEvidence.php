<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPEvidence extends Model
{
    protected $table      = 'op_evidence';
    protected $primaryKey = 'evid_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'evid__complaint_id', 
		'evid_compliment_id', 
		'evid_type', 
		'evid_privacy', 
		'evid_date_time', 
		'evid_title', 
		'evid_evidence_desc', 
		'evid_upload_file', 
		'evid_stored_file', 
		'evid_video_link', 
		'evid_video_duration', 
		'evid_civilian_id', 
		'evid_dept_id', 
		'evid_scene_id', 
		'evid_event_sequence_id', 
		'evid_injury_id', 
		'evid_note_id', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
