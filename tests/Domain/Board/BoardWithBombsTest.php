<?php

declare(strict_types = 1);

namespace Tests\Domain\Board;

use App\Domain\Board\Board;
use App\Domain\Board\BoardWithBombs;
use App\Domain\Tiles\Tile;
use PHPUnit\Framework\TestCase;

class BoardWithBombsTest extends TestCase
{
    private Board $board;

    protected function setUp(): void
    {
        $this->board = Board::empty(8, 8);
    }

    public function test_board_must_have_at_least_one_bomb(): void
    {
        $this->expectException(\DomainException::class);
        new BoardWithBombs($this->board, 0);
    }

    public function test_board_can_have_only_one_bomb(): void
    {
        $boardWithBombs = new BoardWithBombs($this->board, 1);
        $this->assertCount(1, $boardWithBombs->board()->tiles()->bombs());
    }

    public function test_board_cannot_have_more_bombs_than_its_size(): void
    {
        $this->expectException(\DomainException::class);
        new BoardWithBombs($this->board, 70);
    }

    public function test_board_can_have_the_same_amount_of_bombs_as_it_size(): void
    {
        $boardWithBombs = new BoardWithBombs($this->board, 64);
        $this->assertCount(64, $boardWithBombs->board()->tiles()->bombs());
    }

    public function test_tiles_must_be_in_random_order(): void
    {
        $boardWithBombsA = new BoardWithBombs($this->board, 9);
        $boardWithBombsB = new BoardWithBombs($this->board, 9);

        $this->assertNotEquals(
            json_encode($boardWithBombsA->board(), JSON_THROW_ON_ERROR, 512),
            json_encode($boardWithBombsB->board(), JSON_THROW_ON_ERROR, 512)
        );
    }

//    public function test_board_must_contain_safe_tiles(): void
//    {
//        $boardWithBombs = new BoardWithBombs($this->board, 9);
//
//        $this->assertTrue($boardWithBombs->board()->tiles()->safe()->count() > 0);
//    }

    public function test_y_positions_must_be_increased_by_one(): void
    {
        $boardWithBombs = new BoardWithBombs($this->board, 9);
        $y              = [];

        /** @var Tile $tile */
        foreach ($boardWithBombs->board()->tiles() as $tile) {
            $y[] = $tile->position()->y();
        }

        $y = array_values(array_unique($y));

        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8], $y);
    }

    public function test_x_positions_must_be_increased_by_one(): void
    {
        $boardWithBombs = new BoardWithBombs($this->board, 9);
        $x              = [];

        /** @var Tile $tile */
        foreach ($boardWithBombs->board()->tiles() as $tile) {
            $x[] = $tile->position()->x();
        }

        $x = array_values(array_unique($x));

        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8], $x);
    }
}
