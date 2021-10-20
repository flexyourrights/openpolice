<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPzEditOversight extends Model
{
	use Cachable;

    protected $table      = 'op_z_edit_oversight';
    protected $primaryKey = 'zed_over_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'zed_over_zed_dept_id', 
		'zed_over_online_research', 
		'zed_over_made_dept_call', 
		'zed_over_made_ia_call', 
		'zed_over_notes', 
		'zed_over_over_ID', 
		'zed_over_over_type', 
		'zed_over_over_user_id', 
		'zed_over_over_dept_id', 
		'zed_over_over_agnc_name', 
		'zed_over_over_verified', 
		'zed_over_over_name_prefix', 
		'zed_over_over_name_first', 
		'zed_over_over_nickname', 
		'zed_over_over_name_middle', 
		'zed_over_over_name_last', 
		'zed_over_over_name_suffix', 
		'zed_over_over_title', 
		'zed_over_over_id_number', 
		'zed_over_over_website', 
		'zed_over_over_facebook', 
		'zed_over_over_twitter', 
		'zed_over_over_youtube', 
		'zed_over_over_homepage_complaint_link', 
		'zed_over_over_web_complaint_info', 
		'zed_over_over_complaint_pdf', 
		'zed_over_over_complaint_web_form', 
		'zed_over_over_email', 
		'zed_over_over_phone_work', 
		'zed_over_over_address', 
		'zed_over_over_address2', 
		'zed_over_over_address_city', 
		'zed_over_over_address_state', 
		'zed_over_over_address_zip', 
		'zed_over_over_submit_deadline', 
		'zed_over_over_official_form_not_req', 
		'zed_over_over_official_anon', 
		'zed_over_over_way_sub_online', 
		'zed_over_over_way_sub_email', 
		'zed_over_over_way_sub_verbal_phone', 
		'zed_over_over_way_sub_paper_mail', 
		'zed_over_over_way_sub_paper_in_person', 
		'zed_over_over_way_sub_notary', 
		'zed_over_over_keep_email_private', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
