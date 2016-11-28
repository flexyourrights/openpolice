<?php

namespace OpenPolice\Models;

use Illuminate\Database\Eloquent\Model;

class OPCzVolunTmp extends Model
{
	protected $table = 'OPC_zVolunTmp';
	protected $primaryKey = 'TmpID';
	public $timestamps = true;
	
	protected $fillable = [
		'TmpUser', 'TmpType', 'TmpVal', 'TmpDate'
	];
    
    
}
