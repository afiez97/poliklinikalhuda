<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('admin')]
#[Middleware('web')]
class AdminController extends Controller
{
    #[Get('/login', name: 'admin.login')]
    public function login()
    {
        return view('admin.login');
    }

    #[Post('/login', name: 'admin.doLogin')]
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

    #[Get('/dashboard', name: 'admin.dashboard')]
    #[Middleware('auth')]
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    #[Get('/appointments', name: 'admin.appointments')]
    #[Middleware('auth')]
    public function appointments()
    {
        return view('admin.appointments');
    }

    #[Get('/services', name: 'admin.services')]
    #[Middleware('auth')]
    public function services()
    {
        return view('admin.services');
    }
    
        public function register()
    {
        return view('auth.register');
    }
}
