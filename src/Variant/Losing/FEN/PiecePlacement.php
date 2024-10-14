<?php

namespace Chess\Variant\Losing\FEN;

use Chess\Variant\Classical\FEN\PiecePlacement as ClassicalPiecePlacement;

class PiecePlacement extends ClassicalPiecePlacement
{
    protected function kings(array $fields)
    {
        return true;
    }

    protected function validChars(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match("#^[rnbqmpRNBQMP1-8]+$#", $field)) {
                return false;
            }
        }

        return true;
    }
}
