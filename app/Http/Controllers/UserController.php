<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ambil semua pengguna
        $users = User::all();
        
        // Kembalikan respons menggunakan UserResource
        return UserResource::collection($users);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
            ]);
    
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
    
            // Simpan pengguna baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
    
            // Kembalikan respons menggunakan UserResource
            return response()->json(['message' => 'User created successfully!', 'data' => new UserResource($user)], 201);
    
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        }
    }
    

    /**
     * Display the specified user.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Kembalikan pengguna menggunakan UserResource
        return new UserResource($user);
    }

    /**
     * Update the specified user in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'string|min:8|confirmed',
            'phone' => 'string|max:15',
            'address' => 'string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($request->all());
        return response()->json(['message' => 'User updated successfully!', 'data' => $user]);
    }
    

    /**
     * Remove the specified user from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Hapus pengguna
        $user->delete();

        // Kembalikan respons sukses
        return response()->json(['message' => 'User deleted successfully!']);
    }
}
