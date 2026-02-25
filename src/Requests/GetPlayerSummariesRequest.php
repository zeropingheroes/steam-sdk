<?php

namespace Zeropingheroes\SteamApis\Requests;

use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Request\CreatesDtoFromResponse;
use Zeropingheroes\SteamApis\Data\PlayerSummary;

class GetPlayerSummariesRequest extends Request
{
    use CreatesDtoFromResponse;

    protected Method $method = Method::GET;

    public function __construct(
        public readonly array|string $steamids,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/ISteamUser/GetPlayerSummaries/v2';
    }

    public function defaultQuery(): array
    {
        return [
            'steamids' => implode(',', (array) $this->steamids),
        ];
    }

    /**
     * @return Collection<array-key, PlayerSummary>
     */
    public function createDtoFromResponse(Response $response): Collection
    {
        return new Collection(PlayerSummary::collect($response->json('response.players')));
    }
}
