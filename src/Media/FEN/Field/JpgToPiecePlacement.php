<?php

namespace Chess\Media\FEN\Field;

use Chess\Media\PGN\AN\JpgToPiece;

class JpgToPiecePlacement
{
    const STORAGE_TMP_FOLDER = __DIR__.'/../../../../storage/tmp';

    protected string $filename;

    protected \GdImage $image;

    protected array $size;

    protected array $fen;

    public function __construct(string $filename)
    {
        $this->filename = $filename;

        $this->image = imagecreatefromjpeg($filename);

        $this->size = getimagesize($filename);

        $this->calcTiles();
    }

    protected function calcTiles(): void
    {
        $side = $this->size[0] / 8;
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
                    $filepath = self::STORAGE_TMP_FOLDER."/example-$i$j.jpg";
                    imagejpeg($tile, $filepath);
                    imagedestroy($tile);
                }
                $x += $side;
            }
            $y += $side;
        }
    }

    public function predict(): string
    {
        // TODO ...

        return '';
    }
}
