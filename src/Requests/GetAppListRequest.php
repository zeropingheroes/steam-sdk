<?php

namespace Astrotomic\SteamSdk\Requests;

use Astrotomic\SteamSdk\Data\App;
use Astrotomic\SteamSdk\Data\AppList;
use Illuminate\Support\Collection;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Request\CreatesDtoFromResponse;

class GetAppListRequest extends Request
{
    use CreatesDtoFromResponse;

    protected Method $method = Method::GET;

    public function __construct(
        public readonly ?int $max_results = null,
        public readonly ?int $last_appid = null,
        public readonly ?int $if_modified_since = null,
        public readonly ?bool $include_games = null,
        public readonly ?bool $include_dlc = null,
        public readonly ?bool $include_software = null,
        public readonly ?bool $include_videos = null,
        public readonly ?bool $include_hardware = null,
        public readonly ?string $have_description_language = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/IStoreService/GetAppList/v1';
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'max_results' => $this->max_results,
            'last_appid' => $this->last_appid,
            'if_modified_since' => $this->if_modified_since,
            'include_games' => $this->include_games,
            'include_dlc' => $this->include_dlc,
            'include_software' => $this->include_software,
            'include_videos' => $this->include_videos,
            'include_hardware' => $this->include_hardware,
            'have_description_language' => $this->have_description_language,
        ]);
    }

    /**
     * @return AppList
     */
    public function createDtoFromResponse(Response $response): AppList
    {
        $apps = new Collection(App::collect($response->json('response.apps')));
        $have_more_results = $response->json('response.have_more_results');
        $last_appid = $response->json('response.last_appid');
        return new AppList($apps, $have_more_results, $last_appid);
    }
}
