<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\Data\StoreApp;
use Zeropingheroes\SteamApis\SteamWebApiConnector;

it('returns app details', function (int $appid): void {
    $apps = app(SteamWebApiConnector::class)->appDetails($appid);

    Assert::assertInstanceOf(StoreApp::class, $apps);
})->with('appids');
