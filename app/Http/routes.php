<?php

Route::get('test', function () {
    // throw new App\Exceptions\AppException('ASMK001', 'Data Error.');
    echo ASSET_DIR.'file-input/css/fileinput.min.css';
});

Route::get('dummyEntry', function () {
    return view('dummyEntry');
});


Route::group(['middleware' => ['welcome']], function () {

    Route::match(['get', 'post'], 'welcome', 'WelcomeController@welcome')->name('welcome');
});

Route::group(['middleware' => ['web']], function () {

	Route::get('RoleList', 'RoleController@listAll')->name('RoleList');
});
