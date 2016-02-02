<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['middleware' => ['web']], function () {

    Route::get ('/cms',             'CmsController@index');
    Route::get ('/cms/register',    'Auth\AuthController@getRegister');
    Route::post('/cms/register',    'Auth\AuthController@postRegister');
    Route::get ('/cms/login',       'Auth\AuthController@getLogin');
    Route::post('/cms/login',       'Auth\AuthController@postLogin');
    Route::get ('/cms/logout',      'Auth\AuthController@getLogout');  

    Route::get ('/cms/profile/edit', 'CmsController@getEditProfile');
    Route::post('/cms/profile/edit', 'CmsController@postEditProfile');

    Route::get ('/cms/product/add',  'CmsController@getAddProduct');
    Route::post('/cms/product/add',  'CmsController@postAddProduct');
});


Route::group(['middleware' => ['weixin']], function () {
    Route::controller('/', 'MainController');
});
