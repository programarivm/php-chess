<?php

namespace Chess\Image;

use Chess\Ascii;
use Chess\Board;
use Imagine\Gd\Imagine;
use Imagine\Image\Point;

class BoardToPng
{
    const FILEPATH = __DIR__.'/../../img';

    protected $board;

    protected $imagine;

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->imagine = new Imagine();
    }

    public function output(string $filepath)
    {
        $chessboard = $this->imagine->open(self::FILEPATH . '/chessboard.png');
        $array = (new Ascii())->toArray($this->board);
        $x = $y = 0;
        foreach ($array as $i => $rank) {
            foreach ($rank as $j => $piece) {
                if ($piece !== ' . ') {
                    $filename = trim($piece);
                    $image = $this->imagine->open(self::FILEPATH . "/pieces/$filename.png");
                    $chessboard->paste($image, new Point($x, $y));
                }
                $x += 90;
            }
            $x = 0;
            $y += 90;
        }

        $chessboard->save($filepath);
    }
}
