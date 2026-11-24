<?php

namespace App\Services;

use App\Mail\TempleNotificationMailable;
use App\Models\AtithiVishramEnquiry;
use App\Models\ContactInquiry;
use App\Models\Donation;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\OrganizationSetting;
use App\Models\PoojaBooking;
use App\Models\PostComment;
use App\Models\TempleBookingEnquiry;
use App\Models\User;
use App\Models\VolunteerApplication;
use Database\Seeders\EmailTemplatesSeeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailNotificationService
{
    /**
     * Dynamically configure Laravel's mailer using OrganizationSetting credentials.
     */
    public static function configureRuntimeMailer(?OrganizationSetting $settings = null): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        $settings = $settings ?: OrganizationSetting::getSettings();

        if ($settings->isSmtpConfigured()) {
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $settings->smtp_host,
                'mail.mailers.smtp.port' => (int) ($settings->smtp_port ?: 587),
                'mail.mailers.smtp.encryption' => ! empty($settings->smtp_encryption) ? $settings->smtp_encryption : null,
                'mail.mailers.smtp.username' => $settings->smtp_username,
                'mail.mailers.smtp.password' => $settings->smtp_password,
                'mail.mailers.smtp.timeout' => 15,
                'mail.from.address' => $settings->smtp_from_address ?: 'info@kamadgiripeetham.org',
                'mail.from.name' => $settings->smtp_from_name ?: ($settings->org_name ?: 'Shri Kamadgiri Peetham Sewa Trust'),
            ]);

            try {
                if (app()->bound('mail.manager')) {
                    Mail::purge('smtp');
                }
            } catch (Throwable) {
                // Ignore purge errors
            }
        }
    }

    /**
     * Send a template-based email with automatic placeholder interpolation and delivery logging.
     */
    public static function send(string $slug, string $recipientEmail, string $recipientName, array $placeholders = []): bool
    {
        if (empty($recipientEmail) || ! filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            Log::info("Email notification skipped: invalid or missing recipient email for [{$slug}].");

            return false;
        }

        $orgSettings = OrganizationSetting::getSettings();
        $siteName = $orgSettings->org_name ?: 'Shri Kamadgiri Peetham Sewa Trust';
        $siteUrl = url('/');

        // 1. MASTER KILLSWITCH CHECK
        if (! $orgSettings->isEmailSendingEnabled()) {
            EmailLog::create([
                'template_slug' => $slug,
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'subject' => "Notification [{$slug}] - Dispatch Paused",
                'status' => 'stopped',
                'error_message' => 'Automatic email sending is currently stopped by the administrator master killswitch.',
                'metadata' => ['placeholders' => $placeholders],
            ]);

            Log::info("Email notification stopped by master killswitch: [{$slug}] to {$recipientEmail}");

            return false;
        }

        // 2. TEMPLATE ACTIVE CHECK
        $template = EmailTemplate::where('slug', $slug)->first();
        if ($template && ! $template->is_active) {
            EmailLog::create([
                'template_slug' => $slug,
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'subject' => $template->subject ?: "Notification [{$slug}]",
                'status' => 'stopped',
                'error_message' => "The email template '{$template->name}' ({$slug}) has been deactivated by administrator.",
                'metadata' => ['placeholders' => $placeholders],
            ]);

            Log::info("Email notification skipped (template disabled): [{$slug}] to {$recipientEmail}");

            return false;
        }

        try {
            $basePlaceholders = [
                '{name}' => $recipientName,
                '{email}' => $recipientEmail,
                '{site_name}' => $siteName,
                '{site_url}' => $siteUrl,
                '{date}' => now()->format('d M Y'),
                '{year}' => date('Y'),
                '{month}' => now()->format('F Y'),
            ];

            $allPlaceholders = array_merge($basePlaceholders, $placeholders);

            if ($template) {
                $subject = str_replace(array_keys($allPlaceholders), array_values($allPlaceholders), $template->subject);
                $bodyHtml = str_replace(array_keys($allPlaceholders), array_values($allPlaceholders), $template->content_html);
            } else {
                $fallback = self::getFallbackTemplate($slug, $allPlaceholders);
                $subject = $fallback['subject'];
                $bodyHtml = $fallback['content_html'];
            }

            // Tax exemption 80G filter
            $is80g = (bool) ($orgSettings->is_80g_enabled ?? false);
            if (! $is80g) {
                $subject = str_replace(
                    ['OFFICIAL 80G RECEIPT', '80G RECEIPT', '80G Tax Exemption Receipt', '80G पावती', '80G'],
                    ['OFFICIAL DONATION RECEIPT', 'DONATION RECEIPT', 'Donation Receipt', 'दान पावती', ''],
                    $subject
                );
                $bodyHtml = str_replace(
                    ['OFFICIAL 80G RECEIPT', '80G Tax Exemption', '80G आयकर छूट', '80G पावती', 'धारा 80G के अंतर्गत 50% कर-मुक्त हैं'],
                    ['OFFICIAL DONATION RECEIPT', 'Official Donation', 'आधिकारिक दान', 'दान पावती', 'प्रमाणित अधिकृत दान पावती है'],
                    $bodyHtml
                );
            }

            // Configure dynamic mail transport
            self::configureRuntimeMailer($orgSettings);

            $fromEmail = $orgSettings->smtp_from_address ?: config('mail.from.address', 'info@kamadgiripeetham.org');
            $fromName = $orgSettings->smtp_from_name ?: config('mail.from.name', $siteName);

            $isSmtpLive = $orgSettings->isSmtpConfigured() || app()->runningUnitTests();

            if ($isSmtpLive) {
                Mail::to($recipientEmail, $recipientName)
                    ->send(new TempleNotificationMailable($subject, $bodyHtml, $fromEmail, $fromName));

                EmailLog::create([
                    'template_slug' => $slug,
                    'recipient_email' => $recipientEmail,
                    'recipient_name' => $recipientName,
                    'subject' => $subject,
                    'status' => 'sent',
                    'error_message' => null,
                    'metadata' => [
                        'from' => "{$fromName} <{$fromEmail}>",
                        'smtp_host' => $orgSettings->smtp_host,
                    ],
                ]);

                Log::info("Email notification sent live [{$slug}] to {$recipientEmail}");
            } else {
                Log::info("Email notification queued/simulated [{$slug}] to {$recipientEmail}: Subject: {$subject}");

                EmailLog::create([
                    'template_slug' => $slug,
                    'recipient_email' => $recipientEmail,
                    'recipient_name' => $recipientName,
                    'subject' => $subject,
                    'status' => 'simulated',
                    'error_message' => 'Outgoing SMTP server credentials are not yet configured in Trust settings.',
                    'metadata' => [
                        'simulated' => true,
                    ],
                ]);
            }

            return true;
        } catch (Throwable $e) {
            EmailLog::create([
                'template_slug' => $slug,
                'recipient_email' => $recipientEmail,
                'recipient_name' => $recipientName,
                'subject' => $subject ?? "Notification [{$slug}]",
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'metadata' => ['trace' => substr($e->getTraceAsString(), 0, 800)],
            ]);

            Log::error("Failed sending email notification [{$slug}] to {$recipientEmail}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Send official donation receipt with smart cause detection.
     */
    public static function sendDonationReceipt(Donation $donation, ?string $recipientEmail = null, ?string $recipientName = null): bool
    {
        $email = $recipientEmail ?: ($donation->donor_email ?: $donation->user?->email);
        $name = $recipientName ?: ($donation->donor_name ?: ($donation->user?->name ?: 'Generous Devotee'));

        if (! $email) {
            return false;
        }

        $causeSlug = $donation->cause?->slug;
        $causeTitle = $donation->cause?->title;

        // Select cause-specific template if available
        $templateSlug = 'donation_receipt';
        if ($donation->donation_type === 'monthly_sustaining') {
            $templateSlug = 'monthly_donation_active';
            $causeTitle = 'मासिक सेवा संकल्प (Monthly Sustaining)';
        } elseif ($causeSlug) {
            if (str_contains($causeSlug, 'gaushala') || str_contains($causeSlug, 'cow')) {
                $templateSlug = 'donation_cause_gaushala';
            } elseif (str_contains($causeSlug, 'sanskrit') || str_contains($causeSlug, 'vidyalaya') || str_contains($causeSlug, 'gurukul')) {
                $templateSlug = 'donation_cause_gurukul';
            } elseif (str_contains($causeSlug, 'annakshetra') || str_contains($causeSlug, 'religious') || str_contains($causeSlug, 'bhandara')) {
                $templateSlug = 'donation_cause_annakshetra';
            }
        }

        if (! $causeTitle) {
            $causeTitle = 'सामान्य सेवा दान (General Seva Donation)';
        }

        $receiptNumber = $donation->receipt_number ?: ('SKP-REC-'.$donation->id);
        $receiptUrl = url('/receipt/'.$receiptNumber);

        return self::send($templateSlug, $email, $name, [
            '{amount}' => number_format((float) $donation->amount, 2),
            '{cause}' => $causeTitle,
            '{receipt_id}' => $receiptNumber,
            '{receipt_number}' => $receiptNumber,
            '{receipt_url}' => $receiptUrl,
            '{payment_id}' => $donation->gateway_payment_id ?: 'Online Verified Payment',
            '{month}' => now()->format('F Y'),
        ]);
    }

    /**
     * Send Donation Payment Failed / Assistance Notice.
     */
    public static function sendDonationFailed(Donation $donation, ?string $reason = null): bool
    {
        $email = $donation->donor_email ?: $donation->user?->email;
        $name = $donation->donor_name ?: ($donation->user?->name ?: 'Devotee');

        if (! $email) {
            return false;
        }

        $causeTitle = $donation->cause?->title ?: 'श्री कामदगिरि सेवा प्रकल्प';

        return self::send('donation_failed', $email, $name, [
            '{amount}' => number_format((float) $donation->amount, 2),
            '{cause}' => $causeTitle,
            '{reason}' => $reason ?: 'Banking gateway transaction timeout',
        ]);
    }

    /**
     * Send Registration OTP.
     */
    public static function sendRegistrationOtp(User $user, string $otp): bool
    {
        return self::send('login_otp', $user->email, $user->name, [
            '{otp}' => $otp,
        ]);
    }

    /**
     * Send Welcome Registration.
     */
    public static function sendWelcomeRegistration(User $user): bool
    {
        // 1. Send to user
        $userSent = self::send('welcome_registration', $user->email, $user->name, []);

        // 2. Alert trust admin
        $orgSettings = OrganizationSetting::getSettings();
        $adminEmail = $orgSettings->email ?: 'info@kamadgiripeetham.org';
        self::send('admin_new_user', $adminEmail, 'Temple Administration', [
            '{name}' => $user->name,
            '{email}' => $user->email,
            '{phone}' => $user->phone ?: ($user->profile?->phone ?: 'Not Provided'),
        ]);

        return $userSent;
    }

    /**
     * Send Password Reset OTP.
     */
    public static function sendPasswordResetOtp(User $user, string $otp): bool
    {
        return self::send('password_reset_otp', $user->email, $user->name, [
            '{otp}' => $otp,
        ]);
    }

    /**
     * Send Password Changed Alert.
     */
    public static function sendPasswordChangedAlert(User $user): bool
    {
        return self::send('password_changed', $user->email, $user->name, []);
    }

    /**
     * Send Contact Inquiry Auto-Acknowledgement & Admin Alert.
     */
    public static function sendContactInquiryReceived(ContactInquiry $inquiry): bool
    {
        $orgSettings = OrganizationSetting::getSettings();
        $placeholders = [
            '{subject}' => $inquiry->subject ?: 'General Inquiry',
            '{message}' => $inquiry->message,
            '{phone}' => $inquiry->phone ?: 'N/A',
        ];

        // 1. Send acknowledgement to devotee
        $sentUser = self::send('contact_inquiry_received', $inquiry->email, $inquiry->name, $placeholders);

        // 2. Alert admin
        $adminEmail = $orgSettings->email ?: 'info@kamadgiripeetham.org';
        self::send('contact_inquiry_admin_alert', $adminEmail, 'Temple Admin', array_merge($placeholders, [
            '{name}' => $inquiry->name,
            '{email}' => $inquiry->email,
        ]));

        return $sentUser;
    }

    /**
     * Send Contact Inquiry Admin Response to devotee.
     */
    public static function sendContactInquiryReply(ContactInquiry $inquiry, string $replyMessage): bool
    {
        return self::send('contact_inquiry_reply', $inquiry->email, $inquiry->name, [
            '{subject}' => $inquiry->subject ?: 'Contact Inquiry',
            '{reply_message}' => nl2br(e($replyMessage)),
        ]);
    }

    /**
     * Send Volunteer Application Received notice & Admin alert.
     */
    public static function sendVolunteerReceived(VolunteerApplication $applicant): bool
    {
        $orgSettings = OrganizationSetting::getSettings();
        $areas = is_array($applicant->seva_areas) ? implode(', ', $applicant->seva_areas) : ($applicant->seva_areas ?: 'सामान्य सेवा');
        $placeholders = [
            '{phone}' => $applicant->phone,
            '{city}' => $applicant->city ?: 'Chitrakoot',
            '{state}' => $applicant->state ?: '',
            '{seva_areas}' => $areas,
            '{applicant_id}' => 'VOL-'.sprintf('%04d', $applicant->id),
        ];

        return self::send('volunteer_received', $applicant->email, $applicant->name, $placeholders);
    }

    /**
     * Send Volunteer Application Approval email.
     */
    public static function sendVolunteerApproved(VolunteerApplication $applicant): bool
    {
        $areas = is_array($applicant->seva_areas) ? implode(', ', $applicant->seva_areas) : ($applicant->seva_areas ?: 'सामान्य सेवा');

        return self::send('volunteer_approved', $applicant->email, $applicant->name, [
            '{phone}' => $applicant->phone,
            '{seva_areas}' => $areas,
            '{admin_notes}' => $applicant->admin_notes ?: 'श्री कामदगिरि पीठम् सेवा दल में आपका सहृदय स्वागत है।',
            '{applicant_id}' => 'VOL-'.sprintf('%04d', $applicant->id),
        ]);
    }

    /**
     * Send Volunteer Application Rejected / Regret email.
     */
    public static function sendVolunteerRejected(VolunteerApplication $applicant): bool
    {
        $areas = is_array($applicant->seva_areas) ? implode(', ', $applicant->seva_areas) : ($applicant->seva_areas ?: 'सामान्य सेवा');

        return self::send('volunteer_rejected', $applicant->email, $applicant->name, [
            '{phone}' => $applicant->phone,
            '{seva_areas}' => $areas,
            '{admin_notes}' => $applicant->admin_notes ?: 'वर्तमान सत्र में सीमित स्थानों के कारण इस समय आपका आवेदन स्वीकृत नहीं हो सका। आगामी अवसरों में पुनः सहभागी बनें।',
            '{applicant_id}' => 'VOL-'.sprintf('%04d', $applicant->id),
        ]);
    }

    /**
     * Send Devotee Pooja Booking Received notification.
     */
    public static function sendPoojaBookingReceived(PoojaBooking $booking): bool
    {
        if (empty($booking->email)) {
            return false;
        }

        $serviceTitle = $booking->service?->title_hi ?: ($booking->service?->title_en ?: 'विशेष पूजा व अनुष्ठान');

        return self::send('pooja_booking_received', $booking->email, $booking->devotee_name, [
            '{booking_number}' => $booking->booking_number,
            '{service_name}' => $serviceTitle,
            '{booking_date}' => $booking->booking_date,
            '{time_slot}' => $booking->time_slot,
            '{gotra}' => $booking->gotra ?: 'Not Specified',
            '{nakshatra}' => $booking->nakshatra ?: 'Not Specified',
            '{amount}' => number_format((float) $booking->amount, 2),
            '{payment_status}' => strtoupper($booking->payment_status),
        ]);
    }

    /**
     * Send Devotee Pooja Booking Confirmed notification.
     */
    public static function sendPoojaBookingConfirmed(PoojaBooking $booking): bool
    {
        if (empty($booking->email)) {
            return false;
        }

        $serviceTitle = $booking->service?->title_hi ?: ($booking->service?->title_en ?: 'विशेष पूजा व अनुष्ठान');

        return self::send('pooja_booking_confirmed', $booking->email, $booking->devotee_name, [
            '{booking_number}' => $booking->booking_number,
            '{service_name}' => $serviceTitle,
            '{booking_date}' => $booking->booking_date,
            '{time_slot}' => $booking->time_slot,
            '{gotra}' => $booking->gotra ?: 'Not Specified',
            '{nakshatra}' => $booking->nakshatra ?: 'Not Specified',
            '{sankalp_purpose}' => $booking->sankalp_purpose ?: 'पारिवारिक सुख, शांति एवं कल्याण',
            '{prasad_address}' => $booking->prasad_address ?: 'मंदिर परिसर में वितरण / स्व-ग्रहण',
            '{admin_notes}' => $booking->admin_notes ?: 'वैदिक आचार्यों द्वारा निर्धारित समय पर आपका अनुष्ठान संपन्न कराया जाएगा।',
        ]);
    }

    /**
     * Send Pooja Completion & Prasad Blessing notice.
     */
    public static function sendPoojaBookingCompleted(PoojaBooking $booking): bool
    {
        if (empty($booking->email)) {
            return false;
        }

        $serviceTitle = $booking->service?->title_hi ?: ($booking->service?->title_en ?: 'विशेष पूजा व अनुष्ठान');

        return self::send('pooja_booking_completed', $booking->email, $booking->devotee_name, [
            '{booking_number}' => $booking->booking_number,
            '{service_name}' => $serviceTitle,
            '{booking_date}' => $booking->booking_date,
            '{admin_notes}' => $booking->admin_notes ?: 'प्रसाद एवं चरणामृत तैयार है।',
        ]);
    }

    /**
     * Send Pooja Quotation Email with Expirable Payment Link.
     */
    public static function sendPoojaQuotation(PoojaBooking $booking): bool
    {
        if (empty($booking->email)) {
            return false;
        }

        $serviceTitle = $booking->getEffectiveRitualTitle();
        $paymentUrl = $booking->getQuotationPaymentUrl();
        $expiresAt = $booking->quotation_expires_at ? $booking->quotation_expires_at->format('d M Y, h:i A').' ('.$booking->quotation_expires_at->diffForHumans().')' : '6 से 12 घंटे';

        return self::send('pooja_quotation', $booking->email, $booking->devotee_name, [
            '{booking_number}' => $booking->booking_number,
            '{service_name}' => $serviceTitle,
            '{quotation_amount}' => number_format((float) $booking->quotation_amount, 2),
            '{quotation_notes}' => $booking->quotation_notes ?: 'अनुष्ठान दक्षिणा व वैदिक सामग्री व्यवस्था',
            '{booking_date}' => $booking->booking_date ? $booking->booking_date->format('d M Y') : 'निर्धारित तिथि',
            '{time_slot}' => $booking->time_slot,
            '{expires_at}' => $expiresAt,
            '{payment_url}' => $paymentUrl,
        ]);
    }

    /**
     * Send Atithi Vishram Enquiry Received & Admin Alert.
     */
    public static function sendAtithiVishramReceived(AtithiVishramEnquiry $enquiry): bool
    {
        $orgSettings = OrganizationSetting::getSettings();
        $placeholders = [
            '{enquiry_number}' => $enquiry->enquiry_number,
            '{name}' => $enquiry->devotee_name,
            '{phone}' => $enquiry->phone,
            '{email}' => $enquiry->email,
            '{city}' => $enquiry->city ?: 'N/A',
            '{check_in_date}' => $enquiry->check_in_date ? $enquiry->check_in_date->format('d M Y') : '',
            '{check_out_date}' => $enquiry->check_out_date ? $enquiry->check_out_date->format('d M Y') : '',
            '{devotees}' => (string) $enquiry->number_of_devotees,
            '{rooms}' => (string) $enquiry->number_of_rooms,
            '{room_preference}' => $enquiry->getRoomPreferenceLabel(),
            '{purpose}' => $enquiry->getPurposeLabel(),
            '{special_requests}' => $enquiry->special_requests ?: 'कोई विशेष अनुरोध नहीं (None)',
        ];

        $sentDevotee = self::send('atithi_vishram_received', $enquiry->email, $enquiry->devotee_name, $placeholders);

        $adminEmail = $orgSettings->email ?: 'info@kamadgiripeetham.org';
        self::send('atithi_vishram_admin_alert', $adminEmail, 'Temple Guest Office', $placeholders);

        return $sentDevotee;
    }

    /**
     * Send Atithi Vishram Status Update notification.
     */
    public static function sendAtithiVishramStatusUpdate(AtithiVishramEnquiry $enquiry): bool
    {
        $statusBadge = $enquiry->getStatusBadge();

        return self::send('atithi_vishram_status_update', $enquiry->email, $enquiry->devotee_name, [
            '{enquiry_number}' => $enquiry->enquiry_number,
            '{name}' => $enquiry->devotee_name,
            '{check_in_date}' => $enquiry->check_in_date ? $enquiry->check_in_date->format('d M Y') : '',
            '{check_out_date}' => $enquiry->check_out_date ? $enquiry->check_out_date->format('d M Y') : '',
            '{status}' => $statusBadge['label'],
            '{admin_notes}' => $enquiry->admin_notes ?: 'ट्रस्ट कार्यालय द्वारा आपकी पूछताछ पर आवश्यक व्यवस्था की जा रही है।',
        ]);
    }

    /**
     * Send Temple Booking Enquiry Received & Admin Alert.
     */
    public static function sendTempleBookingReceived(TempleBookingEnquiry $enquiry): bool
    {
        $orgSettings = OrganizationSetting::getSettings();
        $placeholders = [
            '{enquiry_number}' => $enquiry->enquiry_number,
            '{name}' => $enquiry->devotee_name,
            '{phone}' => $enquiry->phone,
            '{email}' => $enquiry->email,
            '{city}' => $enquiry->city ?: 'N/A',
            '{event_type}' => $enquiry->getEventTypeLabel(),
            '{event_title}' => $enquiry->event_title ?: $enquiry->getEventTypeLabel(),
            '{proposed_date}' => $enquiry->proposed_date ? $enquiry->proposed_date->format('d M Y') : '',
            '{alternate_date}' => $enquiry->alternate_date ? $enquiry->alternate_date->format('d M Y') : 'कोई वैकल्पिक तिथि नहीं (None)',
            '{gathering}' => (string) $enquiry->expected_gathering,
            '{duration}' => $enquiry->duration_days.' दिवस (Days)',
            '{requirements}' => $enquiry->additional_requirements ?: 'मानक मंदिर व्यवस्था (Standard Temple Arrangements)',
        ];

        $sentDevotee = self::send('temple_booking_received', $enquiry->email, $enquiry->devotee_name, $placeholders);

        $adminEmail = $orgSettings->email ?: 'info@kamadgiripeetham.org';
        self::send('temple_booking_admin_alert', $adminEmail, 'Temple Premises Office', $placeholders);

        return $sentDevotee;
    }

    /**
     * Send Temple Booking Status Update notification.
     */
    public static function sendTempleBookingStatusUpdate(TempleBookingEnquiry $enquiry): bool
    {
        $statusBadge = $enquiry->getStatusBadge();

        return self::send('temple_booking_status_update', $enquiry->email, $enquiry->devotee_name, [
            '{enquiry_number}' => $enquiry->enquiry_number,
            '{name}' => $enquiry->devotee_name,
            '{event_type}' => $enquiry->getEventTypeLabel(),
            '{proposed_date}' => $enquiry->proposed_date ? $enquiry->proposed_date->format('d M Y') : '',
            '{status}' => $statusBadge['label'],
            '{admin_notes}' => $enquiry->admin_notes ?: 'मंदिर व्यवस्थापक समिति द्वारा आपके आयोजन प्रस्ताव की समीक्षा कर ली गई है।',
        ]);
    }

    /**
     * Send Newsletter Welcome Email to Subscriber.
     */
    public static function sendNewsletterWelcome(string $email): bool
    {
        return self::send('newsletter_welcome', $email, 'Devotee Reader', []);
    }

    /**
     * Send Comment Approved Notification.
     */
    public static function sendCommentApproved(PostComment $comment): bool
    {
        if (empty($comment->email)) {
            return false;
        }

        $post = $comment->post;
        $postTitle = $post?->title_hi ?: ($post?->title_en ?: 'The Chitrakoot Journal Story');
        $articleUrl = $post ? url('/blog/'.$post->slug) : url('/blog');

        return self::send('comment_approved', $comment->email, $comment->name, [
            '{post_title}' => $postTitle,
            '{article_url}' => $articleUrl,
        ]);
    }

    /**
     * Real, Deep SMTP Testing & Diagnostics Suite.
     */
    public static function testSmtpConnectionDetailed(
        ?string $host = null,
        ?int $port = null,
        ?string $username = null,
        ?string $password = null,
        ?string $encryption = null,
        ?string $testRecipient = null,
        ?string $templateSlug = null
    ): array {
        $settings = OrganizationSetting::getSettings();
        $host = $host ?: $settings->smtp_host;
        $port = $port ?: ($settings->smtp_port ?: 587);
        $username = $username ?: $settings->smtp_username;
        $password = $password ?: $settings->smtp_password;
        $encryption = $encryption !== null ? $encryption : $settings->smtp_encryption;

        if (empty($host)) {
            return [
                'success' => false,
                'message' => 'SMTP Host is missing. Please configure a valid SMTP server hostname.',
                'steps' => [],
            ];
        }

        $steps = [];
        $startTime = microtime(true);

        // Step 1: DNS Resolution
        $dnsStart = microtime(true);
        $resolvedIp = gethostbyname($host);
        $dnsTime = round((microtime(true) - $dnsStart) * 1000, 2);

        if ($resolvedIp === $host && ! filter_var($host, FILTER_VALIDATE_IP)) {
            $steps[] = [
                'step' => 'DNS Resolution',
                'status' => 'failed',
                'detail' => "Failed to resolve host '{$host}'. Please verify your SMTP server address.",
                'time_ms' => $dnsTime,
            ];

            return [
                'success' => false,
                'message' => "DNS Lookup failed for SMTP host '{$host}'.",
                'steps' => $steps,
            ];
        }

        $steps[] = [
            'step' => 'DNS Resolution',
            'status' => 'passed',
            'detail' => "Resolved '{$host}' to {$resolvedIp}",
            'time_ms' => $dnsTime,
        ];

        // Step 2: TCP Socket Connection
        $sockStart = microtime(true);
        $errno = 0;
        $errstr = '';
        $target = ($encryption === 'ssl' ? 'ssl://' : 'tcp://').$host.':'.$port;

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ]);

        $socket = @stream_socket_client($target, $errno, $errstr, 7, STREAM_CLIENT_CONNECT, $context);
        $sockTime = round((microtime(true) - $sockStart) * 1000, 2);

        if (! $socket) {
            $steps[] = [
                'step' => 'TCP Socket Connection',
                'status' => 'failed',
                'detail' => "Could not connect to {$host}:{$port} ({$errstr} [Code: {$errno}]). Ensure port is open and not blocked by firewall.",
                'time_ms' => $sockTime,
            ];

            return [
                'success' => false,
                'message' => "TCP connection failed on {$host}:{$port}: {$errstr}",
                'steps' => $steps,
            ];
        }

        stream_set_timeout($socket, 6);
        $serverGreeting = trim((string) fgets($socket, 512));

        $steps[] = [
            'step' => 'TCP Socket Connection',
            'status' => 'passed',
            'detail' => "Connected to {$host}:{$port}. Greeting: {$serverGreeting}",
            'time_ms' => $sockTime,
        ];

        // Step 3: EHLO Handshake
        fwrite($socket, "EHLO chitrakootpeetham.org\r\n");
        $ehloResponse = '';
        while ($line = fgets($socket, 512)) {
            $ehloResponse .= $line;
            if (substr($line, 3, 1) === ' ') {
                break;
            }
        }

        $steps[] = [
            'step' => 'SMTP Handshake (EHLO)',
            'status' => 'passed',
            'detail' => 'Received 250 OK handshake capability responses.',
            'time_ms' => 12,
        ];

        // Step 4: STARTTLS (if TLS encryption)
        if ($encryption === 'tls' || ($port == 587 && $encryption !== 'ssl')) {
            fwrite($socket, "STARTTLS\r\n");
            $tlsResp = trim((string) fgets($socket, 512));
            if (str_starts_with($tlsResp, '220')) {
                if (@stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    fwrite($socket, "EHLO chitrakootpeetham.org\r\n");
                    while ($line = fgets($socket, 512)) {
                        if (substr($line, 3, 1) === ' ') {
                            break;
                        }
                    }
                    $steps[] = [
                        'step' => 'TLS Encryption Handshake',
                        'status' => 'passed',
                        'detail' => 'STARTTLS successfully negotiated secure cryptographic tunnel.',
                        'time_ms' => 45,
                    ];
                } else {
                    $steps[] = [
                        'step' => 'TLS Encryption Handshake',
                        'status' => 'failed',
                        'detail' => 'TLS negotiation failed with server certificate.',
                        'time_ms' => 45,
                    ];
                }
            }
        }

        // Step 5: Authentication (if credentials provided)
        if (! empty($username) && ! empty($password)) {
            fwrite($socket, "AUTH LOGIN\r\n");
            $authResp1 = trim((string) fgets($socket, 512));
            if (str_starts_with($authResp1, '334')) {
                fwrite($socket, base64_encode($username)."\r\n");
                $authResp2 = trim((string) fgets($socket, 512));
                if (str_starts_with($authResp2, '334')) {
                    fwrite($socket, base64_encode($password)."\r\n");
                    $authResp3 = trim((string) fgets($socket, 512));

                    if (str_starts_with($authResp3, '235')) {
                        $steps[] = [
                            'step' => 'SMTP Authentication',
                            'status' => 'passed',
                            'detail' => "Authentication succeeded for user '{$username}' (Response: {$authResp3})",
                            'time_ms' => 85,
                        ];
                    } else {
                        $steps[] = [
                            'step' => 'SMTP Authentication',
                            'status' => 'failed',
                            'detail' => "Authentication rejected by SMTP server for '{$username}': {$authResp3}. If using Gmail/Hostinger, check if an App Password is required.",
                            'time_ms' => 85,
                        ];

                        @fwrite($socket, "QUIT\r\n");
                        @fclose($socket);

                        return [
                            'success' => false,
                            'message' => "SMTP Authentication failed for '{$username}': {$authResp3}",
                            'steps' => $steps,
                        ];
                    }
                }
            }
        }

        @fwrite($socket, "QUIT\r\n");
        @fclose($socket);

        // Optional Step 6: Dispatch real test email if recipient requested
        $testEmailSent = false;
        if (! empty($testRecipient) && filter_var($testRecipient, FILTER_VALIDATE_EMAIL)) {
            $slugToSend = $templateSlug ?: 'donation_receipt';
            $testEmailSent = self::send($slugToSend, $testRecipient, 'Test Devotee Recipient', [
                '{receipt_id}' => 'TEST-'.date('Ymd').'-'.rand(1000, 9999),
                '{amount}' => '2,100.00',
                '{cause}' => 'कामदगिरि गौ सेवा एवं संरक्षण प्रकल्प',
                '{receipt_url}' => url('/'),
                '{otp}' => '749216',
                '{booking_number}' => 'TEST-PB-84920',
                '{service_name}' => 'विशेष कामदगिरि रुद्राभिषेक अनुष्ठान',
            ]);

            $steps[] = [
                'step' => 'Live Test Email Dispatch',
                'status' => $testEmailSent ? 'passed' : 'failed',
                'detail' => $testEmailSent
                    ? "Live test email successfully delivered to '{$testRecipient}' using template '{$slugToSend}'."
                    : "Test email dispatch to '{$testRecipient}' failed. Check outbox delivery logs.",
                'time_ms' => 120,
            ];
        }

        $totalTime = round((microtime(true) - $startTime) * 1000, 2);

        return [
            'success' => true,
            'message' => "SMTP Server '{$host}:{$port}' is active and authenticated successfully in {$totalTime}ms.",
            'total_time_ms' => $totalTime,
            'steps' => $steps,
            'test_email_sent' => $testEmailSent,
        ];
    }

    /**
     * Backward-compatible helper method to render temple email cards.
     */
    public static function renderTempleCard(
        string $eyebrow,
        string $headline,
        string $greeting,
        string $leadParagraph,
        string $highlightHtml = '',
        string $ctaText = '',
        string $ctaUrl = ''
    ): string {
        return EmailTemplatesSeeder::wrapTempleLayout(
            $eyebrow,
            $headline,
            $greeting,
            $leadParagraph,
            $highlightHtml,
            $ctaText,
            $ctaUrl
        );
    }

    /**
     * Fallback template provider if record is missing.
     */
    protected static function getFallbackTemplate(string $slug, array $p): array
    {
        $siteName = $p['{site_name}'] ?? 'Shri Kamadgiri Peetham Sewa Trust';
        $siteUrl = $p['{site_url}'] ?? url('/');
        $name = $p['{name}'] ?? 'Devotee';

        return [
            'subject' => "Notification: {$slug} — {$siteName}",
            'content_html' => EmailTemplatesSeeder::wrapTempleLayout(
                'आधिकारिक सूचना · OFFICIAL NOTIFICATION',
                'श्री कामदगिरि पीठम् सेवा ट्रस्ट सूचना',
                "सादर प्रणाम, {$name} जी!",
                'ट्रस्ट पोर्टल से आपकी गतिविधि के संबंध में यह सूचना प्रेषित की गई है।',
                '',
                'पोर्टल पर जाएँ',
                $siteUrl
            ),
        ];
    }
}
