<?php

declare(strict_types = 1);

namespace App\Views\Html\Dev;

use App\Domain\Board\Board;

final class BoardView
{
    private Board $board;

    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    public function render(): string
    {
        $html = '<div class="board" style="width: ' . $this->board->width()->value() * 15 . 'px">';

        foreach ($this->board->tiles()->withAllOpen() as $tile) {
            $html .= (new TileView($tile))->render();
        }

        return $html . '</div>';
    }
}
