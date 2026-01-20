<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => __('auth.admin.invalid_credentials'),
            'email' => __('auth.admin.invalid_email'),
        ]);
    }

    #[Get('/dashboard', name: 'admin.dashboard')]
    #[Middleware('auth')]
    public function dashboard()
    {
        $stats = [
            'daily_patients' => 0,
            'monthly_visits' => 0,
            'today_appointments' => 0,
            'active_doctors' => 0,
        ];

        return view('admin.dashboard', compact('stats'));
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
