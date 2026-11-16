<?php

namespace App\Http\Controllers;

use App\Models\SevaProject;

class SevaController extends Controller
{
    public function index()
    {
        $sevas = SevaProject::where('is_published', true)->orderBy('sort_order', 'asc')->get();

        return view('pages.seva_index', compact('sevas'));
    }

    public function show($slug)
    {
        $seva = SevaProject::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('pages.seva_show', compact('seva'));
    }
}
