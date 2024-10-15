<?php

namespace Chess\Media;

use Chess\Variant\AbstractBoard;
use Imagine\Gd\Imagine;
use Imagine\Image\Point;

class AbstractBoardToImg
{
    const FILEPATH = __DIR__ . '/../../img';

    protected AbstractBoard $board;

    protected bool $flip;

    protected int $size;

    protected Imagine $imagine;

    public function __construct(AbstractBoard $board, bool $flip = false, $size = 480)
    {
        $this->board = $board;
        $this->flip = $flip;
        $this->size = $size;
        $this->imagine = new Imagine();
    }

    public function setBoard(AbstractBoard $board): AbstractBoardToImg
    {
        $this->board = $board;

        return $this;
    }

    public function output(string $filepath, string $filename = ''): string
    {
        $filename ? $filename = $filename . $this->ext : $filename = uniqid() . $this->ext;
        $this->chessboard($filepath)->save("{$filepath}/{$filename}");

        return $filename;
    }

    protected function chessboard(string $filepath)
    {
        $nSqs = $this->board->square::SIZE['files'] * $this->board->square::SIZE['ranks'];
        $sqSize = $this->size / $this->board->square::SIZE['files'];
        $chessboard = $this->imagine->open(self::FILEPATH . '/chessboard/' . "{$this->size}_{$nSqs}" . '.png');
        $x = $y = 0;
        foreach ($this->board->toArray($this->flip) as $i => $rank) {
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
