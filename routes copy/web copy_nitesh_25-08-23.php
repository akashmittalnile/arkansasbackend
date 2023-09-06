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

Route::group(['namespace' => 'App\Http\Controllers'], function()
{   
    /**
     * Home Routes
     */
    

    Route::group(['middleware' => ['guest']], function() {
        /**
         * Register Routes
         */
        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');
        Route::get('/check_status', 'HomeController@check_status')->name('check_status');

        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')->name('login');
        //Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');
        Route::get('/super-admin/login', 'SuperAdminController@dashboard')->name('SuperAdmin.Login');
        Route::get('/super-admin/dashboard', 'SuperAdminController@dashboard')->name('SA.Dashboard');
        Route::get('/super-admin/addcourse', 'SuperAdminController@add_course')->name('SA.AddCourse');
        Route::post('/super-admin/submitcourse', 'SuperAdminController@submitcourse')->name('SA.SubmitCourse');
        Route::get('/super-admin/help-support', 'SuperAdminController@help_support')->name('SA.HelpSupport');
        Route::get('/super-admin/performance', 'SuperAdminController@performance')->name('SA.Performance');
        Route::get('/super-admin/content-creators', 'SuperAdminController@content_creators')->name('SA.ContentCreators');
        Route::get('/super-admin/course', 'SuperAdminController@course')->name('SA.Course');
        Route::get('/super-admin/students', 'SuperAdminController@students')->name('SA.Students');
        Route::get('/super-admin/earnings', 'SuperAdminController@earnings')->name('SA.Earnings');
        Route::get('/super-admin/products', 'SuperAdminController@products')->name('SA.Products');
        Route::get('/super-admin/notifications', 'SuperAdminController@notifications')->name('SA.Notifications');
        Route::get('/super-admin/listed-course/{id}', 'SuperAdminController@listed_course')->name('SA.ListedCourse');
        Route::get('/super-admin/inactive/{id}', 'SuperAdminController@InactiveStatus')->name('SA.InactiveStatus');
        Route::post('/super-admin/SaveStatusCourse', 'SuperAdminController@SaveStatusCourse')->name('SaveStatusCourse');
        Route::post('/super-admin/save-course-fee', 'SuperAdminController@save_course_fee')->name('Savecoursefee');
        Route::get('/super-admin/account-approval-request', 'SuperAdminController@account_approval_request')->name('SA.AccountApprovalRequest');
        Route::get('/super-admin/update-approval-request/{id}/{status}', 'SuperAdminController@update_approval_request')->name('SA.UpdateApprovalRequest');
        Route::get('/super-admin/addcourse2/{userID}/{courseID}', 'SuperAdminController@addcourse2')->name('SA.Addcourse2');
        Route::get('/super-admin/addcourse2/{userID}/{courseID}/{chapterID}', 'SuperAdminController@course_list')->name('SA.CourseList');

        Route::get('/super-admin/tag-listing', 'SuperAdminController@tag_listing')->name('SA.TagListing');
        Route::get('/load-sectors', 'SuperAdminController@loadSectors')->name('load-sectors');
        Route::get('/super-admin/delete-tags/{id}', 'SuperAdminController@delete_tags')->name('admin.DeleteTags');
        Route::post('/super-admin/SaveTag', 'SuperAdminController@SaveTag')->name('SA.SaveTag');
        Route::post('/super-admin/UpdateTag', 'SuperAdminController@UpdateTag')->name('SA.UpdateTag');

        Route::get('/clear', function () {
            $exitCode = Artisan::call('cache:clear');
            $exitCode = Artisan::call('config:clear');
            $exitCode = Artisan::call('config:cache');
            $exitCode = Artisan::call('view:clear');
            $exitCode = Artisan::call('optimize:clear');
            $exitCode = Artisan::call('route:clear');
            return '<center>Cache clear</center>';
        });

    });

    Route::group(['middleware' => ['auth']], function() {
        /**
         * Logout Routes
         */
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
        Route::get('/', 'HomeController@index')->name('home.index');
        Route::get('/performance', 'HomeController@performance')->name('Home.Performance');
        Route::get('/help-support', 'HomeController@helpSupport')->name('Home.HelpSupport');
        Route::get('/addcourse', 'HomeController@addcourse')->name('Home.Addcourse');
        Route::get('/admin/addcourse2/{courseID}', 'HomeController@addcourse2')->name('Home.Addcourse2');
        Route::get('/admin/addcourse2/{courseID}/{chapterID}', 'HomeController@course_list')->name('Home.CourseList');
        Route::post('/submitcourse', 'HomeController@submitcourse')->name('Home.submitcourse');
        Route::post('/submitquestion', 'HomeController@submitquestion')->name('Home.SaveQuestion');
        Route::get('/delete_option2/{id}', 'HomeController@delete_option2')->name('admin.DeleteOption2');
        Route::get('/admin/delete-question/{id}', 'HomeController@delete_question')->name('admin.DeleteQuestion');
        Route::get('/admin/delete-video/{id}', 'HomeController@delete_video')->name('admin.DeleteVideo');
        Route::get('/admin/update_option_list', 'HomeController@update_option_list')->name('admin.UpdateOptionList');
        Route::get('/admin/update_question_list', 'HomeController@update_question_list')->name('admin.update_question_list');
        Route::get('/admin/delete-pdf/{id}', 'HomeController@delete_pdf')->name('admin.DeletePDF');
        Route::get('/admin/submit-chapter/{courseID}', 'HomeController@submitCourseChapter')->name('admin.SubmitChapter');
        Route::get('/admin/delete-chapter/{id}', 'HomeController@deleteCourseChapter')->name('admin.DeleteChapter');
        Route::get('/admin/delete-quiz/{id}', 'HomeController@deleteQuiz')->name('admin.DeleteQuiz');
        Route::post('/admin/save-answer', 'HomeController@SaveAnswer')->name('SaveAnswer');

    });
    
    Route::middleware(['auth', 'superadmin'])->group(function () {
        Route::get('/superadmin/dashboard', 'SuperAdminController@dashboard')->name('superadmin.dashboard');
    });
    
});