<?php

use Zeropingheroes\SteamApis\Data\StoreApp;
use Zeropingheroes\SteamApis\SteamConnector;
use PHPUnit\Framework\Assert;

it('returns app details', function (int $appid): void {
    $apps = app(SteamConnector::class)->appDetails($appid);

    Assert::assertInstanceOf(StoreApp::class, $apps);
})->with('appids');
