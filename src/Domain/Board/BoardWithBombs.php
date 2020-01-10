<?php

declare(strict_types = 1);

namespace App\Domain\Board;

use App\Domain\Tiles\Tile;
use App\Domain\Tiles\Tiles;

final class BoardWithBombs
{
    private Board $board;
    private int   $bombs;

    public function __construct(Board $board, int $bombs)
    {
        if ($bombs < 1) {
            throw new \DomainException('Bombs amount must be at least 1');
        }

        if ($bombs > $board->tiles()->count()) {
            throw new \DomainException('Bombs amount cannot be greater than amount of tiles');
        }

        $this->board = $board;
        $this->bombs = $bombs;
    }

    public function board(): Board
    {
        return new Board(
            $this->board->width(),
            $this->board->height(),
            $this->tiles()->withNumbers()
        );
    }

    private function tiles(): Tiles
    {
        $tiles = Tiles::fromArray();

        foreach ($this->matrix() as $y => $row) {
            foreach ($row as $x => $value) {
                $tiles->put(Tile::obj($x + 1, $y + 1, $value));
            }
        }

        return $tiles;
    }

    private function matrix(): array
    {
        return array_chunk($this->values(), $this->board->width()->value());
    }

    private function values(): array
    {
        return $this->randomOrder([
            ...array_fill(0, $this->bombs, -1),
            ...array_fill(0, $this->nonBombTilesAmount(), 0)
        ]);
    }

    private function nonBombTilesAmount(): int
    {
        return $this->board->tiles()->count() - $this->bombs;
    }

    private function randomOrder(array $values): array
    {
//        if (isset($values[-1])) {
//            throw new \InvalidArgumentException('Negative index');
//        }

        shuffle($values);

        return $values;
    }
}