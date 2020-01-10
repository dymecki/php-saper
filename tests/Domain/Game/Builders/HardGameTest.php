<?php

declare(strict_types = 1);

namespace Tests\Domain\Game\Builders;

use App\Domain\Game\Builders\HardGame;
use Tests\TestCase;

class HardGameTest extends TestCase
{
    public function test_build_new_game_board(): void
    {
        $game = HardGame::init();

        $this->assertEquals(30, $game->board()->width()->value());
        $this->assertEquals(16, $game->board()->height()->value());
        $this->assertCount(480, $game->board()->tiles());
        $this->assertCount(30, $game->board()->tiles()->bombs());
    }
}
