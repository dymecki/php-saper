<?php

declare(strict_types = 1);

namespace Tests\Domain\Board;

use App\Domain\Board\Dimension;
use App\Domain\Board\Width;
use PHPUnit\Framework\TestCase;

class WidthTest extends TestCase
{
    public function test_width_extends_dimension_class(): void
    {
        $this->assertInstanceOf(Dimension::class, new Width(20));
    }

    public function test_cannot_instantiate_too_big_value(): void
    {
        $this->expectException(\DomainException::class);
        new Width(100);
    }

    public function test_can_use_minimal_value(): void
    {
        $this->assertInstanceOf(Width::class, new Width(Width::MIN_WIDTH));
    }

    public function test_can_use_maximal_value(): void
    {
        $this->assertInstanceOf(Width::class, new Width(Width::MAX_WIDTH));
    }

    public function test_exception_must_be_thrown_when_wrong_value(): void
    {
        $this->expectException(\DomainException::class);
        $this->assertNotInstanceOf(Width::class, new Width(1));
    }
}
