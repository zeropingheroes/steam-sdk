<?php

use Zeropingheroes\SteamApis\Data\OwnedApp;
use Zeropingheroes\SteamApis\SteamConnector;
use PHPUnit\Framework\Assert;

it('returns a player\'s owned apps', function (string $steamid): void {
    $apps = app(SteamConnector::class)->getOwnedGames(steamid: $steamid);

    Assert::assertContainsOnlyInstancesOf(OwnedApp::class, $apps);

})->with('userids');
