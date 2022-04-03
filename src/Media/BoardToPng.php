<?php

namespace Chess\Media;

class BoardToPng extends AbstractBoardToImg
{
    const EXT = '.png';

    public function output(string $filepath)
    {
        $filename = uniqid().self::EXT;

        $this->chessboard($filepath)->save("{$filepath}/{$filename}");

        return $filename;
    }
}
