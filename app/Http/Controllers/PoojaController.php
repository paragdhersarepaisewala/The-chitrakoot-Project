<?php

namespace App\Http\Controllers;

use App\Models\OrganizationSetting;
use App\Models\PoojaBooking;
use App\Models\PoojaService;
use App\Models\PoojaTimeSlot;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PoojaController extends Controller
{
    public function index()
    {
        $orgSettings = OrganizationSetting::getSettings();

        // If prebuilt packages directory is disabled by admin, redirect directly to sacred booking form
        if (! ($orgSettings->is_pooja_packages_enabled ?? false)) {
            return redirect()->route('pooja.bookingForm');
        }

        $poojas = PoojaService::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        return view('pages.pooja.index', compact('poojas', 'orgSettings'));
    }

    public function show($slug)
    {
        $orgSettings = OrganizationSetting::getSettings();

        // If prebuilt packages directory is disabled, redirect to booking form
        if (! ($orgSettings->is_pooja_packages_enabled ?? false)) {
            return redirect()->route('pooja.bookingForm');
        }

        $pooja = PoojaService::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $otherPoojas = PoojaService::where('id', '!=', $pooja->id)->where('is_active', true)->take(3)->get();

        return view('pages.pooja.show', compact('pooja', 'otherPoojas', 'orgSettings'));
    }

    /**
     * Dedicated form-based Pooja and Hawan booking page.
     * Enforces min 2-day advance booking so today & tomorrow are greyed out, enabling any slot without error.
     */
    public function bookingForm(Request $request)
    {
        $allPackages = PoojaService::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        $poojaPackages = $allPackages->where('service_type', '!=', 'hawan');
        $hawanPackages = $allPackages->where('service_type', 'hawan');

        // Fetch admin-managed available temple time slots
        $timeSlots = PoojaTimeSlot::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        // Selected package pre-population from query parameter (?package=slug or ?id=1)
        $selectedPackage = null;
        if ($request->filled('package')) {
            $selectedPackage = $allPackages->firstWhere('slug', $request->package);
        } elseif ($request->filled('id')) {
            $selectedPackage = $allPackages->firstWhere('id', (int) $request->id);
        }

        if (! $selectedPackage && $allPackages->isNotEmpty()) {
            $selectedPackage = $allPackages->first();
        }

        $orgSettings = OrganizationSetting::getSettings();
        // Today and tomorrow are unselectable; minimum booking date is 2 days in advance
        $minDate = now()->addDays(2)->format('Y-m-d');

        return view('pages.pooja.booking-form', compact(
            'allPackages',
            'poojaPackages',
            'hawanPackages',
            'timeSlots',
            'selectedPackage',
            'orgSettings',
            'minDate'
        ));
    }

    public function book(Request $request)
    {
        // Enforce booking date is at least 2 days in advance (today & tomorrow greyed out/blocked)
        $minDate = now()->addDays(2)->format('Y-m-d');

        $request->validate([
            'pooja_service_id' => 'nullable|exists:pooja_services,id',
            'ritual_name' => 'nullable|string|max:255',
            'devotee_name' => 'required|string|max:255',
            'phone' => 'required|string|min:10|max:20',
            'email' => 'required|email|max:180',
            'gotra' => 'nullable|string|max:100',
            'nakshatra' => 'nullable|string|max:100',
            'sankalp_purpose' => 'nullable|string|max:255',
            'booking_date' => 'required|date|after_or_equal:'.$minDate,
            'time_slot' => 'required|string|max:150',
        ], [
            'booking_date.after_or_equal' => 'पूजा व हवन की समुचित व्यवस्था हेतु कम से कम 2 दिन पूर्व (अग्रिम तिथि) का आरक्षण अनिवार्य है। आज और कल की तिथि में आरक्षण उपलब्ध नहीं है।',
            'phone.required' => 'कृपया अपना वैध मोबाइल नंबर दर्ज करें (Mobile number is required).',
            'phone.min' => 'मोबाइल नंबर कम से कम 10 अंकों का होना अनिवार्य है।',
            'email.required' => 'पुष्टिकरण एवं रसीद प्राप्ति हेतु ईमेल पता अनिवार्य है।',
            'booking_date.required' => 'कृपया अनुष्ठान की तिथि का चयन करें।',
            'time_slot.required' => 'कृपया मंदिर समय-सारणी से उपलब्ध स्लॉट का चयन करें।',
        ]);

        $ritualName = $request->ritual_name;
        $serviceId = $request->pooja_service_id;
        if ($serviceId) {
            $service = PoojaService::find($serviceId);
            if ($service && empty($ritualName)) {
                $ritualName = $service->title_hi ?: $service->title_en;
            }
        }

        if (empty($ritualName)) {
            $ritualName = 'विशेष वैदिक पूजा व हवन संकल्प';
        }

        $bookingNumber = 'SKP-PB-'.date('Ymd').'-'.strtoupper(Str::random(5));

        $booking = PoojaBooking::create([
            'booking_number' => $bookingNumber,
            'pooja_service_id' => $serviceId,
            'ritual_name' => $ritualName,
            'user_id' => auth()->id(),
            'devotee_name' => $request->devotee_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'gotra' => $request->gotra,
            'nakshatra' => $request->nakshatra,
            'sankalp_purpose' => $request->sankalp_purpose,
            'booking_date' => $request->booking_date,
            'time_slot' => $request->time_slot,
            'admin_notes' => null,
            'amount' => 0.00,
            'payment_status' => 'pending',
            'status' => 'pending', // Pending contact & quotation by Sanstha
        ]);

        // Send booking acknowledgment email to devotee
        EmailNotificationService::sendPoojaBookingReceived($booking);

        return redirect()->route('pooja.confirmation', ['bookingNumber' => $bookingNumber])
            ->with('success', 'जय कामतानाथ! आपका पूजा व अनुष्ठान संकल्प अनुरोध सफलतापूर्वक प्राप्त हुआ है। न्यास के आचार्य/व्यवस्थापक आपसे संपर्क कर दक्षिणा व व्यवस्था सुनिश्चित करेंगे।');
    }

    public function confirmation($bookingNumber)
    {
        $booking = PoojaBooking::with('service')->where('booking_number', $bookingNumber)->firstOrFail();
        $orgSettings = OrganizationSetting::getSettings();

        return view('pages.pooja.confirmation', compact('booking', 'orgSettings'));
    }

    /**
     * Devotee Expirable Quotation Payment Screen.
     */
    public function showQuotation($token)
    {
        $booking = PoojaBooking::where('quotation_token', $token)->firstOrFail();
        $orgSettings = OrganizationSetting::getSettings();
        $isExpired = $booking->isQuotationExpired();

        $razorpayKeyId = $orgSettings->razorpay_key_id ?: config('services.razorpay.key') ?: env('RAZORPAY_KEY') ?: env('RAZORPAY_KEY_ID') ?: 'rzp_test_placeholder';

        return view('pages.pooja.quotation_pay', compact('booking', 'orgSettings', 'isExpired', 'razorpayKeyId'));
    }

    /**
     * Create Razorpay Order for Pooja Quotation Payment.
     */
    public function createQuotationOrder(Request $request, $token)
    {
        $booking = PoojaBooking::where('quotation_token', $token)->first();

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => 'अमान्य कोटेशन संदर्भ (Invalid quotation token).',
            ], 404);
        }

        if ($booking->isQuotationExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'यह कोटेशन लिंक समाप्त (Expired) हो चुका है। कृपया न्यास कार्यालय से पुनः संपर्क करें।',
            ], 422);
        }

        if ($booking->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'इस अनुष्ठान हेतु दक्षिणा पहले ही प्राप्त हो चुकी है।',
            ], 422);
        }

        $amount = (float) $booking->quotation_amount;
        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'अमान्य दक्षिणा राशि।',
            ], 422);
        }

        $orgSettings = OrganizationSetting::getSettings();
        $razorpayKeyId = $orgSettings->razorpay_key_id ?: config('services.razorpay.key');
        $razorpayKeySecret = $orgSettings->razorpay_key_secret ?: config('services.razorpay.secret');

        $gatewayOrderId = 'order_pb_'.Str::random(14);

        if (! empty($razorpayKeyId) && ! empty($razorpayKeySecret) && ! app()->runningUnitTests()) {
            try {
                $response = Http::withBasicAuth($razorpayKeyId, $razorpayKeySecret)
                    ->timeout(12)
                    ->post('https://api.razorpay.com/v1/orders', [
                        'amount' => (int) round($amount * 100),
                        'currency' => 'INR',
                        'receipt' => 'PB-'.substr($booking->booking_number, -8),
                        'notes' => [
                            'booking_number' => $booking->booking_number,
                            'devotee_name' => $booking->devotee_name,
                            'ritual_name' => $booking->getEffectiveRitualTitle(),
                        ],
                    ]);

                if ($response->successful()) {
                    $orderData = $response->json();
                    $gatewayOrderId = $orderData['id'];
                } else {
                    Log::error('Razorpay Pooja Order Creation Failed: '.$response->body());

                    return response()->json([
                        'success' => false,
                        'message' => 'भुगतान गेटवे आदेश सृजित करने में त्रुटि हुई। कृपया पुनः प्रयास करें।',
                    ], 502);
                }
            } catch (\Throwable $e) {
                Log::error('Razorpay Pooja Order Exception: '.$e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'भुगतान गेटवे से सुरक्षित संपर्क स्थापित नहीं हो सका। कृपया पुनः प्रयास करें।',
                ], 502);
            }
        } elseif (! app()->runningUnitTests()) {
            return response()->json([
                'success' => false,
                'message' => 'ऑनलाइन भुगतान गेटवे अभी कॉन्फ़िगर नहीं है।',
            ], 500);
        }

        $booking->update(['quotation_order_id' => $gatewayOrderId]);

        return response()->json([
            'success' => true,
            'key' => $razorpayKeyId ?: 'rzp_test_placeholder',
            'order_id' => $gatewayOrderId,
            'amount' => (int) round($amount * 100),
            'amount_rupees' => $amount,
            'currency' => 'INR',
            'devotee_name' => $booking->devotee_name,
            'devotee_email' => $booking->email,
            'devotee_phone' => $booking->phone,
            'ritual_name' => $booking->getEffectiveRitualTitle(),
            'booking_number' => $booking->booking_number,
        ]);
    }

    /**
     * Verify Quotation Payment.
     */
    public function verifyQuotationPayment(Request $request, $token)
    {
        $booking = PoojaBooking::where('quotation_token', $token)->firstOrFail();

        if ($booking->payment_status === 'paid') {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'इस अनुष्ठान की दक्षिणा पहले ही सत्यापित की जा चुकी है।',
                    'redirect_url' => route('pooja.confirmation', ['bookingNumber' => $booking->booking_number]),
                ]);
            }

            return redirect()->route('pooja.confirmation', ['bookingNumber' => $booking->booking_number]);
        }

        if ($booking->isQuotationExpired()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'यह कोटेशन लिंक समाप्त (Expired) हो चुका है। कृपया न्यास कार्यालय से पुनः संपर्क करें।',
                ], 422);
            }

            return back()->with('error', 'यह कोटेशन लिंक समाप्त (Expired) हो चुका है। कृपया न्यास कार्यालय से पुनः संपर्क करें।');
        }

        $paymentId = $request->input('razorpay_payment_id') ?: $request->input('payment_id');
        $orderId = $request->input('razorpay_order_id') ?: $booking->quotation_order_id;
        $signature = $request->input('razorpay_signature');

        // Prevent order-swap attacks against server-bound quotation order ID
        if (! empty($booking->quotation_order_id) && $request->filled('razorpay_order_id') && ! hash_equals((string) $booking->quotation_order_id, (string) $request->input('razorpay_order_id'))) {
            Log::warning('Razorpay quotation order ID mismatch for Pooja Booking #'.$booking->id);
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ऑर्डर संदर्भ असंगत है (Order reference mismatch)।',
                ], 422);
            }

            return back()->with('error', 'भुगतान ऑर्डर संदर्भ अमान्य है। कृपया पुनः प्रयास करें।');
        }

        // Prevent payment ID replay across bookings
        if (! empty($paymentId) && PoojaBooking::where('payment_id', $paymentId)->where('id', '!=', $booking->id)->exists()) {
            Log::warning('Razorpay payment ID replay attempt for Pooja Booking #'.$booking->id);
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'यह भुगतान पहचान संख्या पहले ही किसी अन्य बुकिंग में प्रयुक्त हो चुकी है।',
                ], 422);
            }

            return back()->with('error', 'यह भुगतान पहचान संख्या पहले ही प्रयुक्त हो चुकी है।');
        }

        $orgSettings = OrganizationSetting::getSettings();
        $razorpayKeySecret = $orgSettings->razorpay_key_secret ?: config('services.razorpay.secret');

        // Strictly enforce Razorpay online payment verification (fail-closed in production)
        if (! app()->runningUnitTests()) {
            if (empty($razorpayKeySecret)) {
                Log::error('Razorpay quotation verification failed: secret key is not configured on the server.');
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment gateway secret is not configured on the server.',
                    ], 500);
                }

                return back()->with('error', 'भुगतान गेटवे सुरक्षा कुंजी सर्वर पर कॉन्फ़िगर नहीं है।');
            }

            if (empty($paymentId) || empty($orderId) || empty($booking->quotation_order_id) || empty($signature)) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'भुगतान पहचान संख्या या सुरक्षा हस्ताक्षर अपूर्ण है (Missing required Razorpay payment credentials).',
                    ], 422);
                }

                return back()->with('error', 'भुगतान सत्यापन विवरण अपूर्ण है। कृपया पुनः प्रयास करें।');
            }

            $expectedSignature = hash_hmac('sha256', $orderId.'|'.$paymentId, $razorpayKeySecret);
            if (! hash_equals($expectedSignature, (string) $signature)) {
                Log::warning('Razorpay payment signature mismatch for Pooja Booking #'.$booking->id);
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'भुगतान सुरक्षा सत्यापन विफल (Invalid payment signature)।',
                    ], 422);
                }

                return back()->with('error', 'भुगतान सुरक्षा सत्यापन विफल रहा। कृपया पुनः प्रयास करें।');
            }
        } else {
            // Unit test environment verification
            if (empty($paymentId)) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'भुगतान पहचान संख्या (Payment ID) प्राप्त नहीं हुई।',
                    ], 422);
                }

                return back()->with('error', 'भुगतान सत्यापन विफल रहा। कृपया पुनः प्रयास करें।');
            }

            if (! empty($signature) && ! empty($orderId) && ! empty($razorpayKeySecret)) {
                $expectedSignature = hash_hmac('sha256', $orderId.'|'.$paymentId, $razorpayKeySecret);
                if (! hash_equals($expectedSignature, (string) $signature)) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'भुगतान सुरक्षा सत्यापन विफल (Invalid payment signature)।',
                        ], 422);
                    }

                    return back()->with('error', 'भुगतान सुरक्षा सत्यापन विफल रहा। कृपया पुनः प्रयास करें।');
                }
            }
        }

        $booking->update([
            'payment_status' => 'paid',
            'payment_id' => $paymentId,
            'status' => 'confirmed',
        ]);

        EmailNotificationService::sendPoojaBookingConfirmed($booking);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'जय कामतानाथ! आपकी दक्षिणा सफलतापूर्वक प्राप्त हुई एवं आपका पूजा संकल्प पुष्ट कर दिया गया है।',
                'redirect_url' => route('pooja.confirmation', ['bookingNumber' => $booking->booking_number]),
            ]);
        }

        return redirect()->route('pooja.confirmation', ['bookingNumber' => $booking->booking_number])
            ->with('success', 'जय कामतानाथ! आपकी दक्षिणा सफलतापूर्वक प्राप्त हुई एवं आपका पूजा संकल्प पुष्ट (Confirmed) कर दिया गया है।');
    }
}
