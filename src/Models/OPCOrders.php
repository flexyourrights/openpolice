<?php namespace OpenPolice\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPCOrders extends Model
{
	protected $table 		= 'OPC_Orders';
	protected $primaryKey 	= 'OrdID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'OrdEventSequenceID', 
		'OrdDescription', 
		'OrdTroubleUnderYN', 
		'OrdTroubleUnderstading', 
	];
}