<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLZips extends Model
{
    protected $table      = 'SL_Zips';
    protected $primaryKey = 'ZipID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'ZipZip', 
		'ZipLat', 
		'ZipLong', 
		'ZipCity', 
		'ZipState', 
		'ZipCounty', 
    ];
}
