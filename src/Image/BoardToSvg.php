<?php

namespace Chess\Image;

use Chess\Ascii;
use Chess\Board;
use Contao\ImagineSvg\Imagine;

class BoardToSvg
{
    const FILEPATH = __DIR__.'/../../svg';

    protected $board;

    protected $imagine;

    public function __construct(Board $board)
    {
        $this->board = $board;

        $this->imagine = new Imagine();
    }

    public function output(string $filepath)
    {
        $this->imagine->open(self::FILEPATH . '/chessboard.svg')->save($filepath);
    }
}
