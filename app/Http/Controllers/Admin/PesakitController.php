<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;

#[Prefix('admin/pesakit')]
#[Middleware(['web', 'auth'])]
class PesakitController extends Controller
{
    #[Get('/', name: 'admin.pesakit.index')]
    public function index()
    {
        // TODO: Fetch and pass patient list
        return view('admin.pesakit.index');
    }

    #[Get('/create', name: 'admin.pesakit.create')]
    public function create()
    {
        return view('admin.pesakit.create');
    }

    #[Get('/search', name: 'admin.pesakit.search')]
    public function search()
    {
        return view('admin.pesakit.search');
    }
}
