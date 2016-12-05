<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPIncidents extends Model
{
    protected $table         = 'OP_Incidents';
    protected $primaryKey     = 'IncID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'IncComplaintID', 
        'IncAddress', 
        'IncAddress2', 
        'IncAddressCity', 
        'IncAddressState', 
        'IncAddressZip', 
        'IncAddressLat', 
        'IncAddressLng', 
        'IncLandmarks', 
        'IncTimeStart', 
        'IncTimeEnd', 
        'IncDuration', 
    ];
}