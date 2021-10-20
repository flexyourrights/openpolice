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

use FlexYourRights\OpenPolice\Controllers\OpenPolice;

Route::middleware(['web'])->group(function () {

    Route::get('/dept/{deptSlug}', [OpenPolice::class, 'deptPage']);

    Route::get('/filing-your-police-complaint/{deptSlug}',  [OpenPolice::class, 'shareComplaintDept']);
    Route::get('/complaint-or-compliment/{deptSlug}',       [OpenPolice::class, 'shareStoryDept']);
    Route::get('/share-complaint-or-compliment/{deptSlug}', [OpenPolice::class, 'shareStoryDept']);

    Route::get('/org/{prtnSlug}', [OpenPolice::class, 'orgPage']);
    Route::get('/prepare-complaint-for-org/{prtnSlug}', [OpenPolice::class, 'shareStoryOrg']);
    
    Route::get('/attorney/{prtnSlug}',                       [OpenPolice::class, 'attorneyPage']);
    Route::get('/prepare-complaint-for-attorney/{prtnSlug}', [OpenPolice::class, 'shareStoryAttorney']);

    Route::get('/api/dept-all-xml',                [OpenPolice::class, 'apiDeptAllXml']);
    Route::get('/api/complaints-pcif-schema',      [OpenPolice::class, 'printPcifSchema']);
    Route::get('/api/complaints-pcif-schema-xml',  [OpenPolice::class, 'printPcifSchemaXml']);
    Route::get('/api/complaints-pcif-example-xml', [OpenPolice::class, 'printPcifExample']);
    Route::get('/api/complaints-pcif-xml',         [OpenPolice::class, 'printPcifAll']);
    Route::get('/api/complaints-pcif-xml/{cid}',   [OpenPolice::class, 'printPcifOne']);

});    

?>