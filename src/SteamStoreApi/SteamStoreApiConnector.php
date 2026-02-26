<?php

namespace Zeropingheroes\SteamApis\SteamStoreApi;

use Saloon\Http\Connector;
use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Limit;
use Saloon\RateLimitPlugin\Stores\FileStore;
use Saloon\RateLimitPlugin\Traits\HasRateLimits;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Zeropingheroes\SteamApis\SteamStoreApi\Data\App;
use Zeropingheroes\SteamApis\SteamStoreApi\Requests\AppDetailsRequest;

class SteamStoreApiConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use HasRateLimits;

    public function __construct(
        public readonly string $rateLimitStorePath,
    ) {}

    public function resolveBaseUrl(): string
    {
        return 'https://store.steampowered.com/api';
    }

    protected function resolveLimits(): array
    {
        return [
            Limit::allow(200)->everyFiveMinutes(),
        ];
    }

    protected function getLimiterPrefix(): ?string
    {
        return 'store.steampowered.com';
    }

    protected function resolveRateLimitStore(): RateLimitStore
    {
        return new FileStore($this->rateLimitStorePath);
    }

    public function appDetails(int $appid, ?string $countrycode = null): App
    {
        return $this->send(
            new AppDetailsRequest($appid, $countrycode)
        )->dtoOrFail();
    }
}
