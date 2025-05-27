<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortalController extends Controller
{

    public function home()
    {
        return view('portal.home');
    }

    public function about()
    {
        return view('portal.about');
    }

    public function treatments()
    {
        return view('portal.treatments');
    }

    public function appointmentForm()
    {
        return view('portal.appointment');
    }

    public function submitAppointment(Request $request)
    {
        // Validate and save appointment here
        return redirect()->route('portal.home')->with('success', 'Temujanji anda telah dihantar.');
    }

}
