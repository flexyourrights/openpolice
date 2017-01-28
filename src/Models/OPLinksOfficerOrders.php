<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksOfficerOrders extends Model
{
    protected $table      = 'OP_LinksOfficerOrders';
    protected $primaryKey = 'LnkOffOrdID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'LnkOffOrdOffID', 
		'LnkOffOrdOrdID', 
    ];
}
