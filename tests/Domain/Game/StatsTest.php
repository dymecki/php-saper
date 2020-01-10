<?php

declare(strict_types = 1);

namespace Tests\Domain\Game;

use App\Domain\Game\Stats;
use Tests\TestCase;

class StatsTest extends TestCase
{
    public function test_default_value(): void
    {
        $stats = new Stats();
        $this->assertEquals(0, $stats->clicks());
    }

//    public function test_click_property_is_private(): void
//    {
//        $this->expectException(\Error::class);
//        (new Stats())->clicks;
//    }

    public function test_add_one_click_value(): void
    {
        $stats = new Stats();
        $stats->click();
        $this->assertEquals(1, $stats->clicks());
    }

    public function test_json_serialize(): void
    {
        $stats = new Stats();
        $json  = json_encode($stats, JSON_THROW_ON_ERROR, 512);
        $this->assertEquals('{"clicks":0}', $json);

        $stats->click();

        $json = json_encode($stats, JSON_THROW_ON_ERROR, 512);

        $this->assertEquals('{"clicks":1}', $json);
    }
}
