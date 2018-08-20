<?php

namespace PGNChess\PGN;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Tag;

use PGNChess\PGN\Convert;

/**
 * Validates PGN symbols.
 *
 * @author Jordi Bassagañas <info@programarivm.com>
 * @link https://programarivm.com
 * @license GPL
 */
class Validate
{
    /**
     * Validates a color.
     *
     * @param string $color
     * @return string if the color is valid
     * @throws UnknownNotationException
     */
    public static function color($color)
    {
        if ($color !== Symbol::WHITE && $color !== Symbol::BLACK) {
            throw new UnknownNotationException("This is not a valid color: $color.");
        }

        return $color;
    }

    /**
     * Validates a square.
     *
     * @param string $square
     * @return string if the square is valid
     * @throws UnknownNotationException
     */
    public static function square($square)
    {
        if (!preg_match('/^' . Symbol::SQUARE . '$/', $square)) {
            throw new UnknownNotationException("This square is not valid: $square.");
        }

        return $square;
    }

    /**
     * Validates a tag.
     *
     * @param string $tag
     * @return \stdClass if the tag is valid
     * @throws UnknownNotationException
     */
    public static function tag($tag)
    {
        $isValid = false;
        foreach (Tag::getConstants() as $key => $val) {
            if (preg_match('/^\[' . $val . ' \"(.*)\"\]$/', $tag)) {
                $isValid = true;
            }
        }

        if (!$isValid) {
            throw new UnknownNotationException("This tag is not valid: $tag.");
        }

        $result = (object) [
            'name' => null,
            'value' => null,
        ];
        $exploded = explode(' "', $tag);
        $result->name = substr($exploded[0], 1);
        $result->value = substr($exploded[1], 0, -2);

        return $result;
    }

    /**
     * Validates a PGN movetext.
     *
     * @param string $movetext
     * @return bool true if the movetext is valid; otherwise false
     * @throws \PGNChess\Exception\UnknownNotationException
     */
    public static function movetext($movetext)
    {
        $numbers = [];
        $notations = [];
        $moves = array_filter(explode(' ', $movetext));

        foreach ($moves as $move) {
            if (preg_match('/^[1-9][0-9]*\.(.*)$/', $move)) {
                $moveExploded = explode('.', $move);
                $numbers[] = $moveExploded[0];
                $notations[] = $moveExploded[1];
            } else {
                $notations[] = $move;
            }
        }

        $areConsecutiveNumbers = 1;
        for ($i = 0; $i < count($numbers); $i++) {
            $areConsecutiveNumbers *= (int) $numbers[$i] == $i + 1;
        }

        if (!$areConsecutiveNumbers) {
            return false;
        }

        $notations = array_filter($notations);
        foreach ($notations as $move) {
            if ($move !== Symbol::RESULT_WHITE_WINS &&
                $move !== Symbol::RESULT_BLACK_WINS &&
                $move !== Symbol::RESULT_DRAW &&
                $move !== Symbol::RESULT_UNKNOWN
            ) {
                try {
                    Convert::toObject(Symbol::WHITE, $move);
                } catch (UnknownNotationException $e) {
                    return false;
                }
            }
        }

        return true;
    }
}
