<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenDashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.dosen');
    }
}
