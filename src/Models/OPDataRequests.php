<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPDataRequests extends Model
{
    protected $table         = 'op_data_requests';
    protected $primaryKey     = 'req_id';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'req_user_id', 
        'req_timestamp', 
        'req_success', 
        'req_type', 
        'req_id1', 
        'req_id2', 
        'req_id3', 
    ];
}