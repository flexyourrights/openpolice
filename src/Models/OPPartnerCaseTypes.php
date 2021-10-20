<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPPartnerCaseTypes extends Model
{
    use Cachable;

    protected $table      = 'op_partner_case_types';
    protected $primaryKey = 'prt_cas_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'prt_cas_partner_id', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
