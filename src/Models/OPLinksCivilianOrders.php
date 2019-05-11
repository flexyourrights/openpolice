<?php namespace Storage\App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksCivilianOrders extends Model
{
    protected $table      = 'OP_LinksCivilianOrders';
    protected $primaryKey = 'LnkCivOrdID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'LnkCivOrdCivID', 
		'LnkCivOrdOrdID', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
