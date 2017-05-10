<?php

Route::get('test', function () {
    dd(route('StaffList').'/');
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

    Route::get('staff/list/{dept?}', 'StaffController@listAll')->name('StaffList');
    Route::post('staff/remove', 'StaffController@remove')->name('StaffRemove');
    Route::post('staff/edit', 'StaffController@edit')->name('StaffEdit');
    Route::post('staff/add', 'StaffController@add')->name('StaffAdd');

    Route::post('staff/upload', 'StaffBatchController@receiveStaffList')->name('StaffUpload');
    Route::post('staff/confirm', 'StaffBatchController@confirmStaffList')->name('StaffConfirm');
});
