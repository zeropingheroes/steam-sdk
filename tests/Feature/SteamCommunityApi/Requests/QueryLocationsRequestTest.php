<?php

use PHPUnit\Framework\Assert;
use Saloon\Exceptions\Request\ClientException;
use Zeropingheroes\SteamApis\SteamCommunityApi\Data\LocationCity;
use Zeropingheroes\SteamApis\SteamCommunityApi\Data\LocationCountry;
use Zeropingheroes\SteamApis\SteamCommunityApi\Data\LocationState;

it('returns all countries', closure: function (): void {
    $countries = $this->steamCommunityApiConnector->queryLocations();

    Assert::assertContainsOnlyInstancesOf(LocationCountry::class, $countries);
});

it('returns country states', closure: function (string $countrycode): void {
    $states = $this->steamCommunityApiConnector->queryLocations(countrycode: $countrycode);

    Assert::assertContainsOnlyInstancesOf(LocationState::class, $states);
})->with('countries.with_states');

it('returns country state cities', closure: function (string $countrycode, string $statecode): void {
    $cities = $this->steamCommunityApiConnector->queryLocations(countrycode: $countrycode, statecode: $statecode);

    Assert::assertContainsOnlyInstancesOf(LocationCity::class, $cities);
})->with('states');

it('throws client exception for countries without states', closure: function (string $countrycode): void {
    $this->steamCommunityApiConnector->queryLocations(countrycode: $countrycode);
})->with('countries.without_states')->throws(ClientException::class);
