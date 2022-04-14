<?php

namespace Chess\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\FEN\Field\CastlingAbility;
use Chess\FEN\Field\EnPassantTargetSquare;
use Chess\FEN\Field\PiecePlacement;
use Chess\FEN\Field\SideToMove;

/**
 * FEN string.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Str implements ValidationInterface
{
    /**
     * String validation.
     *
     * @param string $value
     * @return string if the value is valid
     * @throws UnknownNotationException
     */
    public static function validate(string $string): string
    {
        $fields = explode(' ', $string);

        PiecePlacement::validate($fields[0]);
        SideToMove::validate($fields[1]);
        CastlingAbility::validate($fields[2]);
        EnPassantTargetSquare::validate($fields[3]);

        return $string;
    }
}
