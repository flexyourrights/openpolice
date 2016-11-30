<?php

namespace OpenPolice\Models;

use Illuminate\Database\Eloquent\Model;

class OPzComplaintEmailed extends Model
{
	protected $table = 'OP_zComplaintEmailed';
	protected $primaryKey = 'ComEmailedID';
	
	protected $fillable = [
		'ComEmailedComplaintID', 'ComEmailedEmailID', 
		'ComEmailedDate', 'ComEmailedTo', 'ComEmailedFromUser', 
		'ComEmailedCustomSpots', 'ComEmailedOpts',  
	];
    
    
}
