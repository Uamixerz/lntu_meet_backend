<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/users', [UserController::class, 'store'])->middleware('json');
Route::post('/add_image', [UserController::class, 'storeImage'])->middleware('json');
Route::post('/users/edit', [UserController::class, 'update'])->middleware('json');
Route::get('/users', [UserController::class, 'index'])->middleware('json');
