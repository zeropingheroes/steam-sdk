<?php

use Astrotomic\PhpunitAssertions\NullableTypeAssertions;
use Astrotomic\PhpunitAssertions\UrlAssertions;
use PHPUnit\Framework\Assert;
use Zeropingheroes\SteamApis\SteamWebApi\Data\NewsItem;
use Zeropingheroes\SteamApis\SteamWebApi\SteamWebApiConnector;

it('returns news for app', function (int $appid): void {
    $news = app(SteamWebApiConnector::class)->getNewsForApp(appid: $appid);

    Assert::assertContainsOnlyInstancesOf(NewsItem::class, $news);

    $news->each(function (NewsItem $item): void {
        Assert::assertNotEmpty($item->gid);
        Assert::assertNotEmpty($item->title);
        UrlAssertions::assertValidLoose($item->url());
        NullableTypeAssertions::assertIsNullableString($item->author);
        Assert::assertNotEmpty($item->contents);
        Assert::assertNotEmpty($item->feedlabel);
        Assert::assertNotEmpty($item->feedname);
    });
})->with('appids');
