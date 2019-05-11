<?php namespace Storage\App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPzVolunStatDays extends Model
{
    protected $table      = 'OP_zVolunStatDays';
    protected $primaryKey = 'VolunStatID';
    public $timestamps    = true;
    protected $fillable   = 
    [    
		'VolunStatDate', 
		'VolunStatSignups', 
		'VolunStatLogins', 
		'VolunStatUsersUnique', 
		'VolunStatDeptsUnique', 
		'VolunStatOnlineResearch', 
		'VolunStatCallsDept', 
		'VolunStatCallsIA', 
		'VolunStatCallsTot', 
		'VolunStatTotalEdits', 
		'VolunStatOnlineResearchV', 
		'VolunStatCallsDeptV', 
		'VolunStatCallsIAV', 
		'VolunStatCallsTotV', 
		'VolunStatTotalEditsV', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
