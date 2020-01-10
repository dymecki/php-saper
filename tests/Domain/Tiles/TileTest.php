<?php

declare(strict_types = 1);

namespace Tests\Domain\Tiles;

use App\Domain\Tiles\Position;
use App\Domain\Tiles\Tile;
use ReflectionObject;
use Tests\TestCase;

class TileTest extends TestCase
{
    private Tile $tile;

    public function setUp(): void
    {
        $this->tile = Tile::obj(1, 1);
    }

    public function test_build_new_empty_tile(): void
    {
        $this->assertFalse($this->tile->isBomb());
    }

    public function test_set_tile_as_a_bomb(): void
    {
        $this->assertFalse($this->tile->isBomb());
    }

    public function test_the_same_positions(): void
    {
        $tileA = Tile::obj(1, 1);
        $tileB = Tile::obj(1, 1);

        $this->assertTrue($tileA->position()->equals($tileB->position()));
    }

    public function test_not_the_same_positions(): void
    {
        $tileB = Tile::obj(2, 2);

        $this->assertFalse($this->tile->position()->equals($tileB->position()));
    }

    public function test_close_tile(): void
    {
        $tile = $this->tile->open();

        $this->assertTrue($tile->isOpen());
        $this->assertFalse($tile->close()->isOpen());
    }

    public function test_json_serialization(): void
    {
        $tile = Tile::obj(1, 2, 2);
        $json = json_encode($tile, JSON_THROW_ON_ERROR, 512);

        $this->assertEquals('{"position":{"x":1,"y":2},"state":{"value":2,"open":false,"flag":false}}', $json);
    }

    public function test_json_serialization_with_open_value(): void
    {
        $tile = Tile::obj(1, 2, 2)->open();
        $json = json_encode($tile, JSON_THROW_ON_ERROR, 512);

        $this->assertEquals('{"position":{"x":1,"y":2},"state":{"value":2,"open":true,"flag":false}}', $json);
    }

    public function test_value_method(): void
    {
        $this->assertEquals(2, Tile::obj(1, 2, 2)->state()->value());
    }

    public function test_bomb_named_constructor_method(): void
    {
        $this->assertEquals(2, Tile::bomb(1, 2)->isBomb());
    }

    public function test_build_object_from_json(): void
    {
        $json = json_decode('{"position":{"x":1,"y":2},"state":{"open":true,"value":2,"flag":false}}', false, 512, JSON_THROW_ON_ERROR);
        $tile = Tile::fromJson($json);

        $this->assertEquals(1, $tile->position()->x());
        $this->assertEquals(2, $tile->position()->y());
        $this->assertEquals(2, $tile->state()->value());
        $this->assertTrue($tile->isOpen());
    }

    public function test_convert_tile_to_number_tile(): void
    {
        $tile = Tile::obj(1, 1);
        $this->assertInstanceOf(Tile::class, $tile);
        $this->assertEquals(0, $tile->state()->value());

        $tile = $tile->withValue(2);
//        $this->assertInstanceOf(NumberTile::class, $tile);
        $this->assertEquals(2, $tile->state()->value());
    }

    public function test_check_if_tile_is_safe(): void
    {
        $this->assertTrue($this->tile->isSafe());
        $this->assertFalse($this->tile->withValue(-1)->isSafe());
    }

    public function test_check_if_tile_is_open_or_closed(): void
    {
        $this->assertFalse($this->tile->isOpen());
        $this->assertTrue($this->tile->isClosed());

        $openedTile = $this->tile->open();

        $this->assertTrue($openedTile->isOpen());
        $this->assertFalse($openedTile->isClosed());
    }

    public function test_remove_flag(): void
    {
        $tile = Tile::obj(1, 1)->putFlag();

        $this->assertTrue($tile->hasFlag());
        $this->assertFalse($tile->removeFlag()->hasFlag());
    }

    public function test_tile_is_null_when_both_position_values_are_zero(): void
    {
        $tile = Tile::obj(1, 1);

        $refObject   = new ReflectionObject($tile->position());
        $refProperty = $refObject->getProperty('x');
        $refProperty->setAccessible(true);
        $refProperty->setValue($tile->position(), -1);

        $this->assertFalse($tile->isNull());
    }

    public function test_tile_is_null_when_both_position_values_are_zero_2(): void
    {
        $tile = Tile::obj(1, 1);

        $refObject   = new ReflectionObject($tile->position());
        $refProperty = $refObject->getProperty('y');
        $refProperty->setAccessible(true);
        $refProperty->setValue($tile->position(), -1);

        $this->assertFalse($tile->isNull());
    }

    public function test_tile_is_null_when_only_one_position_value_is_zero(): void
    {
        $tile = Tile::obj(1, 1);

        $refObject   = new ReflectionObject($tile->position());
        $refProperty = $refObject->getProperty('x');
        $refProperty->setAccessible(true);
        $refProperty->setValue($tile->position(), 0);

        $this->assertTrue($tile->isNull());
    }
}
