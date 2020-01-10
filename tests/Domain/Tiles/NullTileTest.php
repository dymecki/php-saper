<?php

declare(strict_types = 1);

namespace Tests\Domain\Tiles;

use App\Domain\Tiles\Tile;
use Tests\TestCase;

class NullTileTest extends TestCase
{
    public function test_check_nulltile_is_a_bomb(): void
    {
        $this->assertFalse(Tile::obj(0, 0)->isBomb());
    }

    public function test_null_tile_cannot_have_a_position(): void
    {
        $this->expectException(\DomainException::class);
        Tile::obj(0, 0)->position();
    }

    public function test_check_is_null(): void
    {
        $this->assertTrue(Tile::obj(0, 0)->isNull());
    }
}
