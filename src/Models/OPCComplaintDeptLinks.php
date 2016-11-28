<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCComplaintDeptLinks extends Model
{
	protected $table 		= 'OPC_ComplaintDeptLinks';
	protected $primaryKey 	= 'CompDeptLinksID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'CompDeptLinksComplaintID', 
		'CompDeptLinksDeptID', 
	];
}