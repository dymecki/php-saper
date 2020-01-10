<?php

declare(strict_types = 1);

namespace App\Domain\Game\Builders;

use App\Domain\Game\GameBuilder;

final class EasyGame extends GameBuilder
{
    public static function bombs(): int
    {
        return 9;
    }

    public static function width(): int
    {
        return 8;
    }

    public static function height(): int
    {
        return 8;
    }
}