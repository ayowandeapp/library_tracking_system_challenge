<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorAPIController extends Controller
{
    public function getIndex(?int $id = null)
    {
        if ($id) {
            return Author::find($id);
        }

        return Author::orderBy('id', 'DESC')->get();
    }

    public function postIndex(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'biography' => 'nullable|string|max:5000',
        ]);

        return Author::create($data);
    }

    public function putIndex(Request $request, int $id)
    {
        $author = Author::find($id);
        if (empty($author)) {
            throw new Exception('Could not find author.');
        }

        $data = $request->validate([
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'biography' => 'nullable|string|max:5000',
        ]);

        $author->update($data);
        return $author;
    }

    public function deleteIndex(int $id)
    {
        $author = Author::find($id);
        if (empty($author)) {
            throw new Exception('Could not find author.');
        }

        $author->delete();

        return response()->noContent();
    }
}
