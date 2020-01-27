<?php namespace App\Models;
// generated from /resources/views/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class OPPeopleEventLinks extends Model
{
    protected $table         = 'op_PeopleEventLinks';
    protected $primaryKey     = 'PplEvtLnkID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'PplEvtLnk_complaint_id', 
        'PplEvtLnkOfficerID', 
        'PplEvtLnkCivilianID', 
        'PplEvtLnkAllegationID', 
        'PplEvtLnk_event_sequence_id', 
        'PplEvtLnkOrderID', 
    ];
}