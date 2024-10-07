<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\TransactionController;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/accounts', [AuthController::class, 'index']);

Route::post('/transaction-display', [TransactionController::class, 'sort']);
Route::post('/transaction-insert', [TransactionController::class, 'store']);
Route::post('/transaction-search', [TransactionController::class, 'search']);

Route::post('/categories-insert', [CategoriesController::class, 'store']);
Route::get('/categories-display', [CategoriesController::class, 'index']);
Route::post('/categories-update/{id}', [CategoriesController::class, 'update']);
