<?php namespace Storage\App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCustomers extends Model
{
    protected $table         = 'OP_Customers';
    protected $primaryKey     = 'CustID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'CustType', 
        'CustUserID', 
        'CustPersonID', 
        'CustTitle', 
        'CustCompanyName', 
        'CustCompanyWebsite', 
    ];
}