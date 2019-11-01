<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPTesterBeta extends Model
{
    protected $table      = 'OP_TesterBeta';
    protected $primaryKey = 'BetaID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'BetaEmail', 
		'BetaName', 
		'BetaLastName', 
		'BetaYear', 
		'BetaNarrative', 
		'BetaHowHear', 
		'BetaInvited', 
		'BetaUserID', 
		'BetaVersionAB', 
		'BetaSubmissionProgress', 
		'BetaIPaddy', 
		'BetaTreeVersion', 
		'BetaUniqueStr', 
		'BetaIsMobile', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
