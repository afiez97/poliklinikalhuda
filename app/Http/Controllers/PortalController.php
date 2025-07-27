<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Middleware('web')]
class PortalController extends Controller
{
    #[Get('/', name: 'portal.home')]
    public function home()
    {
        return view('portal.home');
    }

    #[Get('/about', name: 'portal.about')]
    public function about()
    {
        return view('portal.about');
    }

    #[Get('/treatments', name: 'portal.treatments')]
    public function treatments()
    {
        return view('portal.treatments');
    }

    #[Get('/appointment', name: 'portal.appointment')]
    public function appointmentForm()
    {
        return view('portal.appointment');
    }

    #[Post('/appointment', name: 'portal.appointment.submit')]
    public function submitAppointment(Request $request)
    {
        // Validate and save appointment here
        return redirect()->route('portal.home')->with('success', 'Temujanji anda telah dihantar.');
    }

    /**
     * Redirect to Google OAuth
     */
    #[Get('/auth/google', name: 'auth.google')]
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    #[Get('/auth/google/callback', name: 'auth.google.callback')]
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find existing user with this Google ID
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // User already exists with Google ID, login
                Auth::login($user);
                return redirect()->route('admin.dashboard')->with('success', __('auth.login_success'));
            }

            // Check if user exists with same email in database
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Link Google account to existing user (user must already exist in database)
                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                Auth::login($existingUser);
                return redirect()->route('admin.dashboard')->with('success', __('auth.account_linked'));
            }

            // User does not exist in database - access denied
            return redirect()->route('admin.login')->with('error', __('auth.access_denied'));

        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', __('auth.google_login_failed'));
        }
    }

    /**
     * Logout user
     */
    #[Post('/logout', name: 'logout')]
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session and regenerate the CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Check if the logout was from admin area and redirect accordingly
        $referer = $request->headers->get('referer');
        if ($referer && str_contains($referer, '/admin')) {
            return redirect()->route('admin.login')->with('success', __('auth.logout_success'));
        }

        return redirect()->route('portal.home')->with('success', __('auth.logout_success'));
    }
}
