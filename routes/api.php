<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/transaction-display', [TransactionController::class, 'index']);
Route::post('/transaction-insert', [TransactionController::class, 'store']);
Route::get('/transaction-search', [TransactionController::class, 'search']);
Route::post('/categories-insert', [Categories::class, 'store']);
