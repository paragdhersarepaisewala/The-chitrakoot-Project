<?php

namespace App\Http\Middleware;

use App\Models\OrganizationSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDevoteeVerifiedIfSmtpConfigured
{
    /**
     * Handle an incoming request.
     * Enforce email OTP verification when SMTP mailer is active and configured.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && is_null($user->email_verified_at)) {
            // Administrative staff bypass devotee email OTP requirement
            if ($user->hasRole('super_admin', 'content_admin', 'donation_admin', 'membership_admin', 'media_editor')) {
                return $next($request);
            }

            $orgSettings = OrganizationSetting::getSettings();
            if ($orgSettings->isSmtpConfigured()) {
                return redirect('/register?step=2&email='.urlencode($user->email))
                    ->with('warning', 'डैशबोर्ड में प्रवेश करने से पूर्व कृपया अपने ईमेल पर प्राप्त 6-अंकीय OTP सत्यापित करें। (Please verify your email OTP before accessing dashboard).');
            }
        }

        return $next($request);
    }
}
