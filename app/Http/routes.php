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
Route::group(['domain' => $domain], function () {
    Route::controller('/', 'MainController');
});

Route::group(['middleware' => ['web']], function () {
    Route::get ('/',            'CmsController@index')->name('cms');
    Route::get ('register',     'Auth\AuthController@getRegister');
    Route::post('register',     'Auth\AuthController@postRegister');
    Route::get ('{login}',      'Auth\AuthController@getLogin')->where(['login' => 'login$']);
    Route::post('login',        'Auth\AuthController@postLogin');
    Route::get ('logout',       'Auth\AuthController@getLogout');
});

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get ('photo/profile',        'PhotoController@getProfilePhoto');
    Route::get ('photo/product/{id}/{type?}',   'PhotoController@getProductPhoto')->where(['id' => '[0-9]+', 'type' => 'front|back|custom1|custom2']);
});

Route::group(['middleware' => ['web', 'auth', 'api']], function () {
    Route::get ('profile/get',      'ProfileController@getProfile');
    Route::post('profile/edit',     'ProfileController@postEditProfile');
    Route::get ('profile/qr',       'ProfileController@getQrPhoto');
    Route::post('product/edit/{id}','ProductController@postEditProduct')->where('id', '[0-9]+');
    Route::post('product/add',      'ProductController@postAddProduct');
    Route::get ('product/all',      'ProductController@getAllProduct');

});

// Admin only pages
Route::group(['middleware' => ['web', 'auth', 'admin']], function () {
   
});

/**
 * Catch all route for non-existing pages
 */
Route::any('{catchcall}', function ($page) {
    dd($page.' requested');
})->where('catchall', '(.*)');


