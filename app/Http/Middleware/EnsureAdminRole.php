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
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('login')->withErrors(['email' => 'Please login to access the administration portal.']);
        }

        // Verify if the user possesses an administrative role
        $adminRoles = ['super_admin', 'content_admin', 'donation_admin', 'membership_admin', 'media_editor'];

        if (! $user->hasRole(...$adminRoles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Administrative credentials required.'], 403);
            }

            abort(403, 'अनधिकृत प्रवेश: इस अनुभाग के लिए प्रशासनिक विशेषाधिकार आवश्यक हैं। (Access denied: Administrative privileges required.)');
        }

        return $next($request);
    }
}
