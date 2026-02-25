<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\SteamConnector;

it('returns single player level', function (string $steamid): void {
    $level = app(SteamConnector::class)->getSteamLevel(steamid: $steamid);

    Assert::assertIsInt($level);
    Assert::assertGreaterThanOrEqual(0, $level);
})->with('userids');
