<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    public function index ()
    {
        return view('login');
    }
    public function auth (LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        return redirect()->route('dashboard');
    }
    return redirect()->back()->with('error', 'Login gagal');
}
    public function logout (Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}