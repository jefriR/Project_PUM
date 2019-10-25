<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('test', 'TestingController@testing');
Route::POST('testarray', 'TestingController@testarray');



//*********** User Route ******************//
Route::POST('login',        'API\UserController@login');
Route::POST('registerpin',  'API\UserController@registerPin');

//*********** Detail PUM Route ******************//
Route::post('detailpum',        'API\DetailPumController@detailPum');
Route::post('summarypum',       'API\DetailPumController@summaryPum');

//*********** Create PUM Route ******************//
Route::post('cekavailablepum',  'API\CreatePumController@cekAvailablePum');
Route::post('getdept',           'API\CreatePumController@getDepartment');
Route::get('gettrxtype',        'API\CreatePumController@getTrxType');
Route::post('getdocdetail',     'API\CreatePumController@getDocDetail');
Route::post('createpum',        'API\CreatePumController@createPum');

//*********** Approval PUM Route ******************//
Route::post('listapproval',     'API\ApprovalController@getListPum');
Route::post('approvepum',       'API\ApprovalController@approvePum');

////*********** Responsibility Pum Route ******************//
Route::post('getdataresponse',  'API\ResponsibilityController@getListData');
Route::post('submitresponse',   'API\ResponsibilityController@submitResponsibility');
Route::post('historyresponse',  'API\ResponsibilityController@historyResponse');

//*********** History Pum Route ******************//
Route::post('historycreatepum',         'API\HistoryPumController@historyCreatePum');
Route::post('historyapprovepum',        'API\HistoryPumController@historyApprovalPum');

//*********** Reporting Route ******************//
Route::get('getreportindex', 'ReportAPI\ReportController@index');


//TEST PDF
Route::get('testpdf', 'TestingController@testingpdf');

/*
 emp_num ; 2001542903
emp_id = 33287
30.000.000 = 33287 & 33288 + 33387 & 33291
 * */