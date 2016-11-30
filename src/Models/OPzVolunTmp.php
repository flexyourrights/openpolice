<?php

namespace OpenPolice\Models;

use Illuminate\Database\Eloquent\Model;

class OPzVolunTmp extends Model
{
	protected $table = 'OP_zVolunTmp';
	protected $primaryKey = 'TmpID';
	public $timestamps = true;
	
	protected $fillable = [
		'TmpUser', 'TmpType', 'TmpVal', 'TmpDate'
	];
    
    
}
