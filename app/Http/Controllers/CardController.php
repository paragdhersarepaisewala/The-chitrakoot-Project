<?php

namespace App\Http\Controllers;

use App\Models\OrganizationSetting;

class CardController extends Controller
{
    public function show()
    {
        $user = auth()->user()->load(['profile', 'membership', 'donations' => function ($q) {
            $q->where('status', 'verified');
        }]);

        $orgSettings = OrganizationSetting::getSettings();
        if ($orgSettings->isSmtpConfigured() && is_null($user->email_verified_at) && ! $user->hasRole('super_admin', 'content_admin', 'donation_admin', 'membership_admin', 'media_editor')) {
            return redirect('/register?step=2&email='.urlencode($user->email))
                ->with('warning', 'कृपया अपने सदस्यता कार्ड तक पहुँचने से पहले अपने ईमेल पर प्राप्त OTP सत्यापित करें।');
        }

        $verifiedDonationsCount = $user->donations->count();
        $isMember = ($user->membership?->status === 'active' || $user->role === 'member');
        $isSupporter = ($verifiedDonationsCount > 0 && ! $isMember);

        $cardType = 'none';
        if ($isMember) {
            $cardType = 'membership';
        } elseif ($isSupporter) {
            $cardType = 'supporter';
        }

        $orgSettings = OrganizationSetting::getSettings();

        return view('user.card', compact('user', 'cardType', 'verifiedDonationsCount', 'orgSettings'));
    }
}
