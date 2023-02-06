<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function() {
    Route::post('login', 'AuthController@login');
    Route::post('magic', 'AuthController@magic');
    Route::post('register', 'AuthController@register');
    Route::post('auth-book', 'AuthController@book');
    Route::post('recovery', 'AuthController@recovery');
    Route::get('activate/{hash:hash}', 'AuthController@activate');

    Route::get('exp', 'ExpController@all');
    Route::get('course', 'CourseController@all');
    Route::get('postcode/available', 'PostcodeController@available');

    Route::prefix('regions')->group(function() {
        Route::get('/', 'RegionController@all');
    });

    Route::prefix('book')->group(function() {
        Route::get('temp/{token}', 'BookController@tempGet');
        Route::post('temp', 'BookController@temp');
    });

    Route::post('enquiry', 'BookController@enquiry');

    Route::middleware(['auth:sanctum', 'activity'])->group(function() {
        Route::get('me', 'AuthController@me');
        Route::post('profile', 'AuthController@profile');
        Route::post('password', 'AuthController@password');

        Route::get('import', 'UserController@import');

        Route::prefix('export')->group(function() {
            Route::post('generate', 'ExportController@generate');
        });

        Route::get('certificates', 'CertificateController@all');
        Route::post('certificates', 'CertificateController@send');
        
        Route::prefix('book')->group(function() {
            Route::get('/', 'BookController@all');
            Route::post('/', 'BookController@save');
            Route::post('create', 'BookController@create');
            Route::post('assign', 'BookController@assign');
            Route::post('track-date-define', 'BookController@trackDateDefine');
            Route::post('track-pay', 'BookController@trackPay');
            Route::post('road-date-define', 'BookController@roadDateDefine');
            Route::post('agree-road-dates', 'BookController@agreeRoadDates');
            Route::post('request-payment', 'BookController@requestPayment');
            Route::get('for-pay', 'BookController@forPay');
            Route::post('pay', 'BookController@pay');
            Route::get('{book}', 'BookController@get');
        });

        Route::prefix('voucher')->group(function() {
            Route::get('/', 'VoucherController@all');
            Route::post('/', 'VoucherController@save');
            Route::get('users', 'VoucherController@users');
            Route::put('{voucher}', 'VoucherController@update');
            Route::get('{code}/check', 'VoucherController@check');
            Route::get('{voucher:code}', 'VoucherController@get');
            Route::post('upload', 'VoucherController@upload');
            Route::delete('{voucher}', 'VoucherController@remove');
        });

        Route::prefix('coach')->group(function() {
            Route::get('/', 'CoachController@all');
            Route::post('/', 'CoachController@save');
            Route::get('password/{coach}', 'CoachController@password');
            Route::post('postcode', 'CoachController@byPostcode');
            Route::get('customers', 'CoachController@customers');
            Route::get('books', 'CoachController@books');
            Route::get('enquiries', 'CoachController@enquiries');
        });

        Route::prefix('event')->group(function() {
            Route::get('/', 'EventController@all');
            Route::post('/', 'EventController@save');
            Route::get('bookings', 'EventController@bookings');
            Route::post('pay', 'EventController@pay');
            Route::get('enquiry', 'EventController@enquiryAll');
            Route::post('book/create', 'EventController@bookCreate');
            Route::put('{event}', 'EventController@update');
            Route::delete('{event}', 'EventController@remove');
            Route::get('{event}/customers', 'EventController@customers');
            Route::post('{event}/book', 'EventController@book');
        });

        Route::prefix('track-day')->group(function() {
            Route::get('/', 'TrackDayController@all');
            Route::post('/', 'TrackDayController@save');
            Route::get('bookings', 'TrackDayController@bookings');
            Route::post('pay', 'TrackDayController@pay');
            Route::get('enquiry', 'TrackDayController@enquiryAll');
            Route::post('book/create', 'TrackDayController@bookCreate');
            Route::put('{trackDay}', 'TrackDayController@update');
            Route::delete('{trackDay}', 'TrackDayController@remove');
            Route::get('{trackDay}/customers', 'TrackDayController@customers');
            Route::post('{trackDay}/book', 'TrackDayController@book');
        });

        Route::prefix('postcode')->group(function() {
            Route::get('/', 'PostcodeController@all');
        });

        Route::prefix('track')->group(function() {
            Route::get('/', 'TrackController@all');
            Route::post('/', 'TrackController@save');
            Route::get('date', 'TrackController@allDate');
            Route::post('date', 'TrackController@saveDate');
            Route::post('date/region', 'TrackController@dateByRegion');
            Route::delete('date/{track_date}', 'TrackController@removeDate');
        });

        Route::prefix('dialogs')->group(function() {
            Route::get('{dialog}', 'DialogController@get');
            Route::get('{user}/all', 'DialogController@all');
            Route::post('{book}', 'DialogController@byBook');
            Route::post('{dialog}/call', 'DialogController@call');
            Route::post('{dialog}/notes', 'DialogController@notes');
            Route::post('{dialog}/message', 'DialogController@message');
            Route::get('{dialog}/close', 'DialogController@close');
        });

        Route::prefix('message')->group(function() {
            Route::get('{message}/read', 'MessageController@read');
        });

        Route::get('enquiry', 'BookController@enquiryAll');

        // Route::get('payment/history', 'PaymentController@history');

        Route::prefix('user')->group(function() {
            Route::get('customers', 'UserController@customers');
            Route::post('customers', 'UserController@customersSave');
            Route::put('customers/{customer}', 'UserController@customersUpdate');
            Route::get('customers/{customer}', 'UserController@customersGet');
            Route::post('card-connect', 'UserController@cardConnect');

            Route::prefix('{user}')->group(function() {
                Route::get('request-payment-details', 'UserController@requestPaymentDetails');

                Route::get('address', 'AddressController@get');
                Route::post('address', 'AddressController@save');

                Route::get('card', 'CardController@all');
                Route::get('card/details', 'CardController@details');
                Route::post('card', 'CardController@save');
                Route::delete('card/{card}', 'CardController@remove');

                Route::get('document', 'DocController@all');
                Route::get('document/{doc}', 'DocController@sign');

                Route::get('date', 'DateController@all');
                Route::post('date', 'DateController@save');

                Route::get('payment', 'PaymentController@all');
                Route::get('payment/history', 'PaymentController@history');
                Route::get('payment/{payment}', 'PaymentController@get');
                Route::post('payment/{payment}', 'PaymentController@pay');
            });
        });
    });

    Route::prefix('event')->group(function() {
        Route::get('temp/{token}', 'EventController@tempGet');
        Route::post('temp', 'EventController@temp');
        Route::post('enquiry', 'EventController@enquiry');
        Route::get('{event}', 'EventController@get');
    });

    Route::prefix('track-day')->group(function() {
        Route::get('temp/{token}', 'TrackDayController@tempGet');
        Route::post('temp', 'TrackDayController@temp');
        Route::post('enquiry', 'TrackDayController@enquiry');
        Route::get('{trackDay}', 'TrackDayController@get');
    });
});
