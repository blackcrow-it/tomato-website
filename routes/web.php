<?php

use Illuminate\Support\Facades\Route;

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

Route::middleware('guest')->group(function () {
    Route::get('auth/google', 'SocialiteController@loginWithGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'SocialiteController@loginWithGoogleCallback')->name('auth.google.callback');
});

Route::namespace('Frontend')
    ->group(function () {
        Route::get('/', 'HomeController@index')->name('home');

        Route::get('tin-tuc/{slug}.html', 'PostController@index')->name('post');

        Route::get('khoa-hoc/{slug}.html', 'CourseController@index')->name('course');

        Route::get('danh-muc/{slug}.html', 'CategoryController@index')->name('category');
    });

Route::prefix('admin')
    ->namespace('Backend')
    ->name('admin.')
    ->group(function () {

        Route::middleware('admin_login_required')->group(function () {
            Route::get('/', 'HomeController@index')->name('home');

            Route::get('user', 'UserController@list')->name('user.list');
            Route::get('user/add', 'UserController@add')->name('user.add');
            Route::post('user/add', 'UserController@submitAdd')->name('user.add');
            Route::get('user/edit/{id}', 'UserController@edit')->name('user.edit');
            Route::post('user/edit/{id}', 'UserController@submitEdit')->name('user.edit');
            Route::post('user/delete/{id}', 'UserController@submitDelete')->name('user.delete');

            Route::get('post', 'PostController@list')->name('post.list');
            Route::get('post/add', 'PostController@add')->name('post.add');
            Route::post('post/add', 'PostController@submitAdd')->name('post.add');
            Route::get('post/edit/{id}', 'PostController@edit')->name('post.edit');
            Route::post('post/edit/{id}', 'PostController@submitEdit')->name('post.edit');
            Route::post('post/enabled', 'PostController@submitEnabled')->name('post.enabled');
            Route::post('post/delete/{id}', 'PostController@submitDelete')->name('post.delete');
            Route::post('post/order-in-category', 'PostController@submitOrderInCategory')->name('post.order_in_category');

            Route::get('category/list/{id?}', 'CategoryController@list')->name('category.list');
            Route::get('category/add', 'CategoryController@add')->name('category.add');
            Route::post('category/add', 'CategoryController@submitAdd')->name('category.add');
            Route::get('category/edit/{id}', 'CategoryController@edit')->name('category.edit');
            Route::post('category/edit/{id}', 'CategoryController@submitEdit')->name('category.edit');
            Route::post('category/delete/{id}', 'CategoryController@submitDelete')->name('category.delete');

            Route::get('course', 'CourseController@list')->name('course.list');
            Route::get('course/add', 'CourseController@add')->name('course.add');
            Route::post('course/add', 'CourseController@submitAdd')->name('course.add');
            Route::get('course/edit/{id}', 'CourseController@edit')->name('course.edit');
            Route::post('course/edit/{id}', 'CourseController@submitEdit')->name('course.edit');
            Route::post('course/enabled', 'CourseController@submitEnabled')->name('course.enabled');
            Route::post('course/delete/{id}', 'CourseController@submitDelete')->name('course.delete');
            Route::post('course/order-in-category', 'CourseController@submitOrderInCategory')->name('course.order_in_category');
        });

        Route::middleware('guest')->group(function () {
            Route::get('login', 'LoginController@index')->name('login');
            Route::post('login', 'LoginController@login')->name('login');
        });

        Route::post('logout', 'LogoutController@logout')->name('logout');
    });
