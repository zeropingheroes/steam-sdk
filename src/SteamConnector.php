<?php

namespace Zeropingheroes\SteamApis;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use xPaw\Steam\SteamID;
use Zeropingheroes\SteamApis\Data\AchievementPercentage;
use Zeropingheroes\SteamApis\Data\ApiInterface;
use Zeropingheroes\SteamApis\Data\App;
use Zeropingheroes\SteamApis\Data\Friend;
use Zeropingheroes\SteamApis\Data\LocationCity;
use Zeropingheroes\SteamApis\Data\LocationCountry;
use Zeropingheroes\SteamApis\Data\LocationState;
use Zeropingheroes\SteamApis\Data\NewsItem;
use Zeropingheroes\SteamApis\Data\OwnedApp;
use Zeropingheroes\SteamApis\Data\PlayerBan;
use Zeropingheroes\SteamApis\Data\PlayerSummary;
use Zeropingheroes\SteamApis\Data\RecentlyPlayedApp;
use Zeropingheroes\SteamApis\Data\StoreApp;
use Zeropingheroes\SteamApis\Enums\Relationship;
use Zeropingheroes\SteamApis\Enums\VanityType;
use Zeropingheroes\SteamApis\Requests\AppDetailsRequest;
use Zeropingheroes\SteamApis\Requests\GetAppListRequest;
use Zeropingheroes\SteamApis\Requests\GetFriendListRequest;
use Zeropingheroes\SteamApis\Requests\GetGlobalAchievementPercentagesForAppRequest;
use Zeropingheroes\SteamApis\Requests\GetNewsForAppRequest;
use Zeropingheroes\SteamApis\Requests\GetOwnedGamesRequest;
use Zeropingheroes\SteamApis\Requests\GetPlayerBansRequest;
use Zeropingheroes\SteamApis\Requests\GetPlayerSummariesRequest;
use Zeropingheroes\SteamApis\Requests\GetRecentlyPlayedGamesRequest;
use Zeropingheroes\SteamApis\Requests\GetSteamLevelRequest;
use Zeropingheroes\SteamApis\Requests\GetSupportedApiListRequest;
use Zeropingheroes\SteamApis\Requests\QueryLocationsRequest;
use Zeropingheroes\SteamApis\Requests\ResolveVanityUrlRequest;

class SteamConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    public function __construct(
        public readonly ?string $apiKey = null,
    ) {}

    public function resolveBaseUrl(): string
    {
        return 'https://api.steampowered.com';
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'key' => $this->apiKey,
            'format' => 'json',
        ]);
    }

    /**
     * @return Collection<array-key, ApiInterface>
     */
    public function getSupportedApiList(): Collection
    {
        return $this->send(
            new GetSupportedApiListRequest
        )->dtoOrFail();
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

    /**
     * @return Collection<array-key, NewsItem>
     */
    public function getNewsForApp(
        int $appid,
        ?int $maxlength = null,
        ?CarbonInterface $enddate = null,
        ?int $count = null,
        ?array $feeds = null,
        ?array $tags = null,
    ): Collection {
        return $this->send(
            new GetNewsForAppRequest($appid, $maxlength, $enddate, $count, $feeds, $tags)
        )->dtoOrFail();
    }

    /**
     * @return Collection<array-key, AchievementPercentage>
     */
    public function getGlobalAchievementPercentagesForApp(int $gameid): Collection
    {
        return $this->send(
            new GetGlobalAchievementPercentagesForAppRequest($gameid)
        )->dtoOrFail();
    }

    /**
     * @param  array<array-key, string>|string  $steamids
     * @return Collection<array-key, PlayerSummary>
     */
    public function getPlayerSummaries(array|string $steamids): Collection
    {
        return $this->send(
            new GetPlayerSummariesRequest($steamids)
        )->dtoOrFail();
    }

    public function getPlayerSummary(string $steamid): ?PlayerSummary
    {
        return $this->getPlayerSummaries($steamid)->firstWhere('steamid', $steamid);
    }

    /**
     * @return Collection<array-key, Friend>
     */
    public function getFriendList(string $steamid, ?Relationship $relationship = null): Collection
    {
        return $this->send(
            new GetFriendListRequest($steamid, $relationship)
        )->dtoOrFail();
    }

    /**
     * @param  array<array-key, string>|string  $steamids
     * @return Collection<array-key, PlayerBan>
     */
    public function getPlayerBans(array|string $steamids): Collection
    {
        return $this->send(
            new GetPlayerBansRequest($steamids)
        )->dtoOrFail();
    }

    public function getPlayerBan(string $steamid): ?PlayerBan
    {
        return $this->getPlayerBans($steamid)->firstWhere('steamid', $steamid);
    }

    public function getSteamLevel(string $steamid): int
    {
        return $this->send(
            new GetSteamLevelRequest($steamid)
        )->dtoOrFail();
    }

    /**
     * @return Collection<array-key, OwnedApp>
     */
    public function getOwnedGames(string $steamid,
        ?bool $include_appinfo = false,
        ?bool $include_extended_appinfo = null,
        ?bool $include_played_free_games = null, ): Collection
    {
        return $this->send(
            new GetOwnedGamesRequest(
                $steamid,
                $include_appinfo,
                $include_extended_appinfo,
                $include_played_free_games,
            )
        )->dtoOrFail();
    }

    /**
     * @return Collection<array-key, RecentlyPlayedApp>
     */
    public function getRecentlyPlayedGames(
        string $steamid,
        ?int $count = 0,
    ): Collection {
        return $this->send(
            new GetRecentlyPlayedGamesRequest(
                $steamid,
                $count
            )
        )->dtoOrFail();
    }

    /**
     * @return array<App>
     */
    public function getAppList(
        ?int $if_modified_since = null,
        ?bool $include_games = null,
        ?bool $include_dlc = null,
        ?bool $include_software = null,
        ?bool $include_videos = null,
        ?bool $include_hardware = null,
        ?string $have_description_language = null,
    ): array {
        return $this->send(
            new GetAppListRequest(
                $if_modified_since,
                $include_games,
                $include_dlc,
                $include_software,
                $include_videos,
                $include_hardware,
                $have_description_language,
            )
        )->dtoOrFail();
    }

    public function appDetails(int $appid, ?string $countrycode = null): StoreApp
    {
        return $this->send(
            new AppDetailsRequest($appid, $countrycode)
        )->dtoOrFail();
    }

    public function resolveVanityUrl(string $vanityurl): ?SteamID
    {
        try {
            return SteamID::SetFromURL($vanityurl, function (string $vanityurl, int $type): ?string {
                return $this->send(
                    new ResolveVanityUrlRequest($vanityurl, VanityType::from($type))
                )->json('response.steamid');
            });
        } catch (InvalidArgumentException) {
            return null;
        }
    }
}
