<?php

namespace Chess\Variant\Capablanca\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\FEN\PiecePlacement as ClassicalFenPiecePlacement;

class PiecePlacement extends ClassicalFenPiecePlacement
{
    public function validate(string $value): string
    {
        $fields = explode('/', $value);

        if ($this->eightFields($fields) &&
            $this->kings($fields) &&
            $this->validChars($fields)
        ) {
            return $value;
        }

        throw new UnknownNotationException();
    }

    protected function validChars(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match("#^[rnbqkpacRNBQKPAC0-9]+$#", $field)) {
                return false;
            }
        }

        return true;
    }
}
