<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PesakitController extends Controller
{
    public function index()
    {
        // TODO: Fetch and pass patient list
        return view('admin.pesakit.index');
    }

    public function create()
    {
        return view('admin.pesakit.create');
    }

    public function search()
    {
        return view('admin.pesakit.search');
    }
}
