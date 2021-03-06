<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['namespace' => 'Admin'], function () {
    Route::get('/dashboard','AdminController@index');
    Route::get('/all-user','AdminController@getAllUser');

    /********** Location Module ***********/
    Route::get('/location','AdminController@location');
     Route::post('/add-new-location','AdminController@postLocation');
    Route::get('/edit-location/{id}','AdminController@editLocation');
    Route::post('/update-location','AdminController@updateLocation');


    /********** Area Of Sectors ***********/
    Route::get('/area-of-sectors','AdminController@areaOfSectors');
    Route::post('/add-new-area-of-sector','AdminController@postAreaOfSectors');
    Route::get('/edit-area-of-sector/{id}','AdminController@editAreaOfSector');
    Route::post('/update-area-of-sector','AdminController@updateAreaOfSectors');

    /********** Specialization ***********/
    Route::get('/specialization','AdminController@specialization');
    Route::post('/add-new-specialization','AdminController@postSpecialization');
    Route::get('/edit-specialization/{id}','AdminController@editSpecialization');
    Route::post('/update-specialization','AdminController@updateSpecialization');

    /********** Qualifications ***********/
    Route::get('/qualification','AdminController@qualification');
    Route::post('/add-new-qualification','AdminController@postQualification');
    Route::get('/edit-qualification/{id}','AdminController@editQualification');
    Route::post('/update-qualification','AdminController@updateQualification');

    /********** Job By Roles ***********/
    Route::get('/job-by-role','AdminController@jobByRoles');
    Route::post('/add-new-job-role','AdminController@postJobByRoles');
    Route::get('/edit-job-by-role/{id}','AdminController@editJobByRoles');
    Route::post('/update-job-role','AdminController@updateJobByRoles');

    /********** Job Types ***********/
    Route::get('/job-types','AdminController@jobTypes');
    Route::post('/add-new-job-type','AdminController@postJobType');
    Route::get('/edit-job-type/{id}','AdminController@editJobTypes');
    Route::post('/update-job-type','AdminController@updateJobType');

});


Route::group(['namespace' => 'Admin','prefix'=>'recruiter'], function () {
    Route::get('/all-recruiter','ManageRecruiterController@getAllRecruiter');
    Route::get('/view-details/{id}','ManageRecruiterController@recruiterViewDetails');
});

Route::group(['namespace' => 'Api'],function (){
   Route::get('api-details','ApiController@index');
   Route::get('seeker-register','ApiController@seekerregister');
   Route::get('seeker-login','ApiController@showSeekerLogin');
   Route::get('seeker-profile','ApiController@showSeekerProfileForm');
   Route::get('recruiter-register','ApiController@recruiterRegister');
   Route::get('recruiter-login','ApiController@showRecruiterLogin');
   Route::get('recruiter-profile','ApiController@showRecruiterProfileForm');
   Route::get('post-new-job','ApiController@showPostJobForm');
   Route::get('active-jobs','ApiController@showActiveJobsForm');
   Route::get('apply-for-job','ApiController@showApplyForJobForm');

});

//


Auth::routes();




