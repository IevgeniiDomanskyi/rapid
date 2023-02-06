<?php

use Illuminate\Http\Request;
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

Route::get('phpinfo', function () {
    return phpinfo();
});

Route::get('{any?}', function () {
    return view('app');
})->where('any', '[\/\w\.-]*');

Route::post('gp/method-notification', function (Request $request) {
    dd($request);
});

Route::post('gp/challenge-notification', function (Request $request) {
    dd($request);
});

Route::post('{any?}', function (Request $request) {
    $res = $request->input('hppResponse');

    $result = false;
    if ( ! empty($res)) {
        $result = service('Card')->process($res);
    }

    return view('app', ['data' => $result]);
})->where('any', '[\/\w\.-]*');