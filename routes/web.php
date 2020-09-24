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

Route::any('test', 'TestController@index');

Route::get('auth/google', 'SocialiteController@loginWithGoogle')->name('auth.google');
Route::get('auth/google/callback', 'SocialiteController@loginWithGoogleCallback')->name('auth.google.callback');

Route::namespace('Frontend')
    ->group(function () {

        Route::get('/', 'HomeController@index')->name('home');

        Route::get('danh-muc/{slug}.html', 'CategoryController@index')->name('category');

        Route::get('tin-tuc/{slug}.html', 'PostController@index')->name('post');

        Route::get('khoa-hoc/{slug}.html', 'CourseController@index')->name('course');

        Route::middleware('auth')->group(function () {
            Route::get('get-video-key/{id}', 'PartVideoController@getKey')->name('part_video.get_key'); // Không được đổi dù bất cứ lý do gì

            Route::get('khoa-hoc/bat-dau/{id}', 'CourseController@start')->name('course.start');

            Route::get('bai-giang/{id}', 'PartController@index')->name('part');

            Route::get('gio-hang', 'CartController@index')->name('cart');
            Route::get('gio-hang/get-data', 'CartController@getData')->name('cart.get_data');
            Route::post('gio-hang/add', 'CartController@add')->name('cart.add');
            Route::post('gio-hang/delete', 'CartController@delete')->name('cart.delete');
            Route::post('gio-hang/submit', 'CartController@submitCart')->name('cart.submit');
            Route::get('gio-hang/xac-nhan-thanh-toan', 'CartController@paymentConfirm')->name('cart.confirm');
            Route::get('gio-hang/xac-nhan-thanh-toan', 'CartController@paymentConfirm')->name('cart.confirm');
            Route::post('gio-hang/hoan-tat-thanh-toan', 'CartController@paymentComplete')->name('cart.complete');

            Route::get('ca-nhan/thong-tin', 'UserController@info')->name('user.info');
            Route::get('ca-nhan/thong-tin/get-data', 'UserController@info_getData')->name('user.info.get_data');
            Route::post('ca-nhan/thong-tin/submit-data', 'UserController@info_submitData')->name('user.info.submit_data');
            Route::get('ca-nhan/lich-su-mua-hang', 'UserController@invoice')->name('user.invoice');
            Route::get('ca-nhan/khoa-hoc-cua-toi', 'UserController@myCourse')->name('user.my_course');
            Route::post('ca-nhan/upload-avatar', 'UserController@uploadAvatar')->name('user.upload_avatar');
            Route::get('ca-nhan/nap-tien', 'UserController@recharge')->name('user.recharge');
            Route::get('ca-nhan/doi-mat-khau', 'UserController@changepass')->name('user.changepass');
            Route::post('ca-nhan/doi-mat-khau', 'UserController@doChangepass')->name('user.changepass');
        });

        Route::middleware('auth')->group(function () {
            Route::post('recharge/momo', 'RechargeMomoController@makeRequest')->name('recharge.momo.request');
            Route::get('recharge/momo-callback', 'RechargeMomoController@processCallback')->name('recharge.momo.callback');

            Route::post('recharge/epay', 'RechargeEpayController@makeRequest')->name('recharge.epay.request');
            Route::get('recharge/epay-callback', 'RechargeEpayController@processCallback')->name('recharge.epay.callback');
        });

        Route::post('recharge/momo-notify', 'RechargeMomoController@processNotify')->name('recharge.momo.notify');
        Route::post('recharge/epay-notify', 'RechargeEpayController@processNotify')->name('recharge.epay.notify');

        Route::get('old-get-video-key/{id}', 'VideoController@oldGetKey')->name('video.old_key');

        Route::middleware('guest')->group(function () {
            Route::get('dang-nhap', 'LoginController@index')->name('login');
            Route::post('dang-nhap', 'LoginController@login')->name('login');

            Route::get('dang-ky', 'RegisterController@index')->name('register');
            Route::post('dang-ky', 'RegisterController@register')->name('register');
        });

        Route::post('dang-xuat', 'LogoutController@logout')->name('logout');
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
            Route::post('post/order-in-position', 'PostController@submitOrderInPosition')->name('post.order_in_position');
            Route::get('post/search-post', 'PostController@getSearchPost')->name('post.search_post');
            Route::get('post/get-related-post', 'PostController@getRelatedPost')->name('post.get_related_post');
            Route::get('post/get-related-course', 'PostController@getRelatedCourse')->name('post.get_related_course');

            Route::get('category/list', 'CategoryController@list')->name('category.list');
            Route::get('category/add', 'CategoryController@add')->name('category.add');
            Route::post('category/add', 'CategoryController@submitAdd')->name('category.add');
            Route::get('category/edit/{id}', 'CategoryController@edit')->name('category.edit');
            Route::post('category/edit/{id}', 'CategoryController@submitEdit')->name('category.edit');
            Route::post('category/delete/{id}', 'CategoryController@submitDelete')->name('category.delete');
            Route::post('category/enabled', 'CategoryController@submitEnabled')->name('category.enabled');
            Route::post('category/order-in-position', 'CategoryController@submitOrderInPosition')->name('category.order_in_position');

            Route::get('course', 'CourseController@list')->name('course.list');
            Route::get('course/add', 'CourseController@add')->name('course.add');
            Route::post('course/add', 'CourseController@submitAdd')->name('course.add');
            Route::get('course/edit/{id}', 'CourseController@edit')->name('course.edit');
            Route::post('course/edit/{id}', 'CourseController@submitEdit')->name('course.edit');
            Route::post('course/enabled', 'CourseController@submitEnabled')->name('course.enabled');
            Route::post('course/delete/{id}', 'CourseController@submitDelete')->name('course.delete');
            Route::post('course/order-in-category', 'CourseController@submitOrderInCategory')->name('course.order_in_category');
            Route::post('course/order-in-position', 'CourseController@submitOrderInPosition')->name('course.order_in_position');
            Route::get('course/search-course', 'CourseController@getSearchCourse')->name('course.search_course');
            Route::get('course/get-related-course', 'CourseController@getRelatedCourse')->name('course.get_related_course');

            Route::get('lesson', 'LessonController@list')->name('lesson.list');
            Route::get('lesson/add', 'LessonController@add')->name('lesson.add');
            Route::post('lesson/add', 'LessonController@submitAdd')->name('lesson.add');
            Route::get('lesson/edit/{id}', 'LessonController@edit')->name('lesson.edit');
            Route::post('lesson/edit/{id}', 'LessonController@submitEdit')->name('lesson.edit');
            Route::post('lesson/enabled', 'LessonController@submitEnabled')->name('lesson.enabled');
            Route::post('lesson/delete/{id}', 'LessonController@submitDelete')->name('lesson.delete');
            Route::post('lesson/order-in-course', 'LessonController@submitOrderInCourse')->name('lesson.order_in_course');

            Route::get('part', 'PartController@list')->name('part.list');
            Route::get('part/add', 'PartController@add')->name('part.add');
            Route::post('part/add', 'PartController@submitAdd')->name('part.add');
            Route::post('part/enabled', 'PartController@submitEnabled')->name('part.enabled');
            Route::post('part/order-in-lesson', 'PartController@submitOrderInLesson')->name('part.order_in_lesson');

            Route::get('part-video/edit/{part_id}', 'PartVideoController@edit')->name('part_video.edit');
            Route::post('part-video/edit/{part_id}', 'PartVideoController@submitEdit')->name('part_video.edit');
            Route::post('part-video/delete/{part_id}', 'PartVideoController@submitDelete')->name('part_video.delete');
            Route::post('part-video/ajax-upload', 'PartVideoController@upload')->name('part_video.upload');
            Route::post('part-video/ajax-clear-s3', 'PartVideoController@clearS3')->name('part_video.clear_s3');

            Route::get('part-youtube/edit/{part_id}', 'PartYoutubeController@edit')->name('part_youtube.edit');
            Route::post('part-youtube/edit/{part_id}', 'PartYoutubeController@submitEdit')->name('part_youtube.edit');
            Route::post('part-youtube/delete/{part_id}', 'PartYoutubeController@submitDelete')->name('part_youtube.delete');

            Route::get('part-content/edit/{part_id}', 'PartContentController@edit')->name('part_content.edit');
            Route::post('part-content/edit/{part_id}', 'PartContentController@submitEdit')->name('part_content.edit');
            Route::post('part-content/delete/{part_id}', 'PartContentController@submitDelete')->name('part_content.delete');
        });

        Route::middleware('guest')->group(function () {
            Route::get('login', 'LoginController@index')->name('login');
            Route::post('login', 'LoginController@login')->name('login');
        });

        Route::post('logout', 'LogoutController@logout')->name('logout');
    });
