<?php

declare(strict_types = 1);

namespace Tests\Views\Html;

use App\Domain\Board\Board;
use App\Domain\Game\Game;
use App\Views\Html\BoardView;
use Tests\TestCase;

class BoardViewTest extends TestCase
{
    public function test_html_render(): void
    {
        $board    = Board::empty(2, 2);
        $view     = new BoardView($board);
        $expected = '<div class="board" style="width: 30px">' .
                    '<div class="tile close" data-x="1" data-y="1"><a href="/index.php?x=1&y=1"></a></div>' .
                    '<div class="tile close" data-x="2" data-y="1"><a href="/index.php?x=2&y=1"></a></div>' .
                    '<div class="tile close" data-x="1" data-y="2"><a href="/index.php?x=1&y=2"></a></div>' .
                    '<div class="tile close" data-x="2" data-y="2"><a href="/index.php?x=2&y=2"></a></div>' .
                    '</div>';

        $this->assertEquals($expected, $view->render());
    }
}
