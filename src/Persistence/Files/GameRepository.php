<?php

declare(strict_types = 1);

namespace App\Persistence\Files;

use App\Domain\Game\Game;

final class GameRepository
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function add(Game $game): void
    {
        file_put_contents(
            $this->path('game.json'),
            json_encode($game, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT, 512)
        );
    }

    public function get(): Game
    {
        return Game::fromJson(json_decode(
            file_get_contents($this->path('game.json')), false, 512, JSON_THROW_ON_ERROR
        ));
    }

    private function path(string $filename): string
    {
        return rtrim($this->path, '/') . '/' . $filename;
    }
}