<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPSearchSeize extends Model
{
    protected $table      = 'OP_SearchSeize';
    protected $primaryKey = 'SrchSeizID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'SrchSeizSearchID', 
		'SrchSeizType', 
    ];
}
