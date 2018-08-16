<?php

namespace PGNChess\PGN;

use PGNChess\Exception\UnknownNotationException;
use PGNChess\PGN\Symbol;
use PGNChess\PGN\Tag;

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

        if (preg_match('/^\[' . Tag::EVENT . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::SITE . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::DATE . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::ROUND . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::WHITE . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::BLACK . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::RESULT . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::WHITE_TITLE . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::BLACK_TITLE . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::WHITE_ELO . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::BLACK_ELO . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::WHITE_USCF . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::BLACK_USCF . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::WHITE_NA . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::BLACK_NA . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::WHITE_TYPE . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::BLACK_TYPE . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::EVENT_DATE . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::EVENT_SPONSOR . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::SECTION . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::STAGE . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::BOARD . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::OPENING . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::VARIATION . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::SUB_VARIATION . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::ECO . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::NIC . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::TIME . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::UTC_TIME . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::UTC_DATE . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::TIME_CONTROL . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::SET_UP . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::FEN . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::TERMINATION . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::ANNOTATOR . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::MODE . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
        } elseif (preg_match('/^\[' . Tag::PLY_COUNT . ' \"(.*)\"\]$/', $tag)) {
            $isValid = true;
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
}
