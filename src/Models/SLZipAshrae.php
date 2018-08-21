<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLZipAshrae extends Model
{
    protected $table         = 'SL_ZipAshrae';
    protected $primaryKey     = 'AshrID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'AshrZone', 
        'AshrState', 
        'AshrCounty', 
    ];
}
