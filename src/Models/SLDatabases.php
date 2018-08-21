<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLDatabases extends Model
{
    protected $table         = 'SL_Databases';
    protected $primaryKey     = 'DbID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'DbUser', 
        'DbPrefix', 
        'DbName', 
        'DbDesc', 
        'DbMission', 
        'DbOpts', 
        'DbTables', 
        'DbFields', 
    ];
}
