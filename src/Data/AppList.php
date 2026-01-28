<?php

namespace Astrotomic\SteamSdk\Data;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class AppList extends Data
{
    public function __construct(
        public readonly Collection $apps,
        public readonly ?bool $have_more_results,
        public readonly ?int $last_appid,
    ) {}
}
