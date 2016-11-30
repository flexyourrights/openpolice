<?php

namespace OpenPolice\Models;

use Illuminate\Database\Eloquent\Model;

class OPzComplaintEmails extends Model
{
	protected $table = 'OP_zComplaintEmails';
	protected $primaryKey = 'ComEmailID';
	
	protected $fillable = [
		'ComEmailType', 'ComEmailCustomSpots', 
		'ComEmailName', 'ComEmailSubject', 'ComEmailBody', 
		'ComEmailOpts', 'ComEmailTotSent', 
	];
    
    
}
