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
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@joinBetaLink'
    );
    
    Route::get(
        '/dept/{deptSlug}',
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@deptPage'
    );
    Route::get(
        '/filing-your-police-complaint/{deptSlug}',
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@shareComplaintDept'
    );
    Route::get(
        '/complaint-or-compliment/{deptSlug}',
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@shareStoryDept'
    );
    Route::get(
        '/share-complaint-or-compliment/{deptSlug}',
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@shareStoryDept'
    );
    
    Route::get(
        '/attorney/{prtnSlug}',
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@attorneyPage'
    );
    Route::get(
        '/prepare-complaint-for-attorney/{prtnSlug}', 
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@shareStoryAttorney'
    );

    Route::get(
        '/org/{prtnSlug}',
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@orgPage'
    );
    Route::get(
        '/prepare-complaint-for-org/{prtnSlug}', 
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@shareStoryOrg'
    );

    Route::get(
        '/api/dept-all-xml', 
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@apiDeptAllXml'
    );

    Route::get(
        '/api/complaints-pcif-schema', 
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@printPcifSchema'
    );
    Route::get(
        '/api/complaints-pcif-schema-xml', 
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@printPcifSchemaXml'
    );
    Route::get(
        '/api/complaints-pcif-example-xml', 
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@printPcifExample'
    );
    Route::get(
        '/api/complaints-pcif-xml', 
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@printPcifAll'
    );
    Route::get(
        '/api/complaints-pcif-xml/{cid}', 
        'FlexYourRights\OpenPolice\Controllers\OpenPolice@printPcifOne'
    );

    
});    

?>