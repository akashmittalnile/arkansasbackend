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
        Route::get('/check_status', 'HomeController@check_status')->name('admin.check_status');

        /**
         * Login Routes
         */
        Route::get('/super-admin/login', 'AdminLoginController@show')->name('SA.LoginShow');
        Route::post('/super-admin/login', 'AdminLoginController@login')->name('SA.login.perform');

        Route::get('/login', 'LoginController@show')->name('login');
        Route::post('/login', 'LoginController@login')->name('login.perform');

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
        Route::get('/my-account', 'HomeController@myAccount')->name('Home.my.account');
        Route::post('/my-data', 'HomeController@storeMyData')->name('Home.store.mydata');
        Route::post('/bank-info', 'HomeController@bankInfo')->name('Home.store.bank.info');
        Route::get('/edit-course/{id}', 'HomeController@editCourse')->name('Home.edit.course');
        Route::post('/edit-course', 'HomeController@updateCourseDetails')->name('Home.updateCourseDetails');
        Route::get('/view-course-details/{id}', 'HomeController@viewCourse')->name('Home.view.course');
        Route::get('/delete-course/{id}', 'HomeController@deleteCourse')->name('Home.delete.course');
        Route::get('/performance', 'HomeController@performance')->name('Home.Performance');
        Route::get('/help-support', 'HomeController@helpSupport')->name('Home.HelpSupport');
        Route::get('/addcourse', 'HomeController@addcourse')->name('Home.Addcourse');
        Route::get('/admin/addcourse2/{courseID}', 'HomeController@addcourse2')->name('Home.Addcourse2');
        Route::get('/admin/addcourse2/{courseID}/{chapterID}', 'HomeController@course_list')->name('Home.CourseList');
        Route::post('/submitcourse', 'HomeController@submitcourse')->name('Home.submitcourse');
        Route::post('/submitquestion', 'HomeController@submitquestion')->name('Home.SaveQuestion');
        Route::get('/delete_option2/{id}', 'HomeController@delete_option2')->name('admin.DeleteOption2');
        Route::get('/admin/delete-question/{id}', 'HomeController@delete_question')->name('admin.DeleteQuestion');
        Route::get('/admin/delete-section/{id}', 'HomeController@delete_section')->name('admin.DeleteSection');
        Route::get('/admin/delete-video/{id}', 'HomeController@delete_video')->name('admin.DeleteVideo');
        Route::post('/admin/add-video', 'HomeController@add_video')->name('admin.add.Video');
        Route::post('/admin/add-pdf', 'HomeController@add_pdf')->name('admin.add.pdf');
        Route::get('/admin/update_option_list', 'HomeController@update_option_list')->name('admin.UpdateOptionList');
        Route::get('/admin/update_question_list', 'HomeController@update_question_list')->name('admin.update_question_list');
        Route::get('/admin/delete-pdf/{id}', 'HomeController@delete_pdf')->name('admin.DeletePDF');
        Route::post('/admin/submit-chapter', 'HomeController@submitCourseChapter')->name('admin.SubmitChapter');
        Route::post('/admin/edit-chapter', 'HomeController@editCourseChapter')->name('admin.EditSubmitChapter');
        Route::get('/admin/delete-chapter/{id}', 'HomeController@deleteCourseChapter')->name('admin.DeleteChapter');
        Route::get('/admin/delete-quiz/{id}', 'HomeController@deleteQuiz')->name('admin.DeleteQuiz');
        Route::post('/admin/save-answer', 'HomeController@SaveAnswer')->name('SaveAnswer');
        Route::get('/admin/add-option', 'HomeController@addOption')->name('admin.add-option');
        Route::get('/admin/change-ordering/{chapterid}/{id}/{val}', 'HomeController@changeOrdering')->name('admin.change-answer');
        Route::get('/admin/change-answer-option/{id}/{val}', 'HomeController@changeAnswerOption')->name('admin.change-answer.option');

        Route::get('/earnings', 'HomeController@earnings')->name('Home.earnings');
        Route::get('/order-details/{id}', 'HomeController@orderDetails')->name('Home.order.details');
        Route::get('/payment-request', 'HomeController@paymentRequest')->name('Home.payment.request');
        Route::post('/payment-request', 'HomeController@paymentRequestStore')->name('Home.payment.request.store');
        
        Route::get('/download-earnings', 'HomeController@downloadEarnings')->name('Home.download.earnings');
        Route::get('/download-invoice/{id}', 'HomeController@downloadInvoice')->name('Home.download.invoice');
    });

    Route::group(['middleware' => ['auth.superadmin']], function() {
        Route::get('/super-admin/dashboard', 'SuperAdminController@dashboard')->name('SA.Dashboard');
        Route::get('/super-admin/my-account', 'SuperAdminController@myAccount')->name('SA.My.Account');
        Route::post('/super-admin/my-data', 'SuperAdminController@storeMyData')->name('SA.Store.Mydata');
        Route::get('/super-admin/addcourse', 'SuperAdminController@add_course')->name('SA.AddCourse');
        Route::get('/super-admin/addproduct', 'SuperAdminController@add_product')->name('SA.AddProduct');
        Route::post('/super-admin/submitcourse', 'SuperAdminController@submitcourse')->name('SA.SubmitCourse');
        Route::post('/super-admin/submitproduct', 'SuperAdminController@submitproduct')->name('SA.SubmitProduct');
        Route::get('/super-admin/help-support', 'SuperAdminController@help_support')->name('SA.HelpSupport');
        Route::get('/super-admin/performance', 'SuperAdminController@performance')->name('SA.Performance');
        Route::get('/super-admin/content-creators', 'SuperAdminController@content_creators')->name('SA.ContentCreators');
        Route::get('/super-admin/course', 'SuperAdminController@course')->name('SA.Course');
        Route::get('/super-admin/course/{courseID}/{chapterID?}', 'SuperAdminController@courseChapter')->name('SA.Course.Chapter');
        Route::post('/super-admin/add-chapter', 'SuperAdminController@addChapter')->name('SA.Course.Addchapter');
        Route::post('/super-admin/submit-chapter', 'SuperAdminController@newCourseChapter')->name('SA.SubmitChapter');
        Route::post('/super-admin/edit-submit-chapter', 'SuperAdminController@editCourseChapter')->name('SA.EditSubmitChapter');
        Route::get('/super-admin/delete-chapter/{id}', 'SuperAdminController@deleteCourseChapter')->name('SA.DeleteChapter');
        Route::get('/super-admin/delete-quiz/{id}', 'SuperAdminController@deleteChapterQuiz')->name('SA.DeleteQuiz');
        Route::get('/super-admin/delete-section/{id}', 'SuperAdminController@deleteChapterSection')->name('SA.DeleteSection');
        Route::get('/super-admin/delete-question/{id}', 'SuperAdminController@deleteChapterQuestion')->name('SA.DeleteQuestion');
        Route::get('/super-admin/delete-option/{id}', 'SuperAdminController@deleteOption')->name('SA.DeleteOption');
        Route::post('/super-admin/save-answer', 'SuperAdminController@SaveAnswer')->name('SA.SaveAnswer');
        Route::get('/super-admin/add-option', 'SuperAdminController@addOption')->name('SA.add-option');
        Route::get('/super-admin/change-ordering/{chapterid}/{id}/{val}', 'SuperAdminController@changeOrdering')->name('SA.change-answer');
        Route::get('/super-admin/change-answer-option/{id}/{val}', 'SuperAdminController@changeAnswerOption')->name('SA.change-answer.option');
        Route::get('/super-admin/delete-video/{id}', 'SuperAdminController@deleteVideo')->name('SA.DeleteVideo');
        Route::get('/super-admin/delete-pdf/{id}', 'SuperAdminController@deletePdf')->name('SA.DeletePDF');
        Route::get('/super-admin/update-option-list', 'SuperAdminController@updateOptionList')->name('SA.UpdateOptionList');
        Route::get('/super-admin/update-question-list', 'SuperAdminController@updateQuestionList')->name('SA.UpdateQuestionList');

        Route::get('/super-admin/edit-course/{id}', 'SuperAdminController@editCourse')->name('SA.edit.course');
        Route::post('/super-admin/edit-course', 'SuperAdminController@updateCourseDetails')->name('SA.updateCourseDetails');
        Route::get('/super-admin/delete-course/{id}', 'SuperAdminController@deleteCourse')->name('SA.delete.course');
        Route::get('/super-admin/view-course-details/{id}', 'SuperAdminController@viewCourse')->name('SA.view.course');
        
        Route::get('/super-admin/earnings', 'SuperAdminController@earnings')->name('SA.Earnings');
        Route::get('/super-admin/products', 'SuperAdminController@products')->name('SA.Products');
        Route::get('/super-admin/edit-product/{id}', 'SuperAdminController@editProduct')->name('SA.Edit.Products');
        Route::post('/super-admin/update-product', 'SuperAdminController@updateProduct')->name('SA.Update.Products');
        Route::get('/super-admin/delete-product/{id}', 'SuperAdminController@deleteProduct')->name('SA.Delete.Products');
        Route::get('/super-admin/delete-product-image/{id}', 'SuperAdminController@deleteProductImage')->name('SA.Delete.Products.Image');
        Route::get('/super-admin/listed-course/{id}', 'SuperAdminController@listed_course')->name('SA.ListedCourse');
        Route::get('/super-admin/inactive/{id}', 'SuperAdminController@InactiveStatus')->name('SA.InactiveStatus');
        Route::post('/super-admin/SaveStatusCourse', 'SuperAdminController@SaveStatusCourse')->name('SaveStatusCourse');
        Route::post('/super-admin/save-course-fee', 'SuperAdminController@save_course_fee')->name('Savecoursefee');
        Route::get('/super-admin/account-approval-request', 'SuperAdminController@account_approval_request')->name('SA.AccountApprovalRequest');
        Route::get('/super-admin/update-approval-request/{id}/{status}', 'SuperAdminController@update_approval_request')->name('SA.UpdateApprovalRequest');
        Route::get('/super-admin/addcourse2/{userID}/{courseID}', 'SuperAdminController@addcourse2')->name('SA.Addcourse2');
        Route::get('/super-admin/addcourse2/{userID}/{courseID}/{chapterID}', 'SuperAdminController@course_list')->name('SA.CourseList');

        Route::get('/super-admin/payment-requests/{userID}', 'SuperAdminController@payment_request')->name('SA.Payment.Request');
        Route::get('/super-admin/changes-payout-status/{id}/{status}', 'SuperAdminController@change_payout_status')->name('SA.Change.Payout.Status');

        Route::get('/super-admin/tag-listing', 'SuperAdminController@tag_listing')->name('SA.TagListing');
        Route::get('/load-sectors', 'SuperAdminController@loadSectors')->name('load-sectors');
        Route::get('/super-admin/delete-tags/{id}', 'SuperAdminController@delete_tags')->name('admin.DeleteTags');
        Route::post('/super-admin/SaveTag', 'SuperAdminController@SaveTag')->name('SA.SaveTag');
        Route::post('/super-admin/UpdateTag', 'SuperAdminController@UpdateTag')->name('SA.UpdateTag');
        Route::get('/super-admin/students', 'SuperAdminController@students')->name('SA.Students');
        Route::get('/super-admin/student-detail/{id}', 'SuperAdminController@student_detail')->name('SA.StudentDetail');

        Route::get('/super-admin/category', 'SuperAdminController@category')->name('SA.Category');
        Route::get('/super-admin/add-category', 'SuperAdminController@add_category')->name('SA.AddCategory');
        Route::get('/super-admin/edit-category/{id}', 'SuperAdminController@edit_category')->name('SA.EditCategory');
        Route::post('/super-admin/submitcategoty', 'SuperAdminController@submit_category')->name('SA.SubmitCategory');
        Route::post('/super-admin/update-categoty', 'SuperAdminController@update_category')->name('SA.UpdateCategory');
        Route::get('/super-admin/delete-category/{id}', 'SuperAdminController@delete_categoty')->name('SA.DeleteCategoty');

        Route::get('/super-admin/coupons', 'SuperAdminController@coupons')->name('SA.Coupons');

        Route::get('/super-admin/download-earnings', 'SuperAdminController@downloadEarnings')->name('SA.Download.Earnings');

        Route::get('/super-admin/notifications', 'SuperAdminController@notifications')->name('SA.Notifications');
        Route::get('/super-admin/create-notifications', 'SuperAdminController@createNotifications')->name('SA.Create.Notifications');
        Route::post('/super-admin/store-notifications', 'SuperAdminController@storeNotifications')->name('SA.Store.Notifications');
       
    });
    
});