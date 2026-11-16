<?php

use App\Http\Middleware\EnsureAdminRole;
use App\Http\Middleware\EnsureDevoteeVerifiedIfSmtpConfigured;
use App\Http\Middleware\SetSecurityHeaders;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->append(SetSecurityHeaders::class);
        $middleware->validateCsrfTokens(except: [
            'api/donations/webhook',
        ]);
        $middleware->alias([
            'admin' => EnsureAdminRole::class,
            'devotee.verified' => EnsureDevoteeVerifiedIfSmtpConfigured::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Site-wide Graceful Handling for Database Out-of-Range & Overflow Errors
        $exceptions->render(function (QueryException|PDOException $e, Request $request) {
            $msg = $e->getMessage();
            $code = (string) $e->getCode();
            $errorInfo = property_exists($e, 'errorInfo') && is_array($e->errorInfo) ? $e->errorInfo : [];
            $driverCode = isset($errorInfo[1]) ? (int) $errorInfo[1] : 0;
            $sqlState = $errorInfo[0] ?? $code;

            $isOutOfRange = (
                $sqlState === '22003' ||
                $driverCode === 1264 ||
                $driverCode === 1690 ||
                str_contains($msg, '22003') ||
                str_contains($msg, 'Numeric value out of range') ||
                str_contains($msg, 'Out of range value') ||
                str_contains($msg, 'overflow')
            );

            $isDataTruncated = (
                $sqlState === '22001' ||
                $driverCode === 1406 ||
                str_contains($msg, 'Data too long') ||
                str_contains($msg, 'Data truncated')
            );

            if (! $isOutOfRange && ! $isDataTruncated) {
                return null;
            }

            $errorMessage = $isOutOfRange
                ? 'दर्ज किया गया संख्यात्मक मान या राशि अनुमत अधिकतम सीमा से अधिक है। कृपया एक मान्य संख्या दर्ज करें। (The entered numeric value exceeds the maximum allowable range).'
                : 'दर्ज किया गया विवरण अनुमत अक्षर सीमा से अधिक लंबा है। कृपया संक्षिप्त विवरण दर्ज करें। (The entered text exceeds the maximum character length).';

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => $isOutOfRange ? 'numeric_value_out_of_range' : 'data_too_long',
                ], 422);
            }

            $errorBag = ['general' => $errorMessage];
            foreach (['quotation_amount', 'amount', 'target_amount', 'price', 'minimum_amount', 'sort_order', 'phone', 'quantity'] as $field) {
                if ($request->filled($field)) {
                    $errorBag[$field] = $errorMessage;
                }
            }

            return back(fallback: route('home'))
                ->withInput($request->except(['password', 'password_confirmation', '_token']))
                ->withErrors($errorBag)
                ->with('error', $errorMessage);
        });
    })->create();
