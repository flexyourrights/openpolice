<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OPzComplaintEmailed extends Model
{
    protected $table = 'op_z_ComplaintEmailed';
    protected $primaryKey = 'ComEmailedID';
    
    protected $fillable = [
        'ComEmailed_complaint_id', 
        'ComEmailedEmailID', 
        'ComEmailedDate', 
        'ComEmailedTo', 
        'ComEmailedFromUser', 
        'ComEmailedCustomSpots', 
        'ComEmailedOpts',  
    ];
    
    
}
