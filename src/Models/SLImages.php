<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLImages extends Model
{
    protected $table         = 'SL_Images';
    protected $primaryKey     = 'ImgID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'ImgDatabaseID', 
        'ImgUserID', 
        'ImgFileOrig', 
        'ImgFileLoc', 
        'ImgFullFilename', 
        'ImgTitle', 
        'ImgCredit', 
        'ImgCreditUrl', 
        'ImgNodeID', 
        'ImgType', 
        'ImgFileSize',
        'ImgWidth', 
        'ImgHeight', 
    ];
}
