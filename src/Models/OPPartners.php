<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPPartners extends Model
{
	use Cachable;

    protected $table      = 'op_partners';
    protected $primaryKey = 'part_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'part_type', 
		'part_status', 
		'part_user_id', 
		'part_person_id', 
		'part_bio', 
		'part_slug', 
		'part_company_name', 
		'part_title', 
		'part_company_website', 
		'part_bio_url', 
		'part_help_reqs', 
		'part_geo_desc', 
		'part_photo_url', 
		'part_alerts', 
		'part_version_ab', 
		'part_submission_progress', 
		'part_ip_addy', 
		'part_tree_version', 
		'part_unique_str', 
		'part_is_mobile', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
