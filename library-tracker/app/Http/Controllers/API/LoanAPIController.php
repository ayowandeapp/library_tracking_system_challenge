<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Loan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanAPIController extends Controller
{
    public function putExtend(Request $request, $loan_id)
    {
        $validated = $request->validate([
            'additional_days' => 'required|integer|min:1|max:14',
        ]);

        $loan = Loan::findOrFail($loan_id);

        if ($loan->returned_at) {
            return response()->json(['message' => 'Loan already returned'], 400);
        }

        if ($loan->due_at->isPast()) {
            return response()->json(['message' => 'Cannot extend overdue loan'], 400);
        }

        $loan->due_at = $loan->due_at->addDays($validated['additional_days']);
        $loan->save();

        return response()->json([
            'message' => 'Loan extended successfully',
            'loan' => $loan,
        ]);
    }

    public function getIndex(?int $id = null)
    {
        if ($id) {
            return Loan::with('book', 'user')->find($id);
        }

        return Loan::with('book', 'user')->orderBy('id', 'DESC')->get();
    }

    public function postIndex(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'returned_at' => 'nullable|datetime',
        ]);

        return Loan::create([
            'book_id' => $data['book_id'],
            'user_id' => $data['user_id'],
            'loaned_at' => now(),
            'returned_at' => $data['returned_at'] ?? null,
        ]);
    }

    public function putIndex(Request $request, int $id)
    {
        $loan = Loan::find($id);
        if (empty($loan)) {
            throw new Exception('Could not find loan.');
        }

        $data = $request->validate([
            'returned_at' => 'nullable|datetime',
        ]);

        $loan->update($data);
        return $loan;
    }

    public function deleteIndex(int $id)
    {
        $loan = Loan::find($id);
        if (empty($loan)) {
            throw new Exception('Could not find loan.');
        }

        $loan->delete();

        return response()->noContent();
    }
}
