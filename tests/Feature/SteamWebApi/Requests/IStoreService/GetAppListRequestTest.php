<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\SteamWebApi\Data\App;
use Zeropingheroes\SteamApis\SteamWebApi\SteamWebApiConnector;

it('returns app list', function (): void {
    $appList = app(SteamWebApiConnector::class)->getAppList();

    Assert::assertContainsOnlyInstancesOf(App::class, $appList);
});
