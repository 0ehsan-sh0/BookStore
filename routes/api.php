<?php

use App\Http\Controllers\MainCategoryController;
use App\Models\MainCategory;
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


// Main Category Routes -------------------
Route::post('main_category/restore/{main_category}', [MainCategoryController::class, 'restoreData'])->withTrashed()->name('main_category.restore');
Route::resource('main_category', MainCategoryController::class)->only('index','store','update','destroy');
Route::get('main_category/trashed',[MainCategoryController::class, 'trashed'])->name('main_category.trashed');
// ------------------- Main Category Routes
