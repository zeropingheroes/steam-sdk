<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\Data\App;
use Zeropingheroes\SteamApis\SteamConnector;

it('returns app list', function (): void {
    $appList = app(SteamConnector::class)->getAppList();

    Assert::assertContainsOnlyInstancesOf(App::class, $appList);
});
