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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('test', 'TestingController@testing');
Route::POST('testarray', 'TestingController@testarray');


//
////*********** User Route ******************//
//Route::POST('login',    'UserController@login');
//Route::POST('register', 'UserController@register');
//
////*********** Detail PUM Route ******************//
//Route::post('detailpum',        'PumController\DetailPumController@detailPum');
//Route::post('summarycreatepum', 'PumController\DetailPumController@summaryCreatePum');
//
////*********** Create PUM Route ******************//
//Route::post('cekavailablepum',  'PumController\CreatePumController@cekAvailablePum');
//Route::get('getdept',           'PumController\CreatePumController@getdept');
//Route::get('gettrxtype',        'PumController\CreatePumController@gettrxtype');
//Route::post('getdocdetail',     'PumController\CreatePumController@getDocDetail');
//Route::post('createpum',        'PumController\CreatePumController@createPum');
//
//
////*********** Approval PUM Route ******************//
//Route::post('listapproval',     'PumController\ApprovalController@listApproval');;
//Route::post('approvepum',       'PumController\ApprovalController@approvePum');
//
////*********** History Pum Route ******************//
//Route::post('historycreatepum',         'PumController\HistoryPumController@historyCreatePum');
//Route::post('filterhistorycreatepum',   'PumController\HistoryPumController@filterHistoryCreatePum');
//Route::post('historyapprovepum',        'PumController\HistoryPumController@historyApprovalPum');
//
////*********** Responsibility Pum Route ******************//
//Route::post('getdataresponse',  'PumController\ResponsibilityController@getAllData');
//Route::post('submitresponse',     'PumController\ResponsibilityController@submitResponsibility');
//



//*********** User Route ******************//
Route::POST('login',        'API\UserController@login');
Route::POST('registerpin',  'API\UserController@registerPin');

//*********** Detail PUM Route ******************//
Route::post('detailpum',        'API\DetailPumController@detailPum');
Route::post('summarypum',       'API\DetailPumController@summaryPum');

//*********** Create PUM Route ******************//
Route::post('cekavailablepum',  'API\CreatePumController@cekAvailablePum');
Route::get('getdept',           'API\CreatePumController@getDepartment');
Route::get('gettrxtype',        'API\CreatePumController@getTrxType');
Route::post('getdocdetail',     'API\CreatePumController@getDocDetail');
Route::post('createpum',        'API\CreatePumController@createPum');

//*********** Approval PUM Route ******************//
Route::post('listapproval',     'API\ApprovalController@getListPum');
Route::post('approvepum',       'API\ApprovalController@approvePum');

////*********** Responsibility Pum Route ******************//
Route::post('getdataresponse',  'API\ResponsibilityController@getListData');
Route::post('submitresponse',   'API\ResponsibilityController@submitResponsibility');

//*********** History Pum Route ******************//
Route::post('historycreatepum',         'API\HistoryPumController@historyCreatePum');
Route::post('historyapprovepum',        'API\HistoryPumController@historyApprovalPum');



//TEST PDF
Route::get('testpdf', 'TestingController@testingpdf');

/*
 emp_num ; 2001542903
emp_id = 33287
30.000.000 = 33287 & 33288 + 33387 & 33291
 * */