<?php

declare(strict_types = 1);

namespace App\Domain\Game;

use App\Domain\Tiles\Tiles;

final class GameActions
{
    private Game  $game;
    private Tiles $tiles;

    public function __construct(Game $game)
    {
        $this->game  = $game;
        $this->tiles = $this->game->board()->tiles();
    }

    public function dispatch(string $action, int $x, int $y): void
    {
        switch ($action) {
            default:
            case 'click':
                $this->click($x, $y);
                break;
            case 'flag':
                $this->putFlag($x, $y);
                break;
            case 'remove-flag':
                $this->removeFlag($x, $y);
                break;
        }
    }

    public function putFlag(int $x, int $y): void
    {
        $this->tiles->put(
            $this->tiles->tile($x, $y)->putFlag()
        );
    }

    public function removeFlag(int $x, int $y): void
    {
        $this->tiles->put(
            $this->tiles->tile($x, $y)->removeFlag()
        );
    }

    public function click(int $x, int $y): void
    {
        $tile = $this->tiles->tile($x, $y);

        if ($tile->hasFlag() || $tile->isOpen()) {
            return;
        }

        if ($tile->isBomb()) {
            $this->openBombs();
        }

        if ($tile->isSafe()) {
            $this->tiles->merge(
                $this->tiles->safeArea($tile)->withAllOpen()
            );
        }

        $this->tiles->put($tile->open());
    }

    public function openBombs(): void
    {
        $this->tiles->merge(
            $this->tiles->bombs()->withAllOpen()
        );
    }

    public function closeAll(): void
    {
        $this->tiles->withAllClosed();
    }

    public function game(): Game
    {
        return $this->game;
    }
}