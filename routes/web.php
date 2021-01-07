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

Route::get('auth/facebook', 'SocialiteController@loginWithFacebook')->name('auth.facebook');
Route::get('auth/facebook/callback', 'SocialiteController@loginWithFacebookCallback')->name('auth.facebook.callback');

Route::namespace('Frontend')
    ->group(function () {

        Route::get('/', 'HomeController@index')->name('home');

        Route::get('{slug}-ct{id}.html', 'CategoryController@index')->name('category')->where(['slug' => '.*', 'id' => '\d+']);

        Route::get('{slug}-p{id}.html', 'PostController@index')->name('post')->where(['slug' => '.*', 'id' => '\d+']);

        Route::get('{slug}-c{id}.html', 'CourseController@index')->name('course')->where(['slug' => '.*', 'id' => '\d+']);

        Route::get('{slug}-b{id}.html', 'BookController@index')->name('book')->where(['slug' => '.*', 'id' => '\d+']);

        Route::middleware('auth')->group(function () {
            Route::get('get-video-key/{id}', 'PartVideoController@getKey')->name('part_video.get_key'); // Không được đổi dù bất cứ lý do gì

            Route::get('khoa-hoc/bat-dau/{id}', 'CourseController@start')->name('course.start');

            Route::get('bai-giang/{id}', 'PartController@index')->name('part');
            Route::get('part-test/get-data/{id}', 'PartTestController@getData')->name('part_test.get_data');

            Route::get('gio-hang', 'CartController@index')->name('cart');
            Route::get('gio-hang/get-data', 'CartController@getData')->name('cart.get_data');
            Route::post('gio-hang/add', 'CartController@add')->name('cart.add');
            Route::post('gio-hang/delete', 'CartController@delete')->name('cart.delete');
            Route::post('gio-hang/hoan-tat-thanh-toan', 'CartController@paymentConfirm')->name('cart.confirm');
            Route::get('gio-hang/hoan-tat-thanh-toan', 'CartController@paymentComplete')->name('cart.complete');
            Route::post('gio-hang/mua-ngay', 'CartController@instantBuy')->name('cart.instant_buy');
            Route::post('gio-hang/get-promo', 'CartController@getPromo')->name('cart.get_promo');

            Route::get('ca-nhan/thong-tin', 'UserController@info')->name('user.info');
            Route::get('ca-nhan/thong-tin/get-data', 'UserController@info_getData')->name('user.info.get_data');
            Route::post('ca-nhan/thong-tin/submit-data', 'UserController@info_submitData')->name('user.info.submit_data');
            Route::get('ca-nhan/lich-su-mua-hang', 'UserController@invoice')->name('user.invoice');
            Route::get('ca-nhan/khoa-hoc-cua-toi', 'UserController@myCourse')->name('user.my_course');
            Route::post('ca-nhan/upload-avatar', 'UserController@uploadAvatar')->name('user.upload_avatar');
            Route::get('ca-nhan/nap-tien', 'UserController@recharge')->name('user.recharge');
            Route::get('ca-nhan/lich-su-nap-tien', 'UserController@rechargeHistory')->name('user.recharge_history');
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

        Route::get('lienhe.html', 'ContactController@index')->name('contact');
        Route::post('lienhe.html', 'ContactController@submit')->name('contact');
    });

Route::prefix('admin')
    ->namespace('Backend')
    ->name('admin.')
    ->group(function () {

        Route::middleware('can_access_admin_dashboard')->group(function () {
            Route::get('/', 'HomeController@index')->name('home');

            Route::prefix('user')->name('user.')->group(function() {
                Route::get('/', 'UserController@list')->name('list');
                Route::get('add', 'UserController@add')->name('add');
                Route::post('add', 'UserController@submitAdd')->name('add');
                Route::get('edit/{id}', 'UserController@edit')->name('edit');
                Route::post('edit/{id}', 'UserController@submitEdit')->name('edit');
                Route::post('delete/{id}', 'UserController@submitDelete')->name('delete');
                Route::get('get-user-courses/{id}', 'UserController@getUserCourses')->name('get_user_courses');
            });

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
            Route::get('course/get-related-book', 'CourseController@getRelatedBook')->name('course.get_related_book');

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
            Route::post('part-video/ajax-upload-transcode', 'PartVideoController@uploadTranscode')->name('part_video.upload_transcode');
            Route::post('part-video/ajax-upload-video', 'PartVideoController@uploadVideo')->name('part_video.upload_video');
            Route::post('part-video/ajax-upload-drive', 'PartVideoController@uploadDrive')->name('part_video.upload_drive');
            Route::post('part-video/ajax-clear-s3', 'PartVideoController@clearS3')->name('part_video.clear_s3');
            Route::post('part-video/ajax-get-drive-token', 'PartVideoController@getGoogleDriveToken')->name('part_video.get_drive_token');

            Route::get('part-youtube/edit/{part_id}', 'PartYoutubeController@edit')->name('part_youtube.edit');
            Route::post('part-youtube/edit/{part_id}', 'PartYoutubeController@submitEdit')->name('part_youtube.edit');
            Route::post('part-youtube/delete/{part_id}', 'PartYoutubeController@submitDelete')->name('part_youtube.delete');

            Route::get('part-content/edit/{part_id}', 'PartContentController@edit')->name('part_content.edit');
            Route::post('part-content/edit/{part_id}', 'PartContentController@submitEdit')->name('part_content.edit');
            Route::post('part-content/delete/{part_id}', 'PartContentController@submitDelete')->name('part_content.delete');

            Route::get('part-test/edit/{part_id}', 'PartTestController@edit')->name('part_test.edit');
            Route::post('part-test/edit/{part_id}', 'PartTestController@submitEdit')->name('part_test.edit');
            Route::get('part-test/get-data/{part_id}', 'PartTestController@getData')->name('part_test.get_data');
            Route::post('part-test/delete/{part_id}', 'PartTestController@submitDelete')->name('part_test.delete');
            Route::post('part-test/upload-audio', 'PartTestController@uploadAudio')->name('part_test.upload_audio');
            Route::post('part-test/delete-audio', 'PartTestController@deleteAudio')->name('part_test.delete_audio');

            Route::get('part-survey/edit/{part_id}', 'PartSurveyController@edit')->name('part_survey.edit');
            Route::post('part-survey/edit/{part_id}', 'PartSurveyController@submitEdit')->name('part_survey.edit');
            Route::post('part-survey/delete/{part_id}', 'PartSurveyController@submitDelete')->name('part_survey.delete');

            Route::get('recharge', 'RechargeController@list')->name('recharge.list');
            Route::get('recharge/get-data', 'RechargeController@getData')->name('recharge.get_data');
            Route::post('recharge/recheck', 'RechargeController@recheck')->name('recharge.recheck');

            Route::get('setting/edit/{view}', 'SettingController@edit')->name('setting.edit');
            Route::post('setting/submit', 'SettingController@submit')->name('setting.submit');
            Route::post('setting/upload-image', 'SettingController@uploadImage')->name('setting.upload_image');
            Route::get('setting/drive/redirect', 'SettingController@redirectAuthGoogleDriveApi')->name('setting.drive.redirect');
            Route::get('setting/drive/callback', 'SettingController@callbackAuthGoogleDriveApi')->name('setting.drive.callback');

            Route::get('book', 'BookController@list')->name('book.list');
            Route::get('book/add', 'BookController@add')->name('book.add');
            Route::post('book/add', 'BookController@submitAdd')->name('book.add');
            Route::get('book/edit/{id}', 'BookController@edit')->name('book.edit');
            Route::post('book/edit/{id}', 'BookController@submitEdit')->name('book.edit');
            Route::post('book/enabled', 'BookController@submitEnabled')->name('book.enabled');
            Route::post('book/delete/{id}', 'BookController@submitDelete')->name('book.delete');
            Route::post('book/order-in-category', 'BookController@submitOrderInCategory')->name('book.order_in_category');
            Route::post('book/order-in-position', 'BookController@submitOrderInPosition')->name('book.order_in_position');
            Route::get('book/search-book', 'BookController@getSearchBook')->name('book.search_book');
            Route::get('book/get-related-course', 'BookController@getRelatedCourse')->name('book.get_related_course');
            Route::get('book/get-related-book', 'BookController@getRelatedBook')->name('book.get_related_book');

            Route::prefix('invoice')->name('invoice.')->group(function () {
                Route::get('list', 'InvoiceController@list')->name('list');
                Route::get('detail/{id}', 'InvoiceController@detail')->name('detail');
                Route::post('detail/{id}/change-status', 'InvoiceController@changeStatus')->name('change_status');
            });

            Route::prefix('teacher')->name('teacher.')->group(function () {
                Route::get('list', 'TeacherController@list')->name('list');
                Route::get('add', 'TeacherController@add')->name('add');
                Route::post('add', 'TeacherController@submitAdd')->name('add');
                Route::get('edit/{id}', 'TeacherController@edit')->name('edit');
                Route::post('edit/{id}', 'TeacherController@submitEdit')->name('edit');
                Route::post('delete/{id}', 'TeacherController@submitDelete')->name('delete');
            });

            Route::prefix('promo')->name('promo.')->group(function () {
                Route::get('list', 'PromoController@list')->name('list');
                Route::get('get-item/{id}', 'PromoController@getItem')->name('get_item');
                Route::get('add', 'PromoController@add')->name('add');
                Route::post('add', 'PromoController@submitAdd')->name('add');
                Route::get('edit/{id}', 'PromoController@edit')->name('edit');
                Route::post('edit/{id}', 'PromoController@submitEdit')->name('edit');
                Route::post('delete/{id}', 'PromoController@submitDelete')->name('delete');
            });
        });

        Route::middleware('guest')->group(function () {
            Route::get('login', 'LoginController@index')->name('login');
            Route::post('login', 'LoginController@login')->name('login');
        });

        Route::post('logout', 'LogoutController@logout')->name('logout');
    });
