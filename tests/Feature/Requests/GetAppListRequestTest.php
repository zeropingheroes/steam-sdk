<?php

use Zeropingheroes\SteamApis\Data\App;
use Zeropingheroes\SteamApis\SteamConnector;
use PHPUnit\Framework\Assert;

it('returns app list', function (): void {
    $appList = app(SteamConnector::class)->getAppList();

    Assert::assertContainsOnlyInstancesOf(App::class, $appList);
});
