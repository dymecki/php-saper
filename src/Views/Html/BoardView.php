<?php

declare(strict_types = 1);

namespace App\Views\Html;

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
        $html = '<div class="board" style="width: '. $this->board->width()->value() * 15 . 'px">';

        foreach ($this->board->tiles() as $tile) {
            $html .= (new TileView($tile))->render();
        }

        return $html . '</div>';
    }

//    public function render(): string
//    {
//        $html     = '<div class="board">';
//        $iterator = $this->board->tiles()->getIterator();
//
//        for ($y = 0; $y < $this->board->height(); $y++) {
//            $html .= '<div class="row">';
//
//            for ($x = 0; $x < $this->board->width(); $x++) {
//                /** @var Tile $tile */
//                $tile = $iterator->current();
//                $html .= (new TileView($tile))->render();
//
//                $iterator->next();
//            }
//
//            $html .= '</div>';
//        }
//
//        $html .= '</div>';
//
//        return $html;
//    }
}
