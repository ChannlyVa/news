<?php

// use Illuminate\Http\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ContaceusController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // log in
    Route::post('logout', [AuthController::class, 'logout']);
    // log articles
    Route::apiResource('/article', ArticleController::class);
    Route::get('/article/category/{categoryId}', [ArticleController::class, 'getByCategory']);
    Route::get('/article/author/{authorId}', [ArticleController::class, 'getByAuthor']);
    // log categories
    Route::apiResource('/category', CategoryController::class);
    // log users
    Route::apiResource('/user', UserController::class);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::get('/user', [UserController::class, 'getCurrentUser']);
    Route::get('/users', [UserController::class, 'index']);
    // log contactus
    Route::apiResource('/contactus', ContaceusController::class);
});
