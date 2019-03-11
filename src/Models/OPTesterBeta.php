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
		'BetaSubmissionProgress', 
		'BetaVersionAB', 
		'BetaTreeVersion', 
		'BetaIsMobile', 
		'BetaUniqueStr', 
		'BetaIPaddy', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
