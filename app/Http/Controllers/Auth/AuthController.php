<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SupporterProfile;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->hasRole('super_admin', 'content_admin', 'donation_admin', 'membership_admin', 'media_editor')) {
                return redirect()->intended('/admin');
            }
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password credentials.']);
    }

    public function showRegister(Request $request)
    {
        $step = (int) $request->get('step', 1);
        $email = $request->get('email');
        return view('auth.register', compact('step', 'email'));
    }

    // Step 1: Account Creation & Send OTP
    public function registerStep1(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $otp = (string) rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'donor',
            'approval_status' => 'approved',
            'is_active' => true,
            'email_otp' => $otp,
            'email_otp_expires_at' => now()->addMinutes(15),
        ]);

        SupporterProfile::create(['user_id' => $user->id]);

        Membership::create([
            'user_id' => $user->id,
            'membership_id' => 'SKP-MEM-' . strtoupper(Str::random(6)),
            'status' => 'inactive',
            'consecutive_qualifying_months' => 0,
        ]);

        // Attempt sending email OTP
        try {
            Mail::raw("Pranam {$user->name},\n\nYour 6-digit OTP for Shri Kamadgiri Peetham Sewa Trust registration is: {$otp}\n\nThis OTP is valid for 15 minutes.", function ($message) use ($user) {
                $message->to($user->email)->subject('Verification OTP — Shri Kamadgiri Peetham Sewa Trust');
            });
        } catch (\Throwable $e) {
            // Log fallback if SMTP is not configured yet
            logger("OTP for {$user->email}: {$otp}");
        }

        Auth::login($user);

        return redirect('/register?step=2&email=' . urlencode($user->email))
            ->with('success', "Verification OTP sent to {$user->email}. (Test OTP: {$otp})");
    }

    // Step 2: Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($user->email_otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP code. Please check and try again.']);
        }

        $user->update([
            'email_verified_at' => now(),
            'email_otp' => null,
            'email_otp_expires_at' => null,
        ]);

        return redirect('/register?step=3')->with('success', 'Email verified successfully! Now complete your profile details.');
    }

    // Step 3: Complete Profile Details & Photo
    public function completeProfile(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'father_name' => 'nullable|string|max255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $request->phone,
                'father_name' => $request->father_name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'occupation' => $request->occupation,
                'address' => $request->address,
                'city' => $request->city,
                'district' => $request->district,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'profile_photo_path' => $photoPath ? asset('storage/' . $photoPath) : $user->profile?->profile_photo_path,
            ]
        );

        return redirect('/dashboard')->with('success', 'Welcome to Shri Kamadgiri Peetham Sewa Trust family! Your profile registration is complete.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
