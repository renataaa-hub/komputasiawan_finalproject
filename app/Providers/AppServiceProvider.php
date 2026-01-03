<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /**
         * FIX "Form is not secure" di Azure App Service / reverse proxy.
         * Laravel kadang ngira request itu http, jadi form action jadi http.
         */
        if (app()->environment('production')) {
            // Paksa semua URL yang digenerate Laravel jadi https
            URL::forceScheme('https');

            // Biar Request::isSecure() juga true saat ada proxy (Azure) yang kirim header ini
            Request::setTrustedProxies(
                ['*'],
                Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
            );
        }
    }
}