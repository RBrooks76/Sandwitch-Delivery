<?php

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Auth::routes();
Route::get('/', 'HomepageController@index')->name('index');

Route::get('PrintOrder/{ID}', 'OrderController@viewPrintOrder');