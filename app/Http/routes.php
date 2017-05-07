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

	Route::get('role/list', 'RoleController@listAll')->name('RoleList');
	Route::post('role/select', 'RoleController@select')->name('RoleSelect');
    Route::post('role/remove', 'RoleController@remove')->name('RoleRemove');
    Route::post('role/add', 'RoleController@add')->name('RoleAdd');

    Route::get('staff/list', 'StaffController@listAll')->name('StaffList');
    Route::post('staff/upload', 'StaffController@receiveStaffList')->name('StaffUpload');
    Route::post('staff/confirm', 'StaffController@confirmStaffList')->name('StaffConfirm');
});
