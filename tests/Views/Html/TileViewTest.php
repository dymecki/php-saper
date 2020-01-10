<?php

declare(strict_types = 1);

namespace Tests\Views\Html;

use App\Domain\Game\Game;
use App\Domain\Tiles\Tile;
use App\Views\Html\TileView;
use Tests\TestCase;

class TileViewTest extends TestCase
{
    private Tile $tile;

    public function setUp(): void
    {
        $this->tile = Tile::obj(1, 2);
    }

    public function test_safe_html_class(): void
    {
        $this->assertEquals(
            '<div class="tile safe" data-x="1" data-y="2"></div>',
            (new TileView($this->tile->open()))->render()
        );
    }

    public function test_value_html_class(): void
    {
        $tile = Tile::obj(1, 2, 2);

        $this->assertEquals(
            '<div class="tile v2" data-x="1" data-y="2">2</div>',
            (new TileView($tile->open()))->render()
        );
    }

    public function test_html_bomb_class(): void
    {
        $tile = Tile::obj(1, 2, -1);

        $this->assertEquals(
            '<div class="tile bomb" data-x="1" data-y="2">x</div>',
            (new TileView($tile->open()))->render()
        );
    }

    public function test_html_flag_class(): void
    {
        $tile = Tile::obj(1, 2)->putFlag();

        $this->assertEquals(
            '<div class="tile flag" data-x="1" data-y="2">&#9873;</div>',
            (new TileView($tile))->render()
        );
    }
}
