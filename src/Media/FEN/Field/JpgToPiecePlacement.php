<?php

namespace Chess\Media\FEN\Field;

use Chess\Media\PGN\AN\JpgToPiece;

class JpgToPiecePlacement
{
    protected \GdImage $image;

    public function __construct(\GdImage $image)
    {
        $this->image = $image;
    }

    public function predict(): string
    {
        $result = '';

        $side  = imagesx($this->image) / 8;
        $y = 0;
        for ($i = 0; $i < 8; $i++) {
            $x = 0;
            for ($j = 0; $j < 8; $j++) {
                $tile = imagecrop($this->image, [
                    'x' => $x,
                    'y' => $y,
                    'width' => $side,
                    'height' => $side,
                ]);
                if ($tile !== false) {
                    $result .= (new JpgToPiece($tile))->predict();
                    imagedestroy($tile);
                }
                $x += $side;
            }
            $result .= '/';
            $y += $side;
        }

        $result = substr($result, 0, -1);
        $result = str_replace('11111111', '8', $result);
        $result = str_replace('1111111', '7', $result);
        $result = str_replace('111111', '6', $result);
        $result = str_replace('11111', '5', $result);
        $result = str_replace('1111', '4', $result);
        $result = str_replace('111', '3', $result);
        $result = str_replace('11', '2', $result);

        return $result;
    }
}
