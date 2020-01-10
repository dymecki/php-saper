<?php

declare(strict_types = 1);

namespace Tests\Domain\Game;

use App\Domain\Game\Game;
use App\Persistence\Files\GameRepository;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class GameTest extends TestCase
{
    private Game $game;

    protected function setUp(): void
    {
        $this->game = (new GameRepository('tests'))->get();
    }

    public function testFromJson(): void
    {
        $this->assertEquals(8, $this->game->board()->width()->value());
        $this->assertEquals(8, $this->game->board()->height()->value());
        $this->assertEquals(9, $this->game->board()->tiles()->bombs()->count());

        $this->assertTrue($this->game->board()->tiles()->tile(4, 3)->isNumber());
    }

    public function testStats(): void
    {
        $this->assertSame(0, $this->game->stats()->clicks());
    }

    public function test_method_isgamelost_must_be_public(): void
    {
        $method = new ReflectionMethod(Game::class, 'isGameLost');

        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isProtected());
    }

    public function testJsonSerialize(): void
    {
        $json = json_encode($this->game, JSON_THROW_ON_ERROR, 512);
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        $this->assertTrue(isset($data['board']));
        $this->assertTrue(isset($data['board']['width']));
        $this->assertTrue(isset($data['board']['height']));
        $this->assertTrue(isset($data['board']['tiles']));
        $this->assertCount(64, $data['board']['tiles']);
        $this->assertTrue(isset($data['stats']));
        $this->assertTrue(isset($data['stats']['clicks']));
    }
}
