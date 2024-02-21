<?php
/**
 * Route front
 */


if(sc_config('Transbank')) {
Route::group(
    [
        'prefix'    => 'plugin/transbank/webpayplus',
        'middleware' => SC_FRONT_MIDDLEWARE,
        'namespace' => 'App\Plugins\Payment\Transbank\Controllers',
    ],
    function () {
        Route::get('index', 'FrontController@index')
        ->name('webpayplus.index');
        Route::get('process-order', 'FrontController@processOrder')
        ->name('webpayplus.process_order'); 
        Route::get('finish/{orderId}', 'FrontController@finish')
        ->name('webpayplus.finish');
    }
);
}
/**
 * Route admin
 */
Route::group(
    [
        'prefix' => SC_ADMIN_PREFIX.'/Transbank',
        'middleware' => SC_ADMIN_MIDDLEWARE,
        'namespace' => 'App\Plugins\Payment\Transbank\Admin',
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
