<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Pengecualian validasi CSRF untuk route logout agar terhindar dari 419 Page Expired
        $middleware->validateCsrfTokens(except: [
            'logout',
        ]);

        //
        // Redirect unauthenticated users (guests) yang mencoba akses route protected
        // ke halaman login.
        //
        $middleware->redirectGuestsTo(fn (Request $request) => route('login'));

        //
        // Redirect authenticated users yang mencoba akses route guest (login/register)
        // langsung ke dashboard mereka masing-masing — mencegah redirect loop.
        //
        $middleware->redirectUsersTo(function (Request $request) {
            if (Auth::guard('admin')->check()) {
                return route('admin.dashboard');
            }

            if (Auth::guard('pelanggan')->check()) {
                return route('customer.dashboard');
            }

            return route('welcome');
        });

        // Alias middleware untuk kemudahan penggunaan di route
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
