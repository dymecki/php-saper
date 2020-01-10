<?php

declare(strict_types = 1);

namespace Tests\Domain\Game;

use App\Domain\Game\GameActions;
use App\Domain\Tiles\Tile;
use App\Persistence\Files\GameRepository;
use PHPUnit\Framework\TestCase;

class GameActionsTest extends TestCase
{
    private GameActions $actions;

    protected function setUp(): void
    {
        $game          = (new GameRepository('tests'))->get();
        $this->actions = new GameActions($game);
    }

    public function testClick(): void
    {
        $x          = 2;
        $y          = 3;
        $tileBefore = $this->actions->game()->board()->tiles()->tile($x, $y);

//        var_dump($tileBefore);

//        $this->assertFalse($tileBefore->isOpen());

        $this->actions->click($x, $y);
        $this->assertTrue($this->actions->game()->board()->tiles()->tile($x, $y)->isOpen());
    }

    public function test_is_gameover(): void
    {
        $this->assertFalse($this->actions->game()->isGameOver());
        $this->actions->click(3, 1);
        $this->assertTrue($this->actions->game()->isGameOver());
    }

    public function test_put_flag(): void
    {
        $this->assertFalse($this->actions->game()->board()->tiles()->tile(3, 1)->hasFlag());
        $this->assertCount(0, $this->actions->game()->board()->tiles()->flags());

        $this->actions->putFlag(3, 1);

        $this->assertTrue($this->actions->game()->board()->tiles()->tile(3, 1)->hasFlag());
        $this->assertCount(1, $this->actions->game()->board()->tiles()->flags());
    }

    public function test_remove_flag(): void
    {
        $this->actions->putFlag(3, 1);
        $this->assertTrue($this->actions->game()->board()->tiles()->tile(3, 1)->hasFlag());
        $this->assertCount(1, $this->actions->game()->board()->tiles()->flags());

        $this->actions->removeFlag(3, 1);
        $this->assertFalse($this->actions->game()->board()->tiles()->tile(3, 1)->hasFlag());
        $this->assertCount(0, $this->actions->game()->board()->tiles()->flags());
    }

    public function test_cannot_put_a_flag_on_the_opened_tile(): void
    {
        $tile = $this->actions->game()->board()->tiles()->tile(3, 1)->open();
        $this->actions->game()->board()->tiles()->put($tile);

        $this->assertCount(0, $this->actions->game()->board()->tiles()->flags());
        $this->actions->putFlag(3, 1);
    }

    public function test_is_game_won(): void
    {
        $this->assertFalse($this->actions->game()->isGameWon());
        $this->assertFalse($this->actions->game()->isGameOver());

        /** @var Tile $bomb */
        foreach ($this->actions->game()->board()->tiles()->bombs() as $bomb) {
            $this->actions->putFlag($bomb->position()->x(), $bomb->position()->y());
        }

        $this->assertTrue($this->actions->game()->isGameWon());
        $this->assertTrue($this->actions->game()->isGameOver());
    }

    public function test_click_bomb_tile(): void
    {
        $this->assertCount(0, $this->actions->game()->board()->tiles()->bombs()->open());
        $this->actions->click(3, 1);
        $this->assertCount(9, $this->actions->game()->board()->tiles()->bombs()->open());
    }

    public function test_click_safe_tile(): void
    {
        $this->assertCount(0, $this->actions->game()->board()->tiles()->safe()->open());
        $this->actions->click(5, 1);
        $this->assertCount(7, $this->actions->game()->board()->tiles()->safe()->open());
        $this->assertCount(10, $this->actions->game()->board()->tiles()->numbers()->open());
    }

    public function test_close_all(): void
    {
        $tile = $this->actions->game()->board()->tiles()->tile(1, 1)->open();

        $this->actions->game()->board()->tiles()->put($tile);
        $this->assertCount(1, $this->actions->game()->board()->tiles()->open());
        $this->actions->game()->board()->tiles()->withAllClosed();
        $this->assertCount(1, $this->actions->game()->board()->tiles()->open());
    }
}
