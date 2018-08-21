<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLContact extends Model
{
    protected $table         = 'SL_Contact';
    protected $primaryKey     = 'ContID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'ContType', 
        'ContFlag', 
        'ContEmail', 
        'ContSubject', 
        'ContBody', 
    ];
}
