<?php

namespace Chess\Media;

use Chess\Board;

class BoardToGif
{
    protected $board;

    protected $flip;

    protected $frames = [];

    public function __construct(Board $board, bool $flip = false)
    {
        $this->board = $board;

        $this->flip = $flip;
    }

    public function output(string $foldername)
    {
        if (!file_exists($foldername)) {
            throw new \InvalidArgumentException('The folder does not exist.');
        }

        $filename = uniqid();

        $this->frames($foldername, $filename)
            ->animate($foldername, $filename)
            ->cleanup($foldername, $filename);

        return $filename.'.gif';
    }

    private function frames(string $foldername, string $filename)
    {
        $salt = uniqid();
        $board = new Board();
        $boardToPng = new BoardToPng($board, $this->flip);
        foreach ($this->board->getHistory() as $key => $item) {
            $n = sprintf("%02d", $key);
            $board->play($item->move->color, $item->move->pgn);
            $this->frames[] = $boardToPng->setBoard($board)->output($foldername, $filename);
        }

        return $this;
    }

    private function animate(string $foldername, string $filename)
    {
        exec("convert -delay 100 -loop 0 {$foldername}/{$filename}*.png {$foldername}/{$filename}.gif");

        return $this;
    }

    private function cleanup(string $foldername, string $filename)
    {
        if (file_exists("{$foldername}/$filename.gif")) {
            array_map('unlink', glob($foldername . '/*.png'));
        }
    }
}
