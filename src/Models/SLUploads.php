<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLUploads extends Model
{
    protected $table         = 'SL_Uploads';
    protected $primaryKey     = 'UpID';
    public $timestamps         = true;
    protected $fillable     = 
    [
		'UpTreeID', 
		'UpCoreID', 
		'UpType', 
		'UpPrivacy', 
		'UpTitle', 
		'UpDesc', 
		'UpUploadFile', 
		'UpStoredFile', 
		'UpVideoLink', 
		'UpVideoDuration', 
		'UpNodeID', 
		'UpLinkFldID', 
		'UpLinkRecID', 
    ];
}
