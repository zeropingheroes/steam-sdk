<?php

namespace Zeropingheroes\SteamApis\SteamWebApi;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Saloon\Http\Auth\QueryAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use xPaw\Steam\SteamID;
use Zeropingheroes\SteamApis\SteamCommunityApi\Data\LocationCity;
use Zeropingheroes\SteamApis\SteamCommunityApi\Data\LocationCountry;
use Zeropingheroes\SteamApis\SteamCommunityApi\Data\LocationState;
use Zeropingheroes\SteamApis\SteamCommunityApi\Requests\QueryLocationsRequest;
use Zeropingheroes\SteamApis\SteamStoreApi\Data\App;
use Zeropingheroes\SteamApis\SteamWebApi\Data\AchievementPercentage;
use Zeropingheroes\SteamApis\SteamWebApi\Data\ApiInterface;
use Zeropingheroes\SteamApis\SteamWebApi\Data\Friend;
use Zeropingheroes\SteamApis\SteamWebApi\Data\NewsItem;
use Zeropingheroes\SteamApis\SteamWebApi\Data\OwnedApp;
use Zeropingheroes\SteamApis\SteamWebApi\Data\PlayerBan;
use Zeropingheroes\SteamApis\SteamWebApi\Data\PlayerSummary;
use Zeropingheroes\SteamApis\SteamWebApi\Data\RecentlyPlayedApp;
use Zeropingheroes\SteamApis\SteamWebApi\Enums\Relationship;
use Zeropingheroes\SteamApis\SteamWebApi\Enums\VanityType;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\IPlayerService\GetOwnedGamesRequest;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\IPlayerService\GetRecentlyPlayedGamesRequest;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\IPlayerService\GetSteamLevelRequest;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\ISteamNews\GetNewsForAppRequest;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\ISteamUser\GetFriendListRequest;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\ISteamUser\GetPlayerBansRequest;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\ISteamUser\GetPlayerSummariesRequest;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\ISteamUser\ResolveVanityUrlRequest;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\ISteamUserStats\GetGlobalAchievementPercentagesForAppRequest;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\ISteamWebAPIUtil\GetSupportedApiListRequest;
use Zeropingheroes\SteamApis\SteamWebApi\Requests\IStoreService\GetAppListRequest;

class SteamWebApiConnector extends Connector
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

    protected function defaultAuth(): QueryAuthenticator
    {
        return new QueryAuthenticator('key', $this->apiKey);
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
        ?bool $include_played_free_games = null,
    ): Collection {
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
