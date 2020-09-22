<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksComplaintOversight extends Model
{
    protected $table      = 'op_links_complaint_oversight';
    protected $primaryKey = 'lnk_com_over_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'lnk_com_over_complaint_id', 
		'lnk_com_over_dept_id', 
		'lnk_com_over_over_id', 
		'lnk_com_over_submitted', 
		'lnk_com_over_still_no_response', 
		'lnk_com_over_received', 
		'lnk_com_over_investigated', 
		'lnk_com_over_report_date', 
		'lnk_com_over_oversight_report_evidence_id', 
		'lnk_com_over_agency_complaint_number', 
        'lnk_com_over_declined',
        'lnk_com_over_declined_evidence_id',
    ];
    
    // END Survloop auto-generated portion of Model
    
}
