<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

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
        Model::preventLazyLoading(! app()->isProduction());

        RateLimiter::for('public-forms', function (Request $request): Limit {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('ai-explanations', function (Request $request): array {
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perDay(30)->by($request->ip()),
            ];
        });

        if ($this->settingsTableIsAvailable()) {
            $provider = SiteSetting::read('email.provider', 'log');
            if (in_array($provider, ['smtp', 'brevo'], true) && filled(SiteSetting::read('email.host')) && filled(SiteSetting::readSecret('email.password'))) {
                config(['mail.default' => 'smtp', 'mail.mailers.smtp.host' => SiteSetting::read('email.host'), 'mail.mailers.smtp.port' => (int) SiteSetting::read('email.port', 587), 'mail.mailers.smtp.username' => SiteSetting::read('email.username'), 'mail.mailers.smtp.password' => SiteSetting::readSecret('email.password'), 'mail.mailers.smtp.scheme' => SiteSetting::read('email.encryption') === 'ssl' ? 'smtps' : null, 'mail.from.address' => SiteSetting::read('email.from.address', config('mail.from.address')), 'mail.from.name' => SiteSetting::read('email.from.name', 'InvestAI')]);
            }
            View::composer('layouts.public', function ($view): void {
                $logo = SiteSetting::read('brand.logo');
                $favicon = SiteSetting::read('brand.favicon');
                $view->with(['brandName' => SiteSetting::read('brand.name', 'InvestAI'), 'brandLogo' => $logo ? Storage::disk('public')->url($logo) : '/images/investai-logo.png', 'brandFavicon' => $favicon ? Storage::disk('public')->url($favicon) : '/favicon.ico', 'defaultSeoDescription' => SiteSetting::read('seo.default.description', 'Make clearer investment decisions with AI-assisted research.'), 'footerDescription' => SiteSetting::read('footer.description', 'AI-assisted investment research designed to make goals, risk, evidence and fees easier to understand.'), 'settingsTheme' => SiteSetting::read('appearance.theme', 'ivory')]);
            });
        }
    }

    private function settingsTableIsAvailable(): bool
    {
        try {
            return Schema::hasTable('site_settings');
        } catch (Throwable) {
            // Composer package discovery and first-time setup run before a
            // database connection or the settings table necessarily exists.
            return false;
        }
    }
}
