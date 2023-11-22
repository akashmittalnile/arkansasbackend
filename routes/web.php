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
    
    Route::get('/check_sku_code', 'SuperAdminController@checkSkuCode')->name('checkSkuCode');
    Route::get('/check_coupon_code', 'SuperAdminController@checkCouponCode')->name('checkCouponCode');
    Route::post('/image-upload', 'SuperAdminController@imageUpload')->name('imageUpload');
    Route::post('/image-delete', 'SuperAdminController@destroy')->name('imageDelete');
    Route::get('/check_password', 'SuperAdminController@checkPassword')->name('checkPassword');
    
    /**
     * Register Routes
     */
    Route::get('/register', 'RegisterController@show')->name('register.show');
    Route::post('/register', 'RegisterController@register')->name('register.perform');
    Route::get('/check_status', 'HomeController@check_status')->name('admin.check_status');
    Route::get('/check_email', 'HomeController@check_email')->name('admin.check_email');


    Route::get('/forgot_password', 'RegisterController@forgot_password')->name('admin.forgot.password');
    Route::post('/forgot_password', 'RegisterController@forgot_password_email')->name('admin.forgot_password.email');
    Route::get('/reset_password/{email}', 'RegisterController@reset_password')->name('admin.reset_password');
    Route::post('/reset_password', 'RegisterController@reset_password_otp')->name('admin.reset_password.otp');
    Route::get('/change_password/{email}', 'RegisterController@change_password')->name('admin.change_password');
    Route::post('/change_password', 'RegisterController@change_password_update')->name('admin.change_password_update');

    Route::get('/super-admin/forgot_password', 'AdminLoginController@forgot_password')->name('SA.forgot.password');
    Route::post('/super-admin/forgot_password', 'AdminLoginController@forgot_password_email')->name('SA.forgot_password.email');
    Route::get('/super-admin/reset_password/{email}', 'AdminLoginController@reset_password')->name('SA.reset_password');
    Route::post('/super-admin/reset_password', 'AdminLoginController@reset_password_otp')->name('SA.reset_password.otp');
    Route::get('/super-admin/change_password/{email}', 'AdminLoginController@change_password')->name('SA.change_password');
    Route::post('/super-admin/change_password', 'AdminLoginController@change_password_update')->name('SA.change_password_update');


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


    Route::group(['middleware' => ['isContentCreator']], function() {
        /**
         * Logout Routes
         */
        Route::get('/admin/logout', 'LogoutController@contentCreatorLogout')->name('logout.perform');
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
        Route::post('/help-support-save-img', 'HomeController@help_support_save_img')->name('Home.HelpSupport.Save.Img');

        Route::get('/addcourse', 'HomeController@addcourse')->name('Home.Addcourse');
        Route::get('/admin/addcourse2/{courseID}', 'HomeController@add_course2')->name('Home.Addcourse2');
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
        Route::get('/admin/change-ordering/{chapterid}', 'HomeController@changeOrdering')->name('admin.change-answer');
        Route::get('/admin/change-answer-option/{id}', 'HomeController@changeAnswerOption')->name('admin.change-answer.option');
        Route::post('/admin/add-new-question', 'HomeController@addNewQuestion')->name('admin.add.new.Question');
        Route::post('/admin/add-new-question-survey', 'HomeController@addNewSurveyQuestion')->name('admin.add.new.survey.Question');

        Route::get('/earnings', 'HomeController@earnings')->name('Home.earnings');
        Route::get('/order-details/{id}', 'HomeController@orderDetails')->name('Home.order.details');
        Route::get('/payment-request', 'HomeController@paymentRequest')->name('Home.payment.request');
        Route::post('/payment-request', 'HomeController@paymentRequestStore')->name('Home.payment.request.store');

        Route::post('/admin/update-title-percentage/{id}', 'HomeController@updateTitlePercentage')->name('Home.Update.Details');
        Route::get('/admin/change-prerequisite', 'HomeController@changePrerequisite')->name('Home.change.prerequisite');
        
        Route::get('/download-earnings', 'HomeController@downloadEarnings')->name('Home.download.earnings');
        Route::get('/download-invoice/{id}', 'HomeController@downloadInvoice')->name('Home.download.invoice');

        Route::get('/admin/clear-notification', 'HomeController@clearNotification')->name('Home.clear.notification');

        Route::get('/admin/students', 'HomeController@students')->name('Home.students');
        Route::get('/admin/student-details/{id}', 'HomeController@studentDetails')->name('Home.student.details');
        Route::get('/admin/completion-status/{courseId}/{id}', 'HomeController@progressReport')->name('Home.progress.report');
        Route::get('/admin/students-result/{id}', 'HomeController@studentResult')->name('Home.student.result');
    });

    Route::group(['middleware' => ['isSuperAdmin']], function() {
        Route::get('/super-admin/logout', 'LogoutController@superAdminLogout')->name('SA.logout.perform');
        Route::get('/super-admin/dashboard', 'SuperAdminController@dashboard')->name('SA.Dashboard');
        Route::get('/super-admin/my-account', 'SuperAdminController@myAccount')->name('SA.My.Account');
        Route::post('/super-admin/my-data', 'SuperAdminController@storeMyData')->name('SA.Store.Mydata');
        Route::post('/super-admin/setting', 'SuperAdminController@storeSetting')->name('SA.Store.Setting');
        Route::post('/super-admin/change-password', 'SuperAdminController@changePassword')->name('SA.Change.Password');

        Route::get('/super-admin/addcourse', 'SuperAdminController@add_course')->name('SA.AddCourse');
        Route::get('/super-admin/addproduct', 'SuperAdminController@add_product')->name('SA.AddProduct');
        Route::post('/super-admin/submitcourse', 'SuperAdminController@submitcourse')->name('SA.SubmitCourse');
        Route::post('/super-admin/submitproduct', 'SuperAdminController@submitproduct')->name('SA.SubmitProduct');

        Route::get('/super-admin/help-support', 'SuperAdminController@help_support')->name('SA.HelpSupport');
        Route::post('/super-admin/help-support-save-img', 'SuperAdminController@help_support_save_img')->name('SA.HelpSupport.Save.Img');

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
        Route::get('/super-admin/change-ordering/{chapterid}', 'SuperAdminController@changeOrdering')->name('SA.change-answer');
        Route::get('/super-admin/change-answer-option/{id}', 'SuperAdminController@changeAnswerOption')->name('SA.change-answer.option');
        Route::get('/super-admin/delete-video/{id}', 'SuperAdminController@deleteVideo')->name('SA.DeleteVideo');
        Route::get('/super-admin/delete-pdf/{id}', 'SuperAdminController@deletePdf')->name('SA.DeletePDF');
        Route::get('/super-admin/update-option-list', 'SuperAdminController@updateOptionList')->name('SA.UpdateOptionList');
        Route::get('/super-admin/update-question-list', 'SuperAdminController@updateQuestionList')->name('SA.UpdateQuestionList');

        Route::post('/super-admin/add-new-question', 'SuperAdminController@addNewQuestion')->name('SA.add.new.Question');
        Route::post('/super-admin/add-new-question-survey', 'SuperAdminController@addNewSurveyQuestion')->name('SA.add.new.survey.Question');

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
        Route::get('/super-admin/view-product/{id}', 'SuperAdminController@productViewDetails')->name('SA.Product.View.Details');

        Route::get('/super-admin/listed-course/{id}', 'SuperAdminController@listed_course')->name('SA.ListedCourse');
        Route::get('/super-admin/inactive/{id}', 'SuperAdminController@InactiveStatus')->name('SA.InactiveStatus');
        Route::post('/super-admin/SaveStatusCourse', 'SuperAdminController@SaveStatusCourse')->name('SaveStatusCourse');
        Route::post('/super-admin/save-course-fee', 'SuperAdminController@save_course_fee')->name('Savecoursefee');
        Route::get('/super-admin/account-approval-request', 'SuperAdminController@account_approval_request')->name('SA.AccountApprovalRequest');
        Route::get('/super-admin/update-approval-request/{id}/{status}', 'SuperAdminController@update_approval_request')->name('SA.UpdateApprovalRequest');
        Route::get('/super-admin/addcourse2/{userID}/{courseID}/{chapterID?}', 'SuperAdminController@addcourse2')->name('SA.Addcourse2');
        Route::post('/super-admin/listed-course/submit-chapter', 'SuperAdminController@newListedCourseChapter')->name('SA.Listed-Course.SubmitChapter');
        Route::post('/super-admin/listed-course/edit-submit-chapter', 'SuperAdminController@editListedCourseChapter')->name('SA.Listed-Course.EditSubmitChapter');
        Route::get('/super-admin/listed-course/delete-chapter/{id}/{userID}', 'SuperAdminController@deleteListedCourseChapter')->name('SA.Listed-Course.DeleteChapter');
        // Route::get('/super-admin/addcourse2/{userID}/{courseID}/{chapterID}', 'SuperAdminController@course_list')->name('SA.CourseList');

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
        Route::post('/super-admin/add-coupon', 'SuperAdminController@add_coupon')->name('SA.Store.Coupon');
        Route::get('/super-admin/get-coupon-details', 'SuperAdminController@get_coupon_details')->name('SA.Get.Detail.Coupon');
        Route::post('/super-admin/update-coupon', 'SuperAdminController@update_coupon')->name('SA.Update.Coupon');
        Route::get('/super-admin/delete-coupon/{id}', 'SuperAdminController@delete_coupon')->name('SA.Coupon.Delete');

        Route::post('update-title-percentage/{id}', 'SuperAdminController@updateTitlePercentage')->name('SA.update.title.percentage');
        Route::get('change-prerequisite', 'SuperAdminController@changePrerequisite')->name('SA.change.prerequisite');

        Route::get('/super-admin/download-earnings', 'SuperAdminController@downloadEarnings')->name('SA.Download.Earnings');

        Route::get('/super-admin/notifications', 'SuperAdminController@notifications')->name('SA.Notifications');
        Route::get('/super-admin/create-notifications', 'SuperAdminController@createNotifications')->name('SA.Create.Notifications');
        Route::post('/super-admin/store-notifications', 'SuperAdminController@storeNotifications')->name('SA.Store.Notifications');
        Route::get('/super-admin/delete-notifications/{id}', 'SuperAdminController@deleteNotifications')->name('SA.Delete.Notifications');
       
        Route::get('/super-admin/download-invoice/{id}', 'SuperAdminController@downloadInvoice')->name('SA.download.invoice');

        Route::get('/super-admin/clear-notification', 'SuperAdminController@clearNotification')->name('SA.clear.notification');

        Route::get('/super-admin/completion-status/{courseId}/{id}', 'SuperAdminController@progressReport')->name('SA.progress.report');

        Route::get('/super-admin/orders', 'SuperAdminController@product_orders')->name('SA.Product.Orders');
        Route::get('/super-admin/orders-details/{id}', 'SuperAdminController@product_order_details')->name('SA.Product.order.details');

        Route::get('/super-admin/posts', 'SuperAdminController@posts')->name('SA.Posts');
        Route::get('/super-admin/create-post', 'SuperAdminController@create_post')->name('SA.Create.Post');
        Route::post('/super-admin/store-post', 'SuperAdminController@store_post')->name('SA.Submit.Post');
        Route::get('/super-admin/delete-post/{id}', 'SuperAdminController@delete_post')->name('SA.Delete.Post');
        Route::get('/super-admin/edit-post/{id}', 'SuperAdminController@edit_post')->name('SA.Edit.Post');
        Route::post('/super-admin/update-post', 'SuperAdminController@update_post')->name('SA.Update.Post');

        Route::get('/super-admin/content-creator-course', 'SuperAdminController@content_creator_course')->name('SA.Content-Creator.Course');
        Route::get('/super-admin/content-creator-course/chapters/{courseID}/{chapterID?}', 'SuperAdminController@content_creator_course_chapters')->name('SA.Content-Creator.Course.Chapter');
        Route::post('/super-admin/content-creator-course/submit-chapter', 'SuperAdminController@newContentCourseChapter')->name('SA.Content-Creator.SubmitChapter');
        Route::post('/super-admin/content-creator-course/edit-submit-chapter', 'SuperAdminController@editContentCourseChapter')->name('SA.Content-Creator.EditSubmitChapter');
        Route::get('/super-admin/content-creator-course/delete-chapter/{id}', 'SuperAdminController@deleteContentCourseChapter')->name('SA.Content-Creator.DeleteChapter');
    });
    
});