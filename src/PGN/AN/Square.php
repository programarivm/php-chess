<?php

namespace Chess\PGN\AN;

use Chess\Exception\UnknownNotationException;
use Chess\PGN\AbstractNotation;
use Chess\PGN\ValidationInterface;

/**
 * Square.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
class Square extends AbstractNotation implements ValidationInterface
{
    const REGEX = '[a-h]{1}[1-8]{1}';

    /**
     * Validate.
     *
     * @param string $value
     * @return string if the value is valid
     * @throws UnknownNotationException
     */
    public static function validate(string $value): string
    {
        if (!preg_match('/^' . self::REGEX . '$/', $value)) {
            throw new UnknownNotationException;
        }

        return $value;
    }

    /**
     * Returns the square's color.
     *
     * @param string $sq
     * @return string
     */
    public static function color(string $sq): string
    {
        self::validate($sq);

        $w = [
            'a2', 'a4', 'a6', 'a8',
            'b1', 'b3', 'b5', 'b7',
            'c2', 'c4', 'c6', 'c8',
            'd1', 'd3', 'd5', 'd7',
            'e2', 'e4', 'e6', 'e8',
            'f1', 'f3', 'f5', 'f7',
            'g2', 'g4', 'g6', 'g8',
            'h1', 'h3', 'h5', 'h7',
        ];

        if (in_array($sq, $w)) {
            return Color::W;
        }

        return Color::B;
    }
}
