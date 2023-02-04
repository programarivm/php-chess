<?php

namespace Chess\Media\FEN\Field;

use Chess\Media\PGN\AN\JpgToPiece;

class JpgToPiecePlacement
{
    protected string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function predict(): string
    {
        // TODO ...

        return '';
    }
}
