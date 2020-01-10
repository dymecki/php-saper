<?php

declare(strict_types = 1);

namespace Tests\Domain\Board;

use App\Domain\Board\Board;
use App\Domain\Board\Height;
use App\Domain\Board\Width;
use App\Domain\Tiles\Tile;
use App\Domain\Tiles\Tiles;
use Tests\TestCase;

class BoardTest extends TestCase
{
    public function test_init_new_board(): void
    {
        $board = Board::empty(8, 8);

        $this->assertEquals(8, $board->width()->value());
        $this->assertEquals(8, $board->height()->value());
    }

    public function test_too_small_board_should_throw_an_exception(): void
    {
        $this->expectException(\DomainException::class);
        Board::empty(1, 1);
    }

    public function test_try_assign_too_many_tiles_to_board(): void
    {
        $tiles = [
            Tile::obj(1, 1),
            Tile::obj(1, 2),
            Tile::obj(1, 3),
            Tile::obj(1, 4),
            Tile::obj(1, 5),
            Tile::obj(1, 6)
        ];

        $this->expectException(\DomainException::class);
        new Board(new Width(2),new Height(2), Tiles::fromArray($tiles));
    }
}
