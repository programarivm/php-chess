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
        $this->png($foldername, $filename)
            ->animate($foldername, $filename)
            ->cleanup($foldername, $filename);
    }

    private function png($foldername, $filename)
    {
        $board = new Board();
        $boardToPng = new BoardToPng($board, $this->flip);
        foreach ($this->board->getHistory() as $key => $item) {
            $n = sprintf("%02d", $key);
            $board->play($item->move->color, $item->move->pgn);
            $boardToPng->setBoard($board)->output("{$foldername}/{$n}_{$filename}.png");
        }

        return $this;
    }

    private function animate(string $foldername, string $filename)
    {
        exec("convert -delay 100 -loop 0 {$foldername}/*.png {$foldername}/{$filename}.gif");

        return $this;
    }

    private function cleanup($foldername, $filename)
    {
        if (file_exists("{$foldername}/{$filename}.gif")) {
            array_map('unlink', glob($foldername . '/*.png'));
        }
    }
}
