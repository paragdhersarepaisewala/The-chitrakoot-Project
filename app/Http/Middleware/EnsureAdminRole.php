<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('login')->withErrors(['email' => 'Please login to access the administration portal.']);
        }

        if ($user->is_active === false || $user->approval_status === 'rejected') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Account is inactive or suspended.'], 403);
            }

            abort(403, 'खाता निष्क्रिय या निलंबित है। (Access denied: Account is inactive.)');
        }

        // Verify if the user possesses one of the required administrative roles
        $allowedRoles = ! empty($roles)
            ? $roles
            : ['super_admin', 'content_admin', 'donation_admin', 'membership_admin', 'media_editor'];

        if (! $user->hasRole(...$allowedRoles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Administrative credentials required.'], 403);
            }

            abort(403, 'अनधिकृत प्रवेश: इस अनुभाग के लिए प्रशासनिक विशेषाधिकार आवश्यक हैं। (Access denied: Administrative privileges required.)');
        }

        return $next($request);
    }
}
