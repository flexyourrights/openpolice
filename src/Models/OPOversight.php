<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPOversight extends Model
{
    protected $table      = 'op_oversight';
    protected $primaryKey = 'over_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'over_type', 
		'over_user_id', 
		'over_dept_id', 
		'over_agnc_name', 
		'over_verified', 
		'over_name_prefix', 
		'over_name_first', 
		'over_nickname', 
		'over_name_middle', 
		'over_name_last', 
		'over_name_suffix', 
		'over_title', 
		'over_id_number', 
		'over_website', 
		'over_facebook', 
		'over_twitter', 
		'over_youtube', 
		'over_homepage_complaint_link', 
		'over_web_complaint_info', 
		'over_complaint_pdf', 
		'over_complaint_web_form', 
		'over_email', 
		'over_phone_work', 
		'over_address', 
		'over_address2', 
		'over_address_city', 
		'over_address_county',
		'over_address_state', 
		'over_address_zip', 
		'over_submit_deadline', 
		'over_official_form_not_req', 
		'over_official_anon', 
		'over_way_sub_online', 
		'over_way_sub_email', 
		'over_way_sub_verbal_phone', 
		'over_way_sub_paper_mail', 
		'over_way_sub_paper_in_person', 
		'over_way_sub_notary', 
		'over_keep_email_private', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
