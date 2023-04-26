<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('category')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index')->name('api.category.index');
    Route::get('/{id}', 'show')->name('api.category.show');
    Route::post('/', 'store')->name('api.category.store');
    Route::put('/{id}', 'update')->name('api.category.update');
    Route::delete('/{id}', 'destroy')->name('api.category.destroy');
});
