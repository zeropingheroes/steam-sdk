<?php

use Astrotomic\SteamSdk\Data\AppList;
use Astrotomic\SteamSdk\SteamConnector;
use PHPUnit\Framework\Assert;

it('returns app list', function (): void {
    $appList = app(SteamConnector::class)->getAppList();

    Assert::assertInstanceOf(AppList::class, $appList);
});
