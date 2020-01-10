<?php

declare(strict_types = 1);

namespace App\Domain\Game\Builders;

use App\Domain\Game\GameBuilder;

final class VerySmallGame extends GameBuilder
{
    public static function bombs(): int
    {
        return 1;
    }

    public static function width(): int
    {
        return 4;
    }

    public static function height(): int
    {
        return 2;
    }
}