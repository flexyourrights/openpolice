<?php namespace App\Models;
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
		'VolunStatTot', 
		'VolunStatTotalEdits', 
		'VolunStatOnlineResearchV', 
		'VolunStatCallsDeptV', 
		'VolunStatCallsIAV', 
		'VolunStatTotV', 
		'VolunStatTotalEditsV', 
    ];
    
    // END SurvLoop auto-generated portion of Model
    
}
