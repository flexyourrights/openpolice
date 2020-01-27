<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPComplaintOversight extends Model
{
    protected $table         = 'op_complaint_oversight';
    protected $primaryKey     = 'comp_oversight_id';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'comp_oversight_complaint_id', 
        'comp_oversight_over_id', 
        'comp_oversight_submitted', 
        'comp_oversight_still_no_response', 
        'comp_oversight_received', 
        'comp_oversight_investigating', 
        'comp_oversight_report_date', 
        'comp_oversight_oversight_report_evidence_id', 
        'comp_oversight_agency_complaint_number', 
    ];
}