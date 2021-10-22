<?php
/**
 * Route front
 */
if(sc_config('WebpayPlus')) {
Route::group(
    [
        'prefix'    => 'plugin/webpayplus',
        'namespace' => 'App\Plugins\Payment\WebpayPlus\Controllers',
    ],
    function () {
        Route::get('index', 'FrontController@index')
        ->name('webpayplus.index');
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
