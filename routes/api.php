<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function()
{
    Route::post('/store', 'register');
    Route::post('/login','login');
    Route::post('/logout','logout');
    Route::post('/refresh','refresh');
});

Route::controller(PageController::class)->group(function()
{
    Route::get('/category/{main_category}/{category}/{subcategory}','products');
    Route::get('/category/{main_category}/{category}','lastLayer');
    Route::get('/category/{main_category}','show');
    Route::get('/category','index');
    Route::get('/product/{id}','gallery');
});
Route::middleware('auth:api')->group(function()
{
    Route::controller(ProductController::class)->group(function()
    {
        Route::get('/admin/products','index');
        Route::post('/store/product','store');
        Route::put('/edit/path/{id}','editPath');
        Route::delete('/delete/product/{id}','delete');
    });

    Route::controller(ImageController::class)->group(function()
    {
        Route::post('/store/image','store');
        Route::put('/change/image/{id}','change');
        Route::delete('/delete/image/{id}','delete');
    });

    Route::controller(CategoryController::class)->group(function()
    {
        Route::get('/admin/{category}','index');
        Route::post('/store/{category}','store');
        Route::put('/edit/{category}/{id}','edit');
        Route::delete('/delete/{category}/{id}','delete');
    });
});



