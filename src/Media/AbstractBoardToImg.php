<?php

namespace Chess\Media;

use Chess\Board;
use Chess\Array\AsciiArray;
use Imagine\Gd\Imagine;
use Imagine\Image\Point;

class AbstractBoardToImg
{
    const FILEPATH = __DIR__ . '/../../img';

    const SIZES = [
        480 => 60,
        3000 => 375,
    ];

    protected Board $board;

    protected Imagine $imagine;

    protected bool $flip;

    protected int $size;

    public function __construct(Board $board, bool $flip = false, $size = 480)
    {
        $this->board = $board;

        $this->imagine = new Imagine();

        $this->flip = $flip;

        $this->size = $size;
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
        $chessboard = $this->imagine->open(self::FILEPATH.'/chessboard/'.$this->size.'.png');
        $array = $this->board->toAsciiArray($this->flip);
        $x = $y = 0;
        foreach ($array as $i => $rank) {
            foreach ($rank as $j => $piece) {
                if ($piece !== ' . ') {
                    $filename = trim($piece);
                    $image = $this->imagine->open(
                        self::FILEPATH .'/pieces/png/'.self::SIZES[$this->size].'/'.$filename.'.png'
                    );
                    $chessboard->paste($image, new Point($x, $y));
                }
                $x += self::SIZES[$this->size];
            }
            $x = 0;
            $y += self::SIZES[$this->size];
        }

        return $chessboard;
    }
}
