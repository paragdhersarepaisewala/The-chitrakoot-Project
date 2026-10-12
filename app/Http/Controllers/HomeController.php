<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\SevaProject;
use App\Models\Destination;
use App\Models\Event;
use App\Models\Cause;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $heroSlide = HeroSlide::where('is_active', true)->orderBy('sort_order', 'asc')->first();

        $sevas = SevaProject::where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->take(3)
            ->get();

        $destinations = Destination::where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->take(4)
            ->get();

        $events = Event::upcoming()
            ->take(3)
            ->get();

        $causes = Cause::where('status', 'active')
            ->where('is_archived', false)
            ->take(3)
            ->get();

        return view('home', compact('heroSlide', 'sevas', 'destinations', 'events', 'causes'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        return back()->with('success', 'आपके संदेश के लिए धन्यवाद। हम आपसे शीघ्र ही संपर्क करेंगे।');
    }
}
