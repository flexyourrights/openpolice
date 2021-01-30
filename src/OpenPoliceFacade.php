<?php
/**
  * OpenPoliceFacade in Laravel is a class which redirects static 
  * method calls to the dynamic methods of an underlying class
  *
  * OpenPolice.org
  * @package  flexyourrights/openpolice
  * @author  Morgan Lesko <morgan@flexyourrights.org>
  * @since 0.2.8
  */
namespace FlexYourRights\OpenPolice;

use Illuminate\Support\Facades\Facade;

class OpenPoliceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'openpolice';
    }
}
