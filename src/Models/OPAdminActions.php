<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPAdminActions extends Model
{
    protected $table      = 'OP_AdminActions';
    protected $primaryKey = 'AdmActID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'AdmActUserID', 
		'AdmActTimestamp', 
		'AdmActTable', 
		'AdmActRecordID', 
		'AdmActOldData', 
		'AdmActNewData', 
    ];
}
