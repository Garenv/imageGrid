<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userId = Auth::user()["UserID"];
        return view('home', ['userId' => $userId]);
    }

    public function login() {
        return view('auth.login');
    }

    public function checkSession()
    {
        if (!Auth::check()) {
            return response()->json(['authenticated' => false]);
        }

        return response()->json(['authenticated' => true]);

    }

}
