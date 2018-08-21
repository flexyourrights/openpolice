<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SLEmails extends Model
{
    protected $table = 'SL_Emails';
    protected $primaryKey = 'EmailID';
    
    protected $fillable = [
        'EmailTree', 
        'EmailType', 
        'EmailName', 
        'EmailSubject', 
        'EmailBody', 
        'EmailOpts', 
        'EmailTotSent', 
    ];
    
    
}
