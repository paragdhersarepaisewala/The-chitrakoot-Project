<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load(['profile', 'membership', 'donations.receipt', 'donations.cause']);
        return view('user.dashboard', compact('user'));
    }
}
