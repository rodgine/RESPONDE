<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    // Display list of users
    public function index()
    {
        $users = User::orderBy('id', 'asc')->get();
        return view('admin.user-management', compact('users'));
    }

    // Store new user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|min:6',
            'role' => 'required|string|in:admin,responder,user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'title' => 'Validation Failed',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'status' => 'success',
            'title' => 'User Added',
            'message' => "{$user->name} has been added successfully.",
        ]);
    }



    // Update existing user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20',
            'role' => 'required|string|in:admin,responder,user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'title' => 'Validation Failed',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user->update($request->only('name', 'username', 'email', 'phone_number', 'role'));

        return response()->json([
            'status' => 'success',
            'title' => 'User Updated',
            'message' => "{$user->name}'s record has been updated successfully.",
        ]);
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'title' => 'User Deleted',
            'message' => "{$user->name} has been removed from the system.",
        ]);
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:15',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return response()->json([
            'status' => 'success',
            'title' => 'User Updated',
            'message' => 'User details have been updated successfully.',
        ]);
    }

    public function editResponder($id)
    {
        $user = User::where('id', $id)
                    ->where('role', 'responder')
                    ->firstOrFail();

        return view('responder.edit', compact('user'));
    }

    public function updateResponder(Request $request, $id)
    {
        $user = User::where('id', $id)
                    ->where('role', 'responder')
                    ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:15',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'title' => 'Responder Updated',
            'message' => 'Responder details have been updated successfully.',
        ]);
    }
}
