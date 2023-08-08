<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WriterController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TranslatorController;
use App\Http\Controllers\MainCategoryController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//return $request->user();
//});


// Main Category Routes -------------------
Route::post('main_category/restore/{main_category}', [MainCategoryController::class, 'restoreData'])->withTrashed()->name('main_category.restore');
Route::resource('main_category', MainCategoryController::class)->only('index','store','update','destroy');
Route::get('main_category/trashed',[MainCategoryController::class, 'trashed'])->name('main_category.trashed');
// ------------------- Main Category Routes

// Category Routes -------------------
Route::post('category/restore/{category}', [CategoryController::class, 'restoreData'])->withTrashed()->name('category.restore');
Route::resource('category', CategoryController::class)->only('index','store','update','destroy');
Route::get('category/trashed',[CategoryController::class, 'trashed'])->name('category.trashed');
// ------------------- Category Routes

// User Routes -------------------
// Authentication Routes -------------------
Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('logout', [UserController::class, 'logout'])->name('logout');
// ------------------- Authentication Routes
Route::post('user/restore/{user}', [UserController::class, 'restoreData'])->withTrashed()->name('user.restore');
Route::get('user/trashed',[UserController::class, 'trashed'])->name('user.trashed');
Route::resource('user', UserController::class)->only('index','update','destroy','show');
// ------------------- User Routes

// Address Routes -------------------
Route::get('address/trashed',[AddressController::class, 'trashed'])->name('address.trashed');
Route::post('address/restore/{address}', [AddressController::class, 'restoreData'])->withTrashed()->name('address.restore');
Route::resource('address', AddressController::class)->only('index','store','update','destroy','show');
// ------------------- Address Routes

// Writer Routes -------------------
Route::get('writer/trashed',[WriterController::class, 'trashed'])->name('writer.trashed');
Route::post('writer/restore/{writer}', [WriterController::class, 'restoreData'])->withTrashed()->name('writer.restore');
Route::resource('writer', WriterController::class)->only('index','store','update','destroy','show');
// ------------------- Writer Routes

// Translator Routes -------------------
Route::get('translator/trashed',[TranslatorController::class, 'trashed'])->name('translator.trashed');
Route::post('translator/restore/{translator}', [TranslatorController::class, 'restoreData'])->withTrashed()->name('translator.restore');
Route::resource('translator', TranslatorController::class)->only('index','store','update','destroy','show');
// ------------------- Translator Routes

// Book Routes -------------------
Route::get('book/trashed',[BookController::class, 'trashed'])->name('book.trashed');
Route::post('book/restore/{book}', [BookController::class, 'restoreData'])->withTrashed()->name('book.restore');
Route::resource('book', BookController::class)->only('index','store','update','destroy','show');
// ------------------- Book Routes

// Cart Routes -------------------
Route::get('cart/trashed',[CartController::class, 'trashed'])->name('cart.trashed');
Route::post('cart/restore/{cart}', [CartController::class, 'restoreData'])->withTrashed()->name('cart.restore');
Route::resource('cart', CartController::class)->only('index','store','update','destroy','show');
// ------------------- Cart Routes