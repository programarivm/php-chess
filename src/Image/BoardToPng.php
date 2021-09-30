<?php

namespace Chess\Image;

use Chess\Ascii;
use Chess\Board;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
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
        $wPawn = $this->imagine->open(self::FILEPATH . '/pieces/wPawn.png');
        $chessboard = $this->imagine->open(self::FILEPATH . '/chessboard.png');

        $wPawnSize = $wPawn->getSize();
        $chessboardSize = $chessboard->getSize();

        $bottomRight = new Point($chessboardSize->getWidth() - $wPawnSize->getWidth(), $chessboardSize->getHeight() - $wPawnSize->getHeight());

        $chessboard->paste($wPawn, $bottomRight);

        $chessboard->save($filepath);
    }
}
