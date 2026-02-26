<?php

namespace Zeropingheroes\SteamApis;

use Illuminate\Support\Collection;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Zeropingheroes\SteamApis\Data\LocationCity;
use Zeropingheroes\SteamApis\Data\LocationCountry;
use Zeropingheroes\SteamApis\Data\LocationState;
use Zeropingheroes\SteamApis\Requests\QueryLocationsRequest;

class SteamCommunityApiConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    public function __construct() {}

    public function resolveBaseUrl(): string
    {
        return 'https://steamcommunity.com';
    }

    /**
     * @return Collection<array-key, LocationCountry|LocationState|LocationCity>
     */
    public function queryLocations(?string $countrycode = null, ?string $statecode = null): Collection
    {
        return $this->send(
            new QueryLocationsRequest($countrycode, $statecode)
        )->dtoOrFail();
    }
}
