<?php

declare(strict_types = 1);

namespace App\Domain\Game;

use App\Domain\Board\Board;
use App\Domain\Board\BoardWithBombs;

abstract class GameBuilder
{
    public static function init(): Game
    {
        $board = Board::empty(static::width(), static::height());
        $board = (new BoardWithBombs($board, static::bombs()))->board();

        return new Game($board, new Stats());
    }

    abstract public static function bombs(): int;

    abstract public static function width(): int;

    abstract public static function height(): int;
}