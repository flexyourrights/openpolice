<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksOfficerEvents extends Model
{
    protected $table         = 'OP_LinksOfficerEvents';
    protected $primaryKey     = 'LnkOffEveID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'LnkOffEveOffID', 
        'LnkOffEveEveID', 
    ];
}