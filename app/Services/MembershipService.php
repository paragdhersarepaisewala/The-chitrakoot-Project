<?php

namespace App\Models; // Or App\Services

namespace App\Services;

use App\Models\Donation;
use App\Models\Membership;
use App\Models\MembershipStatusHistory;
use App\Models\User;
use Carbon\Carbon;

class MembershipService
{
    /**
     * Process a verified sustaining monthly donation for a user
     * and update their 3-consecutive-months streak and active status.
     */
    public function recordSustainingDonation(User $user, Donation $donation): Membership
    {
        $membership = Membership::firstOrCreate(
            ['user_id' => $user->id],
            [
                'membership_id' => 'SKP-MEM-'.strtoupper(str()->random(6)),
                'status' => 'inactive',
                'consecutive_qualifying_months' => 0,
            ]
        );

        // Check if donation is qualifying (>= 1000 INR monthly sustaining)
        if ($donation->donation_type === 'monthly_sustaining' && $donation->amount >= 1000) {
            $currentMonth = Carbon::now()->format('Y-m');

            if ($membership->last_qualifying_month !== $currentMonth) {
                $lastMonth = Carbon::now()->subMonth()->format('Y-m');

                if ($membership->last_qualifying_month === $lastMonth) {
                    $membership->consecutive_qualifying_months += 1;
                } else {
                    $membership->consecutive_qualifying_months = 1;
                }

                $membership->last_qualifying_month = $currentMonth;

                // 3 consecutive qualifying months grants Active Member status
                if ($membership->consecutive_qualifying_months >= 3 && $membership->status !== 'active') {
                    $oldStatus = $membership->status;
                    $membership->status = 'active';
                    $membership->became_member_at = now();

                    MembershipStatusHistory::create([
                        'user_id' => $user->id,
                        'previous_status' => $oldStatus,
                        'new_status' => 'active',
                        'reason' => 'Achieved 3 consecutive months of qualifying sustaining contributions (₹1,000+/mo).',
                    ]);

                    $user->update(['role' => 'member']);
                }

                $membership->save();
            }
        }

        return $membership;
    }
}
