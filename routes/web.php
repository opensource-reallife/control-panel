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
    return view('welcome');
});

Route::namespace('Auth')->prefix('auth')->group(function () {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::post('logout', 'LoginController@logout')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::resource('users', 'UserController');
    Route::resource('factions', 'FactionController');
    Route::resource('companies', 'CompanyController');
    Route::resource('groups', 'GroupController');
    Route::resource('textures', 'TextureController');
    Route::resource('teamspeak', 'TeamspeakController');
    Route::get('/home', 'HomeController@index')->name('home');


    Route::namespace('Event')->prefix('events')->name('events.')->group(function () {
        Route::resource('santa', 'SantaController')->only(['index', 'store', 'create']);
    });

    Route::namespace('Admin')->prefix('admin')->group(function () {
        Route::resource('dashboard', 'DashboardController', ['as' => 'admin'])->only('index');
        Route::resource('users.logs', 'UserLogController', ['as' => 'admin'])->only('index', 'show');
        Route::get('users/search', 'UserSearchController@index')->name('admin.user.search');
        Route::get('users/forum/{forumId}', 'ForumUserController@show')->name('admin.user.forum');
        Route::get('textures', 'TextureController@index')->name('admin.texture');
    });
});
