<?php

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
        // VS Code Port Forwarding meneruskan host/protokol publik melalui proxy lokal.
        $middleware->trustProxies(at: ['127.0.0.1', '::1']);

        $middleware->redirectUsersTo(function (Request $request): string {
            $role = $request->user()?->role;

            return in_array($role, [
                'superadmin',
                'kepalabpmp',
                'kasubag',
                'adminpersediaan',
                'adminsarpras',
                'adminasettetap',
                'pegawai',
                'tamu',
            ], true) ? "/{$role}/dashboard" : '/';
        });

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'checkrole' => \App\Http\Middleware\CheckRole::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, Request $request) {
            return redirect()->route('login')->with('info', 'Sesi Anda telah berakhir. Silakan login kembali.');
        });
    })->create();
