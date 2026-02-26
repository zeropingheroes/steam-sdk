<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\SteamWebApi\SteamWebApiConnector;

it('returns single player level', function (string $steamid): void {
    $level = app(SteamWebApiConnector::class)->getSteamLevel(steamid: $steamid);

    Assert::assertIsInt($level);
    Assert::assertGreaterThanOrEqual(0, $level);
})->with('userids');
