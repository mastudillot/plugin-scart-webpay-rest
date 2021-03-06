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
        ->name('webpayplus.process_order'); 
        Route::any('finish/{orderId}', 'FrontController@finish')
        ->withoutMiddleware(VerifyCsrfToken::class)
        ->name('webpayplus.finish');
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
        Route::post('/config', 'AdminController@saveConfig')
        ->name('admin_webpayplus.config.save');
        Route::get('/transaction/{id}', 'AdminController@transactionDetail')
        ->name('admin_webpayplus.transaction');
    }
);
