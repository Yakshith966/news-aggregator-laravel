<?php

namespace App\Providers;

use App\Models\User;
use App\Services\NewsAPIService;
use App\Services\NewsApiServiceInterface;
use App\Services\NYTimesService;
use App\Services\TheGuardianService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(NewsApiServiceInterface::class, function ($app, $params) {
            switch ($params['service'] ?? null) {
                case 'nytimes':
                    return $app->make(NYTimesService::class);
                case 'guardian':
                    return $app->make(TheGuardianService::class);
                default:
                    return $app->make(NewsAPIService::class);
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->configureResetPasswordURL();
        $this->configureResetPasswordMail();
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                optional($request->user())->id ?: $request->ip()
            );
        });
    }

    protected function configureResetPasswordURL(): void
    {
        ResetPassword::createUrlUsing(function (User $user, string $token) {
            return $token;
        });
    }

    protected function configureResetPasswordMail(): void
    {
        ResetPassword::toMailUsing(function (User $user, string $token) {
            return (new MailMessage)
                ->subject(Lang::get('Reset Password Notification'))
                ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
                ->action(Lang::get('Reset Password'), $token)
                ->line(Lang::get('This password reset token will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
                ->line(Lang::get('Please copy the provided token add send it with passwrod reset api'))
                ->line(Lang::get('If you did not request a password reset, no further action is required.'));
        });
    }
}
