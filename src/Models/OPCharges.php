<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCharges extends Model
{
    protected $table         = 'OP_Charges';
    protected $primaryKey     = 'ChrgID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'ChrgArrestID', 
        'ChrgStopID', 
        'ChrgCharges', 
    ];
}