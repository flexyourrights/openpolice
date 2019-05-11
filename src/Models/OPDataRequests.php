<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPDataRequests extends Model
{
    protected $table         = 'OP_DataRequests';
    protected $primaryKey     = 'ReqID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'ReqUserID', 
        'ReqTimestamp', 
        'ReqSuccess', 
        'ReqType', 
        'ReqID1', 
        'ReqID2', 
        'ReqID3', 
    ];
}