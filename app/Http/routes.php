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
if( env('APP_ENV') === 'production' ) {
    $domain = 'wechat.shopus.li';   
} else {
    $domain = 'wechat.shopus.app';
} 

// The single point of entry for wechat interface
Route::group(['domain' => $domain ,'middleware' => ['weixin']], function () {
        Route::controller('/', 'MainController');
});

Route::group(['middleware' => ['web']], function () {

    // Account management
    Route::get ('/',                'CmsController@index')->name('cms');
    Route::get ('register',         'Auth\AuthController@getRegister');
    Route::post('register',         'Auth\AuthController@postRegister');
    Route::get ('login',            'Auth\AuthController@getLogin');
    Route::post('login',            'Auth\AuthController@postLogin');
    Route::get ('logout',           'Auth\AuthController@getLogout');

    Route::get ('photo/profile',   'PhotoController@getProfilePhoto');

    });

Route::group(['middleware' => ['api', 'auth']], function () {
    Route::get ('profile/get',      'ProfileController@getProfile');
    Route::post('profile/edit',     'ProfileController@postEditProfile');

    Route::post('product/edit/{id}','ProductController@postEditProduct')->where('id', '[0-9]+');
    Route::post('product/add',      'ProductController@postAddProduct');
    Route::get ('product/all',      'ProductController@getAllProduct');

});

// Admin only pages
Route::group(['middleware' => ['web', 'auth', 'admin']], function () {
   
});



