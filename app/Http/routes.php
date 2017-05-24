<?php

Route::get('test', function () {

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
    Route::get('staff/info/{empNo}', 'StaffController@view')->name('StaffInfo');
    Route::post('staff/remove', 'StaffController@remove')->name('StaffRemove');
    Route::post('staff/edit', 'StaffController@edit')->name('StaffEdit');
    Route::post('staff/add', 'StaffController@add')->name('StaffAdd');

    Route::post('staff/upload', 'StaffBatchController@receiveStaffList')->name('StaffUpload');
    Route::post('staff/confirm', 'StaffBatchController@confirmStaffList')->name('StaffConfirm');

    Route::get('staff/view/ex/{dept?}', 'ExStaffController@listAll')->name('StaffViewEx');

    Route::get('occupational/list/{dept?}', 'OccupationalRiskController@listAll')->name('OccupationalRiskList');
    Route::get('occupational/maker', 'OccupationalRiskController@makerPage')->name('OccupationalRiskMaker');
    Route::get('occupational/checker', 'OccupationalRiskController@checkerPage')->name('OccupationalRiskChecker');
    Route::post('occupational/checker/approve', 'OccupationalRiskController@checkerApprove')->name('OccupationalRiskCheckerApprove');
    Route::post('occupational/checker/reject', 'OccupationalRiskController@checkerReject')->name('OccupationalRiskCheckerReject');
    Route::post('occupational/delete/pending', 'OccupationalRiskController@deletePending')->name('OccupationalRiskDeletePending');
    Route::post('occupational/add', 'OccupationalRiskController@add')->name('OccupationalRiskAdd');
    Route::post('occupational/delete', 'OccupationalRiskController@delete')->name('OccupationalRiskDelete');
    Route::post('occupational/edit', 'OccupationalRiskController@edit')->name('OccupationalRiskEdit');

    Route::post('occupational/receive', 'OccupationalRiskBatchController@receive')->name('OccupationalBatchReceive');
    Route::post('occupational/approve/all', 'OccupationalRiskBatchController@approveAll')->name('OccupationalApproveAll');
    Route::post('occupational/reject/all', 'OccupationalRiskBatchController@rejectAll')->name('OccupationalRejectAll');

    Route::get('kye/case/create/{empNo}', 'KYECaseController@create')->name('KYECaseCreate');
});
