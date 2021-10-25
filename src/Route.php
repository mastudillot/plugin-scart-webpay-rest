<?php
/**
 * Route front
 */

use App\Http\Middleware\VerifyCsrfToken;

if(sc_config('WebpayPlus')) {
Route::group(
    [
        'prefix'    => 'plugin/webpayplus',
        'middleware' => SC_FRONT_MIDDLEWARE,
        'namespace' => 'App\Plugins\Payment\WebpayPlus\Controllers',
    ],
    function () {
        Route::get('index', 'FrontController@index')
        ->name('webpayplus.index');
        Route::get('process-order', 'FrontController@processOrder')
        ->name('webpayplus.processOrder'); 
        Route::any('return/{orderId}', 'FrontController@return')
        ->withoutMiddleware(VerifyCsrfToken::class)
        ->name('webpayplus.return');
    }
);
}
/**
 * Route admin
 */
Route::group(
    [
        'prefix' => SC_ADMIN_PREFIX.'/webpayplus',
        'middleware' => SC_ADMIN_MIDDLEWARE,
        'namespace' => 'App\Plugins\Payment\WebpayPlus\Admin',
    ], 
    function () {
        Route::get('/', 'AdminController@index')
        ->name('admin_webpayplus.index');
    }
);
