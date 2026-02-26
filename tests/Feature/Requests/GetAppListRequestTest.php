<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\Data\App;
use Zeropingheroes\SteamApis\SteamWebApiConnector;

it('returns app list', function (): void {
    $appList = app(SteamWebApiConnector::class)->getAppList();

    Assert::assertContainsOnlyInstancesOf(App::class, $appList);
});
