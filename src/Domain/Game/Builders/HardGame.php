<?php

declare(strict_types = 1);

namespace App\Domain\Game\Builders;

use App\Domain\Game\GameBuilder;

final class HardGame extends GameBuilder
{
    public static function bombs(): int
    {
        return 30;
    }

    public static function width(): int
    {
        return 30;
    }

    public static function height(): int
    {
        return 16;
    }
}