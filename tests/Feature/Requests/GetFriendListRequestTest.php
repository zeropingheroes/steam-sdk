<?php

use PHPUnit\Framework\Assert;
use Saloon\Exceptions\Request\Statuses\UnauthorizedException;
use Zeropingheroes\SteamApis\Data\Friend;
use Zeropingheroes\SteamApis\SteamWebApiConnector;

it('returns friend list', function (string $steamid): void {
    try {
        $friends = app(SteamWebApiConnector::class)->getFriendList(steamid: $steamid);

        Assert::assertContainsOnlyInstancesOf(Friend::class, $friends);

        $friends->each(function (Friend $friend): void {
            Assert::assertSame($friend->steamid, $friend->steamid()->ConvertToUInt64());
        });
    } catch (UnauthorizedException $exception) {
        Assert::assertInstanceOf(UnauthorizedException::class, $exception);
    }
})->with('userids');
