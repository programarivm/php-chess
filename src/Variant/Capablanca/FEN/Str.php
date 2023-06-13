<?php

namespace Chess\Variant\Capablanca\FEN;

use Chess\Variant\Capablanca\FEN\Field\PiecePlacement;
use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\FEN\Field\EnPassantTargetSquare;
use Chess\Variant\Classical\FEN\Field\SideToMove;
use Chess\Variant\Classical\FEN\Str as ClassicalFenStr;

/**
 * FEN string.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Str extends ClassicalFenStr
{
    /**
     * String validation.
     *
     * @param string $string
     * @return string if the value is valid
     * @throws \Chess\Exception\UnknownNotationException
     */
    public function validate(string $string): string
    {
        $fields = explode(' ', $string);

        PiecePlacement::validate($fields[0]);
        SideToMove::validate($fields[1]);
        CastlingAbility::validate($fields[2]);
        EnPassantTargetSquare::validate($fields[3]);

        return $string;
    }
}
