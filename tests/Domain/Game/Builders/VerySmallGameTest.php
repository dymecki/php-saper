<?php

declare(strict_types = 1);

namespace Tests\Domain\Game\Builders;

use App\Domain\Game\Builders\EasyGame;
use App\Domain\Game\Builders\VerySmallGame;
use Tests\TestCase;

class VerySmallGameTest extends TestCase
{
    public function test_build_new_game_board(): void
    {
        $game = VerySmallGame::init();

        $this->assertEquals(4, $game->board()->width()->value());
        $this->assertEquals(2, $game->board()->height()->value());
        $this->assertCount(8, $game->board()->tiles());
        $this->assertCount(1, $game->board()->tiles()->bombs());
    }
}
