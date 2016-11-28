<?php

namespace OpenPolice\Models;

use Illuminate\Database\Eloquent\Model;

class OPCzComplaintEmails extends Model
{
	protected $table = 'OPC_zComplaintEmails';
	protected $primaryKey = 'ComEmailID';
	
	protected $fillable = [
		'ComEmailType', 'ComEmailCustomSpots', 
		'ComEmailName', 'ComEmailSubject', 'ComEmailBody', 
		'ComEmailOpts', 'ComEmailTotSent', 
	];
    
    
}
