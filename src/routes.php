<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::get(
        '/join-beta-test/{campaign}', 
        'OpenPolice\Controllers\OpenPolice@joinBetaLink'
    );
    
    Route::get(
        '/dept/{deptSlug}',
        'OpenPolice\Controllers\OpenPolice@deptPage'
    );
    Route::get(
        '/complaint-or-compliment/{deptSlug}',
        'OpenPolice\Controllers\OpenPolice@shareStoryDept'
    );
    
    Route::get(
        '/attorney/{prtnSlug}',
        'OpenPolice\Controllers\OpenPolice@attorneyPage'
    );
    Route::get(
        '/prepare-complaint-for-attorney/{prtnSlug}', 
        'OpenPolice\Controllers\OpenPolice@shareStoryAttorney'
    );

    Route::get(
        '/org/{prtnSlug}',
        'OpenPolice\Controllers\OpenPolice@orgPage'
    );
    Route::get(
        '/prepare-complaint-for-org/{prtnSlug}', 
        'OpenPolice\Controllers\OpenPolice@shareStoryOrg'
    );
    
});    

?>