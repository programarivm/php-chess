<?php

namespace Chess\Media;

use Chess\Variant\Classical\Board;
use Imagine\Gd\Imagine;
use Imagine\Image\Point;

class AbstractBoardToImg
{
    const FILEPATH = __DIR__ . '/../../img';

    protected Board $board;

    protected bool $flip;

    protected int $size;

    protected Imagine $imagine;

    public function __construct(Board $board, bool $flip = false, $size = 480)
    {
        $this->board = $board;
        $this->flip = $flip;
        $this->size = $size;
        $this->imagine = new Imagine();
    }

    public function setBoard(Board $board): AbstractBoardToImg
    {
        $this->board = $board;

        return $this;
    }

    public function output(string $filepath, string $salt = ''): string
    {
        $salt ? $filename = $salt.$this->ext : $filename = uniqid().$this->ext;
        $this->chessboard($filepath)->save("{$filepath}/{$filename}");

        return $filename;
    }

    protected function chessboard(string $filepath)
    {
        $nSqs = $this->board->getSize()['files'] * $this->board->getSize()['ranks'];
        $sqSize = $this->size / $this->board->getSize()['files'];
        $chessboard = $this->imagine->open(self::FILEPATH.'/chessboard/'."{$this->size}_{$nSqs}".'.png');
        $x = $y = 0;
        foreach ($this->board->toAsciiArray($this->flip) as $i => $rank) {
            foreach ($rank as $j => $piece) {
                if ($piece !== ' . ') {
                    $filename = trim($piece);
                    $isWhite = strtoupper($filename) === $filename;
                    $image = $this->imagine->open(
                        self::FILEPATH . '/pieces/png/' . $sqSize . ($isWhite ? 'white' : 'black') . '/' . $filename . '.png'
                    );
                    $chessboard->paste($image, new Point($x, $y));
                }
                $x += $sqSize;
            }
            $x = 0;
            $y += $sqSize;
        }

        return $chessboard;
    }
}
