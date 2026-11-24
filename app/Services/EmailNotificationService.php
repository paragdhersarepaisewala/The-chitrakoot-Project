<?php

namespace App\Services;

use App\Mail\TempleNotificationMailable;
use App\Models\AtithiVishramEnquiry;
use App\Models\Donation;
use App\Models\EmailTemplate;
use App\Models\OrganizationSetting;
use App\Models\PoojaBooking;
use App\Models\TempleBookingEnquiry;
use App\Models\VolunteerApplication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailNotificationService
{
    /**
     * Send a template email with automatic placeholder interpolation.
     */
    public static function send(string $slug, string $recipientEmail, string $recipientName, array $placeholders = []): bool
    {
        if (empty($recipientEmail) || ! filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            Log::info("Email notification skipped: invalid or missing recipient email for {$slug}.");

            return false;
        }

        try {
            $orgSettings = OrganizationSetting::getSettings();
            $siteName = $orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust';
            $siteUrl = url('/');

            $template = EmailTemplate::where('slug', $slug)->where('is_active', true)->first();

            $basePlaceholders = [
                '{name}' => $recipientName,
                '{email}' => $recipientEmail,
                '{site_name}' => $siteName,
                '{site_url}' => $siteUrl,
                '{date}' => now()->format('d M Y'),
                '{year}' => date('Y'),
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

            $fromEmail = config('mail.from.address', 'info@kamadgiripeetham.org');
            $fromName = config('mail.from.name', $siteName);

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

            Mail::to($recipientEmail, $recipientName)
                ->send(new TempleNotificationMailable($subject, $bodyHtml, $fromEmail, $fromName));

            Log::info("Email notification sent [{$slug}] to {$recipientEmail}");

            return true;
        } catch (Throwable $e) {
            Log::error("Failed sending email notification [{$slug}] to {$recipientEmail}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Send Official Donation Receipt Email to Donor (Guest or Logged-in).
     */
    public static function sendDonationReceipt(Donation $donation, ?string $recipientEmail = null, ?string $recipientName = null): bool
    {
        $email = $recipientEmail ?: ($donation->donor_email ?: $donation->user?->email);
        $name = $recipientName ?: ($donation->donor_name ?: ($donation->user?->name ?: 'Generous Devotee'));

        if (! $email) {
            return false;
        }

        $causeTitle = $donation->cause?->title ?? ($donation->donation_type === 'monthly_sustaining' ? 'मासिक सेवा संकल्प (Monthly Sustaining)' : 'सामान्य सेवा दान (General Seva)');
        $receiptNumber = $donation->receipt_number ?? ('SKP-REC-'.$donation->id);
        $receiptUrl = url('/receipt/'.$receiptNumber);

        return self::send('donation_receipt', $email, $name, [
            '{amount}' => number_format((float) $donation->amount, 2),
            '{cause}' => $causeTitle,
            '{receipt_id}' => $receiptNumber,
            '{receipt_number}' => $receiptNumber,
            '{receipt_url}' => $receiptUrl,
            '{payment_id}' => $donation->gateway_payment_id ?? 'Verified Online Payment',
        ]);
    }

    /**
     * Send Volunteer Application Received notice.
     */
    public static function sendVolunteerReceived(VolunteerApplication $applicant): bool
    {
        $areas = is_array($applicant->seva_areas) ? implode(', ', $applicant->seva_areas) : ($applicant->seva_areas ?: 'सामान्य सेवा');

        return self::send('volunteer_received', $applicant->email, $applicant->name, [
            '{phone}' => $applicant->phone,
            '{city}' => $applicant->city ?? 'Chitrakoot',
            '{state}' => $applicant->state ?? '',
            '{seva_areas}' => $areas,
            '{applicant_id}' => 'VOL-'.sprintf('%04d', $applicant->id),
        ]);
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
     * Send Pooja Quotation Email with Expirable Razorpay Payment Link.
     */
    public static function sendPoojaQuotation(PoojaBooking $booking): bool
    {
        if (empty($booking->email)) {
            return false;
        }

        $serviceTitle = $booking->getEffectiveRitualTitle();
        $paymentUrl = $booking->getQuotationPaymentUrl();
        $expiresAt = $booking->quotation_expires_at ? $booking->quotation_expires_at->format('d M Y, h:i A').' ('.$booking->quotation_expires_at->diffForHumans().')' : '6 घंटे (अधिकतम 12 घंटे)';

        return self::send('pooja_quotation', $booking->email, $booking->devotee_name, [
            '{booking_number}' => $booking->booking_number,
            '{service_name}' => $serviceTitle,
            '{quotation_amount}' => number_format((float) $booking->quotation_amount, 2),
            '{quotation_notes}' => $booking->quotation_notes ?: 'अनुष्ठान दक्षिणा व सामग्री व्यवस्था',
            '{booking_date}' => $booking->booking_date ? $booking->booking_date->format('d M Y') : 'निर्धारित तिथि',
            '{time_slot}' => $booking->time_slot,
            '{expires_at}' => $expiresAt,
            '{payment_url}' => $paymentUrl,
        ]);
    }

    /**
     * Send Devotee Atithi Vishram Enquiry Received notification & Admin alert.
     */
    public static function sendAtithiVishramReceived(AtithiVishramEnquiry $enquiry): bool
    {
        $orgSettings = OrganizationSetting::getSettings();
        $placeholders = [
            '{enquiry_number}' => $enquiry->enquiry_number,
            '{name}' => $enquiry->devotee_name,
            '{phone}' => $enquiry->phone,
            '{email}' => $enquiry->email,
            '{city}' => $enquiry->city ?? 'N/A',
            '{check_in_date}' => $enquiry->check_in_date ? $enquiry->check_in_date->format('d M Y') : '',
            '{check_out_date}' => $enquiry->check_out_date ? $enquiry->check_out_date->format('d M Y') : '',
            '{devotees}' => (string) $enquiry->number_of_devotees,
            '{rooms}' => (string) $enquiry->number_of_rooms,
            '{room_preference}' => $enquiry->getRoomPreferenceLabel(),
            '{purpose}' => $enquiry->getPurposeLabel(),
            '{special_requests}' => $enquiry->special_requests ?: 'कोई विशेष अनुरोध नहीं (None)',
        ];

        // 1. Send receipt confirmation to Devotee
        $sentDevotee = self::send('atithi_vishram_received', $enquiry->email, $enquiry->devotee_name, $placeholders);

        // 2. Send operational alert to Temple Administration
        $adminEmail = $orgSettings->email ?? 'info@kamadgiripeetham.org';
        self::send('atithi_vishram_admin_alert', $adminEmail, 'Temple Guest Office', $placeholders);

        return $sentDevotee;
    }

    /**
     * Send Devotee Atithi Vishram Enquiry Status Update notification.
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
     * Send Devotee Temple Booking Enquiry Received notification & Admin alert.
     */
    public static function sendTempleBookingReceived(TempleBookingEnquiry $enquiry): bool
    {
        $orgSettings = OrganizationSetting::getSettings();
        $placeholders = [
            '{enquiry_number}' => $enquiry->enquiry_number,
            '{name}' => $enquiry->devotee_name,
            '{phone}' => $enquiry->phone,
            '{email}' => $enquiry->email,
            '{city}' => $enquiry->city ?? 'N/A',
            '{event_type}' => $enquiry->getEventTypeLabel(),
            '{event_title}' => $enquiry->event_title ?: $enquiry->getEventTypeLabel(),
            '{proposed_date}' => $enquiry->proposed_date ? $enquiry->proposed_date->format('d M Y') : '',
            '{alternate_date}' => $enquiry->alternate_date ? $enquiry->alternate_date->format('d M Y') : 'कोई वैकल्पिक तिथि नहीं (None)',
            '{gathering}' => (string) $enquiry->expected_gathering,
            '{duration}' => $enquiry->duration_days.' दिवस (Days)',
            '{requirements}' => $enquiry->additional_requirements ?: 'मानक मंदिर व्यवस्था (Standard Temple Arrangements)',
        ];

        // 1. Send receipt confirmation to Devotee
        $sentDevotee = self::send('temple_booking_received', $enquiry->email, $enquiry->devotee_name, $placeholders);

        // 2. Send operational alert to Temple Administration
        $adminEmail = $orgSettings->email ?? 'info@kamadgiripeetham.org';
        self::send('temple_booking_admin_alert', $adminEmail, 'Temple Premises Office', $placeholders);

        return $sentDevotee;
    }

    /**
     * Send Devotee Temple Booking Enquiry Status Update notification.
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
     * Dynamic fallback templates if database record has not yet been seeded.
     */
    protected static function getFallbackTemplate(string $slug, array $p): array
    {
        $siteName = $p['{site_name}'] ?? 'Shri Kamadgiri Peetham Sewa Trust';
        $siteUrl = $p['{site_url}'] ?? url('/');
        $name = $p['{name}'] ?? 'Devotee';

        switch ($slug) {
            case 'volunteer_approved':
                $subject = "बधाई! आपका सेवादार आवेदन स्वीकृत हुआ — {$siteName}";
                $body = self::renderTempleCard(
                    'सेवादार अनुमोदन पत्र · SEVA DAL ENROLMENT APPROVED',
                    'श्री कामदगिरि सेवादार परिवार में आपका सहर्ष स्वागत है',
                    "जय सियाराम, {$name} जी!",
                    'अत्यंत हर्ष के साथ सूचित किया जाता है कि श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आपका सेवादार पंजीकरण आवेदन आधिकारिक रूप से स्वीकृत (Approved) कर लिया गया है। आप अब हमारे सेवा दल के सम्मानित सदस्य हैं।',
                    '<div style="background: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">स्थिति: आधिकारिक रूप से स्वीकृत (APPROVED)</strong><br>
                        <strong>सेवादार नाम:</strong> '.$name.'<br>
                        <strong>सेवा क्षेत्र:</strong> '.($p['{seva_areas}'] ?? 'समर्पित सेवा').'<br>
                        <strong>व्यवस्थापक संदेश:</strong> '.($p['{admin_notes}'] ?? 'शीघ्र ही आपको आगामी सेवा कार्यभार प्रेषित किया जाएगा।').'
                    </div>',
                    'सेवादार डैशबोर्ड देखें',
                    "{$siteUrl}/dashboard"
                );
                break;

            case 'volunteer_rejected':
                $subject = "सेवादार आवेदन के संबंध में सूचना — {$siteName}";
                $body = self::renderTempleCard(
                    'सेवादार आवेदन सूचना · APPLICATION STATUS',
                    'आपके सेवादार आवेदन के संबंध में सूचना',
                    "सादर प्रणाम, {$name} जी!",
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट के सेवा प्रकल्पों में आपकी सहभागिता की भावना का हम सम्मान करते हैं। वर्तमान सत्र में सेवादार स्लॉट्स की सीमित उपलब्धता के कारण इस समय आपका आवेदन स्वीकृत नहीं किया जा सका है।',
                    '<div style="background: #faf7f0; border: 1px solid #e8dfd3; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>आवेदक नाम:</strong> '.$name.'<br>
                        <strong>संदेश:</strong> '.($p['{admin_notes}'] ?? 'आगामी उत्सवों एवं आयोजनों में पुनः सेवादार नामांकन आमंत्रित किए जाएंगे।').'
                    </div>',
                    'ट्रस्ट के अन्य प्रकल्प देखें',
                    "{$siteUrl}/seva"
                );
                break;

            case 'volunteer_received':
                $subject = 'सेवादार पंजीकरण आवेदन प्राप्त हुआ: संदर्भ #'.($p['{applicant_id}'] ?? 'VOL')." — {$siteName}";
                $body = self::renderTempleCard(
                    'सेवा संकल्प पावती · VOLUNTEER APPLICATION RECEIVED',
                    'सेवादार पंजीकरण आवेदन सफलतापूर्वक प्राप्त हुआ',
                    "सादर प्रणाम, {$name} जी!",
                    'चित्रकूट धाम सेवा प्रकल्पों में आपकी निःस्वार्थ सेवा भावना हेतु आपका आवेदन दर्ज कर लिया गया है। ट्रस्ट कार्यालय द्वारा विवरण की समीक्षा कर आपसे संपर्क किया जाएगा।',
                    '<div style="background: #faf7f0; border: 1px solid #b98550; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>संदर्भ संख्या:</strong> '.($p['{applicant_id}'] ?? 'VOL-NEW').'<br>
                        <strong>चयनित सेवा:</strong> '.($p['{seva_areas}'] ?? 'सामान्य सेवा').'<br>
                        <strong>स्थिति:</strong> समीक्षाधीन (Under Review)
                    </div>',
                    'भक्त पोर्टल खोलें',
                    "{$siteUrl}/dashboard"
                );
                break;

            case 'pooja_booking_received':
                $subject = 'पूजा व अनुष्ठान संकल्प अनुरोध प्राप्त हुआ: #'.($p['{booking_number}'] ?? 'PB')." — {$siteName}";
                $body = self::renderTempleCard(
                    'पूजा संकल्प पावती · POOJA BOOKING RECEIVED',
                    'पूजा व अनुष्ठान संकल्प अनुरोध दर्ज हुआ',
                    "जय कामतानाथ, {$name} जी!",
                    'चित्रकूट धाम के पावन सानिध्य में <strong>'.($p['{service_name}'] ?? 'वैदिक अनुष्ठान').'</strong> हेतु आपका संकल्प अनुरोध संदर्भ संख्या <strong>#'.($p['{booking_number}'] ?? '').'</strong> के साथ प्राप्त हो चुका है।',
                    '<div style="background: #faf7f0; border: 1px solid #e8dfd3; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>बुकिंग संख्या:</strong> #'.($p['{booking_number}'] ?? '').'<br>
                        <strong>अनुष्ठान:</strong> '.($p['{service_name}'] ?? '').'<br>
                        <strong>तिथि व समय:</strong> '.($p['{booking_date}'] ?? '').' ('.($p['{time_slot}'] ?? '').')<br>
                        <strong>सहयोग राशि:</strong> ₹'.($p['{amount}'] ?? '0.00').'
                    </div>',
                    'संकल्प विवरण देखें',
                    "{$siteUrl}/dashboard"
                );
                break;

            case 'pooja_booking_confirmed':
                $subject = 'पूजा व अनुष्ठान संकल्प पुष्ट हुआ: #'.($p['{booking_number}'] ?? 'PB')." — {$siteName}";
                $body = self::renderTempleCard(
                    'अनुष्ठान पुष्टि पत्र · POOJA SANKALP CONFIRMED',
                    'आपका वैदिक पूजा व अनुष्ठान संकल्प सुनिश्चित हुआ',
                    "परम आदरणीय {$name} जी!",
                    'चित्रकूट धाम में कामदगिरि महाराज के चरणों में आपके द्वारा समर्पित <strong>'.($p['{service_name}'] ?? 'वैदिक पूजा').'</strong> का संकल्प पुष्ट कर दिया गया है।',
                    '<div style="background: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">स्थिति: अनुष्ठान पुष्ट (CONFIRMED)</strong><br>
                        <strong>बुकिंग संख्या:</strong> #'.($p['{booking_number}'] ?? '').'<br>
                        <strong>अनुष्ठान:</strong> '.($p['{service_name}'] ?? '').'<br>
                        <strong>तिथि व समय:</strong> '.($p['{booking_date}'] ?? '').' ('.($p['{time_slot}'] ?? '').')<br>
                        <strong>गोत्र / नक्षत्र:</strong> '.($p['{gotra}'] ?? '').' / '.($p['{nakshatra}'] ?? '').'<br>
                        <strong>पुरोहित संदेश:</strong> '.($p['{admin_notes}'] ?? 'यज्ञ व अनुष्ठान की संपूर्ण व्यवस्थाएं संपन्न हैं।').'
                    </div>',
                    'भक्त डैशबोर्ड में देखें',
                    "{$siteUrl}/dashboard"
                );
                break;

            case 'pooja_quotation':
                $subject = 'पूजा व अनुष्ठान संकल्प दक्षिणा कोटेशन: #'.($p['{booking_number}'] ?? 'PB')." — {$siteName}";
                $body = self::renderTempleCard(
                    'पूजा दक्षिणा कोटेशन · POOJA DAKSHINA QUOTATION',
                    'आपके अनुष्ठान संकल्प हेतु आधिकारिक दक्षिणा पत्र',
                    "जय कामतानाथ, {$name} जी!",
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आपके <strong>'.($p['{service_name}'] ?? 'वैदिक पूजा').'</strong> के संकल्प अनुरोध (संदर्भ #'.($p['{booking_number}'] ?? '').') की समीक्षा उपरांत आचार्य जी से परामर्श कर यह दक्षिणा विवरण प्रेषित किया गया है।',
                    '<div style="background: #faf7f0; border: 1.5px solid #d4ad7d; border-radius: 10px; padding: 20px; margin: 20px 0; font-size: 14px; line-height: 1.9;">
                        <strong style="font-size: 16px; color: #8f1719; display: block; margin-bottom: 8px;">स्वीकृत दक्षिणा राशि: ₹'.($p['{quotation_amount}'] ?? '0.00').'</strong>
                        <strong>अनुष्ठान:</strong> '.($p['{service_name}'] ?? '').'<br>
                        <strong>प्रस्तावित तिथि:</strong> '.($p['{booking_date}'] ?? '').' ('.($p['{time_slot}'] ?? '').')<br>
                        <strong>व्यवस्था विवरण:</strong> '.($p['{quotation_notes}'] ?? 'वैदिक सामग्री एवं दक्षिणा').'<br>
                        <span style="display: block; margin-top: 8px; font-size: 12.5px; color: #b98550;">⏳ यह पावन भुगतान लिंक केवल <strong>'.($p['{expires_at}'] ?? '६ से १२ घंटे').'</strong> तक सक्रिय रहेगा। कृपया समय समाप्त होने से पूर्व दक्षिणा अर्पित कर अपना पूजा स्लॉट सुनिश्चित करें।</span>
                    </div>',
                    'ऑनलाइन दक्षिणा अर्पित करें (Pay Dakshina Online)',
                    $p['{payment_url}'] ?? "{$siteUrl}/dashboard"
                );
                break;

            case 'atithi_vishram_received':
                $subject = 'अतिथि विश्राम गृह पूछताछ प्राप्त हुई: #'.($p['{enquiry_number}'] ?? 'AVE')." — {$siteName}";
                $body = self::renderTempleCard(
                    'अतिथि विश्राम पावती · GUEST HOUSE ENQUIRY RECEIVED',
                    'मंदिर अतिथि विश्राम पूछताछ सफलतापूर्वक दर्ज हुई',
                    "जय सियाराम, {$name} जी!",
                    'चित्रकूट धाम तीर्थ यात्रा के दौरान कामदगिरि पीठम् अतिथि विश्राम गृह में प्रवास हेतु आपकी पूछताछ संदर्भ संख्या <strong>#'.($p['{enquiry_number}'] ?? '').'</strong> के साथ प्राप्त हो गई है।',
                    '<div style="background: #faf7f0; border: 1px solid #d4ad7d; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>पूछताछ संदर्भ:</strong> #'.($p['{enquiry_number}'] ?? '').'<br>
                        <strong>आगमन तिथि (Check-in):</strong> '.($p['{check_in_date}'] ?? '').'<br>
                        <strong>प्रस्थान तिथि (Check-out):</strong> '.($p['{check_out_date}'] ?? '').'<br>
                        <strong>भक्त संख्या:</strong> '.($p['{devotees}'] ?? '1').' भक्त &middot; <strong>कक्ष:</strong> '.($p['{rooms}'] ?? '1').'<br>
                        <strong>कक्ष प्राथमिकता:</strong> '.($p['{room_preference}'] ?? 'Standard').'<br>
                        <strong>यात्रा उद्देश्य:</strong> '.($p['{purpose}'] ?? 'तीर्थ दर्शन').'<br>
                        <span style="display: block; margin-top: 8px; color: #b45309; font-size: 12px;">* ध्यान दें: यह विश्राम कक्ष की पूछताछ है। उपलब्धता एवं आश्रम नियमों के अनुसार कार्यालय द्वारा शीघ्र संपर्क कर व्यवस्था सुनिश्चित की जाएगी।</span>
                    </div>',
                    'विश्राम स्थिति देखें',
                    "{$siteUrl}/dashboard"
                );
                break;

            case 'atithi_vishram_admin_alert':
                $subject = '[ADMIN ALERT] नई अतिथि विश्राम पूछताछ: #'.($p['{enquiry_number}'] ?? 'AVE').' — '.$name;
                $body = self::renderTempleCard(
                    'प्रबंधन सूचना · ADMIN ATITHI VISHRAM ALERT',
                    'नई अतिथि विश्राम पूछताछ प्राप्त हुई',
                    'आदरणीय व्यवस्थापक जी,',
                    'मंदिर पोर्टल पर भक्त द्वारा तीर्थ विश्राम गृह में ठहरने हेतु नई पूछताछ दर्ज की गई है। विवरण नीचे दिया गया है:',
                    '<div style="background: #fff8e1; border: 1px solid #ffe082; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>संदर्भ संख्या:</strong> #'.($p['{enquiry_number}'] ?? '').'<br>
                        <strong>भक्त नाम:</strong> '.$name.'<br>
                        <strong>संपर्क:</strong> '.($p['{phone}'] ?? '').' &bull; '.($p['{email}'] ?? '').'<br>
                        <strong>आगमन / प्रस्थान:</strong> '.($p['{check_in_date}'] ?? '').' से '.($p['{check_out_date}'] ?? '').'<br>
                        <strong>यात्री संख्या:</strong> '.($p['{devotees}'] ?? '1').' &bull; <strong>कक्ष:</strong> '.($p['{rooms}'] ?? '1').'<br>
                        <strong>कक्ष प्रकार:</strong> '.($p['{room_preference}'] ?? '').'<br>
                        <strong>विशेष अनुरोध:</strong> '.($p['{special_requests}'] ?? 'कोई नहीं').'
                    </div>',
                    'एडमिन पैनल में समीक्षा करें',
                    "{$siteUrl}/admin/atithi-vishram"
                );
                break;

            case 'atithi_vishram_status_update':
                $subject = 'अतिथि विश्राम पूछताछ अद्यतन: #'.($p['{enquiry_number}'] ?? 'AVE')." — {$siteName}";
                $body = self::renderTempleCard(
                    'विश्राम स्थिति अद्यतन · GUEST STAY STATUS UPDATE',
                    'अतिथि विश्राम पूछताछ की अद्यतन स्थिति',
                    "सादर प्रणाम, {$name} जी!",
                    'कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आपकी अतिथि विश्राम गृह पूछताछ संदर्भ <strong>#'.($p['{enquiry_number}'] ?? '').'</strong> की समीक्षा कर ली गई है।',
                    '<div style="background: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">वर्तमान स्थिति: '.($p['{status'] ?? 'समीक्षित').'</strong><br>
                        <strong>आगमन तिथि:</strong> '.($p['{check_in_date}'] ?? '').' &mdash; <strong>प्रस्थान:</strong> '.($p['{check_out_date}'] ?? '').'<br>
                        <strong>व्यवस्थापक संदेश / निर्देश:</strong> '.($p['{admin_notes}'] ?? 'शीघ्र ही आवश्यक दिशा-निर्देश प्रेषित किए जाएंगे।').'
                    </div>',
                    'डैशबोर्ड पर विवरण देखें',
                    "{$siteUrl}/dashboard"
                );
                break;

            case 'temple_booking_received':
                $subject = 'मंदिर परिसर व आयोजन आरक्षण पूछताछ प्राप्त हुई: #'.($p['{enquiry_number}'] ?? 'TBE')." — {$siteName}";
                $body = self::renderTempleCard(
                    'परिसर आरक्षण पावती · TEMPLE PREMISES BOOKING ENQUIRY',
                    'मंदिर परिसर व धार्मिक आयोजन पूछताछ दर्ज हुई',
                    "जय सियाराम, {$name} जी!",
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट के पावन परिसर में <strong>'.($p['{event_type}'] ?? 'धार्मिक आयोजन').'</strong> के आयोजन हेतु आपकी पूछताछ संदर्भ संख्या <strong>#'.($p['{enquiry_number}'] ?? '').'</strong> के साथ प्राप्त हो गई है।',
                    '<div style="background: #faf7f0; border: 1px solid #d4ad7d; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>पूछताछ संदर्भ:</strong> #'.($p['{enquiry_number}'] ?? '').'<br>
                        <strong>आयोजन प्रकार:</strong> '.($p['{event_type}'] ?? '').'<br>
                        <strong>प्रस्तावित तिथि:</strong> '.($p['{proposed_date}'] ?? '').'<br>
                        <strong>वैकल्पिक तिथि:</strong> '.($p['{alternate_date}'] ?? 'None').'<br>
                        <strong>अनुमानित उपस्थिति:</strong> लगभग '.($p['{gathering}'] ?? '50').' श्रद्धालु &middot; <strong>अवधि:</strong> '.($p['{duration}'] ?? '1 दिन').'<br>
                        <span style="display: block; margin-top: 8px; color: #b45309; font-size: 12px;">* कृपया ध्यान दें: यह आयोजन स्थल आरक्षण पूछताछ है, अंतिम बुकिंग नहीं। ट्रस्ट प्रबंधन शुभ मुहूर्त, मंदिर मर्यादा एवं उपलब्धता के आधार पर शीघ्र संपर्क कर परामर्श प्रदान करेगा।</span>
                    </div>',
                    'आयोजन पूछताछ विवरण देखें',
                    "{$siteUrl}/dashboard"
                );
                break;

            case 'temple_booking_admin_alert':
                $subject = '[ADMIN ALERT] नया मंदिर परिसर आयोजन प्रस्ताव: #'.($p['{enquiry_number}'] ?? 'TBE').' — '.$name;
                $body = self::renderTempleCard(
                    'प्रबंधन सूचना · ADMIN TEMPLE BOOKING ALERT',
                    'मंदिर परिसर आयोजन आरक्षण पूछताछ प्राप्त हुई',
                    'आदरणीय व्यवस्थापक जी,',
                    'मंदिर परिसर / यज्ञशाला / कथा मंडप आरक्षण हेतु भक्त द्वारा नया आयोजन प्रस्ताव दर्ज किया गया है:',
                    '<div style="background: #fff8e1; border: 1px solid #ffe082; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>संदर्भ संख्या:</strong> #'.($p['{enquiry_number}'] ?? '').'<br>
                        <strong>आयोजक नाम:</strong> '.$name.'<br>
                        <strong>संपर्क:</strong> '.($p['{phone}'] ?? '').' &bull; '.($p['{email}'] ?? '').'<br>
                        <strong>आयोजन:</strong> '.($p['{event_type}'] ?? '').' &bull; <strong>तिथि:</strong> '.($p['{proposed_date}'] ?? '').'<br>
                        <strong>उपस्थिति:</strong> '.($p['{gathering}'] ?? '').' श्रद्धालु &bull; <strong>अवधि:</strong> '.($p['{duration}'] ?? '').'<br>
                        <strong>अतिरिक्त आवश्यकताएं:</strong> '.($p['{requirements}'] ?? 'मानक व्यवस्था').'
                    </div>',
                    'एडमिन पैनल में समीक्षा करें',
                    "{$siteUrl}/admin/temple-bookings"
                );
                break;

            case 'temple_booking_status_update':
                $subject = 'मंदिर परिसर आरक्षण पूछताछ अद्यतन: #'.($p['{enquiry_number}'] ?? 'TBE')." — {$siteName}";
                $body = self::renderTempleCard(
                    'आयोजन स्थिति अद्यतन · TEMPLE EVENT STATUS UPDATE',
                    'मंदिर परिसर आरक्षण प्रस्ताव की अद्यतन स्थिति',
                    "सादर प्रणाम, {$name} जी!",
                    'कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आपके <strong>'.($p['{event_type}'] ?? 'धार्मिक आयोजन').'</strong> प्रस्ताव (संदर्भ <strong>#'.($p['{enquiry_number}'] ?? '').'</strong>) की समीक्षा कर ली गई है।',
                    '<div style="background: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">वर्तमान स्थिति: '.($p['{status}'] ?? 'समीक्षित').'</strong><br>
                        <strong>प्रस्तावित तिथि:</strong> '.($p['{proposed_date}'] ?? '').'<br>
                        <strong>समिति संदेश व दिशा-निर्देश:</strong> '.($p['{admin_notes}'] ?? 'कार्यालय प्रतिनिधि आपसे विस्तृत व्यवस्था हेतु संपर्क करेंगे।').'
                    </div>',
                    'डैशबोर्ड पर विवरण देखें',
                    "{$siteUrl}/dashboard"
                );
                break;

            default:
                $subject = "Official Notification — {$siteName}";
                $body = self::renderTempleCard(
                    'आधिकारिक सूचना · OFFICIAL NOTIFICATION',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट सूचना',
                    "सादर प्रणाम, {$name} जी!",
                    'ट्रस्ट पोर्टल से आपकी गतिविधि के संबंध में यह सूचना प्रेषित की गई है।',
                    '',
                    'पोर्टल पर जाएँ',
                    $siteUrl
                );
                break;
        }

        return [
            'subject' => $subject,
            'content_html' => $body,
        ];
    }

    /**
     * Render the exquisite temple-themed HTML email layout.
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
        $ctaHtml = '';
        if (! empty($ctaText) && ! empty($ctaUrl)) {
            $ctaHtml = '
            <div style="text-align: center; margin: 30px 0 10px;">
              <a href="'.$ctaUrl.'" style="display: inline-block; background: linear-gradient(135deg, #8f1719 0%, #b98550 100%); color: #ffffff; text-decoration: none; padding: 13px 32px; border-radius: 9999px; font-weight: 700; font-size: 13px; letter-spacing: 0.5px; box-shadow: 0 4px 15px rgba(143,23,25,0.25);">'.$ctaText.' &rarr;</a>
            </div>';
        }

        return '<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>'.$headline.'</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f4ed; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #f7f4ed; padding: 36px 12px;">
  <tr>
    <td align="center">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="max-width: 620px; background-color: #ffffff; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 35px rgba(22,16,13,0.08); border: 1px solid #e5ded5;">
        <!-- Top Gradient Strip -->
        <tr>
          <td style="height: 5px; background: linear-gradient(90deg, #b98550 0%, #8f1719 50%, #e09f3e 100%);"></td>
        </tr>
        <!-- Masthead -->
        <tr>
          <td style="background-color: #16100d; padding: 32px 28px; text-align: center;">
            <div style="font-size: 11px; letter-spacing: 3px; color: #d4a365; text-transform: uppercase; font-weight: 800; margin-bottom: 6px;">श्री कामदगिरि पीठम् सेवा ट्रस्ट</div>
            <div style="font-size: 22px; font-weight: 700; color: #ffffff; font-family: Georgia, \'Times New Roman\', serif; letter-spacing: 0.5px;">चित्रकूट धाम · SEWA & SANATAN HERITAGE</div>
            <div style="width: 48px; height: 1.5px; background-color: #b98550; margin: 12px auto 0;"></div>
          </td>
        </tr>
        <!-- Content Area -->
        <tr>
          <td style="padding: 36px 32px 30px;">
            <div style="font-size: 10.5px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #b98550; margin-bottom: 8px;">'.$eyebrow.'</div>
            <h1 style="font-size: 22px; font-weight: 700; color: #16100d; margin: 0 0 16px; font-family: Georgia, \'Times New Roman\', serif; line-height: 1.35;">'.$headline.'</h1>
            <div style="font-size: 15px; color: #593a28; margin-bottom: 14px; font-weight: 600;">'.$greeting.'</div>
            <div style="font-size: 14.5px; line-height: 1.7; color: #433830; margin-bottom: 20px;">'.$leadParagraph.'</div>
            '.$highlightHtml.'
            '.$ctaHtml.'
            <div style="font-size: 12px; color: #8c7e73; line-height: 1.5; margin-top: 24px; border-top: 1px dashed #e8e2d8; padding-top: 16px;">
              यदि आपको इस संबंध में कोई सहायता अथवा जानकारी चाहिए, तो कृपया हमारे सेवा केंद्र पर संपर्क करें।
            </div>
          </td>
        </tr>
        <!-- Footer -->
        <tr>
          <td style="background-color: #faf7f2; border-top: 1px solid #ede7dc; padding: 24px 30px; text-align: center;">
            <div style="font-size: 12px; color: #6a5d52; line-height: 1.6;">
              <strong style="color: #16100d;">श्री कामदगिरि पीठम् सेवा ट्रस्ट (रजि.)</strong><br>
              कामदगिरि परिक्रमा मार्ग, चित्रकूट धाम, उत्तर प्रदेश / मध्य प्रदेश<br>
            </div>
            <div style="font-size: 11px; color: #9c8e82; margin-top: 10px; border-top: 1px dashed #e2dbce; padding-top: 10px;">
              '.(OrganizationSetting::getSettings()->is_80g_enabled ? 'सभी दान आयकर अधिनियम की धारा 80G के अंतर्गत 50% कर-मुक्त हैं।' : 'यह श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा जारी डिजिटल रूप से प्रमाणित अधिकृत दान पावती है।').'
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>';
    }
}
