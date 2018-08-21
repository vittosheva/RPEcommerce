<?php

/**
 * INVOICES
 */
Route::group([
    'prefix'    => 'billing',
    'as'        => 'billing.',
    'namespace'	=> 'Billing'
], function () {
    Route::get('invoice-lists', 'PrestashopOrdersController@index')->name('index');
    Route::get('invoice-process/{id}', 'PrestashopOrdersController@processOrder')->name('process');
    Route::get('invoice-verify/{id}', 'PrestashopOrdersController@verifyOrder')->name('verify');
    Route::get('invoice-detail/{id}', 'PrestashopOrdersController@getOrderDetail')->name('details');
});
