<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPOfficersVerified extends Model
{
    protected $table      = 'OP_OfficersVerified';
    protected $primaryKey = 'OffVerID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'OffVerStatus', 
		'OffVerPersonID', 
		'OffVerCntComplaints', 
		'OffVerCntAllegations', 
        'OffVerCntCompliments',
        'OffVerCntCommends',
		'OffVerUniqueStr', 
		'OffVerSubmissionProgress', 
		'OffVerVersionAB', 
		'OffVerTreeVersion', 
		'OffVerIPaddy', 
		'OffVerIsMobile', 
		'OffVerUserID', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
