<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLConditionsNodes extends Model
{
    protected $table         = 'SL_ConditionsNodes';
    protected $primaryKey     = 'CondNodeID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'CondNodeCondID', 
        'CondNodeNodeID', 
        'CondNodeLoopID', 
    ];
}
