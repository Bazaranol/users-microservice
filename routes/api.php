<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::controller(\App\Http\Controllers\ClientController::class)->group(function () {
    Route::get('clients/{id}',  'getClient')->where('id', '[0-9]+');
    Route::get('clients',  'paginateClients');
    Route::get('clients/all',  'getClients');
    Route::post('create-client', 'createClient');
    Route::post('client/{id}/delete', 'deleteClient')->where('id', '[0-9]+');
    Route::post('client/{id}/block', 'blockClient')->where('id', '[0-9]+');
});

Route::controller(\App\Http\Controllers\EmployeeController::class)->group(function () {
    Route::get('employee/{id}', 'getEmployee')->where('id', '[0-9]+');
    Route::get('employees', 'paginateEmployees');
    Route::get('employees/all/', 'getEmployees');
    Route::post('create-employee', 'createEmployee');
    Route::post('employee/{id}/delete', 'deleteEmployee')->where('id', '[0-9]+');
    Route::post('employee/{id}/block', 'blockEmployee')->where('id', '[0-9]+');
});



