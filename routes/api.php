<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\StripeController;
use Spatie\FlareClient\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forget-password', [AuthController::class, 'forget_password']);
Route::post('forget-password-verify', [AuthController::class, 'forget_password_verify']);
Route::post('reset-password', [AuthController::class, 'reset_password']);
Route::post('verify-otp', [AuthController::class, "verify_otp"]);
Route::post('resend-otp', [AuthController::class, "resend_otp"]);

Route::get('contest/{chapterId}/{quizId}/{userId}', [ApiController::class, "contestQuizSurvey"]);
Route::get('result/{quizId}/{userId}', [ApiController::class, "resultQuizSurvey"]);
Route::post('contest-form', [ApiController::class, "contestForm"])->name('contest.form');
Route::get('survey/{chapterId}/{surveyId}/{userId}', [ApiController::class, "surveyFormUrl"]);
Route::post('survey-form', [ApiController::class, "surveyForm"])->name('survey.form');
Route::get('thank-you/{quizId}/{userId}', [ApiController::class, "resultSurvey"]);

Route::get('download-pdf/{id}/{uid}', [ApiController::class, "generate_pdf"]);
Route::get('download-invoice/{id}', [ApiController::class, "download_invoice"]);

Route::post('push-notification', [ApiController::class, "sendNotificationApi"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, "logout"]);
    Route::get('course-listing', [ApiController::class, "course_listing"]);
    Route::post('trending-course', [ApiController::class, "trending_course"]);
    Route::post('wishlist-listing', [ApiController::class, "wishlist_listing"]);
    Route::get('all-category', [ApiController::class, "all_category"]);
    Route::post('object-type-details', [ApiController::class, "object_type_details"]);/*Course details & Product details*/
    Route::post('all-type-listing', [ApiController::class, "all_type_listing"]);/*Listing of Course & Product*/
    Route::post('suggested-list', [ApiController::class, "suggested_list"]);
    Route::post('add-wishlist', [ApiController::class,"add_wishlist"]);
    Route::post('remove-wishlist', [ApiController::class,"remove_wishlist"]);
    Route::post('submit-review', [ApiController::class,"submit_review"]);
    Route::post('review-list', [ApiController::class,"review_list"]);
    Route::get('home', [ApiController::class, "home"]);
    Route::get('profile', [ApiController::class, "profile"]);
    Route::post('update-profile', [ApiController::class, "update_profile"]);
    Route::get('certificates', [ApiController::class, "certificates"]);
    Route::get('notifications', [ApiController::class, "notifications"]);
    Route::post('change-password', [ApiController::class,"change_password"]);

    Route::get('save-card-listing', [ApiController::class, "save_card_listing"]);
    Route::post('add-card', [ApiController::class, "add_card"]);
    Route::post('delete-card', [ApiController::class,"delete_card"]);

    Route::post('search-category', [ApiController::class,"search_category"]);
    Route::post('search-object-type-all', [ApiController::class,"search_object_type_all"]);
    Route::post('search-object-type-treending', [ApiController::class,"search_object_type_trending"]);
    Route::post('search-object-type-suggest', [ApiController::class,"search_object_type_suggest"]);
    Route::get('cart-details-payment', [ApiController::class, "cart_details_payment_page"]);

    Route::post('coupon-list', [ApiController::class, "coupon_list"]);
    // Route::post('applyed-coupon', [ApiController::class, "applyed_coupon"]);
    // Route::post('remove-coupon', [ApiController::class, "remove_coupon"]);
    // Route::post('remove-cart-list', [ApiController::class, "remove-cart-list"]);

    Route::post('my-order', [ApiController::class, "my_order"]);

    Route::post('order-detail', [ApiController::class, "order_detail"]);
    

    Route::get("cart-count", [ApiController::class, "cart_count"]);
    Route::post("assignment-upload-file", [ApiController::class, "assignment_upload_file"]);
    Route::post("mark-as-complete", [ApiController::class, "mark_complete"]);

    Route::post('special-courses', [ApiController::class, "special_courses"]);
    Route::post('make-payment', [StripeController::class, "makePayment"]);

    Route::get('notifications', [ApiController::class, 'getNotification']);
    Route::get('clear-notifications', [ApiController::class, 'clearNotification']);

    Route::post('/address', [ApiController::class, 'storeAddress']);
    Route::get('/address', [ApiController::class, 'getAddress']);
    Route::get('/address-details/{id}', [ApiController::class, 'getAddressDetails']);
    Route::delete('/address/{id}', [ApiController::class, 'deleteAddress']);
    Route::post('/update-address', [ApiController::class, 'updateAddress']);

    Route::post('add-to-cart', [CartController::class, "add_to_cart"]);
    Route::get('cart-list', [CartController::class, "cart_list"]);
    Route::post('update-product-quantity', [CartController::class, "update_product_quantity"]);
    Route::post('shipping-address', [CartController::class, "shipping_address"]);
    Route::post('remove-cart', [CartController::class, "remove_cart"]);
    Route::post('remove-cart-course', [CartController::class, "remove_cart_course"]);
    Route::post('get-shipping-rates', [CartController::class, "get_shipping_rates"]);
    Route::post('choose-shipping-option', [CartController::class, "choose_shipping_option"]);
    Route::get('coupons', [CartController::class, "get_coupons"]);
    Route::post('coupon-applied', [CartController::class, "coupon_applied"]);
    Route::post('remove-applied-coupon', [CartController::class, "remove_coupon_applied"]);
    Route::post('save-order', [CartController::class, "save_order"]);
    Route::post('empty-cart', [CartController::class, "empty_cart"]);

});
