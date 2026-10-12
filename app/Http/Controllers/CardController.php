<?php

namespace App\Http\Controllers;

use App\Models\OrganizationSetting;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function show()
    {
        $user = auth()->user()->load(['profile', 'membership', 'donations' => function ($q) {
            $q->where('status', 'verified');
        }]);

        $verifiedDonationsCount = $user->donations->count();
        $isMember = ($user->membership?->status === 'active' || $user->role === 'member');
        $isSupporter = ($verifiedDonationsCount > 0 && !$isMember);

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
