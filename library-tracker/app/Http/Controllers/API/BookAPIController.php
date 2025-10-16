<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookAPIController extends Controller
{
    public function getIndex(?int $id = null)
    {
        if ($id) {
            return Book::with('author')->find($id);
        }

        return Book::with('author')->orderBy('id', 'DESC')->get();
    }

    public function postIndex(Request $request) {
        $data = $request->validate([
            'author_id'   => 'required|exists:authors,id',
            'title'       => 'required|string|max:255',
            'genre'       => 'nullable|string|max:50',
            'isbn'        => 'nullable|string|max:32|unique:books,isbn',
            'published_at'=> 'nullable|datetime',
        ]);

        return Book::create($data);
    }

    public function putIndex(Request $request, int $id) {
        $book = Book::find($id);
        if (empty($book)) {
            throw new Exception('Could not find book.');
        }

        $data = $request->validate([
            'author_id'   => 'sometimes|exists:authors,id',
            'title'       => 'sometimes|string|max:255',
            'genre'       => 'nullable|string|max:50',
            'isbn'        => 'nullable|string|max:32|unique:books,isbn,' . $id,
            'published_at'=> 'nullable|datetime',
        ]);

        $book->update($data);
        return $book;
    }

    public function deleteIndex(int $id) {
        $book = Book::find($id);
        if (empty($book)) {
            throw new Exception('Could not find book.');
        }

        $book->delete();

        return response()->noContent();
    }
}
