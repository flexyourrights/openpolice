<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPPartnerFilters extends Model
{
    use Cachable;

    protected $table      = 'op_partner_filters';
    protected $primaryKey = 'prt_flt_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'prt_flt_case_id', 
		'prt_flt_filter', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
