<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLSessEmojis extends Model
{
    protected $table         = 'SL_SessEmojis';
    protected $primaryKey     = 'SessEmoID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'SessEmoUserID',
        'SessEmoTreeID', 
        'SessEmoRecID', 
        'SessEmoDefID',
    ];
}
