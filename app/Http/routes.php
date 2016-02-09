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


Route::group(['middleware' => ['web'], 'prefix' => 'cms'], function () {

    Route::get ('/',                'CmsController@index')->name('cms');
    Route::get ('register',         'Auth\AuthController@getRegister');
    Route::post('register',         'Auth\AuthController@postRegister');
    Route::get ('login',            'Auth\AuthController@getLogin');
    Route::post('login',            'Auth\AuthController@postLogin');
    Route::get ('logout',           'Auth\AuthController@getLogout');

    Route::get ('profile/edit',     'CmsController@getEditProfile')->name('cms:profile:edit');
    Route::post('profile/edit',     'CmsController@postEditProfile');

    Route::get ('products/add',     'ProductController@getAddProduct');
    Route::post('products/add',     'ProductController@postAddProduct');
    Route::post('products/photo',   'ProductController@postProductPhotoAsync');

    Route::get ('profile/photo/{userid}', 'PhotoController@getPhoto')->name('cms:photo');
});


Route::group(['middleware' => ['weixin']], function () {
    Route::controller('/', 'MainController');
});
