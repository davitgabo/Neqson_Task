<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/store', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');

Route::get('/admin/products', [ProductController::class, 'index'])->middleware('auth:api');
Route::get('/admin/{category}', [CategoryController::class, 'index'])->middleware('auth:api');

Route::get('/category/{main_category}/{category}/{subcategory}', [PageController::class, 'products']);
Route::get('/category/{main_category}/{category}', [PageController::class, 'lastLayer']);
Route::get('/category/{main_category}', [PageController::class, 'show']);
Route::get('/category', [PageController::class, 'index']);
Route::get('/product/{id}', [PageController::class, 'gallery']);
