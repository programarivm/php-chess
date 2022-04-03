<?php

namespace Chess\Media;

class BoardToJpg extends AbstractBoardToImg
{
    const EXT = '.jpg';

    public function output(string $filepath)
    {
        $filename = uniqid().self::EXT;

        $this->chessboard($filepath)->save("{$filepath}/{$filename}");

        return $filename;
    }
}
