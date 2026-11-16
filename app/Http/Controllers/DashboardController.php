<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\OrganizationSetting;
use App\Models\PoojaBooking;
use App\Models\VolunteerApplication;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load(['profile', 'membership']);

        $orgSettings = OrganizationSetting::getSettings();
        if ($orgSettings->isSmtpConfigured() && is_null($user->email_verified_at) && ! $user->hasRole('super_admin', 'content_admin', 'donation_admin', 'membership_admin', 'media_editor')) {
            return redirect('/register?step=2&email='.urlencode($user->email))
                ->with('warning', 'डैशबोर्ड में प्रवेश करने से पूर्व कृपया अपने ईमेल पर प्राप्त 6-अंकीय OTP सत्यापित करें। (Please verify your email OTP before accessing dashboard).');
        }

        // Auto-link any previous guest records matching devotee's email
        if (! empty($user->email)) {
            Donation::whereNull('user_id')->where('donor_email', $user->email)->update(['user_id' => $user->id]);
            VolunteerApplication::whereNull('user_id')->where('email', $user->email)->update(['user_id' => $user->id]);
            PoojaBooking::whereNull('user_id')->where('email', $user->email)->update(['user_id' => $user->id]);
        }

        // Fetch all donations (by user_id and email)
        $donations = Donation::with(['receipt', 'cause'])
            ->where(function ($q) use ($user): void {
                $q->where('user_id', $user->id);
                if (! empty($user->email)) {
                    $q->orWhere('donor_email', $user->email);
                }
            })
            ->latest()
            ->get();

        // Fetch all volunteer applications (by user_id and email)
        $volunteerApplications = VolunteerApplication::where(function ($q) use ($user): void {
            $q->where('user_id', $user->id);
            if (! empty($user->email)) {
                $q->orWhere('email', $user->email);
            }
        })
            ->latest()
            ->get();

        // Fetch all pooja & ritual bookings (by user_id and email)
        $poojaBookings = PoojaBooking::with('service')
            ->where(function ($q) use ($user): void {
                $q->where('user_id', $user->id);
                if (! empty($user->email)) {
                    $q->orWhere('email', $user->email);
                }
            })
            ->latest()
            ->get();

        return view('user.dashboard', compact('user', 'donations', 'volunteerApplications', 'poojaBookings'));
    }

    /**
     * Update devotee profile details.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $maxDob = now()->subYears(18)->format('Y-m-d');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Male,Female,Other,male,female,other',
            'date_of_birth' => 'nullable|date|before_or_equal:'.$maxDob,
            'occupation' => 'nullable|string|max:150',
            'district' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:500',
        ], [
            'date_of_birth.before_or_equal' => 'आयु १८ वर्ष या उससे अधिक होनी चाहिए (Must be at least 18 years old).',
        ]);

        $user->update(['name' => $validated['name']]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'occupation' => $validated['occupation'] ?? null,
                'district' => $validated['district'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'pincode' => $validated['pincode'] ?? null,
                'address' => $validated['address'] ?? null,
            ]
        );

        return back()->with('success', 'आपकी व्यक्तिगत जानकारी (Profile Details) सफलतापूर्वक अद्यतन कर दी गई है।');
    }
}
