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
    Route::get('kye/case/list/pending', 'KYECaseController@listPending')->name('KYECaseListPending');
    Route::get('kye/case/checker/{logID}', 'KYECaseController@checkerPage')->name('KYECaseChecker');
    Route::get('kye/case/view/{caseID}', 'KYECaseController@view')->name('KYECaseView');
    Route::post('kye/case/confirm', 'KYECaseController@checkerApprove')->name('KYECaseApprove');
    Route::post('kye/case/reject', 'KYECaseController@checkerReject')->name('KYECaseReject');
    Route::post('kye/case/make', 'KYECaseController@make')->name('KYECaseMake');
    Route::post('kye/case/delete', 'KYECaseController@delete')->name('KYECaseDelete');

    Route::get('review/period/view', 'ReviewPeriodController@view')->name('ReviewPeriodView');
    Route::get('reivew/period/edit', 'ReviewPeriodController@edit')->name('ReviewPeriodEdit');
    Route::post('review/period/update', 'ReviewPeriodController@update')->name('ReviewPeriodUpdate');

    Route::get('doc/display/{docID}/{name}', 'DocumentController@display')->name('DocDisplay');
    Route::get('doc/error/log/{logName}', 'DocumentController@errorLog')->name('DocErrorLog');

    Route::get('log/view', 'LogController@view')->name('LogView');
    Route::get('log/error', 'LogController@error')->name('LogError');
});
