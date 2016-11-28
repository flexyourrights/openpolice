<?php

namespace OpenPolice\Models;

use Illuminate\Database\Eloquent\Model;

class OPCzComplaintEmailed extends Model
{
	protected $table = 'OPC_zComplaintEmailed';
	protected $primaryKey = 'ComEmailedID';
	
	protected $fillable = [
		'ComEmailedComplaintID', 'ComEmailedEmailID', 
		'ComEmailedDate', 'ComEmailedTo', 'ComEmailedFromUser', 
		'ComEmailedCustomSpots', 'ComEmailedOpts',  
	];
    
    
}
