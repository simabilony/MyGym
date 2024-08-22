<?php

namespace App\Http;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'role' => \App\Http\Middleware\CheckUserRole::class
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(command: 'SendCartReminders')->daily();
        $schedule->command('command: Send PromotionalEmails')->weeklyon(1, time: '8:00');
        $schedule->command('command: CleanOldRecords')->monthly();
        $schedule->command('Command: SendCartReminders')->daily()->withoutOverlapping();
        $schedule->command('command: FirstTask')->daily()->then(callback: function () {
            Artisan::call(command: 'SecondTask');
        });
        $schedule->command(command: 'command: SendCartReminders')->daily()->onQueue('emails');
        $schedule->command( command: 'command: sendEmails')->daily()->onSuccess(callback: function () {
            Log::info('Emails sent successfully.');
        })
        ->onFailure( function () {
         Log::error(message: 'Failed to send emails.');
            });
        $schedule->command(command: 'command: sendEmails')->dailyAt( '14:00')->timezone ( 'Asia/Damascus');
        $schedule->command( 'command: cleanupOldFiles')->daily()->appendOutputTo( '/path/to/output.log');
        $schedule->command('command: optimizeDatabase')->daily()->before( callback: function () {
        Log::info( message: 'Starting database optimization.');
})
        ->after (function () {
    Log::info ( 'Finished database optimization.');
});
        $schedule->command('command generateReports')->daily()->runInBackground();

    }
}
