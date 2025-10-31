<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAPIController extends Controller
{
    public function getTopActive()
    {
        $users = User::withCount([
            'loans' => function ($q) {
                $q->whereNull('returned_at');
            }
        ])
            ->orderByDesc('loans_count')
            ->take(5)
            ->get(['id', 'name', 'email']);

        return response()->json($users->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'active_loans' => $u->loans_count,
            ];
        }));
    }

    public function getIndex(?int $id = null)
    {
        if ($id) {
            return User::find($id);
        }

        return User::orderBy('id', 'DESC')->get();
    }

    public function postIndex(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        return User::create($data);
    }

    public function putIndex(Request $request, int $id)
    {
        $user = User::find($id);
        if (empty($user)) {
            throw new Exception('Could not find user.');
        }

        $data = $request->validate([
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
        ]);

        $user->update($data);
        return $user;
    }

    public function deleteIndex(int $id)
    {
        $user = User::find($id);
        if (empty($user)) {
            throw new Exception('Could not find user.');
        }

        $user->delete();

        return response()->noContent();
    }
}
