<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLBusRules extends Model
{
    protected $table      = 'SL_BusRules';
    protected $primaryKey = 'RuleID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'RuleDatabase', 
		'RuleStatement', 
		'RuleConstraint', 
		'RuleTables', 
		'RuleFields', 
		'RuleIsAppOrient', 
		'RuleIsRelation', 
		'RuleTestOn', 
		'RulePhys', 
		'RuleLogic', 
		'RuleRel', 
		'RuleAction', 
    ];
}
