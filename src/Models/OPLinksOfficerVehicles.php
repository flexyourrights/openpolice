<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPLinksOfficerVehicles extends Model
{
	protected $table 		= 'OP_LinksOfficerVehicles';
	protected $primaryKey 	= 'LnkOffVehicID';
	public $timestamps 		= true;
	protected $fillable 	= 
	[	
		'LnkOffVehicOffID', 
		'LnkOffVehicVehicID', 
		'LnkOffVehicRole', 
	];
}