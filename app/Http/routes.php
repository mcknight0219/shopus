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

    // Account management
    Route::get ('/',                'CmsController@index')->name('cms');
    Route::get ('register',         'Auth\AuthController@getRegister');
    Route::post('register',         'Auth\AuthController@postRegister');
    Route::get ('login',            'Auth\AuthController@getLogin');
    Route::post('login',            'Auth\AuthController@postLogin');
    Route::get ('logout',           'Auth\AuthController@getLogout');

    // Profile facing public
    Route::get('profile/{id}',          'ProfileController@getProfile')
        ->where('id', '[0-9]+');
    Route::get ('profile/photo/{id}',   'PhotoController@getProfilePhoto')
        ->where('id', '[0-9]+');
});

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'cms'], function () {
    // Only authenticated user can edit *his* profile
    Route::get ('profile/edit',     'ProfileController@getEditProfile')->name('cms:profile:edit');
    Route::post('profile/edit',     'ProfileController@postEditProfile');

    // Product management
    Route::get ('product/add',     'ProductController@getAddProduct');
    Route::post('product/add',     'ProductController@postAddProduct');
    Route::post('product/photo',   'ProductController@postProductPhotoAsync');
    Route::get ('product/{id}',    'ProductController@showProduct')->where('id', '[0-9]+');
    Route::get ('product/{id}/photo', 'PhotoController@getProductPhoto')->where('id', '[0-9]+');
    
});

// Admin only pages
Route::group(['middleware' => ['web', 'auth', 'admin'], 'prefix' => 'cms'], function () {
    Route::get('brand',             'BrandController@getBrand');
    Route::post('brand/{id}/edit',  'BrandController@psotBrandEdit')->where('id', '[0-9]+');
});

Route::group(['middleware' => ['weixin']], function () {
    Route::controller('/', 'MainController');
});
