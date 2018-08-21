<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLNode extends Model
{
    protected $table         = 'SL_Node';
    protected $primaryKey     = 'NodeID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'NodeTree', 
        'NodeParentID', 
        'NodeParentOrder', 
        'NodeType', 
        'NodePrompText', 
        'NodePromptNotes', 
        'NodePromptAfter', 
        'NodeInternalNotes', 
        'NodeResponseSet', 
        'NodeDefault', 
        'NodeDataBranch', 
        'NodeDataStore', 
        'NodeTextSuggest', 
        'NodeCharLimit', 
        'NodeLikes', 
        'NodeDislikes', 
        'NodeOpts', 
    ];
}
