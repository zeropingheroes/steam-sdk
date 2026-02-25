# Steam APIs

Interface with Steam's APIs using PHP.

Forked with ❤️ from [Astrotomic/steam-sdk](https://github.com/Astrotomic/steam-sdk).

## Installation

```bash
composer require astrotomic/steam-sdk
```

In your Laravel project's `config/services.php` add your [Steam API key](https://steamcommunity.com/dev/apikey):

```php
<?php

return [
    'steam' => [
        'api_key' => env('STEAM_API_KEY'),
    ],
];
```

## Usage example

```php
$steam = app(\Zeropingheroes\SteamApis\SteamConnector::class);

$steam->getPlayerSummaries($steamid);
```

### Implemented requests

| Steam API     | Steam Interface    | Method                                    |
|---------------|--------------------|-------------------------------------------|
| Web API       | `ISteamApps`       | `getAppList()`                            |
| Web API       | `ISteamNews`       | `getNewsForApp()`                         |
| Web API       | `ISteamUser`       | `getFriendList()`                         |
| Web API       | `ISteamUser`       | `getPlayerBans()`                         |
| Web API       | `ISteamUser`       | `getPlayerSummaries()`                    |
| Web API       | `ISteamUser`       | `resolveVanityUrl()`                      |
| Web API       | `IPlayerService`   | `getRecentlyPlayedGames()`                |
| Web API       | `IPlayerService`   | `getSteamLevel()`                         |
| Web API       | `ISteamUserStats`  | `getGlobalAchievementPercentagesForApp()` |
| Web API       | `ISteamWebAPIUtil` | `getSupportedApiList()`                   |
| Community API | ?                  | `queryLocations()`                        |

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
