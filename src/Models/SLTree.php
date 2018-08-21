<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLTree extends Model
{
    protected $table         = 'SL_Tree';
    protected $primaryKey     = 'TreeID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'TreeDatabase', 
        'TreeUser', 
        'TreeType', 
        'TreeName', 
        'TreeDesc', 
        'TreeSlug', 
        'TreeRoot', 
        'TreeFirstPage', 
        'TreeLastPage', 
        'TreeCoreTable',
        'TreeOpts',
    ];
}
