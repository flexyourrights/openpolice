<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SLEmailed extends Model
{
    protected $table = 'SL_Emailed';
    protected $primaryKey = 'EmailedID';
    
    protected $fillable = [
        'EmailedTree', 
        'EmailedRecID', 
        'EmailedEmailID', 
        'EmailedTo', 
        'EmailedToUser', 
        'EmailedFromUser', 
        'EmailedSubject', 
        'EmailedBody', 
        'EmailedOpts',  
    ];
    
    
}
