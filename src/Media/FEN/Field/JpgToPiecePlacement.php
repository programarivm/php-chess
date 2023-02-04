<?php

namespace Chess\Media\FEN\Field;

use Chess\Media\PGN\AN\JpgToPiece;

class JpgToPiecePlacement
{
    protected string $filename;

    protected \GdImage $image;

    public function __construct(string $filename)
    {
        $this->filename = $filename;

        $this->image = imagecreatefromjpeg($filename);
    }

    public function predict(): string
    {
        // TODO ...

        return '';
    }
}
