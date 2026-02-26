<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\Data\PlayerBan;
use Zeropingheroes\SteamApis\SteamWebApiConnector;

it('returns player bans', function (string $steamid): void {
    $bans = app(SteamWebApiConnector::class)->getPlayerBans(steamids: $steamid);

    Assert::assertContainsOnlyInstancesOf(PlayerBan::class, $bans);

    $bans->each(function (PlayerBan $ban): void {
        Assert::assertSame($ban->steamid, $ban->steamid()->ConvertToUInt64());
    });
})->with('userids');

it('returns single player bans', function (string $steamid): void {
    $ban = app(SteamWebApiConnector::class)->getPlayerBan(steamid: $steamid);

    Assert::assertInstanceOf(PlayerBan::class, $ban);

    Assert::assertSame($ban->steamid, $ban->steamid()->ConvertToUInt64());
})->with('userids');
