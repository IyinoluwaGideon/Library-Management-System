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
Route::patch('users/{user}', [AuthController::class, 'update']);
Route::delete('users/{user}', [AuthController::class, 'delete']);
Route::get('/users', [AuthController::class, 'getAllUsers']);
Route::get('/users/{user}', [AuthController::class, 'getUserById']);
Route::post('/books', [BookController::class, 'store']);
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{book}', [BookController::class, 'show']);
Route::patch('/books/{book}', [BookController::class, 'update']);
Route::delete('/books/{book}', [BookController::class, 'delete']);
Route::post('/borrow', [BorrowController::class, 'borrowBook']);
Route::post('/borrows/{borrow}/return', [BorrowController::class, 'returnBook']);
Route::get('/borrows', [BorrowController::class, 'getAllBorrows']);
Route::get('/borrows/{borrow}', [BorrowController::class, 'getBorrowById']);
Route::patch('/inventory/{book}', [InventoryController::class, 'updateInventory']);




































// Route::get('/dashboard', function () {
//     return response()->json(['message' => 'You are logged in and can access this protected route.']);
// })->middleware(EnsureUserIsLoggedIn::class);