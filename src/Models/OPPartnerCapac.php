<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OPPartnerCapac extends Model
{
    use Cachable;

    protected $table      = 'op_partner_capac';
    protected $primaryKey = 'prt_cap_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'prt_cap_part_id', 
		'prt_cap_capacity', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
