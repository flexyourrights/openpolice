<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksCivilianEvents extends Model
{
    protected $table      = 'OP_LinksCivilianEvents';
    protected $primaryKey = 'LnkCivEveID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'LnkCivEveCivID', 
		'LnkCivEveEveID', 
    ];
}
