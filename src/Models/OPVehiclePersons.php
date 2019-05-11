<?php namespace Storage\App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPVehiclePersons extends Model
{
    protected $table         = 'OP_VehiclePersons';
    protected $primaryKey     = 'VehicPersID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'VehicPersVehicID', 
        'VehicPersCivID', 
        'VehicPersOffID', 
        'VehicPersRole', 
    ];
}