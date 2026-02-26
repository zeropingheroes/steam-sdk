<?php

namespace Zeropingheroes\SteamApis;

use Illuminate\Support\ServiceProvider;

class SteamSdkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SteamWebApiConnector::class, function (): SteamWebApiConnector {
            return new SteamWebApiConnector(
                apiKey: config('services.steam.api_key'),
            );
        });
        $this->app->bind(SteamCommunityApiConnector::class, function (): SteamCommunityApiConnector {
            return new SteamCommunityApiConnector;
        });
    }
}
