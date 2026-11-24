<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\AtithiVishramEnquiry;
use App\Models\HomepageSetting;
use App\Models\OrganizationSetting;
use App\Models\PoojaService;
use App\Models\TempleBookingEnquiry;
use App\Services\EmailNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicesController extends Controller
{
    /**
     * Services Hub: Showcase all sacred services, poojas, aartis, atithi vishram, and temple booking.
     */
    public function index(): View
    {
        $orgSettings = OrganizationSetting::getSettings();
        $homeSettings = HomepageSetting::getAll();
        $poojas = PoojaService::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        $configuredAarti = ! empty($homeSettings['aarti_slots_json']) ? json_decode($homeSettings['aarti_slots_json'], true) : [];
        $normalAartiSlots = (! empty($configuredAarti) && is_array($configuredAarti)) ? $configuredAarti : [
            ['name' => 'प्रातः मंगला आरती', 'time' => '05:00 AM', 'desc' => 'कामदगिरि मुख्य गर्भगृह', 'is_live' => false],
            ['name' => 'ठाकुर जी शृंगार व बालभोग', 'time' => '07:30 AM', 'desc' => 'कामदगिरि मंदिर', 'is_live' => false],
            ['name' => 'राजभोग महाआरती', 'time' => '12:00 PM', 'desc' => 'अन्नकूट एवं भोग अर्पण', 'is_live' => false],
            ['name' => 'श्री कामदगिरि संध्या महाआरती', 'time' => '06:30 PM', 'desc' => 'रामघाट मन्दाकिनी पावन तट', 'is_live' => true],
            ['name' => 'शयन आरती', 'time' => '09:00 PM', 'desc' => 'कपाट विश्राम', 'is_live' => false],
        ];

        return view('pages.services.index', compact('orgSettings', 'homeSettings', 'poojas', 'normalAartiSlots'));
    }

    /**
     * Dedicated Aarti & Darshan Schedule Page.
     */
    public function aarti(): View
    {
        $orgSettings = OrganizationSetting::getSettings();
        $homeSettings = HomepageSetting::getAll();

        $configuredAarti = ! empty($homeSettings['aarti_slots_json']) ? json_decode($homeSettings['aarti_slots_json'], true) : [];
        $normalAartiSlots = (! empty($configuredAarti) && is_array($configuredAarti)) ? $configuredAarti : [
            ['name' => 'प्रातः मंगला आरती', 'time' => '05:00 AM', 'desc' => 'कामदगिरि मुख्य गर्भगृह', 'is_live' => false],
            ['name' => 'ठाकुर जी शृंगार व बालभोग', 'time' => '07:30 AM', 'desc' => 'कामदगिरि मंदिर', 'is_live' => false],
            ['name' => 'राजभोग महाआरती', 'time' => '12:00 PM', 'desc' => 'अन्नकूट एवं भोग अर्पण', 'is_live' => false],
            ['name' => 'श्री कामदगिरि संध्या महाआरती', 'time' => '06:30 PM', 'desc' => 'रामघाट मन्दाकिनी पावन तट', 'is_live' => true],
            ['name' => 'शयन आरती', 'time' => '09:00 PM', 'desc' => 'कपाट विश्राम', 'is_live' => false],
        ];

        $configuredAmavasya = ! empty($homeSettings['aarti_amavasya_slots_json']) ? json_decode($homeSettings['aarti_amavasya_slots_json'], true) : [];
        $amavasyaSlots = (! empty($configuredAmavasya) && is_array($configuredAmavasya)) ? $configuredAmavasya : [
            ['name' => 'परिक्रमा मंगला आरती', 'time' => '03:30 AM', 'desc' => 'प्रातः विशेष कामदगिरि परिक्रमा', 'is_live' => false],
            ['name' => 'अखण्ड वैदिक हवन', 'time' => '08:00 AM – 11:30 AM', 'desc' => 'ऋषिकुल यज्ञशाला', 'is_live' => false],
            ['name' => 'मन्दाकिनी महा दीपोत्सव आरती', 'time' => '07:00 PM', 'desc' => 'रामघाट 11,000+ दीप प्रज्ज्वलन', 'is_live' => true],
            ['name' => 'अखण्ड रात्रि महापूजा व जागरण', 'time' => '10:30 PM', 'desc' => 'अमावस्या मध्यरात्रि विशेष अनुष्ठान', 'is_live' => false],
            ['name' => 'भोर आरती व कपाट विश्राम', 'time' => '04:00 AM', 'desc' => 'पर्व समापन एवं आशीर्वाद', 'is_live' => false],
        ];

        return view('pages.services.aarti', compact('orgSettings', 'homeSettings', 'normalAartiSlots', 'amavasyaSlots'));
    }

    /**
     * Dedicated Atithi Vishram (Pilgrim Guest House Stay) Enquiry Page.
     */
    public function atithiVishram(): View
    {
        $orgSettings = OrganizationSetting::getSettings();

        return view('pages.services.atithi-vishram', compact('orgSettings'));
    }

    /**
     * Process Atithi Vishram Enquiry Form Submission.
     */
    public function submitAtithiVishram(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'devotee_name' => 'required|string|max:150',
            'phone' => 'required|string|min:10|max:20',
            'email' => 'required|email|max:150',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after_or_equal:check_in_date',
            'number_of_devotees' => 'required|integer|min:1|max:100',
            'number_of_rooms' => 'required|integer|min:1|max:20',
            'room_preference' => 'required|string|in:standard,ac_room,family_suite,dormitory',
            'purpose_of_stay' => 'required|string|max:100',
            'id_proof_type' => 'nullable|string|max:50',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        $validated['enquiry_number'] = AtithiVishramEnquiry::generateEnquiryNumber();
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'new';
        $validated['is_read'] = false;

        $enquiry = AtithiVishramEnquiry::create($validated);

        ActivityLog::log(
            'services',
            'atithi_vishram_enquiry',
            "New guest house enquiry #{$enquiry->enquiry_number} submitted by {$enquiry->devotee_name} ({$enquiry->phone})"
        );

        // Dispatch email notification
        EmailNotificationService::sendAtithiVishramReceived($enquiry);

        return redirect()->route('services.atithiVishram')
            ->with('success', 'आपकी अतिथि विश्राम पूछताछ संदर्भ संख्या #'.$enquiry->enquiry_number.' के साथ सफलतापूर्वक दर्ज हो गई है। हमारी आश्रम व्यवस्था समिति आपसे शीघ्र संपर्क करेगी।')
            ->with('enquiry_number', $enquiry->enquiry_number);
    }

    /**
     * Dedicated Temple Hall & Premises Booking Enquiry Page.
     */
    public function templeBooking(): View
    {
        $orgSettings = OrganizationSetting::getSettings();

        return view('pages.services.temple-booking', compact('orgSettings'));
    }

    /**
     * Process Temple Hall & Premises Booking Enquiry Submission.
     */
    public function submitTempleBooking(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'devotee_name' => 'required|string|max:150',
            'phone' => 'required|string|min:10|max:20',
            'email' => 'required|email|max:150',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'event_type' => 'required|string|in:shadi,hawan,katha,pooja,bhandara,satsang,mundan,other',
            'event_title' => 'nullable|string|max:150',
            'proposed_date' => 'required|date|after_or_equal:today',
            'alternate_date' => 'nullable|date|after_or_equal:today',
            'duration_days' => 'required|integer|min:1|max:30',
            'expected_gathering' => 'required|integer|min:5|max:10000',
            'requires_priest' => 'nullable|boolean',
            'requires_catering' => 'nullable|boolean',
            'requires_accommodation' => 'nullable|boolean',
            'additional_requirements' => 'nullable|string|max:2000',
        ]);

        $validated['requires_priest'] = $request->boolean('requires_priest');
        $validated['requires_catering'] = $request->boolean('requires_catering');
        $validated['requires_accommodation'] = $request->boolean('requires_accommodation');
        $validated['enquiry_number'] = TempleBookingEnquiry::generateEnquiryNumber();
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'new';
        $validated['is_read'] = false;

        $enquiry = TempleBookingEnquiry::create($validated);

        ActivityLog::log(
            'services',
            'temple_booking_enquiry',
            "New temple booking enquiry #{$enquiry->enquiry_number} ({$enquiry->getEventTypeLabel()}) submitted by {$enquiry->devotee_name}"
        );

        // Dispatch email notification
        EmailNotificationService::sendTempleBookingReceived($enquiry);

        return redirect()->route('services.templeBooking')
            ->with('success', 'आपका मंदिर परिसर आरक्षण प्रस्ताव संदर्भ संख्या #'.$enquiry->enquiry_number.' के साथ प्राप्त हो गया है। ट्रस्ट प्रबंधन शुभ मुहूर्त एवं व्यवस्था अनुसार आपसे परामर्श हेतु संपर्क करेगा।')
            ->with('enquiry_number', $enquiry->enquiry_number);
    }
}
