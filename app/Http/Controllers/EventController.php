<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::upcoming()->paginate(10);
        return view('pages.events_index', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('pages.events_show', compact('event'));
    }
}
