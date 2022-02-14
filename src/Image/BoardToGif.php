<?php

namespace Chess\Image;

use Chess\Board;

class BoardToGif
{
    const FILEPATH = __DIR__ . '/../../img';

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
        $uniqid = uniqid();
        foreach ($this->board->getHistory() as $key => $item) {
            $board->play($item->move->color, $item->move->pgn);
            $boardToPng->setBoard($board)->output("{$foldername}/{$key}_{$uniqid}.png");
        }
    }
}
