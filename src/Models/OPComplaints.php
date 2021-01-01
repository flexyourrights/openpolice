<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPComplaints extends Model
{
    protected $table      = 'op_complaints';
    protected $primaryKey = 'com_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'com_user_id', 
		'com_status', 
		'com_type', 
		'com_incident_id', 
		'com_scene_id', 
		'com_privacy', 
		'com_award_medallion', 
		'com_summary', 
		'com_alleg_list', 
		'com_tried_other_ways', 
		'com_tried_other_ways_desc', 
		'com_officer_injured', 
		'com_officer_injured_desc', 
		'com_attorney_has', 
		'com_attorney_oked', 
		'com_attorney_want', 
		'com_anyone_charged', 
		'com_all_charges_resolved', 
		'com_unresolved_charges_actions', 
		'com_file_lawsuit', 
		'com_how_hear', 
		'com_feedback', 
		'com_officer_disciplined', 
		'com_officer_discipline_type', 
		'com_media_links', 
		'com_admin_id', 
		'com_att_id', 
		'com_notes', 
		'com_slug', 
		'com_record_submitted', 
		'com_submission_progress', 
		'com_version_ab', 
		'com_tree_version', 
		'com_honey_pot', 
		'com_is_mobile', 
		'com_unique_str', 
		'com_ip_addy', 
		'com_public_id', 
		'com_is_demo', 
		'com_share_data', 
		'com_anon', 
		'com_publish_user_name',
		'com_publish_officer_name',
		'com_want_attorney_but_file',
    ];
    
    // END Survloop auto-generated portion of Model
    
}
