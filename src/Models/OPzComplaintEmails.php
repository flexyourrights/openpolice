<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OPzComplaintEmails extends Model
{
    protected $table = 'op_z_ComplaintEmails';
    protected $primaryKey = 'ComEmailID';
    
    protected $fillable = [
        'ComEmailType', 
        'ComEmailCustomSpots', 
        'ComEmailName', 
        'ComEmailSubject', 
        'ComEmailBody', 
        'ComEmailOpts', 
        'ComEmailTotSent', 
    ];
    
    
}
