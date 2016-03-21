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

    // for ajax updating profile.
    // TODO create api route group
    Route::post('profile/edit',     'ProfileController@postEditProfile');
    Route::get ('profile/get',      'ProfileController@getProfile');

    // add product throught ajax
    Route::post('product/add',      'ProductController@postAddProduct');
    Route::post('product/edit',     'ProductController@postEditProduct');
});

Route::group(['middleware' => ['web', 'auth']], function () {
    // Product management
    Route::get ('product/{id}',    'ProductController@showProduct')->where('id', '[0-9]+');
    Route::get ('product/{id}/photo', 'PhotoController@getProductPhoto')->where('id', '[0-9]+');
});

// Admin only pages
Route::group(['middleware' => ['web', 'auth', 'admin']], function () {
    Route::get ('brand',            'BrandController@getBrand');
    Route::post('brand/{id}/edit',  'BrandController@postBrandAsync')->where('id', '[0-9]+');
    Route::get ('brand/{id}/logo',  'BrandController@getBrandLogo')->where('id', '[0-9]+');
});



