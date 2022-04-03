<?php

namespace Chess\Media;

class BoardToJpg extends AbstractBoardToImg
{
    public function output(string $filepath, string $salt = '')
    {
        $salt ? $filename = $salt.'_'.uniqid().'.jpg' : $filename = uniqid().'.jpg';

        $this->chessboard($filepath, $salt)
            ->save("{$filepath}/{$filename}");

        return $filename;
    }
}
