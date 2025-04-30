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
        // Handle authentication here
        // If success: return redirect()->route('admin.dashboard');
        // If fail: return back()->withErrors([...]);
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
}
