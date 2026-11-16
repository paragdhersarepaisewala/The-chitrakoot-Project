<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use App\Models\ContactInquiry;
use App\Models\Destination;
use App\Models\Event;
use App\Models\HeroSlide;
use App\Models\HomepageSetting;
use App\Models\LeadershipProfile;
use App\Models\NewsletterSubscriber;
use App\Models\OrganizationSetting;
use App\Models\PoojaService;
use App\Models\SevaProject;
use App\Models\VolunteerApplication;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $homeSettings = HomepageSetting::getAll();
        $heroSlide = HeroSlide::where('is_active', true)->orderBy('sort_order', 'asc')->first();

        $parseIds = function ($raw): array {
            if (empty($raw)) {
                return [];
            }
            if (is_array($raw)) {
                return array_values(array_filter(array_map('intval', $raw)));
            }
            $decoded = json_decode((string) $raw, true);
            if (is_array($decoded)) {
                return array_values(array_filter(array_map('intval', $decoded)));
            }

            return array_values(array_filter(array_map('intval', explode(',', (string) $raw))));
        };

        // 1. Granular Seva Projects Selection
        $selectedSevaIds = $parseIds($homeSettings['selected_seva_ids'] ?? null);
        if (! empty($selectedSevaIds)) {
            $sevas = SevaProject::whereIn('id', $selectedSevaIds)->get();
        } else {
            $sevas = SevaProject::where('is_published', true)
                ->orderBy('sort_order', 'asc')
                ->take(3)
                ->get();
        }

        // 2. Granular Sacred Destinations Selection
        $selectedDestIds = $parseIds($homeSettings['selected_destination_ids'] ?? null);
        if (! empty($selectedDestIds)) {
            $destinations = Destination::whereIn('id', $selectedDestIds)->get();
        } else {
            $destinations = Destination::where('is_published', true)
                ->orderBy('sort_order', 'asc')
                ->take(4)
                ->get();
        }

        // 3. Granular Donation Causes Selection
        $selectedCauseIds = $parseIds($homeSettings['selected_cause_ids'] ?? null);
        if (! empty($selectedCauseIds)) {
            $causes = Cause::whereIn('id', $selectedCauseIds)->get();
        } else {
            $causes = Cause::where('status', 'active')
                ->where('is_archived', false)
                ->take(3)
                ->get();
        }

        // 4. Granular Pooja Services Selection
        $rawPoojaIds = $homeSettings['selected_pooja_ids'] ?? null;
        if ($rawPoojaIds !== null) {
            $selectedPoojaIds = $parseIds($rawPoojaIds);
            $poojaServices = ! empty($selectedPoojaIds)
                ? PoojaService::whereIn('id', $selectedPoojaIds)->get()
                : collect();
        } else {
            $poojaServices = PoojaService::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->take(4)
                ->get();
        }

        Event::autoArchiveExpired();

        $events = Event::upcoming()
            ->take(3)
            ->get();

        return view('home', compact('heroSlide', 'sevas', 'destinations', 'events', 'causes', 'poojaServices', 'homeSettings'));
    }

    public function about()
    {
        $homeSettings = HomepageSetting::getAll();
        $orgSettings = OrganizationSetting::getSettings();
        $sevas = SevaProject::where('is_published', true)->orderBy('sort_order', 'asc')->get();
        $causes = Cause::where('status', 'active')->where('is_archived', false)->get();

        return view('pages.about', compact('homeSettings', 'orgSettings', 'sevas', 'causes'));
    }

    public function leadership()
    {
        $profiles = LeadershipProfile::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $orgSettings = OrganizationSetting::getSettings();

        return view('pages.leadership', compact('profiles', 'orgSettings'));
    }

    public function contact()
    {
        $orgSettings = OrganizationSetting::getSettings();

        return view('pages.contact', compact('orgSettings'));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactInquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? 'Website Contact Message',
            'message' => $validated['message'],
            'status' => 'new',
            'is_read' => false,
        ]);

        return back()->with('success', 'जय कामतानाथ! आपके पावन संदेश के लिए धन्यवाद। ट्रस्ट कार्यालय द्वारा आपसे शीघ्र ही संपर्क किया जाएगा। (Thank you for contacting us. We will get back to you shortly.)');
    }

    public function newsletterSubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:180',
            'phone' => 'nullable|string|max:20',
        ]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => strtolower($request->email)],
            [
                'phone' => $request->phone,
                'source' => $request->source ?? 'website_newsletter',
                'is_active' => true,
                'subscribed_at' => now(),
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'प्रणाम! आपका ईमेल दैनिक दर्शन व मासिक पत्रिका सूची में सफलतापूर्वक पंजीकृत कर लिया गया है।',
            ]);
        }

        return back()->with('success', 'प्रणाम! आपका ईमेल दैनिक दर्शन व मासिक पत्रिका सूची में सफलतापूर्वक पंजीकृत कर लिया गया है।');
    }

    public function volunteer()
    {
        $homeSettings = HomepageSetting::getAll();
        $raw = $homeSettings['volunteer_registration_enabled'] ?? '1';
        $isEnabled = ! in_array(strtolower((string) $raw), ['0', 'no', 'false', 'disabled'], true);

        return view('pages.volunteer', compact('isEnabled', 'homeSettings'));
    }

    public function volunteerSubmit(Request $request)
    {
        $homeSettings = HomepageSetting::getAll();
        $raw = $homeSettings['volunteer_registration_enabled'] ?? '1';
        $isEnabled = ! in_array(strtolower((string) $raw), ['0', 'no', 'false', 'disabled'], true);

        if (! $isEnabled) {
            return back()->with('error', 'वर्तमान में नए सेवादार पंजीकरण अस्थायी रूप से स्थगित हैं। कृपया अन्य जानकारी के लिए संपर्क करें।');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:180',
            'phone' => 'required|string|max:20',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:150',
            'seva_areas' => 'nullable|array',
            'availability' => 'nullable|string|max:100',
            'experience_notes' => 'nullable|string|max:1000',
        ]);

        $applicant = VolunteerApplication::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'city' => $request->city,
            'state' => $request->state,
            'occupation' => $request->occupation,
            'seva_areas' => $request->seva_areas ?: ['सामान्य सेवा'],
            'availability' => $request->availability,
            'experience_notes' => $request->experience_notes,
            'status' => 'pending',
        ]);

        // Send volunteer application acknowledgment email to applicant
        EmailNotificationService::sendVolunteerReceived($applicant);

        return back()->with('success', 'जय सियाराम! आपका सेवादार पंजीकरण आवेदन सफलतापूर्वक प्राप्त हो गया है। ट्रस्ट कार्यालय द्वारा आपसे शीघ्र ही संपर्क किया जाएगा।');
    }
}
