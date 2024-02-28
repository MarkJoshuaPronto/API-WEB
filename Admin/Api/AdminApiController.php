<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AdminApiController extends Controller
{
    public function list()
    {
        $admins = User::getAdmin();
        return response()->json($admins);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->status = $request->status;
        $user->is_admin = 1;
        $user->save();

        return response()->json(['message' => 'Admin successfully created'], 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|string|min:6',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->status = $request->status;
        $user->save();

        return response()->json(['message' => 'Admin successfully updated'], 200);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Admin not found'], 404);
        }

        $user->is_delete = 1;
        $user->save();

        return response()->json(['message' => 'Admin successfully deleted'], 200);
    }
}
