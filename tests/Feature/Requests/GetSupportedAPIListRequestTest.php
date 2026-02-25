<?php

use Zeropingheroes\SteamApis\Data\ApiInterface;
use Zeropingheroes\SteamApis\Data\ApiMethod;
use Zeropingheroes\SteamApis\Data\ApiParameter;
use PHPUnit\Framework\Assert;

it('returns supported api list', function (): void {
    $interfaces = $this->steam->getSupportedApiList();

    Assert::assertContainsOnlyInstancesOf(ApiInterface::class, $interfaces);

    $interfaces->each(function (ApiInterface $interface): void {
        Assert::assertContainsOnlyInstancesOf(ApiMethod::class, $interface->methods);

        $interface->methods->each(function (ApiMethod $method): void {
            Assert::assertContainsOnlyInstancesOf(ApiParameter::class, $method->parameters);
        });
    });
});
