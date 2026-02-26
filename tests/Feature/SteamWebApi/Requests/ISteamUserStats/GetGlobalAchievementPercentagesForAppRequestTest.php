<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\SteamWebApi\Data\AchievementPercentage;
use Zeropingheroes\SteamApis\SteamWebApi\SteamWebApiConnector;

it('returns global achievement percentages for game', function (int $gameid): void {
    $achievements = app(SteamWebApiConnector::class)->getGlobalAchievementPercentagesForApp(gameid: $gameid);

    Assert::assertContainsOnlyInstancesOf(AchievementPercentage::class, $achievements);

    $achievements->each(function (AchievementPercentage $achievement): void {
        Assert::assertNotEmpty($achievement->name);
        Assert::assertGreaterThanOrEqual(0, $achievement->percent);
        Assert::assertLessThanOrEqual(100, $achievement->percent);
    });
})->with('appids');
