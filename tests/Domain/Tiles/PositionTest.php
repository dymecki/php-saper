<?php

declare(strict_types = 1);

namespace Tests\Domain\Tiles;

use App\Domain\Board\Board;
use App\Domain\Tiles\Position;
use Tests\TestCase;

class PositionTest extends TestCase
{
    public function test_init_new_position(): void
    {
        $position = new Position(2, 5);

        $this->assertEquals(2, $position->x());
        $this->assertEquals(5, $position->y());
    }

    public function test_x_property_cannot_be_negative(): void
    {
        $this->expectException(\DomainException::class);
        new Position(-2, 5);
    }

    public function test_y_property_cannot_be_negative(): void
    {
        $this->expectException(\DomainException::class);
        new Position(2, -5);
    }

    public function test_position_values_must_be_integers(): void
    {
        $this->expectException(\TypeError::class);
        new Position('2', 5.7);
    }

    public function test_conversion_to_string(): void
    {
        $position = new Position(2, 5);

        $this->assertEquals('2.5', (string) $position);
        $this->assertSame((string) $position, $position->label());
    }

    public function test_too_small_board_should_throw_an_exception(): void
    {
        $this->expectException(\DomainException::class);
        Board::empty(1, 1);
    }

    public function test_inconsistent_null_position_should_throw_an_exception(): void
    {
        $this->expectException(\DomainException::class);
        new Position(2, 0);
        new Position(0, 2);
    }

    public function test_positions_are_the_same_when_both_values_are_the_same(): void
    {
        $positionA = new Position(2, 5);
        $positionB = new Position(2, 3);

        $this->assertFalse($positionA->equals($positionB));
    }
}
