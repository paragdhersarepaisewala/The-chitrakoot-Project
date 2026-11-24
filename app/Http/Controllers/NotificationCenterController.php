<?php

namespace App\Http\Controllers;

use App\Models\AtithiVishramEnquiry;
use App\Models\ContactInquiry;
use App\Models\Donation;
use App\Models\NewsletterSubscriber;
use App\Models\PoojaBooking;
use App\Models\PostComment;
use App\Models\TempleBookingEnquiry;
use App\Models\User;
use App\Models\VolunteerApplication;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationCenterController extends Controller
{
    /**
     * Poll real-time administrative notifications across all core portal activities.
     */
    public function adminPoll(Request $request): JsonResponse
    {
        $lastSeen = session('admin_last_notification_seen_at')
            ? Carbon::parse(session('admin_last_notification_seen_at'))
            : now()->subDays(1);

        $notifications = [];

        // 1. Comments
        $comments = PostComment::with('post')->latest()->take(10)->get();
        foreach ($comments as $comment) {
            $created = $comment->created_at ?? now();
            $notifications[] = [
                'id' => 'comment_'.$comment->id,
                'type' => 'comment',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
                'title' => 'नया विचार / टिप्पणी (New Blog Comment)',
                'description' => ($comment->author_name ?: 'अतिथि').' ने लेख पर टिप्पणी की: "'.Str::limit($comment->content, 60).'"',
                'url' => route('admin.comments'),
                'created_at' => $created->toIso8601String(),
                'timestamp' => $created->timestamp,
                'time_ago' => $created->diffForHumans(),
                'is_unread' => $created->gt($lastSeen),
            ];
        }

        // 2. User Registrations
        $users = User::where('role', '!=', 'super_admin')->latest()->take(10)->get();
        foreach ($users as $user) {
            $created = $user->created_at ?? now();
            $notifications[] = [
                'id' => 'user_'.$user->id,
                'type' => 'user',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
                'title' => 'नया भक्त पंजीकरण (New Devotee Registered)',
                'description' => $user->name.' ('.$user->email.') ने नया खाता बनाया',
                'url' => route('admin.users'),
                'created_at' => $created->toIso8601String(),
                'timestamp' => $created->timestamp,
                'time_ago' => $created->diffForHumans(),
                'is_unread' => $created->gt($lastSeen),
            ];
        }

        // 3. Donations
        $donations = Donation::with('cause')->latest()->take(10)->get();
        foreach ($donations as $donation) {
            $created = $donation->created_at ?? now();
            $causeTitle = $donation->cause?->title ?? 'सामान्य सेवा निधि';
            $notifications[] = [
                'id' => 'donation_'.$donation->id,
                'type' => 'donation',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></svg>',
                'title' => 'नया सेवा समर्पण (New Donation: ₹'.number_format((float) $donation->amount, 0).')',
                'description' => ($donation->donor_name ?: 'गुप्त दानदाता').' &bull; '.$causeTitle.' &bull; स्थिति: '.strtoupper($donation->status ?? 'pending'),
                'url' => route('admin.causes'),
                'created_at' => $created->toIso8601String(),
                'timestamp' => $created->timestamp,
                'time_ago' => $created->diffForHumans(),
                'is_unread' => $created->gt($lastSeen),
            ];
        }

        // 4. Pooja Bookings
        $bookings = PoojaBooking::with('service')->latest()->take(10)->get();
        foreach ($bookings as $booking) {
            $created = $booking->created_at ?? now();
            $notifications[] = [
                'id' => 'booking_'.$booking->id,
                'type' => 'booking',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
                'title' => 'नया पूजा संकल्प अनुरोध (New Ritual Booking)',
                'description' => $booking->devotee_name.' ('.$booking->getEffectiveRitualTitle().') &bull; संदर्भ: '.$booking->booking_number,
                'url' => route('admin.poojaBookings'),
                'created_at' => $created->toIso8601String(),
                'timestamp' => $created->timestamp,
                'time_ago' => $created->diffForHumans(),
                'is_unread' => $created->gt($lastSeen),
            ];
        }

        // 5. Volunteer Applications
        $volunteers = VolunteerApplication::latest()->take(10)->get();
        foreach ($volunteers as $vol) {
            $created = $vol->created_at ?? now();
            $notifications[] = [
                'id' => 'volunteer_'.$vol->id,
                'type' => 'volunteer',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#78350F" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>',
                'title' => 'नया सेवादार आवेदन (New Volunteer Enrollment)',
                'description' => $vol->name.' ('.$vol->city.', '.$vol->phone.') &bull; स्थिति: '.ucfirst($vol->status),
                'url' => route('admin.volunteers'),
                'created_at' => $created->toIso8601String(),
                'timestamp' => $created->timestamp,
                'time_ago' => $created->diffForHumans(),
                'is_unread' => $created->gt($lastSeen),
            ];
        }

        // 6. Newsletter Subscribers
        $subscribers = NewsletterSubscriber::latest()->take(8)->get();
        foreach ($subscribers as $sub) {
            $created = $sub->created_at ?? now();
            $notifications[] = [
                'id' => 'sub_'.$sub->id,
                'type' => 'subscriber',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
                'title' => 'नया समाचार पत्रिका सदस्य (New Newsletter Subscriber)',
                'description' => $sub->email.' ने पत्रिका सदस्यता ग्रहण की',
                'url' => route('admin.subscribers'),
                'created_at' => $created->toIso8601String(),
                'timestamp' => $created->timestamp,
                'time_ago' => $created->diffForHumans(),
                'is_unread' => $created->gt($lastSeen),
            ];
        }

        // 7. Contact Inquiries
        $inquiries = ContactInquiry::latest()->take(8)->get();
        foreach ($inquiries as $inq) {
            $created = $inq->created_at ?? now();
            $notifications[] = [
                'id' => 'inquiry_'.$inq->id,
                'type' => 'inquiry',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
                'title' => 'नया संपर्क संदेश (New Contact Inquiry)',
                'description' => $inq->name.': "'.Str::limit($inq->subject ?: $inq->message, 50).'"',
                'url' => route('admin.contactInquiries'),
                'created_at' => $created->toIso8601String(),
                'timestamp' => $created->timestamp,
                'time_ago' => $created->diffForHumans(),
                'is_unread' => $created->gt($lastSeen),
            ];
        }

        // 8. Atithi Vishram Enquiries
        $atithiEnquiries = AtithiVishramEnquiry::latest()->take(8)->get();
        foreach ($atithiEnquiries as $ave) {
            $created = $ave->created_at ?? now();
            $checkInStr = $ave->check_in_date ? $ave->check_in_date->format('d M') : '';
            $notifications[] = [
                'id' => 'atithi_'.$ave->id,
                'type' => 'atithi_vishram',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2D6A4F" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2"/><path d="M9 22v-4h6v4"/></svg>',
                'title' => 'नई अतिथि विश्राम पूछताछ (New Guest Stay Enquiry)',
                'description' => $ave->devotee_name.' ('.$ave->number_of_devotees.' यात्री, आगमन: '.$checkInStr.') &bull; #'.$ave->enquiry_number,
                'url' => route('admin.atithiVishram'),
                'created_at' => $created->toIso8601String(),
                'timestamp' => $created->timestamp,
                'time_ago' => $created->diffForHumans(),
                'is_unread' => $created->gt($lastSeen),
            ];
        }

        // 9. Temple Premises Booking Enquiries
        $templeEnquiries = TempleBookingEnquiry::latest()->take(8)->get();
        foreach ($templeEnquiries as $tbe) {
            $created = $tbe->created_at ?? now();
            $propDateStr = $tbe->proposed_date ? $tbe->proposed_date->format('d M') : '';
            $notifications[] = [
                'id' => 'temple_booking_'.$tbe->id,
                'type' => 'temple_booking',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8F1719" stroke-width="2"><line x1="3" x2="21" y1="22" y2="22"/><polygon points="12 2 20 7 4 7"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="12" x2="12" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/></svg>',
                'title' => 'नया परिसर आरक्षण प्रस्ताव (New Temple Event Enquiry)',
                'description' => $tbe->devotee_name.' ('.$tbe->getEventTypeLabel().', तिथि: '.$propDateStr.') &bull; #'.$tbe->enquiry_number,
                'url' => route('admin.templeBookings'),
                'created_at' => $created->toIso8601String(),
                'timestamp' => $created->timestamp,
                'time_ago' => $created->diffForHumans(),
                'is_unread' => $created->gt($lastSeen),
            ];
        }

        // Sort descending by timestamp
        usort($notifications, fn ($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        // Limit to 25 most recent items
        $notifications = array_slice($notifications, 0, 25);

        $unreadCount = count(array_filter($notifications, fn ($n) => $n['is_unread']));

        return response()->json([
            'unread_count' => $unreadCount,
            'last_seen_at' => $lastSeen->toIso8601String(),
            'notifications' => $notifications,
        ]);
    }

    /**
     * Auto mark all admin notifications as seen.
     */
    public function adminMarkSeen(Request $request): JsonResponse
    {
        session(['admin_last_notification_seen_at' => now()->toIso8601String()]);

        return response()->json([
            'success' => true,
            'marked_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * Poll real-time updates for devotee dashboard (quotations, booking status, volunteer progress, receipts).
     */
    public function userPoll(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['unread_count' => 0, 'notifications' => []]);
        }

        $sessionKey = 'devotee_last_notification_seen_at_'.$user->id;
        $lastSeen = session($sessionKey)
            ? Carbon::parse(session($sessionKey))
            : now()->subDays(2);

        $notifications = [];

        // 1. Devotee Pooja Bookings & Active Quotations
        $bookings = PoojaBooking::with('service')
            ->where(function ($q) use ($user): void {
                $q->where('user_id', $user->id);
                if (! empty($user->email)) {
                    $q->orWhere('email', $user->email);
                }
            })
            ->latest('updated_at')
            ->take(8)
            ->get();

        $activeQuotationsCount = 0;

        foreach ($bookings as $b) {
            $updated = $b->updated_at ?? $b->created_at ?? now();

            if ($b->isQuotationActive()) {
                $activeQuotationsCount++;
                $notifications[] = [
                    'id' => 'booking_quote_'.$b->id,
                    'type' => 'quotation',
                    'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
                    'title' => 'पूजा दक्षिणा कोटेशन प्राप्त (Quotation Received)',
                    'description' => 'अनुष्ठान: '.$b->getEffectiveRitualTitle().' &bull; स्वीकृत दक्षिणा: ₹'.number_format((float) $b->quotation_amount, 0).' &bull; शीघ्र दक्षिणा अर्पित करें।',
                    'url' => $b->getQuotationPaymentUrl(),
                    'is_urgent' => true,
                    'updated_at' => $updated->toIso8601String(),
                    'timestamp' => $updated->timestamp,
                    'time_ago' => $updated->diffForHumans(),
                    'is_unread' => $updated->gt($lastSeen),
                ];
            } elseif ($b->status === 'confirmed') {
                $notifications[] = [
                    'id' => 'booking_conf_'.$b->id,
                    'type' => 'booking_confirmed',
                    'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
                    'title' => 'पूजा संकल्प पुष्ट (Booking Confirmed)',
                    'description' => 'संकल्प: '.$b->getEffectiveRitualTitle().' (संदर्भ: '.$b->booking_number.') पुष्टि हो गई है।',
                    'url' => route('pooja.confirmation', ['bookingNumber' => $b->booking_number]),
                    'is_urgent' => false,
                    'updated_at' => $updated->toIso8601String(),
                    'timestamp' => $updated->timestamp,
                    'time_ago' => $updated->diffForHumans(),
                    'is_unread' => $updated->gt($lastSeen),
                ];
            }
        }

        // 2. Devotee Volunteer Applications
        $volunteers = VolunteerApplication::where(function ($q) use ($user): void {
            $q->where('user_id', $user->id);
            if (! empty($user->email)) {
                $q->orWhere('email', $user->email);
            }
        })
            ->latest('updated_at')
            ->take(5)
            ->get();

        foreach ($volunteers as $v) {
            $updated = $v->updated_at ?? $v->created_at ?? now();
            if ($v->status === 'approved') {
                $notifications[] = [
                    'id' => 'vol_appr_'.$v->id,
                    'type' => 'volunteer_approved',
                    'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#78350F" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>',
                    'title' => 'सेवादार आवेदन स्वीकृत (Volunteer Approved)',
                    'description' => 'श्री कामदगिरि पीठम् सेवा दल में आपका स्वागत है! आपका आवेदन स्वीकृत किया गया है।',
                    'url' => url('/dashboard#tab-volunteers'),
                    'is_urgent' => false,
                    'updated_at' => $updated->toIso8601String(),
                    'timestamp' => $updated->timestamp,
                    'time_ago' => $updated->diffForHumans(),
                    'is_unread' => $updated->gt($lastSeen),
                ];
            } elseif ($v->status === 'rejected') {
                $notifications[] = [
                    'id' => 'vol_rej_'.$v->id,
                    'type' => 'volunteer_rejected',
                    'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
                    'title' => 'सेवादार आवेदन समीक्षा पूर्ण',
                    'description' => 'आपके सेवादार आवेदन की समीक्षा पूर्ण हो चुकी है। विवरण देखें।',
                    'url' => url('/dashboard#tab-volunteers'),
                    'is_urgent' => false,
                    'updated_at' => $updated->toIso8601String(),
                    'timestamp' => $updated->timestamp,
                    'time_ago' => $updated->diffForHumans(),
                    'is_unread' => $updated->gt($lastSeen),
                ];
            }
        }

        // 3. Devotee Donations & Receipts
        $donations = Donation::where(function ($q) use ($user): void {
            $q->where('user_id', $user->id);
            if (! empty($user->email)) {
                $q->orWhere('donor_email', $user->email);
            }
        })
            ->whereNotNull('receipt_number')
            ->latest('updated_at')
            ->take(5)
            ->get();

        foreach ($donations as $d) {
            $updated = $d->updated_at ?? $d->created_at ?? now();
            $notifications[] = [
                'id' => 'don_rcpt_'.$d->id,
                'type' => 'donation_receipt',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
                'title' => 'डिजिटल दान रसीद उपलब्ध',
                'description' => 'समर्पण राशि: ₹'.number_format((float) $d->amount, 0).' &bull; रसीद: '.$d->receipt_number,
                'url' => url('/receipt/'.$d->receipt_number),
                'is_urgent' => false,
                'updated_at' => $updated->toIso8601String(),
                'timestamp' => $updated->timestamp,
                'time_ago' => $updated->diffForHumans(),
                'is_unread' => $updated->gt($lastSeen),
            ];
        }

        // 4. Devotee Atithi Vishram Enquiries
        $userAtithi = AtithiVishramEnquiry::where(function ($q) use ($user): void {
            $q->where('user_id', $user->id);
            if (! empty($user->email)) {
                $q->orWhere('email', $user->email);
            }
        })
            ->latest('updated_at')
            ->take(5)
            ->get();

        foreach ($userAtithi as $ua) {
            $updated = $ua->updated_at ?? $ua->created_at ?? now();
            $statusBadge = $ua->getStatusBadge();
            $notifications[] = [
                'id' => 'atithi_usr_'.$ua->id,
                'type' => 'atithi_status',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2D6A4F" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2"/><path d="M9 22v-4h6v4"/></svg>',
                'title' => 'अतिथि विश्राम स्थिति: '.$statusBadge['label'],
                'description' => 'संदर्भ: #'.$ua->enquiry_number.' &bull; आगमन: '.optional($ua->check_in_date)->format('d M Y'),
                'url' => url('/dashboard#tab-atithi'),
                'is_urgent' => false,
                'updated_at' => $updated->toIso8601String(),
                'timestamp' => $updated->timestamp,
                'time_ago' => $updated->diffForHumans(),
                'is_unread' => $updated->gt($lastSeen),
            ];
        }

        // 5. Devotee Temple Booking Enquiries
        $userTemple = TempleBookingEnquiry::where(function ($q) use ($user): void {
            $q->where('user_id', $user->id);
            if (! empty($user->email)) {
                $q->orWhere('email', $user->email);
            }
        })
            ->latest('updated_at')
            ->take(5)
            ->get();

        foreach ($userTemple as $ut) {
            $updated = $ut->updated_at ?? $ut->created_at ?? now();
            $statusBadge = $ut->getStatusBadge();
            $notifications[] = [
                'id' => 'temple_usr_'.$ut->id,
                'type' => 'temple_status',
                'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8F1719" stroke-width="2"><line x1="3" x2="21" y1="22" y2="22"/><polygon points="12 2 20 7 4 7"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="12" x2="12" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/></svg>',
                'title' => 'परिसर आयोजन स्थिति: '.$statusBadge['label'],
                'description' => $ut->getEventTypeLabel().' &bull; संदर्भ: #'.$ut->enquiry_number,
                'url' => url('/dashboard#tab-temple-bookings'),
                'is_urgent' => false,
                'updated_at' => $updated->toIso8601String(),
                'timestamp' => $updated->timestamp,
                'time_ago' => $updated->diffForHumans(),
                'is_unread' => $updated->gt($lastSeen),
            ];
        }

        usort($notifications, fn ($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        $unreadCount = count(array_filter($notifications, fn ($n) => $n['is_unread']));

        return response()->json([
            'unread_count' => $unreadCount,
            'active_quotations_count' => $activeQuotationsCount,
            'last_seen_at' => $lastSeen->toIso8601String(),
            'notifications' => array_slice($notifications, 0, 15),
        ]);
    }

    /**
     * Auto mark devotee notifications as seen.
     */
    public function userMarkSeen(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            session(['devotee_last_notification_seen_at_'.$user->id => now()->toIso8601String()]);
        }

        return response()->json([
            'success' => true,
            'marked_at' => now()->toIso8601String(),
        ]);
    }
}
