<?php

namespace Chess\Media;

use Chess\Variant\Classical\Board;
use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

class AbstractBoardToImg
{
    public const FILEPATH = __DIR__ . '/../../img';

    public const SIZES = [
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
        $salt ? $filename = $salt.$this->ext : $filename = uniqid('', true).$this->ext;

        $this->chessboard($filepath)->save("$filepath/$filename");

        return $filename;
    }

    protected function chessboard(string $filepath): ImageInterface
    {
        $chessboard = $this->imagine->open(self::FILEPATH.'/chessboard/'.$this->size.'.png');
        $array = $this->board->toAsciiArray($this->flip);
        $x = $y = 0;
        foreach ($array as $rank) {
            foreach ($rank as $piece) {
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
