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

    Route::get( '/dept/{deptSlug}',               'OpenPolice\Controllers\OpenPolice@deptPage');
    Route::get( '/sharing-your-story/{deptSlug}', 'OpenPolice\Controllers\OpenPolice@shareStoryDept');
    
    Route::get( '/attorney/{prtnSlug}',                       'OpenPolice\Controllers\OpenPolice@attorneyPage');
    Route::get( '/prepare-complaint-for-attorney/{prtnSlug}', 'OpenPolice\Controllers\OpenPolice@shareStoryAttorney');

    Route::get( '/org/{prtnSlug}',                       'OpenPolice\Controllers\OpenPolice@orgPage');
    Route::get( '/prepare-complaint-for-org/{prtnSlug}', 'OpenPolice\Controllers\OpenPolice@shareStoryOrg');

    Route::get('/evidence/{cid}/{upID}',     [
        'uses' => 'OpenPolice\Controllers\OpenPoliceReport@retrieveUpload',
        'middleware' => ['auth']
    ]);
    
    Route::get( '/form-tree', function () {
        header("Location: /tree/complaint");
        exit;
    });
    
    
    
    
    Route::get('/dashboard/complaint/{cid}/', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@complaintView', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/complaint/{cid}', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@complaintView', 
        'middleware' => ['auth']
    ]);
    
    Route::post('/dashboard/complaint/{cid}/review/save', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@complaintReviewPost', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/complaint/{cid}/review', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@complaintReview', 
        'middleware' => ['auth']
    ]);
    
    Route::post('/dashboard/complaint/{cid}/emails/send', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@complaintEmailsSend', 
        'middleware' => ['auth']
    ]);
    
    Route::post('/dashboard/complaint/{cid}/emails/type', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@complaintEmailsType', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/complaint/{cid}/emails', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@complaintEmails', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/complaint/{cid}/update', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@complaintUpdate', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/complaint/{cid}/history', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@complaintHistory', 
        'middleware' => ['auth']
    ]);
    
    
    /********************************************************/
    
    
    
    Route::get('/dashboard/officers', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@listOfficers', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/depts', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@listDepts', 
        'middleware' => ['auth']
    ]);
    
    
    Route::post('/dashboard/overs/assign/{overID}', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@quickAssign', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/legal', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@listLegal', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/academic', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@listAcademic', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/media', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@listMedia', 
        'middleware' => ['auth']
    ]);
    
    
    
    /********************************************************/
    
    
    
    
    Route::get('/admin', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@dashHome', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@dashHome', 
        'middleware' => ['auth']
    ]);

});    

?>