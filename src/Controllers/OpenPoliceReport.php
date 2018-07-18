<?php
namespace OpenPolice\Controllers;

use Auth;
use App\Models\User;

use OpenPolice\Controllers\OpenPolice;

class OpenPoliceReport extends OpenPolice
{
    public $classExtension  = 'OpenPoliceReport';
    public $treeID          = 1;
    protected $isReport     = true;
    
    
}
