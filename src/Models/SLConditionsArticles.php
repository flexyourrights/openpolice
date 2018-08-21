<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLConditionsArticles extends Model
{
    protected $table         = 'SL_ConditionsArticles';
    protected $primaryKey     = 'ArticleID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'ArticleCondID', 
        'ArticleURL', 
        'ArticleTitle', 
    ];
}
