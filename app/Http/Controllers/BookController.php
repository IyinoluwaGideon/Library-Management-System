<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function store(Request $request)
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
        return response([
            'message' => 'Book registered successfully',
            'book' => $book
        ], 201);
    }

    public function getAllBooks()
    {
        $books = Book::all();
        return response([
            'message' => 'Books retrieved successfully',
            'books' => $books
        ]);
    }

    public function getBook($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response([
                'message' => 'Book not found'
            ], 404);
        }
        return response([
            'message' => 'Book retrieved successfully',
            'book' => $book
        ]);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response([
                'message' => 'Book not found'
            ], 404);
        }
        $validated = $request->validate([
            'author' => 'sometimes|required|string|max:255',
            'genre' => 'sometimes|nullable|string|max:100',
            'copies' => 'sometimes|required|integer|min:0',
            'description' => 'sometimes|nullable|string',
        ]);
        $book->update($validated);
        return response([
            'message' => 'Book updated successfully',
            'book' => $book
        ], 200);
    }

    public function delete($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return response([
                'message' => 'Book not found'
            ], 404);
        }
        $book->delete();
        return response([
            'message' => 'Book deleted successfully'
        ]);
    }
}
