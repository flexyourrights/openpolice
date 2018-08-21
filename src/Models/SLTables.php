<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLTables extends Model
{
    protected $table         = 'SL_Tables';
    protected $primaryKey     = 'TblID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'TblDatabase', 
        'TblAbbr', 
        'TblName', 
        'TblEng', 
        'TblDesc', 
        'TblNotes', 
        'TblType', 
        'TblGroup', 
        'TblOrd', 
        'TblOpts', 
        'TblActive', 
        'TblNumFields', 
        'TblNumForeignKeys', 
        'TblNumForeignIn', 
    ];
}
