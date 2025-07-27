<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

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
}
