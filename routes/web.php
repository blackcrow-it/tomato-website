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

Route::any('healthcheck', function () {
    return 'OK';
});

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

        Route::get('khoa-hoc/tat-ca', 'CourseController@all')->name('course.all');
        Route::get('tai-lieu/tat-ca', 'BookController@all')->name('book.all');

        Route::middleware('auth')->group(function () {
            Route::get('khoa-hoc/bat-dau/{id}', 'CourseController@start')->name('course.start');

            Route::get('bai-giang/{id}', 'PartController@index')->name('part');
            Route::post('gui-bai-viet/{part_id}', 'PartContentController@send')->name('part_content.send_mail');
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
        Route::get('get-video-key/{id}', 'PartVideoController@getKey')->middleware('cors')->name('part_video.get_key'); // Không được đổi dù bất cứ lý do gì

        Route::middleware('guest')->group(function () {
            Route::get('dang-nhap', 'LoginController@index')->name('login');
            Route::post('dang-nhap', 'LoginController@login')->name('login');

            Route::get('dang-ky', 'RegisterController@index')->name('register');
            Route::post('dang-ky', 'RegisterController@register')->name('register');

            Route::get('quen-mat-khau', 'ForgotPasswordController@index')->name('forgot');
            Route::post('quen-mat-khau', 'ForgotPasswordController@sendCodeResetPassword')->name('sendCodeResetPassword');
            Route::get('/password/reset', 'ForgotPasswordController@resetPassword')->name('resetPassword');
            Route::post('/reset', 'ForgotPasswordController@saveResetPassword')->name('saveResetPassword');
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
            Route::get('get-invoice', 'HomeController@getInvoice')->name('get_invoice');

            Route::prefix('user')->name('user.')->middleware('can:admin')->group(function () {
                Route::get('list', 'UserController@list')->name('list');
                Route::get('add', 'UserController@add')->name('add');
                Route::post('add', 'UserController@submitAdd')->name('add');
                Route::get('edit/{id}', 'UserController@edit')->name('edit');
                Route::post('edit/{id}', 'UserController@submitEdit')->name('edit');
                Route::post('delete/{id}', 'UserController@submitDelete')->name('delete');
                Route::get('get-user-courses/{id}', 'UserController@getUserCourses')->name('get_user_courses');
            });

            Route::prefix('post')->name('post.')->group(function () {
                Route::get('list', 'PostController@list')->name('list')->middleware('can:post.list');
                Route::get('add', 'PostController@add')->name('add')->middleware('can:post.add');
                Route::post('add', 'PostController@submitAdd')->name('add')->middleware('can:post.add');
                Route::get('edit/{id}', 'PostController@edit')->name('edit')->middleware('can:post.edit');
                Route::post('edit/{id}', 'PostController@submitEdit')->name('edit')->middleware('can:post.edit');
                Route::post('enabled', 'PostController@submitEnabled')->name('enabled')->middleware('can:post.edit');
                Route::post('delete/{id}', 'PostController@submitDelete')->name('delete')->middleware('can:post.delete');
                Route::post('order-in-category', 'PostController@submitOrderInCategory')->name('order_in_category')->middleware('can:post.edit');
                Route::post('order-in-position', 'PostController@submitOrderInPosition')->name('order_in_position')->middleware('can:post.edit');
                Route::get('search-post', 'PostController@getSearchPost')->name('search_post');
                Route::get('get-related-post', 'PostController@getRelatedPost')->name('get_related_post');
                Route::get('get-related-course', 'PostController@getRelatedCourse')->name('get_related_course');
            });

            Route::prefix('category')->name('category.')->group(function () {
                Route::get('list', 'CategoryController@list')->name('list')->middleware('can:category.list');
                Route::get('add', 'CategoryController@add')->name('add')->middleware('can:category.add');
                Route::post('add', 'CategoryController@submitAdd')->name('add')->middleware('can:category.add');
                Route::get('edit/{id}', 'CategoryController@edit')->name('edit')->middleware('can:category.edit');
                Route::post('edit/{id}', 'CategoryController@submitEdit')->name('edit')->middleware('can:category.edit');
                Route::post('delete/{id}', 'CategoryController@submitDelete')->name('delete')->middleware('can:category.delete');
                Route::post('enabled', 'CategoryController@submitEnabled')->name('enabled')->middleware('can:category.edit');
                Route::post('order-in-position', 'CategoryController@submitOrderInPosition')->name('order_in_position')->middleware('can:category.edit');
            });

            Route::prefix('course')->name('course.')->group(function () {
                Route::get('list', 'CourseController@list')->name('list')->middleware('can:course.list');
                Route::get('add', 'CourseController@add')->name('add')->middleware('can:course.add');
                Route::post('add', 'CourseController@submitAdd')->name('add')->middleware('can:course.add');
                Route::get('edit/{id}', 'CourseController@edit')->name('edit')->middleware('can:course.edit');
                Route::post('edit/{id}', 'CourseController@submitEdit')->name('edit')->middleware('can:course.edit');
                Route::post('enabled', 'CourseController@submitEnabled')->name('enabled')->middleware('can:course.edit');
                Route::post('delete/{id}', 'CourseController@submitDelete')->name('delete')->middleware('can:course.delete');
                Route::post('order-in-category', 'CourseController@submitOrderInCategory')->name('order_in_category')->middleware('can:course.edit');
                Route::post('order-in-position', 'CourseController@submitOrderInPosition')->name('order_in_position')->middleware('can:course.edit');
                Route::get('search-course', 'CourseController@getSearchCourse')->name('search_course');
                Route::get('get-related-course', 'CourseController@getRelatedCourse')->name('get_related_course');
                Route::get('get-related-book', 'CourseController@getRelatedBook')->name('get_related_book');
            });

            Route::prefix('combo_courses')->name('combo_courses.')->group(function () {
                Route::get('list', 'ComboCourseController@list')->name('list')->middleware('can:course.list');
                Route::get('add', 'ComboCourseController@add')->name('add')->middleware('can:course.add');
                Route::post('add', 'ComboCourseController@submitAdd')->name('add')->middleware('can:course.add');
                Route::get('edit/{id}', 'ComboCourseController@edit')->name('edit')->middleware('can:course.edit');
                Route::post('edit/{id}', 'ComboCourseController@submitEdit')->name('edit')->middleware('can:course.edit');
                Route::post('enabled', 'ComboCourseController@submitEnabled')->name('enabled')->middleware('can:course.edit');
                Route::post('delete/{id}', 'ComboCourseController@submitDelete')->name('delete')->middleware('can:course.delete');
                Route::get('search-course', 'ComboCourseController@getSearchCourse')->name('search_course');
                Route::get('get-related-course', 'ComboCourseController@getRelatedCourse')->name('get_related_course');
            });

            Route::prefix('lesson')->name('lesson.')->group(function () {
                Route::get('list', 'LessonController@list')->name('list')->middleware('can:course.list');
                Route::get('add', 'LessonController@add')->name('add')->middleware('can:course.add');
                Route::post('add', 'LessonController@submitAdd')->name('add')->middleware('can:course.add');
                Route::get('edit/{id}', 'LessonController@edit')->name('edit')->middleware('can:course.edit');
                Route::post('edit/{id}', 'LessonController@submitEdit')->name('edit')->middleware('can:course.edit');
                Route::post('enabled', 'LessonController@submitEnabled')->name('enabled')->middleware('can:course.edit');
                Route::post('delete/{id}', 'LessonController@submitDelete')->name('delete')->middleware('can:course.delete');
                Route::post('order-in-course', 'LessonController@submitOrderInCourse')->name('order_in_course')->middleware('can:course.edit');
            });

            Route::prefix('part')->name('part.')->group(function () {
                Route::get('list', 'PartController@list')->name('list')->middleware('can:course.list');
                Route::get('add', 'PartController@add')->name('add')->middleware('can:course.add');
                Route::post('add', 'PartController@submitAdd')->name('add')->middleware('can:course.add');
                Route::post('enabled', 'PartController@submitEnabled')->name('enabled')->middleware('can:course.edit');
                Route::post('order-in-lesson', 'PartController@submitOrderInLesson')->name('order_in_lesson')->middleware('can:course.edit');
            });

            Route::prefix('part-video')->name('part_video.')->group(function () {
                Route::get('edit/{part_id}', 'PartVideoController@edit')->name('edit')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('edit/{part_id}', 'PartVideoController@submitEdit')->name('edit')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('delete/{part_id}', 'PartVideoController@submitDelete')->name('delete')->middleware('can:course.delete');
                Route::post('ajax-upload-transcode', 'PartVideoController@uploadTranscode')->name('upload_transcode')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('ajax-upload-video', 'PartVideoController@uploadVideo')->name('upload_video')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('ajax-upload-drive', 'PartVideoController@uploadDrive')->name('upload_drive')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('ajax-clear-s3', 'PartVideoController@clearS3')->name('clear_s3')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('ajax-get-drive-token', 'PartVideoController@getGoogleDriveToken')->name('get_drive_token')->middleware(['can:course.add', 'can:course.edit']);
            });

            Route::prefix('part-youtube')->name('part_youtube.')->group(function () {
                Route::get('edit/{part_id}', 'PartYoutubeController@edit')->name('edit')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('edit/{part_id}', 'PartYoutubeController@submitEdit')->name('edit')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('delete/{part_id}', 'PartYoutubeController@submitDelete')->name('delete')->middleware('can:course.delete');
            });

            Route::prefix('part-content')->name('part_content.')->group(function () {
                Route::get('edit/{part_id}', 'PartContentController@edit')->name('edit')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('edit/{part_id}', 'PartContentController@submitEdit')->name('edit')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('delete/{part_id}', 'PartContentController@submitDelete')->name('delete')->middleware('can:course.delete');
            });

            Route::prefix('part-test')->name('part_test.')->group(function () {
                Route::get('edit/{part_id}', 'PartTestController@edit')->name('edit')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('edit/{part_id}', 'PartTestController@submitEdit')->name('edit')->middleware(['can:course.add', 'can:course.edit']);
                Route::get('get-data/{part_id}', 'PartTestController@getData')->name('get_data')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('delete/{part_id}', 'PartTestController@submitDelete')->name('delete')->middleware('can:course.delete');
                Route::post('upload-audio', 'PartTestController@uploadAudio')->name('upload_audio')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('delete-audio', 'PartTestController@deleteAudio')->name('delete_audio')->middleware(['can:course.add', 'can:course.edit']);
            });

            Route::prefix('part-survey')->name('part_survey.')->group(function () {
                Route::get('edit/{part_id}', 'PartSurveyController@edit')->name('edit')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('edit/{part_id}', 'PartSurveyController@submitEdit')->name('edit')->middleware(['can:course.add', 'can:course.edit']);
                Route::post('delete/{part_id}', 'PartSurveyController@submitDelete')->name('delete')->middleware('can:course.delete');
            });

            Route::prefix('recharge')->name('recharge.')->middleware('can:recharge.list')->group(function () {
                Route::get('list', 'RechargeController@list')->name('list');
                Route::get('get-data', 'RechargeController@getData')->name('get_data');
                Route::post('recheck', 'RechargeController@recheck')->name('recheck');
            });

            Route::prefix('setting')->name('setting.')->middleware('can:admin')->group(function () {
                Route::get('edit/{view}', 'SettingController@edit')->name('edit');
                Route::post('submit', 'SettingController@submit')->name('submit');
                Route::post('upload-image', 'SettingController@uploadImage')->name('upload_image');
                Route::get('drive/redirect', 'SettingController@redirectAuthGoogleDriveApi')->name('drive.redirect');
                Route::get('drive/callback', 'SettingController@callbackAuthGoogleDriveApi')->name('drive.callback');
            });

            Route::prefix('book')->name('book.')->group(function () {
                Route::get('list', 'BookController@list')->name('list')->middleware('can:book.list');
                Route::get('add', 'BookController@add')->name('add')->middleware('can:book.add');
                Route::post('add', 'BookController@submitAdd')->name('add')->middleware('can:book.add');
                Route::get('edit/{id}', 'BookController@edit')->name('edit')->middleware('can:book.edit');
                Route::post('edit/{id}', 'BookController@submitEdit')->name('edit')->middleware('can:book.edit');
                Route::post('enabled', 'BookController@submitEnabled')->name('enabled')->middleware('can:book.edit');
                Route::post('delete/{id}', 'BookController@submitDelete')->name('delete')->middleware('can:book.delete');
                Route::post('order-in-category', 'BookController@submitOrderInCategory')->name('order_in_category')->middleware('can:book.edit');
                Route::post('order-in-position', 'BookController@submitOrderInPosition')->name('order_in_position')->middleware('can:book.edit');
                Route::get('search-book', 'BookController@getSearchBook')->name('search_book');
                Route::get('get-related-course', 'BookController@getRelatedCourse')->name('get_related_course');
                Route::get('get-related-book', 'BookController@getRelatedBook')->name('get_related_book');
            });

            Route::prefix('invoice')->name('invoice.')->middleware('can:invoice.list')->group(function () {
                Route::get('list', 'InvoiceController@list')->name('list');
                Route::get('detail/{id}', 'InvoiceController@detail')->name('detail');
                Route::post('detail/{id}/change-status', 'InvoiceController@changeStatus')->name('change_status');
            });

            Route::prefix('teacher')->name('teacher.')->group(function () {
                Route::get('list', 'TeacherController@list')->name('list')->middleware('can:teacher.list');
                Route::get('add', 'TeacherController@add')->name('add')->middleware('can:teacher.add');
                Route::post('add', 'TeacherController@submitAdd')->name('add')->middleware('can:teacher.add');
                Route::get('edit/{id}', 'TeacherController@edit')->name('edit')->middleware('can:teacher.edit');
                Route::post('edit/{id}', 'TeacherController@submitEdit')->name('edit')->middleware('can:teacher.edit');
                Route::post('delete/{id}', 'TeacherController@submitDelete')->name('delete')->middleware('can:teacher.delete');
            });

            Route::prefix('promo')->name('promo.')->middleware('can:promo.list')->group(function () {
                Route::get('list', 'PromoController@list')->name('list');
                Route::get('get-item/{id}', 'PromoController@getItem')->name('get_item');
                Route::get('add', 'PromoController@add')->name('add');
                Route::post('add', 'PromoController@submitAdd')->name('add');
                Route::get('edit/{id}', 'PromoController@edit')->name('edit');
                Route::post('edit/{id}', 'PromoController@submitEdit')->name('edit');
                Route::post('delete/{id}', 'PromoController@submitDelete')->name('delete');
            });

            Route::prefix('permission')->name('permission.')->middleware('can:admin')->group(function () {
                Route::get('list', 'PermissionController@list')->name('list');
                Route::get('add', 'PermissionController@add')->name('add');
                Route::post('add', 'PermissionController@submitAdd')->name('add');
                Route::get('edit/{id}', 'PermissionController@edit')->name('edit');
                Route::post('edit/{id}', 'PermissionController@submitEdit')->name('edit');
                Route::post('delete/{id}', 'PermissionController@submitDelete')->name('delete');
            });
        });

        Route::middleware('guest')->group(function () {
            Route::get('login', 'LoginController@index')->name('login');
            Route::post('login', 'LoginController@login')->name('login');
        });

        Route::post('logout', 'LogoutController@logout')->name('logout');
    });
