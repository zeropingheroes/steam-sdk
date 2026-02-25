<?php

use Astrotomic\PhpunitAssertions\UrlAssertions;
use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\Data\PlayerSummary;
use Zeropingheroes\SteamApis\SteamConnector;

it('returns player summaries', function (string $steamid): void {
    $players = app(SteamConnector::class)->getPlayerSummaries(steamids: $steamid);

    Assert::assertContainsOnlyInstancesOf(PlayerSummary::class, $players);

    $players->each(function (PlayerSummary $player): void {
        Assert::assertSame($player->steamid, $player->steamid()->ConvertToUInt64());
        UrlAssertions::assertValidLoose($player->profileurl);
        UrlAssertions::assertValidLoose($player->avatar);
        UrlAssertions::assertValidLoose($player->avatarmedium);
        UrlAssertions::assertValidLoose($player->avatarfull);
    });
})->with('userids');

it('returns single player summary', function (string $steamid): void {
    $player = app(SteamConnector::class)->getPlayerSummary(steamid: $steamid);

    Assert::assertInstanceOf(PlayerSummary::class, $player);

    Assert::assertSame($player->steamid, $player->steamid()->ConvertToUInt64());
    UrlAssertions::assertValidLoose($player->profileurl);
    UrlAssertions::assertValidLoose($player->avatar);
    UrlAssertions::assertValidLoose($player->avatarmedium);
    UrlAssertions::assertValidLoose($player->avatarfull);
})->with('userids');
