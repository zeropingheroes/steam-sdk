<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\SteamStoreApi\Data\App;
use Zeropingheroes\SteamApis\SteamStoreApi\SteamStoreApiConnector;

it('returns app details', function (int $appid): void {
    $apps = app(SteamStoreApiConnector::class)->appDetails($appid);

    Assert::assertInstanceOf(App::class, $apps);
})->with('appids');
