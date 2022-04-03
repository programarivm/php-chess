<?php

namespace Chess\Media;

use Chess\Ascii;
use Chess\Board;
use Imagine\Gd\Imagine;
use Imagine\Image\Point;

class AbstractBoardToImg
{
    const FILEPATH = __DIR__ . '/../../img';

    const SIZES = [
        480 => 60,
        3000 => 375,
    ];

    protected $board;

    protected $imagine;

    protected $flip;

    protected $size;

    public function __construct(Board $board, bool $flip = false, $size = 480)
    {
        $this->board = $board;

        $this->imagine = new Imagine();

        $this->flip = $flip;

        $this->size = $size;
    }

    public function setBoard(Board $board)
    {
        $this->board = $board;

        return $this;
    }

    protected function chessboard(string $filepath)
    {
        $chessboard = $this->imagine->open(self::FILEPATH.'/chessboard/'.$this->size.'.png');
        $array = (new Ascii())->toArray($this->board, $this->flip);
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
