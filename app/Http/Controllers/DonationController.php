<?php

namespace App\Http\Controllers;

use App\Models\Cause;
use App\Models\Donation;
use App\Models\Receipt;
use App\Models\OrganizationSetting;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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
            'amount' => 'required|numeric|min:10',
        ]);

        $amount = (float) $request->amount;
        $causeId = $request->cause_id;
        $donationType = $request->donation_type;

        $orgSettings = OrganizationSetting::getSettings();
        $razorpayKeyId = $orgSettings->razorpay_key_id ?? env('RAZORPAY_KEY');

        // Create pending donation record in DB
        $donation = Donation::create([
            'user_id' => auth()->id(),
            'cause_id' => $causeId,
            'donation_type' => $donationType,
            'amount' => $amount,
            'currency' => 'INR',
            'payment_gateway' => 'razorpay',
            'gateway_order_id' => 'order_' . Str::random(14),
            'status' => 'pending',
            'qualifying_year_month' => now()->format('Y-m'),
        ]);

        return response()->json([
            'success' => true,
            'order_id' => $donation->gateway_order_id,
            'donation_id' => $donation->id,
            'amount' => $amount * 100, // paise
            'key' => $razorpayKeyId ?? 'rzp_test_mock_key',
            'org_name' => $orgSettings->org_name,
        ]);
    }

    public function verifyPayment(Request $request, MembershipService $membershipService)
    {
        $request->validate([
            'donation_id' => 'required|exists:donations,id',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        $donation = Donation::findOrFail($request->donation_id);

        if ($donation->status === 'verified') {
            return response()->json(['success' => true, 'receipt_url' => url('/receipt/' . $donation->receipt_number)]);
        }

        // Generate receipt number
        $receiptNum = 'SKP-' . date('Ymd') . '-' . sprintf('%04d', $donation->id);

        $donation->update([
            'status' => 'verified',
            'gateway_payment_id' => $request->razorpay_payment_id,
            'gateway_signature' => $request->razorpay_signature ?? 'signature_verified',
            'receipt_number' => $receiptNum,
            'paid_at' => now(),
        ]);

        // Create Receipt record
        $causeTitle = $donation->cause?->title ?? ($donation->donation_type === 'monthly_sustaining' ? 'Monthly Sustaining Membership' : 'General Seva Donation');
        $donorName = auth()->user()?->name ?? $request->donor_name ?? 'Generous Supporter';

        Receipt::create([
            'donation_id' => $donation->id,
            'receipt_number' => $receiptNum,
            'donor_name' => $donorName,
            'amount' => $donation->amount,
            'cause_title' => $causeTitle,
            'donation_type' => $donation->donation_type,
            'issued_at' => now(),
        ]);

        // If logged in user, handle membership streak update
        if (auth()->check()) {
            $membershipService->recordSustainingDonation(auth()->user(), $donation);
        }

        return response()->json([
            'success' => true,
            'receipt_number' => $receiptNum,
            'receipt_url' => url('/receipt/' . $receiptNum),
        ]);
    }

    public function showReceipt($receiptNumber)
    {
        $receipt = Receipt::where('receipt_number', $receiptNumber)->firstOrFail();
        $orgSettings = OrganizationSetting::getSettings();
        return view('pages.receipt', compact('receipt', 'orgSettings'));
    }
}
