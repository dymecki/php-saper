<?php

declare(strict_types = 1);

namespace Tests\Domain\Game\Builders;

use App\Domain\Game\Builders\EasyGame;
use Tests\TestCase;

class EasyGameTest extends TestCase
{
    public function test_build_new_game_board(): void
    {
        $game = EasyGame::init();

        $this->assertEquals(8, $game->board()->width()->value());
        $this->assertEquals(8, $game->board()->height()->value());
        $this->assertCount(64, $game->board()->tiles());
        $this->assertCount(9, $game->board()->tiles()->bombs());
    }
}
