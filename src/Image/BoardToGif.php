<?php

namespace Chess\Image;

use Chess\Board;

class BoardToGif
{
    protected $board;

    protected $flip;

    public function __construct(Board $board, bool $flip = false)
    {
        $this->board = $board;

        $this->flip = $flip;
    }

    public function output(string $foldername, string $filename)
    {
        $board = new Board();
        $boardToPng = new BoardToPng($board, $this->flip);
        foreach ($this->board->getHistory() as $key => $item) {
            $n = sprintf("%02d", $key);
            $board->play($item->move->color, $item->move->pgn);
            $boardToPng->setBoard($board)->output("{$foldername}/{$n}_{$filename}.png");
        }

        $this->animate($foldername, $filename);
    }

    private function animate(string $foldername, string $filename)
    {
        exec("convert -delay 100 -loop 0 {$foldername}/*.png {$foldername}/{$filename}.gif");
    }
}
