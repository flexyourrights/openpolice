<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCustomers extends Model
{
    protected $table         = 'op_customers';
    protected $primaryKey     = 'cust_id';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'cust_type', 
        'cust_user_id', 
        'cust_person_id', 
        'cust_title', 
        'cust_company_name', 
        'cust_company_website', 
    ];
}