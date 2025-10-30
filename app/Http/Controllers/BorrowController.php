<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function borrowBook(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrowed_at' => 'required|date',
            'due_at' => 'required|date|after:borrowed_at',
        ]);

        $alreadyBorrowed = Borrow::where('user_id', $validated['user_id'])
            ->where('book_id', $validated['book_id'])
            ->whereNull('returned_at')
            ->first();
            

        if ($alreadyBorrowed) {
            return response([
                'message' => 'Book is already borrowed',
            ], 400);
        }

        $book = Book::with('inventory')->findOrFail($validated['book_id']);
        return $book;
        exit;
        if (!$book->inventory || $book->inventory->available_copies < 1) {
            return response([
                'message' => 'This book is currently unavailable (no copies left).',
            ], 409);
        }

        $borrow = Borrow::create([
            'user_id' => $validated['user_id'],
            'book_id' => $validated['book_id'],
            'borrowed_at' => $validated['borrowed_at'],
            'due_at' => $validated['due_at'],
            'returned_at' => null,
            'fine' => 0,
        ]);

        $book->inventory->decrement('available_copies', 1);

        return response([
            'message' => 'Book borrowed successfully',
            'borrow' => $borrow,
        ], 201);
    }

    public function returnBook(Borrow $borrow)
    {

        if ($borrow->returned_at) {
            return response([
                'message' => 'Book already returned',
            ], 400);
        }

        $returned_date = Carbon::now();

        $dueDate = Carbon::parse($borrow->due_at);
        $daysAfterDueDate = $returned_date->greaterThan($dueDate)
            ? $dueDate->diffInDays($returned_date)
            : 0;
        $ratePerDay = 100;
        $fine = $daysAfterDueDate * $ratePerDay;

        $borrow->returned_at = $returned_date;
        $borrow->fine = $fine;
        $borrow->save();

        $book = $borrow->book()->with('inventory')->first();
        if ($book && $book->inventory) {
            $book->inventory->increment('available_copies', 1);
        }

        return response([
            'message' => 'Book returned successfully',
            'borrow' => $borrow,
            'data' => [
                'days_late' => $daysAfterDueDate,
                'fine' => $fine,
            ],
        ], 200);
    }

    public function getAllBorrows()
    {
        $borrows = Borrow::all();
        return response([
            'data' => $borrows,
        ], 200);
    }

    public function getBorrowById($id)
    {
        $borrow = Borrow::find($id);
        if (!$borrow) {
            return response([
                'message' => 'Borrow record not found'
            ], 404);
        }

        $this->authorize('view', $borrow);
        return response([
            'message' => 'Borrow record retrieved successfully',
            'borrow' => $borrow
        ], 200);
    }
}
