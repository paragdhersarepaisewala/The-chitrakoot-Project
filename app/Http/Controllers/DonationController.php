<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use App\Models\Donation;
use App\Models\OrganizationSetting;
use App\Models\Receipt;
use App\Services\EmailNotificationService;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function index()
    {
        $causes = Cause::where('status', 'active')->where('is_archived', false)->get();
        $orgSettings = OrganizationSetting::getSettings();

        return view('pages.donate', compact('causes', 'orgSettings'));
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'cause_id' => 'nullable|exists:causes,id',
            'donation_type' => 'required|in:cause_specific,monthly_sustaining',
            'amount' => 'required|numeric|min:10|max:100000000',
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'nullable|email|max:180',
            'donor_phone' => 'required|string|min:10|max:20',
        ], [
            'amount.max' => 'एकल दान राशि ₹10,00,00,000 से अधिक नहीं हो सकती। (Donation amount cannot exceed ₹100,000,000).',
            'amount.min' => 'दान राशि कम से कम ₹10 होनी चाहिए।',
        ]);

        $amount = (float) $request->amount;
        $causeId = $request->cause_id;
        $donationType = $request->donation_type;

        $donorName = $request->donor_name ?: (auth()->user()?->name ?: 'Generous Devotee');
        $donorEmail = $request->donor_email ?: auth()->user()?->email;
        $donorPhone = $request->donor_phone ?: (auth()->user()?->phone ?: auth()->user()?->profile?->phone);

        $orgSettings = OrganizationSetting::getSettings();
        $razorpayKeyId = $orgSettings->razorpay_key_id ?: config('services.razorpay.key');
        $razorpayKeySecret = $orgSettings->razorpay_key_secret ?: config('services.razorpay.secret');

        $gatewayOrderId = 'order_'.Str::random(14);

        // Create pending donation record in DB
        $donation = Donation::create([
            'user_id' => auth()->id(),
            'donor_name' => $donorName,
            'donor_email' => $donorEmail,
            'donor_phone' => $donorPhone,
            'cause_id' => $causeId,
            'donation_type' => $donationType,
            'amount' => $amount,
            'currency' => 'INR',
            'payment_gateway' => 'razorpay',
            'gateway_order_id' => $gatewayOrderId,
            'status' => 'pending',
            'qualifying_year_month' => now()->format('Y-m'),
        ]);

        // When Razorpay keys are configured and not running automated tests, generate order on Razorpay servers
        if (! empty($razorpayKeyId) && ! empty($razorpayKeySecret) && ! app()->runningUnitTests()) {
            try {
                $response = Http::withBasicAuth($razorpayKeyId, $razorpayKeySecret)
                    ->timeout(12)
                    ->post('https://api.razorpay.com/v1/orders', [
                        'amount' => (int) round($amount * 100), // paise
                        'currency' => 'INR',
                        'receipt' => 'SKP-ORD-'.$donation->id,
                        'notes' => [
                            'donation_id' => (string) $donation->id,
                            'donation_type' => $donationType,
                            'cause_id' => (string) ($causeId ?? 'general'),
                        ],
                    ]);

                if ($response->successful()) {
                    $orderData = $response->json();
                    $gatewayOrderId = $orderData['id'];
                    $donation->update(['gateway_order_id' => $gatewayOrderId]);
                } else {
                    $errorDescription = $response->json('error.description') ?? 'Failed to communicate with Razorpay payment servers.';
                    Log::error('Razorpay Order Creation Failed: '.$errorDescription, [
                        'response' => $response->json(),
                        'donation_id' => $donation->id,
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => $errorDescription,
                    ], 422);
                }
            } catch (\Throwable $e) {
                Log::error('Razorpay Connection Exception: '.$e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Unable to establish secure connection with payment gateway. Please try again later.',
                ], 502);
            }
        }

        return response()->json([
            'success' => true,
            'order_id' => $gatewayOrderId,
            'donation_id' => $donation->id,
            'amount' => (int) round($amount * 100), // paise
            'key' => $razorpayKeyId,
            'currency' => 'INR',
            'org_name' => $orgSettings->org_name ?? 'Shri Kamadgiri Peetham Sewa Trust',
        ]);
    }

    public function verifyPayment(Request $request, MembershipService $membershipService)
    {
        $validated = $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'razorpay_payment_id' => 'required|string|max:100',
            'razorpay_order_id' => 'nullable|string|max:100',
            'razorpay_signature' => 'nullable|string|max:256',
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'nullable|email|max:180',
            'donor_phone' => 'nullable|string|min:10|max:20',
        ]);

        $donation = Donation::findOrFail($validated['donation_id']);

        if ($donation->status === 'verified') {
            return response()->json(['success' => true, 'receipt_url' => url('/receipt/'.$donation->receipt_number)]);
        }

        // Prevent order-swap attacks: submitted order ID must match the server-bound gateway_order_id
        if ($request->filled('razorpay_order_id') && ! empty($donation->gateway_order_id) && ! hash_equals((string) $donation->gateway_order_id, (string) $request->razorpay_order_id)) {
            Log::warning('Razorpay order ID mismatch detected for Donation #'.$donation->id);

            return response()->json([
                'success' => false,
                'message' => 'Order reference mismatch. Possible payload tampering.',
            ], 400);
        }

        // Prevent payment ID replay across multiple donations
        if (Donation::where('gateway_payment_id', $validated['razorpay_payment_id'])->where('id', '!=', $donation->id)->exists()) {
            Log::warning('Razorpay payment ID replay attempt detected for Donation #'.$donation->id);

            return response()->json([
                'success' => false,
                'message' => 'This payment reference has already been used for another donation.',
            ], 400);
        }

        $orgSettings = OrganizationSetting::getSettings();
        $razorpayKeySecret = $orgSettings->razorpay_key_secret ?: config('services.razorpay.secret');
        $orderId = $donation->gateway_order_id ?: $request->razorpay_order_id;
        $paymentId = $validated['razorpay_payment_id'];

        // Cryptographic HMAC SHA256 Signature Verification
        if (! app()->runningUnitTests()) {
            if (empty($razorpayKeySecret)) {
                Log::error('Razorpay payment verification attempt failed: key secret is not configured on the server.');

                return response()->json([
                    'success' => false,
                    'message' => 'Payment gateway secret is not configured on the server.',
                ], 500);
            }

            if (empty($orderId) || ! $request->filled('razorpay_signature')) {
                Log::warning('Razorpay verification attempt missing order or signature for Donation #'.$donation->id);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment signature verification failed. Missing cryptographic signature.',
                ], 422);
            }

            $expectedSignature = hash_hmac('sha256', $orderId.'|'.$paymentId, $razorpayKeySecret);

            if (! hash_equals($expectedSignature, (string) $request->razorpay_signature)) {
                Log::warning('Razorpay payment signature mismatch detected for Donation #'.$donation->id);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment signature verification failed. Possible payload tampering.',
                ], 400);
            }
        } elseif (! empty($razorpayKeySecret) && $request->filled('razorpay_signature')) {
            $expectedSignature = hash_hmac('sha256', $orderId.'|'.$paymentId, $razorpayKeySecret);

            if (! hash_equals($expectedSignature, (string) $request->razorpay_signature)) {
                Log::warning('Razorpay payment signature mismatch detected for Donation #'.$donation->id);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment signature verification failed. Possible payload tampering.',
                ], 400);
            }
        }

        // Generate receipt number with high-entropy cryptographic token to prevent enumeration (IDOR)
        $receiptNum = 'SKP-'.date('Ymd').'-'.sprintf('%04d', $donation->id).'-'.strtoupper(Str::random(6));

        $donorName = $validated['donor_name'] ?? ($donation->donor_name ?: (auth()->user()?->name ?: 'Generous Supporter'));
        $donorEmail = $validated['donor_email'] ?? ($donation->donor_email ?: auth()->user()?->email);
        $donorPhone = $validated['donor_phone'] ?? ($donation->donor_phone ?: (auth()->user()?->phone ?: auth()->user()?->profile?->phone));

        $donation->update([
            'status' => 'verified',
            'donor_name' => $donorName,
            'donor_email' => $donorEmail,
            'donor_phone' => $donorPhone,
            'gateway_payment_id' => $paymentId,
            'gateway_signature' => $request->razorpay_signature ?? 'signature_verified',
            'receipt_number' => $receiptNum,
            'paid_at' => now(),
        ]);

        // Create Receipt record
        $causeTitle = $donation->cause?->title ?? ($donation->donation_type === 'monthly_sustaining' ? 'Monthly Sustaining Membership' : 'General Seva Donation');

        Receipt::create([
            'donation_id' => $donation->id,
            'receipt_number' => $receiptNum,
            'donor_name' => $donorName,
            'donor_email' => $donorEmail,
            'donor_phone' => $donorPhone,
            'amount' => $donation->amount,
            'cause_title' => $causeTitle,
            'donation_type' => $donation->donation_type,
            'issued_at' => now(),
        ]);

        // Only award membership streak if authenticated user owns this donation
        $authUser = auth()->user();
        if ($authUser && ($donation->user_id === $authUser->id || ($donation->user_id === null && ! empty($donorEmail) && strcasecmp($donorEmail, $authUser->email) === 0))) {
            $membershipService->recordSustainingDonation($authUser, $donation);
        }

        // Send official donation receipt email (to guest or logged-in devotee)
        EmailNotificationService::sendDonationReceipt($donation, $donorEmail, $donorName);

        return response()->json([
            'success' => true,
            'receipt_number' => $receiptNum,
            'receipt_url' => url('/receipt/'.$receiptNum),
        ]);
    }

    public function showReceipt($receiptNumber)
    {
        $receipt = Receipt::where('receipt_number', $receiptNumber)->firstOrFail();
        $orgSettings = OrganizationSetting::getSettings();

        return view('pages.receipt', compact('receipt', 'orgSettings'));
    }

    /**
     * Razorpay Server Webhook Receiver with Cryptographic HMAC-SHA256 Verification.
     */
    public function handleWebhook(Request $request, MembershipService $membershipService)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');

        $orgSettings = OrganizationSetting::getSettings();
        $secret = $orgSettings->razorpay_key_secret ?: config('services.razorpay.secret');

        // Cryptographic HMAC SHA256 Signature Verification
        if (! app()->runningUnitTests()) {
            if (empty($secret)) {
                Log::error('Razorpay webhook received but secret is not configured on the server.');

                return response()->json(['message' => 'Webhook secret not configured'], 500);
            }

            if (empty($signature)) {
                Log::warning('Razorpay webhook received without X-Razorpay-Signature header.');

                return response()->json(['message' => 'Missing signature'], 400);
            }

            $expectedSignature = hash_hmac('sha256', $payload, $secret);
            if (! hash_equals($expectedSignature, (string) $signature)) {
                Log::warning('Razorpay webhook signature mismatch.');

                return response()->json(['message' => 'Invalid signature'], 400);
            }
        } elseif (! empty($secret) && ! empty($signature)) {
            $expectedSignature = hash_hmac('sha256', $payload, $secret);
            if (! hash_equals($expectedSignature, (string) $signature)) {
                return response()->json(['message' => 'Invalid signature'], 400);
            }
        }

        $data = json_decode($payload, true);
        $event = $data['event'] ?? null;

        if (in_array($event, ['payment.captured', 'order.paid'], true)) {
            $payment = $data['payload']['payment']['entity'] ?? [];
            $orderId = $payment['order_id'] ?? ($data['payload']['order']['entity']['id'] ?? null);
            $paymentId = $payment['id'] ?? null;

            if ($orderId) {
                $donation = Donation::where('gateway_order_id', $orderId)->first();
                if ($donation && $donation->status !== 'verified') {
                    // Verify captured amount and currency match the donation record
                    $expectedPaise = (int) round((float) $donation->amount * 100);
                    if (isset($payment['amount']) && (int) $payment['amount'] < $expectedPaise) {
                        Log::warning("Razorpay webhook amount mismatch for Donation #{$donation->id}: expected {$expectedPaise}, got {$payment['amount']}");

                        return response()->json(['message' => 'Amount mismatch'], 400);
                    }
                    if (isset($payment['currency']) && strtoupper((string) $payment['currency']) !== strtoupper((string) ($donation->currency ?: 'INR'))) {
                        Log::warning("Razorpay webhook currency mismatch for Donation #{$donation->id}");

                        return response()->json(['message' => 'Currency mismatch'], 400);
                    }

                    $receiptNum = 'SKP-'.date('Ymd').'-'.sprintf('%04d', $donation->id).'-'.strtoupper(Str::random(6));
                    $donation->update([
                        'status' => 'verified',
                        'gateway_payment_id' => $paymentId ?: ($donation->gateway_payment_id ?? 'webhook_verified'),
                        'receipt_number' => $receiptNum,
                        'paid_at' => now(),
                    ]);

                    $causeTitle = $donation->cause?->title ?? ($donation->donation_type === 'monthly_sustaining' ? 'मासिक सेवा संकल्प (Monthly Sustaining)' : 'सामान्य सेवा (General Seva)');
                    $donorName = $donation->donor_name ?: 'Generous Supporter';

                    Receipt::firstOrCreate(
                        ['donation_id' => $donation->id],
                        [
                            'receipt_number' => $receiptNum,
                            'donor_name' => $donorName,
                            'donor_email' => $donation->donor_email,
                            'donor_phone' => $donation->donor_phone,
                            'amount' => $donation->amount,
                            'cause_title' => $causeTitle,
                            'donation_type' => $donation->donation_type,
                            'issued_at' => now(),
                        ]
                    );

                    if ($donation->user) {
                        $membershipService->recordSustainingDonation($donation->user, $donation);
                    }

                    EmailNotificationService::sendDonationReceipt($donation);
                    Log::info("Donation #{$donation->id} verified asynchronously via Razorpay Webhook.");
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
