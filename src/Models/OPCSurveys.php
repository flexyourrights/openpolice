<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCSurveys extends Model
{
	protected $table 		= 'OPC_Surveys';
	protected $primaryKey 	= 'SurvID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'SurvComplaintID', 
		'SurvAuthUserID', 
	];
}