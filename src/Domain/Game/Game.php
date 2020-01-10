<?php

declare(strict_types = 1);

namespace App\Domain\Game;

use App\Domain\Board\Board;

final class Game implements \JsonSerializable
{
    private Board $board;
    private Stats $stats;

    public function __construct(Board $board, Stats $stats)
    {
        $this->board = $board;
        $this->stats = $stats;
    }

    public function isGameOver(): bool
    {
        return $this->isGameWon() || $this->isGameLost();
    }

    public function isGameLost(): bool
    {
        return $this->board->tiles()->bombs()->open()->count() > 0;
    }

    public function isGameWon(): bool
    {
        return $this->board->tiles()->bombs()->samePositions(
            $this->board->tiles()->flags()
        );
    }

    public function board(): Board
    {
        return $this->board;
    }

    public function stats(): Stats
    {
        return $this->stats;
    }

    public static function fromJson(\stdClass $data): self
    {
        return new self(
            Board::fromJson($data->board),
            new Stats()
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'board' => $this->board,
            'stats' => $this->stats
        ];
    }
}