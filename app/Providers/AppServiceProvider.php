<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * The "login" limiter is referenced by the `throttle:login` middleware
     * on the POST /login route.  It allows 5 attempts per minute, keyed by
     * the client's IP address.  The per-account lockout (email + IP) is
     * handled separately inside LoginRequest::ensureIsNotRateLimited().
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(config('security.login.throttle_per_minute', 10))
                ->by($request->ip())
                ->response(function () {
                    return back()->withErrors([
                        'email' => 'Too many login attempts. Please wait a minute before trying again.',
                    ])->onlyInput('email');
                });
        });
    }
}
