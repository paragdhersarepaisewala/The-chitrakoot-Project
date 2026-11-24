<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Membership;
use App\Models\OrganizationSetting;
use App\Models\PoojaBooking;
use App\Models\SupporterProfile;
use App\Models\User;
use App\Models\VolunteerApplication;
use App\Services\EmailNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            $user = Auth::user();

            // Link any previous guest history matching devotee's email
            $this->linkGuestHistory($user);

            if ($user->hasRole('super_admin', 'content_admin', 'donation_admin', 'membership_admin', 'media_editor')) {
                return redirect()->intended('/admin');
            }

            $orgSettings = OrganizationSetting::getSettings();
            if ($orgSettings->isSmtpConfigured() && is_null($user->email_verified_at)) {
                return redirect('/register?step=2&email='.urlencode($user->email))
                    ->with('warning', 'डैशबोर्ड में प्रवेश करने से पूर्व कृपया अपने ईमेल पर प्राप्त 6-अंकीय OTP सत्यापित करें। (Please verify your email OTP before accessing dashboard).');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password credentials.']);
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'इस ईमेल से संबंधित कोई भक्त खाता पंजीकृत नहीं है।',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $otp = (string) random_int(100000, 999999);

        $user->update([
            'email_otp' => $otp,
            'email_otp_expires_at' => now()->addMinutes(15),
        ]);

        EmailNotificationService::sendPasswordResetOtp($user, $otp);

        return redirect('/reset-password?email='.urlencode($user->email))
            ->with('success', "पासवर्ड रीसेट सुरक्षा कोड {$user->email} पर भेज दिया गया है।");
    }

    public function showResetPassword(Request $request)
    {
        $email = $request->get('email');

        return view('auth.reset-password', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->email_otp_expires_at && now()->isAfter($user->email_otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP कोड की समय सीमा समाप्त हो गई है। कृपया पुनः अनुरोध करें।']);
        }

        if (empty($user->email_otp) || ! hash_equals((string) $user->email_otp, (string) $request->otp)) {
            return back()->withErrors(['otp' => 'प्रविष्ट किया गया OTP कोड अमान्य है।']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'email_otp' => null,
            'email_otp_expires_at' => null,
        ]);

        EmailNotificationService::sendPasswordChangedAlert($user);

        return redirect('/login')->with('success', 'आपका पासवर्ड सफलतापूर्वक बदल दिया गया है! कृपया नए पासवर्ड से लॉगिन करें।');
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
        $maxDob = now()->subYears(18)->format('Y-m-d');
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'date_of_birth' => 'required|date|before_or_equal:'.$maxDob,
            'password' => 'required|string|min:8|confirmed',
        ], [
            'date_of_birth.required' => 'कृपया अपनी जन्म तिथि दर्ज करें (Date of birth is required).',
            'date_of_birth.before_or_equal' => 'पंजीकरण केवल १८ वर्ष या उससे अधिक आयु के भक्तों के लिए अनुमत है (Registration is restricted to individuals 18 years or older).',
        ]);

        $otp = (string) random_int(100000, 999999);

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

        SupporterProfile::create([
            'user_id' => $user->id,
            'date_of_birth' => $request->date_of_birth,
        ]);

        Membership::create([
            'user_id' => $user->id,
            'membership_id' => 'SKP-MEM-'.strtoupper(Str::random(6)),
            'status' => 'inactive',
            'consecutive_qualifying_months' => 0,
        ]);

        EmailNotificationService::sendRegistrationOtp($user, $otp);

        Auth::login($user);

        return redirect('/register?step=2&email='.urlencode($user->email))
            ->with('success', "Verification OTP has been sent to {$user->email}. Please check your inbox.");
    }

    // Step 2: Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = auth()->user();

        if (! $user) {
            return redirect('/login');
        }

        if ($user->email_otp_expires_at && now()->isAfter($user->email_otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP कोड की समय सीमा समाप्त हो गई है। कृपया पुनः अनुरोध करें। (OTP has expired.)']);
        }

        if (empty($user->email_otp) || ! hash_equals((string) $user->email_otp, (string) $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid OTP code. Please check and try again.']);
        }

        $user->update([
            'email_verified_at' => now(),
            'email_otp' => null,
            'email_otp_expires_at' => null,
        ]);

        return redirect('/register?step=3')->with('success', 'Email verified successfully! Now complete your profile details.');
    }

    // Resend Registration Email OTP
    public function resendOtp(Request $request)
    {
        $user = auth()->user();

        if (! $user && $request->filled('email')) {
            $user = User::where('email', $request->email)->first();
        }

        if (! $user) {
            return redirect('/register')->withErrors(['email' => 'User not found. Please register again.']);
        }

        if ($user->email_verified_at) {
            return redirect('/dashboard')->with('info', 'Your email is already verified.');
        }

        $otp = (string) random_int(100000, 999999);

        $user->update([
            'email_otp' => $otp,
            'email_otp_expires_at' => now()->addMinutes(15),
        ]);

        EmailNotificationService::sendRegistrationOtp($user, $otp);

        return redirect('/register?step=2&email='.urlencode($user->email))
            ->with('success', "A new verification OTP has been sent to {$user->email}.");
    }

    // Step 3: Complete Profile Details & Photo
    public function completeProfile(Request $request)
    {
        $user = auth()->user();

        if (! $user) {
            return redirect('/login');
        }

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'father_name' => 'nullable|string|max:255',
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
                'profile_photo_path' => $photoPath ? asset('storage/'.$photoPath) : $user->profile?->profile_photo_path,
            ]
        );

        $this->linkGuestHistory($user);
        EmailNotificationService::sendWelcomeRegistration($user);

        return redirect('/dashboard')->with('success', 'Welcome to Shri Kamadgiri Peetham Sewa Trust family! Your profile registration is complete.');
    }

    /**
     * Automatically associate previous guest donations, volunteer applications, and pooja bookings.
     */
    protected function linkGuestHistory(User $user): void
    {
        if (empty($user->email)) {
            return;
        }

        // Link guest donations
        Donation::whereNull('user_id')
            ->where('donor_email', $user->email)
            ->update(['user_id' => $user->id]);

        // Link guest volunteer applications
        VolunteerApplication::whereNull('user_id')
            ->where('email', $user->email)
            ->update(['user_id' => $user->id]);

        // Link guest pooja bookings
        PoojaBooking::whereNull('user_id')
            ->where('email', $user->email)
            ->update(['user_id' => $user->id]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
