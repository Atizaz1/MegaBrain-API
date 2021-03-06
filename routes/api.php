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

/*
|--------------------------------------------------------------------------
| MegaBrain v1 API Routes
|--------------------------------------------------------------------------
|
*/

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

    // Extra 
    Route::get('getImagePathSubjectWise/{subjectCode}/{areaCode}/{topicCode}','ImageController2@getImagePathSubjectWise');
    // END

    // END

    // News Routes

    Route::get('news', 'NewsController@getNewsList');

    Route::get('getNewsById/{id}', 'NewsController@getNewsById');

    Route::get('getNOrderedNews',  'NewsController@getNOrderedNews');        

    // END

    // Tips Routes

    Route::get('tips', 'TipsController@getTipsList');

    Route::get('getTipsById/{id}', 'TipsController@getTipsById');

    Route::get('getNOrderedTips',  'TipsController@getNOrderedTips');        

    // END
});

// END

/*
|--------------------------------------------------------------------------
| MegaBrain v2 API Routes
|--------------------------------------------------------------------------
|
*/

    // User Registration Route

    Route::post('storeFreeUser2', 'JWTController@storeFreeUser2');

    // END

    // Topics Routes

    Route::get('topics', 'megabrainApiv2\TopicController@getTopicList');

    Route::get('getTopicByCode/{code}', 'megabrainApiv2\TopicController@getTopicByCode');

    Route::get('getTopicByName/{name}', 'megabrainApiv2\TopicController@getTopicByName');

    Route::get('getTopicBySubjectAndAreaCodes/{subjectCode}/{areaCode}', 'megabrainApiv2\TopicController@getTopicBySubjectAndAreaCodes');

    // END

    // Subjects Route

    Route::get('subjects', 'megabrainApiv2\SchoolSubjectController@getSchoolSubjectList');

    Route::get('getSubjectByCode/{code}', 'megabrainApiv2\SchoolSubjectController@getSchoolSubjectByCode');
    Route::get('getSubjectByName/{name}', 'megabrainApiv2\SchoolSubjectController@getSchoolSubjectByName');

    Route::get('getAreasListBySubjectName/{name}', 'megabrainApiv2\SchoolSubjectController@getAreasListBySubjectName');

    Route::get('getAreasListBySubjectCode/{code}', 'megabrainApiv2\SchoolSubjectController@getAreasListBySubjectCode');

    // END

    // Area Routes

    Route::get('areas', 'megabrainApiv2\AreaController@getAreaList');

    Route::get('getAreaByCode/{code}', 'megabrainApiv2\AreaController@getAreaByCode');

    Route::get('getAreaByName/{name}', 'megabrainApiv2\AreaController@getAreaByName');

    Route::get('getSubjectByAreaName/{name}','megabrainApiv2\AreaController@getSubjectByAreaName');

    Route::get('getSubjectByAreaCode/{code}','megabrainApiv2\AreaController@getSubjectByAreaCode');

    // END

    // Image Routes

    Route::get('images', 'megabrainApiv2\ImageController@getImagePathList');

    Route::get('getImagePathByCode/{code}', 'megabrainApiv2\ImageController@getImagePathByCode');

    Route::get('getImagePathByName/{name}', 'megabrainApiv2\ImageController@getImagePathByName');

    Route::get('getImagePathSubjectWise/{subjectCode}/{areaCode}/{topicCode}','megabrainApiv2\ImageController@getImagePathSubjectWise');

    // END

    // News Routes

    Route::get('news', 'megabrainApiv2\NewsController@getNewsList');

    Route::get('getNewsById/{id}', 'megabrainApiv2\NewsController@getNewsById');

    Route::get('getNOrderedNews',  'megabrainApiv2\NewsController@getNOrderedNews');        

    // END

    // FreeUser Routes

    Route::post('storeFreeUser', 'megabrainApiv2\FreeUsersController@storeFreeUser');

    Route::get('checkFreeUser/{email}',  'megabrainApiv2\FreeUsersController@checkFreeUser'); 
    Route::post('verifyFreeUserToken',  'megabrainApiv2\FreeUsersController@verifyFreeUserToken');        

    // END

    // Message Routes

    Route::get('message', 'megabrainApiv2\MessageController@getMessageList');

    Route::get('getMessageById/{id}', 'megabrainApiv2\MessageController@getMessageById');

    Route::get('getTopPriorityMessage',  'megabrainApiv2\MessageController@getTopPriorityMessage');        

    // END

    // Tips Routes

    Route::get('tips', 'megabrainApiv2\TipsController@getTipsList');

    Route::get('getTipsById/{id}', 'megabrainApiv2\TipsController@getTipsById');

    Route::get('getNOrderedTips',  'megabrainApiv2\TipsController@getNOrderedTips');        

    // END

    // User Purchase Routes

    Route::post('verifyPurchase', 'UserPurchaseController@verifyPurchase');

    Route::get('getVerifiedPurchases/{email}', 'UserPurchaseController@getPurchasedSubjectsByUser');

    // Route::get('updatePurchaseDate/{email}', 'UserPurchaseController@lockContent');

    Route::get('getPurchasedEquipment/{email}', 'UserPurchaseController@getPurchasedEquipmentByUser');

    Route::get('updatePurchasedEquipment/{email}/{equipment}', 'UserPurchaseController@updatePurchasedEquipmentByUser');

    Route::get('generateAccessRecoveryToken/{email}', 'UserPurchaseController@generateAccessRecoveryTokenByUser');

    Route::get('verifyAccessRecoveryToken/{email}/{token}', 'UserPurchaseController@verifyAccessRecoveryTokenByUser');

    Route::get('updateUserPartnerCode/{email}/{partnerCode}', 'JWTController@updateUserPartnerCode');

    // END