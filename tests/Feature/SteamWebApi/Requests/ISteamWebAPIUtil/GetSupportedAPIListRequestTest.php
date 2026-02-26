<?php

use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\SteamWebApi\Data\ApiInterface;
use Zeropingheroes\SteamApis\SteamWebApi\Data\ApiMethod;
use Zeropingheroes\SteamApis\SteamWebApi\Data\ApiParameter;

it('returns supported api list', function (): void {
    $interfaces = $this->steamWebApiConnector->getSupportedApiList();

    Assert::assertContainsOnlyInstancesOf(ApiInterface::class, $interfaces);

    $interfaces->each(function (ApiInterface $interface): void {
        Assert::assertContainsOnlyInstancesOf(ApiMethod::class, $interface->methods);

        $interface->methods->each(function (ApiMethod $method): void {
            Assert::assertContainsOnlyInstancesOf(ApiParameter::class, $method->parameters);
        });
    });
});
