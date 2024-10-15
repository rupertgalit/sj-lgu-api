<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\NetPayAPIController;
use App\Http\Controllers\TransactionController;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/accounts', [AuthController::class, 'index']);
Route::post('/account-update-status', [AuthController::class, 'update_status']);
Route::post('/account-update', [AuthController::class, 'update']);
Route::post('/account-change-password/{id}', [AuthController::class, 'change_password']);



Route::post('/transaction-display', [TransactionController::class, 'sort']);
Route::post('/transaction-insert', [TransactionController::class, 'store']);
Route::post('/transaction-search', [TransactionController::class, 'search']);

Route::post('/categories-insert', [CategoriesController::class, 'store']);
Route::get('/categories-display', [CategoriesController::class, 'index']);
Route::post('/categories-update/{id}', [CategoriesController::class, 'update']);

// Route::get('/get_token', [NetPayAPIController::class, 'getToken']);
// Route::post('/pay', [NetPayAPIController::class, 'PayTransaction']);

