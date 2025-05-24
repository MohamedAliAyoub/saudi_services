<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            $url = $request->url();

            // Log for debug if needed
            // \Log::info("Expired session redirect from: $url");

            if (str_contains($url, '/admin')) {
                return redirect()->route('filament.admin.auth.login')->with('error', 'Your session has expired. Please log in again.');
            }

            if (str_contains($url, '/client')) {
                return redirect()->route('filament.client.auth.login')->with('error', 'Your session has expired. Please log in again.');
            }

            if (str_contains($url, '/employee')) {
                return redirect()->route('filament.employee.auth.login')->with('error', 'Your session has expired. Please log in again.');
            }

            // Fallback (optional)
            return redirect('/login')->with('error', 'Your session has expired. Please log in again.');
        }

        return parent::render($request, $e);
    }
}
