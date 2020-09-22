<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPOffCompliments extends Model
{
    protected $table      = 'op_off_compliments';
    protected $primaryKey = 'off_comp_id';
    public $timestamps    = true;
    protected $fillable   = 
    [    
        'off_comp_compliment_id', 
        'off_comp_off_id', 
        'off_comp_valor', 
        'off_comp_lifesaving', 
        'off_comp_deescalation', 
        'off_comp_professionalism', 
        'off_comp_fairness', 
        'off_comp_constitutional', 
        'off_comp_compassion', 
        'off_comp_community', 
    ];
    
    // END Survloop auto-generated portion of Model
    
}
