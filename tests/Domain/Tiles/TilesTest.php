<?php

declare(strict_types = 1);

namespace Tests\Domain\Tiles;

use App\Domain\Tiles\Tile;
use App\Domain\Tiles\Tiles;
use Tests\TestCase;

class TilesTest extends TestCase
{
    public function test_constructor_is_private(): void
    {
        $constructor = new \ReflectionMethod(Tiles::class, '__construct');
        $this->assertTrue($constructor->isPrivate());
    }

    public function test_build_new_empty_collection(): void
    {
        $this->assertEmpty(Tiles::fromArray());
    }

    public function test_build_new_empty_collection_from_array(): void
    {
        $this->assertEmpty(Tiles::fromArray([]));
    }

    /** @dataProvider sampleData */
    public function test_build_collection_from_array(Tiles $tiles): void
    {
        $this->assertCount(8, $tiles);
    }

    public function test_collection_items_must_be_of_tile_type(): void
    {
        $this->expectException(\Error::class);
        $this->assertEmpty(Tiles::fromArray(['']));
    }

    public function test_build_new_collection(): void
    {
        $tiles = [
            Tile::obj(1, 1),
            Tile::obj(1, 2),
            Tile::obj(1, 3)
        ];

        $this->assertCount(3, Tiles::fromArray($tiles));
    }

    public function test_implements_iterator_aggregate_interface(): void
    {
        $this->assertInstanceOf(\IteratorAggregate::class, Tiles::fromArray());
    }

    public function test_position_exists(): void
    {
        $this->assertEquals(3, Tiles::fromArray([Tile::obj(3, 5)])->tile(3, 5)->position()->x());
    }

    /**
     * @dataProvider sampleData
     */
    public function test_has_tile(Tiles $tiles): void
    {
        $this->assertTrue($tiles->has(Tile::obj(1, 2)));
    }

    /** @dataProvider sampleData */
    public function test_get_only_number_tiles(Tiles $tiles): void
    {
        $this->assertCount(8, $tiles);
        $this->assertCount(4, $tiles->numbers());
    }

    public function test_get_only_flag_tiles(): void
    {
        $tiles = Tiles::fromArray([
            Tile::obj(1, 1, 1),
            Tile::obj(1, 2, -1)->putFlag(),
            Tile::obj(1, 3),
            Tile::obj(1, 4, -1),
            Tile::obj(1, 5, 4)->putFlag(),
            Tile::obj(1, 6)->putFlag(),
            Tile::obj(1, 7, 1),
            Tile::obj(1, 8, 2),
        ]);

        $this->assertCount(8, $tiles);
        $this->assertCount(3, $tiles->flags());
    }

    /** @dataProvider sampleData */
    public function test_get_only_bomb_tiles(Tiles $tiles): void
    {
        $this->assertCount(8, $tiles);
        $this->assertCount(2, $tiles->bombs());
    }

    /** @dataProvider sampleData */
    public function test_open_all_tiles(Tiles $tiles): void
    {
        $ok = true;

        /** @var Tile $tile */
        foreach ($tiles as $tile) {
            if ($tile->isOpen() === false) {
                $ok = false;
                break;
            }
        }

        $this->assertFalse($ok);
        $this->assertCount(8, $tiles->withAllOpen());
        $this->assertCount(8, $tiles->withAllClosed());

        $ok = true;

        /** @var Tile $tile */
        foreach ($tiles->withAllOpen() as $tile) {
            if ($tile->isOpen() === false) {
                $ok = false;
                break;
            }
        }

        $this->assertTrue($ok);
    }

    /** @dataProvider sampleData */
    public function test_has_not_tile(Tiles $tiles): void
    {
        $this->assertFalse($tiles->has(Tile::obj(3, 3)));
    }

    /** @dataProvider sampleData */
    public function test_get_tile_by_position_object(Tiles $tiles): void
    {
        $tile = $tiles->position(Tile::obj(1, 5));

        $this->assertEquals(1, $tile->position()->x());
        $this->assertEquals(5, $tile->position()->y());
    }

    /** @dataProvider sampleData */
    public function test_get_non_existent_tile_by_position_object(Tiles $tiles): void
    {
        $this->assertTrue($tiles->position(Tile::obj(5, 5))->isNull());
    }

    /** @dataProvider sampleData */
    public function test_merge_two_tile_collections(Tiles $tilesA, Tiles $tilesB): void
    {
        $this->assertCount(8, $tilesA);
        $this->assertCount(8, $tilesB);
        $this->assertCount(2, $tilesA->bombs());
        $this->assertCount(3, $tilesB->bombs());

        $tiles = $tilesA->merge($tilesB);

        $this->assertCount(16, $tiles);
        $this->assertCount(5, $tiles->bombs());
    }

    /** @dataProvider sampleData */
    public function test_merge_two_tile_collections_with_same_tiles(Tiles $tilesA): void
    {
        $tilesB = Tiles::fromArray([
            Tile::obj(1, 1),
            Tile::obj(2, 2)
        ]);

        $this->assertCount(8, $tilesA);
        $this->assertCount(2, $tilesB);

        $tiles = $tilesA->merge($tilesB);

        $this->assertCount(9, $tiles);
    }

    public function test_is_instance_of_jsonserializable(): void
    {
        $this->assertInstanceOf(\JsonSerializable::class, Tiles::fromArray());
    }

    public function test_json_serialization(): void
    {
        $tiles = Tiles::fromArray([
            Tile::obj(1, 2, 2)->putFlag(),
            Tile::obj(2, 2),
            Tile::obj(2, 3),
        ]);

        $json = json_encode($tiles, JSON_THROW_ON_ERROR, 512);

        $expected = '{"1.2":"{\"position\":{\"x\":1,\"y\":2},\"state\":{\"value\":2,\"open\":false,\"flag\":true}}",'
                    . '"2.2":"{\"position\":{\"x\":2,\"y\":2},\"state\":{\"value\":0,\"open\":false,\"flag\":false}}",'
                    . '"2.3":"{\"position\":{\"x\":2,\"y\":3},\"state\":{\"value\":0,\"open\":false,\"flag\":false}}"}';

        $this->assertEquals($expected, $json);
    }

    public function test_return_null_object_when_position_does_not_exist(): void
    {
        $tile  = Tile::obj(3, 5);
        $tiles = Tiles::fromArray([$tile]);
        $tile  = $tiles->tile(1, 1);

        $this->assertTrue($tile->isNull());
    }

    /** @dataProvider sampleData */
    public function test_all_positions_are_the_same_in_both_collections(Tiles $tiles): void
    {
        $this->assertTrue($tiles->samePositions($tiles));
    }

    /** @dataProvider sampleData */
    public function test_all_positions_are_not_the_same_in_both_collections(Tiles $tilesA, Tiles $tilesB): void
    {
        $this->assertFalse($tilesA->samePositions($tilesB));
    }

    /** @dataProvider sampleData */
    public function test_two_different_length_collections_are_not_the_same(Tiles $tilesA): void
    {
        $tiles = Tiles::fromArray([
            Tile::obj(1, 1, 1), Tile::obj(1, 2, -1), Tile::obj(1, 3), Tile::obj(1, 4, -1)
        ]);

        $this->assertFalse($tilesA->samePositions($tiles));
    }

//    public function test_safe_area():void
//    {
//
//    }

    public function test_eight_tiles_must_be_checked_around(): void
    {
        $tile  = Tile::obj(2, 2);
        $tiles = Tiles::fromArray([
            Tile::obj(1, 1, 2), Tile::obj(2, 1, -1), Tile::obj(3, 1),
            Tile::obj(1, 2, -1), Tile::obj(2, 2), Tile::obj(3, 2, 3),
            Tile::obj(1, 3), Tile::obj(2, 3, 4), Tile::obj(3, 3, -1)
        ]);

        $this->assertCount(8, $tiles->around($tile));
        $this->assertCount(2, $tiles->around($tile)->safe());
    }

    public function test_safe_area(): void
    {
        $tile  = Tile::obj(2, 2);
        $tiles = Tiles::fromArray([
            Tile::obj(1, 1, 2), Tile::obj(2, 1, 1), Tile::obj(3, 1, 1), Tile::obj(4, 1, 1),
            Tile::obj(1, 2, 1), Tile::obj(2, 2), Tile::obj(3, 2), Tile::obj(4, 2, 1),
            Tile::obj(1, 3, 1), Tile::obj(2, 3), Tile::obj(3, 3, 1), Tile::obj(4, 3, 1),
        ]);

        $this->assertCount(12, $tiles->safeArea($tile));
    }

    public function sampleData(): array
    {
        return [
            [
                Tiles::fromArray([
                    Tile::obj(1, 1, 1), Tile::obj(1, 2, -1), Tile::obj(1, 3), Tile::obj(1, 4, -1),
                    Tile::obj(1, 5, 4), Tile::obj(1, 6), Tile::obj(1, 7, 1), Tile::obj(1, 8, 2),
                ]),
                Tiles::fromArray([
                    Tile::obj(2, 1, 1), Tile::bomb(2, 2), Tile::obj(2, 3), Tile::bomb(2, 4),
                    Tile::obj(2, 5, 4), Tile::obj(2, 6), Tile::bomb(2, 7), Tile::obj(2, 8),
                ])
            ]
        ];
    }
}
