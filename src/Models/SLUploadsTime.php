<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLUploadsTime extends Model
{
    protected $table      = 'SL_UploadsTime';
    protected $primaryKey = 'UpTiID';
    public $timestamps    = true;
    protected $fillable   = 
    [
		'UpTiUploadID', 
		'UpTiTimestamp', 
		'UpTiDescription', 
		'UpTiLinkFldID', 
		'UpTiLinkRecID', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
