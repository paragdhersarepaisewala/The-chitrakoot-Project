<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Auto-expire passed events into archive
        Event::autoArchiveExpired();

        $tab = $request->query('tab', 'upcoming');
        if (! in_array($tab, ['upcoming', 'archive'], true)) {
            $tab = 'upcoming';
        }

        $upcomingCount = Event::upcoming()->count();
        $archivedCount = Event::archived()->count();

        if ($tab === 'archive') {
            $events = Event::archived()->paginate(10)->withQueryString();
        } else {
            $events = Event::upcoming()->paginate(10)->withQueryString();
        }

        return view('pages.events_index', compact('events', 'tab', 'upcomingCount', 'archivedCount'));
    }

    public function show($slug)
    {
        Event::autoArchiveExpired();

        $event = Event::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('pages.events_show', compact('event'));
    }
}
