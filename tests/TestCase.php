<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Saloon\Http\Faking\Fixture;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use Saloon\Laravel\Facades\Saloon;
use Zeropingheroes\SteamApis\SteamCommunityApiConnector;
use Zeropingheroes\SteamApis\SteamSdkServiceProvider;
use Zeropingheroes\SteamApis\SteamWebApiConnector;

abstract class TestCase extends Orchestra
{
    protected $enablesPackageDiscoveries = true;

    protected SteamWebApiConnector $steamWebApiConnector;

    protected function setUp(): void
    {
        parent::setUp();

        Saloon::fake([
            SteamWebApiConnector::class => function (PendingRequest $request): Fixture {
                $name = implode('/', array_filter([
                    parse_url($request->getUrl(), PHP_URL_HOST),
                    $request->getMethod()->value,
                    parse_url($request->getUrl(), PHP_URL_PATH),
                    http_build_query(array_diff_key($request->query()->all(), array_flip(['key', 'format']))),
                ]));

                return MockResponse::fixture($name);
            },
        ]);

        $this->steamWebApiConnector = new SteamWebApiConnector(getenv('STEAM_API_KEY'));

        Saloon::fake([
            SteamCommunityApiConnector::class => function (PendingRequest $request): Fixture {
                $name = implode('/', array_filter([
                    parse_url($request->getUrl(), PHP_URL_HOST),
                    $request->getMethod()->value,
                    parse_url($request->getUrl(), PHP_URL_PATH),
                    http_build_query(array_diff_key($request->query()->all(), array_flip(['key', 'format']))),
                ]));

                return MockResponse::fixture($name);
            },
        ]);

        $this->steamCommunityApiConnector = new SteamCommunityApiConnector(getenv('STEAM_API_KEY'));
    }

    protected function getPackageProviders($app): array
    {
        return [
            SteamSdkServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('services.steam', [
            'api_key' => env('STEAM_API_KEY'),
        ]);
    }
}
