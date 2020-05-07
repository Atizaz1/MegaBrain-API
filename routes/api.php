<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) 
{
    return $request->user();
});

// States Routes

Route::get('state', 'StateController@getStateList');

Route::get('/getStateByCode/{code}',
[
    'uses' => 'StateController@getStateByCode',
    'as'   => 'state.code'
]);

Route::get('/getCitiesListByStateName/{state}','StateController@getCitiesListByStateName');

Route::get('/getCitiesListByStateCode/{stateCode}','StateController@getCitiesListByStateCode');

// END

// City Routes

Route::get('city', 'CityController@getCityList');

Route::get('/getCityByCode/{code}',
[
    'uses' => 'CityController@getCityByCode',
    'as'   => 'city.code'
]);

Route::get('/getStateByCityName/{city}','CityController@getStateByCityName');

Route::get('/getStateByCityCode/{cityCode}','CityController@getStateByCityName');

// END

// Social Routes

Route::post('socialSignUp','JWTController@socialRegister');

// END
    
// Protected Routes

Route::group(
[

    'middleware' => 'api',
    'prefix'     => 'auth'

], function ($router) 
{
    // Login/Register Routes

    Route::post('login', 'JWTController@login');

    Route::post('register', 'JWTController@register');

    Route::post('logout', 'JWTController@logout');

    Route::post('refresh', 'JWTController@refresh');

    Route::post('resetPassword', 'JWTController@resetPassword');

    Route::post('me', 'JWtController@me');

    Route::post('verifyToken', 'TokenController@verifyUserAccountToken');

    Route::post('passwordResetInit', 'TokenController@sendPasswordResetToken');

    Route::post('verifyPasswordToken', 'TokenController@verifyPasswordResetToken');

    Route::post('updateUserProfile', 'JWTController@updateUserProfile');

    // END

    // Topics Routes

    Route::get('topics', 'TopicController@getTopicList');

    Route::get('getTopicByCode/{code}', 'TopicController@getTopicByCode');

    Route::get('getTopicByName/{name}', 'TopicController@getTopicByName');

    Route::get('getTopicBySubjectAndAreaCodes/{subjectCode}/{areaCode}', 'TopicController@getTopicBySubjectAndAreaCodes');

    // END

    // Subjects Route

    Route::get('subjects', 'SchoolSubjectController@getSchoolSubjectList');

    Route::get('getSubjectByCode/{code}', 'SchoolSubjectController@getSchoolSubjectByCode');
    Route::get('getSubjectByName/{name}', 'SchoolSubjectController@getSchoolSubjectByName');

    Route::get('getAreasListBySubjectName/{name}', 'SchoolSubjectController@getAreasListBySubjectName');

    Route::get('getAreasListBySubjectCode/{code}', 'SchoolSubjectController@getAreasListBySubjectCode');

    // END

    // Area Routes

    Route::get('areas', 'AreaController@getAreaList');

    Route::get('getAreaByCode/{code}', 'AreaController@getAreaByCode');

    Route::get('getAreaByName/{name}', 'AreaController@getAreaByName');

    Route::get('getSubjectByAreaName/{name}','AreaController@getSubjectByAreaName');

    Route::get('getSubjectByAreaCode/{code}','AreaController@getSubjectByAreaCode');

    // END

    // Image Routes

    Route::get('images', 'ImageController@getImagePathList');

    Route::get('getImagePathByCode/{code}', 'ImageController@getImagePathByCode');

    Route::get('getImagePathByName/{name}', 'ImageController@getImagePathByName');

    Route::get('getImagePathSubjectWise/{subjectCode}/{areaCode}/{topicCode}','ImageController@getImagePathSubjectWise');

    // END

    // News Routes

    Route::get('news', 'NewsController@getNewsList');

    Route::get('getNewsById/{id}', 'NewsController@getNewsById');

    Route::get('getNOrderedNews',  'NewsController@getNOrderedNews');        

    // END
});

// END