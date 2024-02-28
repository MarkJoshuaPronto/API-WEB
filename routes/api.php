<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\ProductControllerApi;
use App\Http\Controllers\Admin\Api\AdminApiController;
use App\Http\Controllers\Admin\Api\BrandApiController;
use App\Http\Controllers\Admin\Api\ColorApiController;
use App\Http\Controllers\Admin\Api\ProductApiController;
use App\Http\Controllers\Admin\Api\CategoryApiController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('admin')->group(function () {
    Route::get('login', [AuthApiController::class, 'login_admin']);
    Route::post('login', [AuthApiController::class, 'auth_login_admin']);
    Route::post('logout', [AuthApiController::class, 'logout_admin']);
});


Route::prefix('admin')->namespace('Admin\Api')->group(function () {
    Route::get('admins', [AdminApiController::class, 'list']);
    Route::post('admins', [AdminApiController::class, 'add']);
    Route::put('admins/{id}', [AdminApiController::class, 'update']);
    Route::delete('admins/{id}', [AdminApiController::class, 'delete']);
});

Route::prefix('admin')->namespace('Admin\Api')->group(function () {
    Route::get('brands', [BrandApiController::class, 'list']);
    Route::post('brands', [BrandApiController::class, 'insert']);
    Route::get('brands/{id}', [BrandApiController::class, 'edit']);
    Route::put('brands/{id}', [BrandApiController::class, 'update']);
    Route::delete('brands/{id}', [BrandApiController::class, 'delete']);
});

Route::prefix('admin')->namespace('Admin\Api')->group(function () {
    Route::get('categories', [CategoryApiController::class, 'list']);
    Route::post('categories', [CategoryApiController::class, 'insert']);
    Route::get('categories/{id}', [CategoryApiController::class, 'edit']);
    Route::put('categories/{id}', [CategoryApiController::class, 'update']);
    Route::delete('categories/{id}', [CategoryApiController::class, 'delete']);
});

Route::prefix('admin')->namespace('Admin\Api')->group(function () {
    Route::get('colors', [ColorApiController::class, 'index']);
    Route::post('colors', [ColorApiController::class, 'store']);
    Route::get('colors/{id}', [ColorApiController::class, 'show']);
    Route::put('colors/{id}', [ColorApiController::class, 'update']);
    Route::delete('colors/{id}', [ColorApiController::class, 'destroy']);
});

Route::prefix('admin')->namespace('Admin\Api')->group(function () {
    Route::get('products', [ProductApiController::class, 'list']);
    Route::post('products', [ProductApiController::class, 'insert']);
    Route::get('products/{product_id}', [ProductApiController::class, 'edit']);
    Route::put('products/{product_id}', [ProductApiController::class, 'update']);
    Route::delete('products/image/{id}', [ProductApiController::class, 'image_delete']);
    Route::post('products/image-sortable', [ProductApiController::class, 'product_image_sortable']);
});

Route::prefix('admin')->namespace('Admin\Api')->group(function () {
    Route::get('subcategories', [SubCategoryApiController::class, 'list']);
    Route::get('subcategories/add', [SubCategoryApiController::class, 'add']);
    Route::post('subcategories/insert', [SubCategoryApiController::class, 'insert']);
    Route::get('subcategories/{id}', [SubCategoryApiController::class, 'edit']);
    Route::put('subcategories/{id}', [SubCategoryApiController::class, 'update']);
    Route::delete('subcategories/{id}', [SubCategoryApiController::class, 'delete']);
    Route::post('subcategories/get_sub_category', [SubCategoryApiController::class, 'get_sub_category']);
});

Route::get('admin/category/{slug}', [ProductControllerApi::class, 'getCategory']);






