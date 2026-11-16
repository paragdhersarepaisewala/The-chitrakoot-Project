<?php

namespace App\Http\Controllers;

use App\Models\Destination;

class ChitrakootController extends Controller
{
    public function index()
    {
        $destinations = Destination::where('is_published', true)->orderBy('sort_order', 'asc')->get();

        return view('pages.chitrakoot_index', compact('destinations'));
    }

    public function show($slug)
    {
        $destination = Destination::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('pages.chitrakoot_show', compact('destination'));
    }
}
