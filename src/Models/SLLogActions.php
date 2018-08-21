<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLLogActions extends Model
{
    protected $table         = 'SL_LogActions';
    protected $primaryKey     = 'LogID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'LogUser', 
        'LogDatabase', 
        'LogTable', 
        'LogField', 
        'LogAction', 
        'LogOldName', 
        'LogNewName', 
    ];
}
