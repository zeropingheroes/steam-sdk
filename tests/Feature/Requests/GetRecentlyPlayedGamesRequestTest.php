<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\SteamWebApi\Data\RecentlyPlayedApp;
use Zeropingheroes\SteamApis\SteamWebApi\SteamWebApiConnector;

it('returns a player\'s recently played apps', function (string $steamid): void {
    $apps = app(SteamWebApiConnector::class)->getRecentlyPlayedGames(steamid: $steamid);

    Assert::assertContainsOnlyInstancesOf(RecentlyPlayedApp::class, $apps);

})->with('userids');

it('returns an empty collection', function (string $steamid): void {
    $apps = app(SteamWebApiConnector::class)->getRecentlyPlayedGames(steamid: $steamid);

    Assert::assertEmpty($apps);

})->with('privateuserids');
