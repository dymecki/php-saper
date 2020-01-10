<?php

declare(strict_types = 1);

namespace Tests\Domain\Tiles;

use App\Domain\Tiles\State;
use App\Domain\Tiles\Tile;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    public function testFlag(): void
    {
        $this->expectException(\DomainException::class);
        new State(2, true, true);

        $data = new State(2, false, true);

        $this->assertTrue($data->open());
        $this->assertTrue($data->flag());

        $data = new State(2, false, false);

        $this->assertFalse($data->open());
        $this->assertFalse($data->flag());
    }

    public function test_with_close(): void
    {
        $this->assertFalse(Tile::obj(1, 1)->close()->hasFlag());
    }

//    public function test_changing_value_should_not_close_the_tile(): void
//    {
//        $this->assertFalse(Tile::obj(1, 1)->withValue(2)->isOpen());
//    }
}
