# Steam APIs

[![PHP Version](https://img.shields.io/packagist/dependency-v/zeropingheroes/steam-apis/php?style=flat-square)](https://packagist.org/packages/zeropingheroes/steam-apis)
[![Laravel Version](https://img.shields.io/packagist/dependency-v/zeropingheroes/steam-apis/illuminate/support?style=flat-square&label=Laravel)](https://packagist.org/packages/zeropingheroes/steam-apis)

Interface with Steam's APIs using PHP.

Forked with ❤️ from [Astrotomic/steam-sdk](https://github.com/Astrotomic/steam-sdk).

## Installation

```bash
composer require zeropingheroes/steam-apis
```

In your Laravel project's `config/services.php` add your [Steam API key](https://steamcommunity.com/dev/apikey):

```php
<?php

return [
    'steam' => [
        'api_key' => env('STEAM_API_KEY'),
        'rate_limit_store_path' => storage_path('app/steam-api'),
    ],
];
```

## Usage example

```php
use Zeropingheroes\SteamApis\SteamWebApiConnector\SteamWebApiConnector;
use Zeropingheroes\SteamApis\SteamCommunityApi\SteamCommunityApiConnector;
use Zeropingheroes\SteamApis\SteamStoreApi\SteamStoreApiConnector;

$steamWebApi = app(SteamWebApiConnector::class);
$playerSummary = $steamWebApi->getPlayerSummaries(steamids: [76561197960287930, 76561198061912622]);

$steamCommunityApi = app(SteamCommunityApiConnector::class);
$locations = $steamCommunityApi->queryLocations(countrycode: 'DE');

$steamStoreApi = app(SteamStoreApiConnector::class);
$appDetails = $steamStoreApi->appDetails(appid: 440);
```

### Implemented requests

| Steam API     | Steam Interface    | Class Method                              | Connector Class              |
|---------------|--------------------|-------------------------------------------|------------------------------|
| Web API       | `ISteamApps`       | `getAppList()`                            | `SteamWebApiConnector`       |
| Web API       | `ISteamNews`       | `getNewsForApp()`                         | `SteamWebApiConnector`       |
| Web API       | `ISteamUser`       | `getFriendList()`                         | `SteamWebApiConnector`       |
| Web API       | `ISteamUser`       | `getPlayerBans()`                         | `SteamWebApiConnector`       |
| Web API       | `ISteamUser`       | `getPlayerSummaries()`                    | `SteamWebApiConnector`       |
| Web API       | `ISteamUser`       | `resolveVanityUrl()`                      | `SteamWebApiConnector`       |
| Web API       | `IPlayerService`   | `getRecentlyPlayedGames()`                | `SteamWebApiConnector`       |
| Web API       | `IPlayerService`   | `getSteamLevel()`                         | `SteamWebApiConnector`       |
| Web API       | `ISteamUserStats`  | `getGlobalAchievementPercentagesForApp()` | `SteamWebApiConnector`       |
| Web API       | `ISteamWebAPIUtil` | `getSupportedApiList()`                   | `SteamWebApiConnector`       |
| Community API | ?                  | `queryLocations()`                        | `SteamCommunityApiConnector` |
| Store API     | ?                  | `appDetails()`                            | `SteamStoreApiConnector`     |

For more information on Steam's APIs, see:
- https://partner.steamgames.com/doc/webapi
- https://steamapi.xpaw.me/
- https://steamcommunity.com/dev
- https://developer.valvesoftware.com/wiki/Steam_Web_API

## Contributing

Fork the repository, implement one feature or bugfix in a new branch and send a pull request.

Good examples of first contributions are implementing missing requests or fixing bugs.

This project uses [Saloon](https://docs.saloon.dev/) to implement Steam API requests.

## License

The MIT License (MIT). See [License File](LICENSE.md) for more information.
