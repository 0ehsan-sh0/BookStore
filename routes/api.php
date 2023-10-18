<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TranslatorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WriterController;
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

// Public Routes ---------------------------------------------------------------------------
// Writer
Route::resource('writer', WriterController::class)->only('show');
// Translator
Route::resource('translator', TranslatorController::class)->only('show');
// Book
Route::get('books/{main_category}/{category}', [CategoryController::class, 'categoryBooks']);
Route::get('books/{main_category}', [MainCategoryController::class, 'mainCategoryBooks']);
Route::resource('book', BookController::class)->only('index', 'show');
// Get books and articles of one tag
Route::get('tags/{tag}', [TagController::class, 'tagBooksAndArticles']);
// Main Category and Categories
Route::resource('main_category', MainCategoryController::class)->only('index');
// Authentication Routes
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
// Article routes
Route::resource('article', ArticleController::class)->only('index', 'show');
// --------------------------------------------------------------------------- Public Routes

// Authenticated Routes ---------------------------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    // Comment
    Route::resource('comment', CommentController::class)->only('destroy', 'store', 'update');
    // User
    Route::post('logout', [UserController::class, 'logout']);
    Route::resource('user', UserController::class)->only('destroy');
    Route::get('user/get/info', [UserController::class, 'getInfo']);
});
// --------------------------------------------------------------------------- Authenticated Routes

// Admin Routes ---------------------------------------------------------------------------
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Comment Routes -------------------
    Route::get('comment/trashed', [CommentController::class, 'trashed']);
    Route::post('comment/restore/{comment}', [CommentController::class, 'restoreData'])->withTrashed();
    Route::post('comment/confirm/{comment}', [CommentController::class, 'confirm']);
    Route::resource('comment', CommentController::class)->only('index');
    // ------------------- Comment Routes

    // Writer Routes -------------------
    Route::get('writer/trashed', [WriterController::class, 'trashed']);
    Route::post('writer/restore/{writer}', [WriterController::class, 'restoreData'])->withTrashed();
    Route::resource('writer', WriterController::class)->only('index', 'store', 'update', 'destroy');
    // ------------------- Writer Routes

    // Translator Routes -------------------
    Route::get('translator/trashed', [TranslatorController::class, 'trashed']);
    Route::post('translator/restore/{translator}', [TranslatorController::class, 'restoreData'])->withTrashed();
    Route::resource('translator', TranslatorController::class)->only('index', 'store', 'update', 'destroy');
    // ------------------- Translator Routes

    // Book Routes -------------------
    Route::get('book/trashed', [BookController::class, 'trashed']);
    Route::post('book/restore/{book}', [BookController::class, 'restoreData'])->withTrashed();
    Route::resource('book', BookController::class)->only('store', 'update', 'destroy');
    // ------------------- Book Routes

    // Main Category Routes -------------------
    Route::post('main_category/restore/{main_category}', [MainCategoryController::class, 'restoreData'])->withTrashed();
    Route::resource('main_category', MainCategoryController::class)->only('store', 'update', 'destroy');
    Route::get('main_category/trashed', [MainCategoryController::class, 'trashed']);
    // ------------------- Main Category Routes

    // Category Routes -------------------
    Route::post('category/restore/{category}', [CategoryController::class, 'restoreData'])->withTrashed();
    Route::resource('category', CategoryController::class)->only('index', 'store', 'update', 'destroy');
    Route::get('category/trashed', [CategoryController::class, 'trashed']);
    // ------------------- Category Routes

    // User Routes -------------------
    Route::post('user/restore/{user}', [UserController::class, 'restoreData'])->withTrashed();
    Route::get('user/trashed', [UserController::class, 'trashed']);
    Route::post('user/promote/{user}', [UserController::class, 'promote']);
    Route::post('user/demote/{user}', [UserController::class, 'demote']);
    Route::resource('user', UserController::class)->only('show', 'index');
    // ------------------- User Routes

    // Cart Routes -------------------
    Route::get('cart/trashed', [CartController::class, 'trashed']);
    Route::resource('cart', CartController::class)->only('index');
    // ------------------- Cart Routes

    // Address Routes -------------------
    Route::get('address/trashed', [AddressController::class, 'trashed']);
    Route::post('address/restore/{address}', [AddressController::class, 'restoreData'])->withTrashed();
    Route::resource('address', AddressController::class)->only('index');
    // ------------------- Address Routes

    // Tag Routes -------------------
    Route::get('tag/trashed', [TagController::class, 'trashed']);
    Route::post('tag/restore/{tag}', [TagController::class, 'restoreData'])->withTrashed();
    Route::resource('tag', TagController::class)->only('store', 'update', 'destroy', 'index');
    // ------------------- Tag Routes

    // Article Routes -------------------
    Route::get('article/trashed', [ArticleController::class, 'trashed']);
    Route::post('article/restore/{article}', [ArticleController::class, 'restoreData'])->withTrashed();
    Route::resource('article', ArticleController::class)->only('store', 'update', 'delete');
    // ------------------- Article Routes
});
// --------------------------------------------------------------------------- Admin Routes

// User Routes ---------------------------------------------------------------------------
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    // User
    Route::put('user', [UserController::class, 'update']);
    // Cart
    Route::resource('cart', CartController::class)->only('store', 'update', 'show');
    // Address
    Route::resource('address', AddressController::class)->only('store', 'update', 'destroy', 'show');
});
// --------------------------------------------------------------------------- User Routes
