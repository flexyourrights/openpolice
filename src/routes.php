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

    Route::post('/profile/{uid}',     [
        'uses' => 'OpenPolice\Controllers\VolunteerController@volunProfileAdm', 
        'middleware' => ['auth']
    ]);
    Route::get( '/profile/{uid}',     [
        'uses' => 'OpenPolice\Controllers\VolunteerController@volunProfileAdm', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/evidence/{cid}/{upID}',     [
        'uses' => 'OpenPolice\Controllers\OpenPoliceReport@retrieveUpload',
        'middleware' => ['auth']
    ]);
    
    Route::get( '/allegations',         'OpenPolice\Controllers\OpenPolice@allegationsList');
    
    Route::get( '/form-tree', function () {
        header("Location: https://app.openpolicecomplaints.org/dox/OPC_Form_Tree_Map.pdf");
        exit;
    });
    
    
    /********************************************************/
    
    
    Route::get('/volunteer', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@index',
        'middleware' => ['auth']
    ]);
    
    Route::get('/volunteer/all', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@indexAll', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/volunteer/nextDept', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@nextDept', 
        'middleware' => ['auth']
    ]);
    
    Route::post('/volunteer/newDept', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@newDept',
        'middleware' => ['auth']
    ]);
    
    Route::get('/volunteer/s/{state}', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@indexSearchS', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/volunteer/d/{deptName}',             [
        'uses' => 'OpenPolice\Controllers\VolunteerController@indexSearchD', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/volunteer/s/{state}/d/{deptName}', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@indexSearchSD', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/volunteer/verify/checklist', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@deptEditCheck', 
        'middleware' => ['auth']
    ]);
    
    Route::post('/volunteer/verify/{deptSlug}', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@deptEditSave', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/volunteer/verify/{deptSlug}', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@deptEdit', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/volunteer/stars', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@printStars', 
        'middleware' => ['auth']
    ]);
    
    Route::post('/volunteer/saveDefaultState', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@saveDefaultState', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/volunteer/user/{uid}', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@volunProfile', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/volun/user/{uid}', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@volunProfileAdm', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/volunteer/user/{uid}/edit', [
        'uses' => 'OpenPolice\Controllers\VolunteerController@updateProfile', 
        'middleware' => ['auth']
    ]);
    
    
    
    /********************************************************/
    
    
    
    
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
    
    
    Route::get('/dashboard/complaints', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@listComplaints', 
        'middleware' => ['auth']
    ]);
    
    
    Route::get('/dashboard/complaints/all', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@listAll', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/complaints/me', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@listMine', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/complaints/waiting', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@listWaiting', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/complaints/incomplete', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@listIncomplete', 
        'middleware' => ['auth']
    ]);
    
    
    
    
    
    /********************************************************/
    
    
    Route::get('/dashboard/volun', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@volunDepts', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/volun/stars', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@volunList', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/user/{uid}', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@profile', 
        'middleware' => ['auth']
    ]);
    
    Route::get( '/dashboard/volun/email', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@volunEmail', 
        'middleware' => ['auth']
    ]);
    
    Route::post('/dashboard/volun/users', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@volunManagePost', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/volun/users', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@volunManage', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/instruct', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@instructList', 
        'middleware' => ['auth']
    ]);
    
    Route::post('/dashboard/instruct/new', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@instructNew', 
        'middleware' => ['auth']
    ]);
    
    Route::post('/dashboard/instruct/{instID}', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@instructEditSave', 
        'middleware' => ['auth']
    ]);
    
    Route::get('/dashboard/instruct/{instID}', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@instructEdit', 
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
    
    Route::get('/dashboard/overs', [
        'uses' => 'OpenPolice\Controllers\OpenPoliceAdmin@listOvers', 
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