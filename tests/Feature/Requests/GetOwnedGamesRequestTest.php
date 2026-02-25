<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\Data\OwnedApp;
use Zeropingheroes\SteamApis\SteamConnector;

it('returns a player\'s owned apps', function (string $steamid): void {
    $apps = app(SteamConnector::class)->getOwnedGames(steamid: $steamid);

    Assert::assertContainsOnlyInstancesOf(OwnedApp::class, $apps);

})->with('userids');
