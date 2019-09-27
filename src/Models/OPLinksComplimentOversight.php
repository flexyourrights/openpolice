<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksComplimentOversight extends Model
{
    protected $table      = 'OP_LinksComplimentOversight';
    protected $primaryKey = 'LnkCompliOverID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'LnkCompliOverComplimentID', 
		'LnkCompliOverDeptID', 
		'LnkCompliOverOverID', 
		'LnkCompliOverSubmitted', 
		'LnkCompliOverReceived', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
