<?php

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PreperController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\ShippingDetailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

    Route::post('/upload-image',        [CustomerController::class,     'image_upload']);
    Route::post('/user/create',         [CustomerController::class,     'register']);
    Route::get('/user/delete/{id}',     [CustomerController::class,     'user_delete']);
    Route::post('/user/login',          [CustomerController::class,     'login']);
    Route::post('/user/period',         [CustomerController::class,     'add_perioid']);
    Route::post('/forgot-password',     [CustomerController::class,     'forgot_password']);
    Route::post('/verify-otp',          [CustomerController::class,     'verify_otp']);
    Route::post('/set-password',        [CustomerController::class,     'set_password']);
    Route::post('/change-password',     [CustomerController::class,     'change_password']);
    Route::get('/user/questions',       [CustomerController::class,     'questions']);
    Route::post('/user/answers',        [CustomerController::class,     'answersSave']);
    Route::get('/user/periods/{id}',    [CustomerController::class,     'periods']);
    Route::get('/user/facts',           [CustomerController::class,     'facts']);
    Route::get('/user/blogs',           [CustomerController::class,     'blogs']);
    Route::get('/user/blog/{id}',       [CustomerController::class,     'blog']);
    Route::get('/user/products',        [CustomerController::class,     'products']);
    Route::get('/user/product/{id}',    [CustomerController::class,     'product']);
    Route::get('/daily-message',        [CustomerController::class,     'daily_message']);
    Route::get('/daily-health',         [CustomerController::class,     'daily_health']);
    Route::post('/pay-stripe',          [CustomerController::class,     'stripe_payment']);
    Route::post('/add-appointment',     [CustomerController::class,     'add_appointment']);
    Route::post('/appointments',        [CustomerController::class,     'appointments']);
    Route::get('/user/recomended-products/{id}',   [CustomerController::class,     'recomended_products']);
    Route::post('/shipping-details', [ShippingDetailController::class, 'store']);
    Route::delete('/shipping-details/{id}', [ShippingDetailController::class, 'destroy']);
    Route::put('/shipping-details/{id}', [ShippingDetailController::class, 'update']);
    Route::get('/shipping-details/user/{userId}', [ShippingDetailController::class, 'getByUserId']);
    
    // Route::group(['middleware' => 'auth:sanctum'], function(){

    
    // });



