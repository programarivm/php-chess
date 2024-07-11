<?php

namespace Chess\Variant\RacingKings\FEN\Field;

use Chess\Variant\Classical\FEN\Field\PiecePlacement as ClassicalPiecePlacement;

class PiecePlacement extends ClassicalPiecePlacement
{
    protected function validChars(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match("#^[rnbqkRNBQK1-8]+$#", $field)) {
                return false;
            }
        }

        return true;
    }
}
