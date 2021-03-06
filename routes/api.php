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
/** Campus/Institution Management APIs */
Route::get('/institution', 'InstitutionController@index');
Route::get('/institution/schooltypes', 'InstitutionController@showSchoolType');
Route::get('/institution/{institution}', 'InstitutionController@show');
Route::post('/institution/update/{institution}', 'InstitutionController@update');

/** User Management APIs */
Route::get('/users', 'UserController@index');
Route::get('/users/{user}', 'UserController@show');
Route::post('/users/update/{user}', 'UserController@update');


/** Department Management APIs */
Route::get('/department', 'DepartmentController@index');
Route::get('/department/{department}', 'DepartmentController@show');
Route::get('/department/specific/{department}', 'DepartmentController@showSpecific');
Route::post('/department/update/{department}', 'DepartmentController@update');
Route::delete('/department/remove/{department}', 'DepartmentController@delete');



/** Vehicle Management APIs */
Route::get('/vehicle', 'VehicleController@index');
Route::get('/vehicle/active', 'VehicleController@showActive');
Route::get('/vehicle/cartypes', 'VehicleController@returnCarTypes');
Route::get('/vehicle/fueltypes', 'VehicleController@returnFuelTypes');
Route::get('/vehicle/carbrands', 'VehicleController@returnCarBrands');
Route::get('/vehicle/{vehicle}', 'VehicleController@show');
Route::post('/vehicle/update/{vehicle}', 'VehicleController@update');

/** Upload Excel File APIs */
Route::get('/trip', 'UploadedTripController@index');
Route::get('/trip/dates', 'UploadedTripController@getDates');
Route::post('/trip/specific', 'UploadedTripController@showSpecificCampus');
Route::post('/trip/specific/date', 'UploadedTripController@showSpecificDate');
Route::get('/trip/{trip}', 'UploadedTripController@show');
Route::post('/trip/process', 'UploadedTripController@preProcess');
Route::post('/trip/process/upload', 'UploadedTripController@uploadToDb');
Route::post('/trip/process/errors', 'UploadedTripController@preProcessErrors');
Route::post('/trip/process/cleaned', 'UploadedTripController@preProcessClean');
Route::post('/trip/process/prepexport', 'UploadedTripController@prepareForExport');

/** We Planted Trees APIs */
Route::get('/tree', 'TreeController@index');
Route::get('/tree/{tree}', 'TreeController@show');

/** Threshold API */
Route::get('/thresholds', 'ThresholdController@index');
Route::post('/thresholds/update', 'ThresholdController@update');

/** Notification API */
Route::post('/notifications', 'NotificationController@index');
Route::post('/notifications/all', 'NotificationController@all');
Route::post('/notifications/update', 'NotificationController@update');
