<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\InventoryController;
use App\Http\Middleware\EnsureUserIsLoggedIn;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::patch('users/{userId}', [AuthController::class, 'update']);
Route::delete('users/{user}', [AuthController::class, 'delete']);
Route::get('/users', [AuthController::class, 'getAllUsers']);
Route::get('/users/{userId}', [AuthController::class, 'getUserById']);
Route::middleware(['auth:sanctum', 'admin' ])->group(function () {
    Route::post('/books', [BookController::class, 'addbook']);
    Route::patch('/inventory/{book}', [InventoryController::class, 'updateInventory']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/borrow', [BorrowController::class, 'borrowBook']);
    Route::post('/borrows/{borrowId}/return', [BorrowController::class, 'returnBook']);
    Route::get('/borrows', [BorrowController::class, 'getAllBorrows']);
    Route::get('/borrows/{borrow}', [BorrowController::class, 'getBorrowById']);
});
Route::get('/books', [BookController::class, 'getAllBooks']);
Route::get('/books/{book}', [BookController::class, 'getBook']);
Route::patch('/books/{book}', [BookController::class, 'updateBook']);
Route::delete('/books/{book}', [BookController::class, 'delete']);
Route::patch('/inventory/{book}', [InventoryController::class, 'updateInventory']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');




































// Route::get('/dashboard', function () {
//     return response()->json(['message' => 'You are logged in and can access this protected route.']);
// })->middleware(EnsureUserIsLoggedIn::class);