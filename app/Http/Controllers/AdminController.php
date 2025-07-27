<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        dd($credentials);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Nama pengguna atau kata laluan tidak sah.',
            'email' => 'Maklumat yang diberikan tidak sepadan dengan rekod kami.',
        ]);
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function appointments()
    {
        return view('admin.appointments');
    }

    public function services()
    {
        return view('admin.services');
    }
    
        public function register()
    {
        return view('auth.register');
    }
}
