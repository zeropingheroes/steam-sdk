<?php

namespace Zeropingheroes\SteamApis\Requests;

use Illuminate\Support\Collection;
use JsonException;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Request\CreatesDtoFromResponse;
use Zeropingheroes\SteamApis\Data\LocationCity;
use Zeropingheroes\SteamApis\Data\LocationCountry;
use Zeropingheroes\SteamApis\Data\LocationState;

class QueryLocationsRequest extends Request
{
    use CreatesDtoFromResponse;

    protected Method $method = Method::GET;

    public function __construct(
        public readonly ?string $countrycode = null,
        public readonly ?string $statecode = null,
    ) {}

    public function resolveEndpoint(): string
    {
        $query = '';

        if ($this->countrycode) {
            $query .= "/{$this->countrycode}";

            if ($this->statecode) {
                $query .= "/{$this->statecode}";
            }
        }

        return "/actions/QueryLocations{$query}";
    }

    /**
     * @return Collection<array-key, LocationCountry|LocationState|LocationCity>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        try {
            $items = $response->json() ?? [];
        } catch (JsonException) {
            $items = [];
        }

        return collect(match (true) {
            ! $this->countrycode && ! $this->statecode => LocationCountry::collect($items),
            $this->countrycode && ! $this->statecode => LocationState::collect($items),
            $this->countrycode && $this->statecode => LocationCity::collect($items),
        });
    }
}
