<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPzComplaintReviews extends Model
{
    protected $table      = 'op_z_complaint_reviews';
    protected $primaryKey = 'com_rev_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'com_rev_complaint', 
		'com_rev_user', 
		'com_rev_date', 
		'com_rev_type', 
		'com_rev_complaint_type', 
		'com_rev_status', 
		'com_rev_next_action', 
		'com_rev_note', 
		'com_rev_one_incident', 
		'com_rev_civilian_contact', 
		'com_rev_one_officer', 
		'com_rev_one_allegation', 
		'com_rev_evidence_uploaded', 
		'com_rev_english_skill', 
		'com_rev_readability', 
		'com_rev_consistency', 
		'com_rev_realistic', 
		'com_rev_outrage', 
		'com_rev_explicit_lang', 
		'com_rev_graphic_content', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
