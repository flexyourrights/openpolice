<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLSearchRecDump extends Model
{
    protected $table         = 'SL_SearchRecDump';
    protected $primaryKey     = 'SchRecDmpID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'SchRecDmpTreeID', 
        'SchRecDmpRecID', 
        'SchRecDmpRecDump',
    ];
}
