<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLDataHelpers extends Model
{
    protected $table         = 'SL_DataHelpers';
    protected $primaryKey     = 'DataHelpID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'DataHelpTree', 
        'DataHelpParentTable', 
        'DataHelpTable', 
        'DataHelpKeyField', 
        'DataHelpValueField', 
    ];
}
