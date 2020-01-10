<?php

declare(strict_types = 1);

namespace Tests\Domain\Board;

use App\Domain\Board\Dimension;
use App\Domain\Board\Height;
use PHPUnit\Framework\TestCase;

class HeightTest extends TestCase
{
    public function test_height_extends_dimension_class(): void
    {
        $this->assertInstanceOf(Dimension::class, new Height(20));
    }

    public function test_cannot_instantiate_negative_value(): void
    {
        $this->expectException(\DomainException::class);
        new Height(-1);
    }

    public function test_cannot_instantiate_zero_value(): void
    {
        $this->expectException(\DomainException::class);
        new Height(0);
    }

    public function test_cannot_instantiate_too_small_value(): void
    {
        $this->expectException(\DomainException::class);
        new Height(1);
    }

    public function test_cannot_instantiate_too_big_value(): void
    {
        $this->expectException(\DomainException::class);
        new Height(100);
    }

    public function test_height_implements_toostring_method(): void
    {
        $this->assertEquals('20', (string) new Height(20));
    }

    public function test_can_use_minimal_value(): void
    {
        $this->assertInstanceOf(Height::class, new Height(Height::MIN_HEIGHT));
    }

    public function test_can_use_maximal_value(): void
    {
        $this->assertInstanceOf(Height::class, new Height(Height::MAX_HEIGHT));
    }
}
