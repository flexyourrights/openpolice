<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPSearchContra extends Model
{
    protected $table         = 'OP_SearchContra';
    protected $primaryKey     = 'SrchConID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'SrchConSearchID', 
        'SrchConType', 
    ];
}