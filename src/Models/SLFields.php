<?php namespace App\Models;
// generated from /resources/views/vendor/survloop/admin/db/export-laravel-model-gen.blade.php

use Illuminate\Database\Eloquent\Model;

class SLFields extends Model
{
    protected $table         = 'SL_Fields';
    protected $primaryKey     = 'FldID';
    public $timestamps         = true;
    protected $fillable     = 
    [    
        'FldDatabase', 
        'FldTable', 
        'FldOrd', 
        'FldSpecType', 
        'FldSpecSource', 
        'FldName', 
        'FldEng', 
        'FldAlias', 
        'FldDesc', 
        'FldNotes', 
        'FldForeignTable', 
        'FldForeignMin', 
        'FldForeignMax', 
        'FldForeign2Min', 
        'FldForeign2Max', 
        'FldValues', 
        'FldDefault', 
        'FldIsIndex', 
        'FldType', 
        'FldDataType', 
        'FldDataLength', 
        'FldDataDecimals', 
        'FldCharSupport', 
        'FldInputMask', 
        'FldDisplayFormat', 
        'FldKeyType', 
        'FldKeyStruct', 
        'FldEditRule', 
        'FldUnique', 
        'FldNullSupport', 
        'FldValuesEnteredBy', 
        'FldRequired', 
        'FldCompareSame', 
        'FldCompareOther', 
        'FldCompareValue', 
        'FldOperateSame', 
        'FldOperateOther', 
        'FldOperateValue', 
        'FldOpts', 
    ];
}
