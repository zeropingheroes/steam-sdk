<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\Data\OwnedApp;
use Zeropingheroes\SteamApis\SteamWebApiConnector;

it('returns a player\'s owned apps', function (string $steamid): void {
    $apps = app(SteamWebApiConnector::class)->getOwnedGames(steamid: $steamid);

    Assert::assertContainsOnlyInstancesOf(OwnedApp::class, $apps);

})->with('userids');
