<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    public function addbook(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'genre' => 'nullable|string|max:100',
            'copies' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'published_at' => 'required|date',
        ]);
        $book = Book::create($validated);
        $book->Inventory()->create([
            'total_copies' => $book->copies,
            'available_copies' => $book->copies,
        ]);

        return response([
            'message' => 'Book registered successfully',
            'data' => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
            ]
        ], 201);
    }

    public function getAllBooks()
    {
        $books = Book::all();
        return response([
            'message' => 'Books retrieved successfully',
            'data' => $books
        ]);
    }

    public function bookNotFound($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response(['Message' => 'Book not found'], 404);
        }
        return $book;
        exit;
    }

    public function getBook($id)
    {
        $book = $this->bookNotFound($id);
        if ($book) {
            return $book;
        }
        return response([
            'Message' => 'Book retrieved successfully',
            'book' => $book
        ], 201);
    }


    public function updateBook(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response([
                'message' => 'Book not found'
            ], 404);
        }
        $validated = $request->validate([
            'author' => 'sometimes|string|max:255',
            'genre' => 'sometimes|nullable|string|max:100',
            'copies' => 'sometimes|integer|min:0',
            'description' => 'sometimes|nullable|string',
        ]);
        $book->update($validated);
        return response([
            'message' => 'Book updated successfully',
            'book' => $book->only(['id', 'title', 'author', 'isbn', 'description', 'published_at']),
        ], 200);
    }

    public function delete($id)
    {
        $book = $this->bookNotFound($id);
        if ($book instanceof Response) {
            return $book;
        }
        $book->delete();
        return response([
            'message' => 'Book deleted successfully'
        ]);
    }
}