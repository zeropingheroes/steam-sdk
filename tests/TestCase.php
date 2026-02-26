<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Saloon\Http\Faking\Fixture;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use Saloon\Laravel\Facades\Saloon;
use Zeropingheroes\SteamApis\SteamCommunityApi\SteamCommunityApiConnector;
use Zeropingheroes\SteamApis\SteamSdkServiceProvider;
use Zeropingheroes\SteamApis\SteamStoreApi\SteamStoreApiConnector;
use Zeropingheroes\SteamApis\SteamWebApi\SteamWebApiConnector;

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

        $this->steamWebApiConnector = new SteamWebApiConnector(
            getenv('STEAM_API_KEY'),
            storage_path(''),
        );

        Saloon::fake([
            SteamCommunityApiConnector::class => function (PendingRequest $request): Fixture {
                $name = implode('/', array_filter([
                    parse_url($request->getUrl(), PHP_URL_HOST),
                    $request->getMethod()->value,
                    parse_url($request->getUrl(), PHP_URL_PATH),
                    http_build_query($request->query()->all()),
                ]));

                return MockResponse::fixture($name);
            },
        ]);

        $this->steamCommunityApiConnector = new SteamCommunityApiConnector;

        Saloon::fake([
            SteamStoreApiConnector::class => function (PendingRequest $request): Fixture {
                $name = implode('/', array_filter([
                    parse_url($request->getUrl(), PHP_URL_HOST),
                    $request->getMethod()->value,
                    parse_url($request->getUrl(), PHP_URL_PATH),
                    http_build_query($request->query()->all()),
                ]));

                return MockResponse::fixture($name);
            },
        ]);

        $this->steamStoreApiConnector = new SteamStoreApiConnector(
            storage_path('')
        );
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
            'rate_limit_store_path' => storage_path(''),
        ]);
    }
}
