<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only("email", "password");
        if(Auth::attempt($credentials, true)) return response()->json(['message' => 'Login successful']);
        return response()->json(['message' => 'The provided credentials do not match our records.'], 401);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logout successful']);
    }
}
