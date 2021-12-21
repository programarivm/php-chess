<?php

namespace Chess\PGN;

use Chess\Exception\UnknownNotationException;
use Chess\PGN\Movetext;
use Chess\PGN\Symbol;
use Chess\PGN\Tag;

/**
 * Validation class.
 *
 * @author Jordi Bassagañas
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
    public static function color(string $color): string
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
    public static function square(string $square): string
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
    public static function tag(string $tag): \stdClass
    {
        $isValid = false;
        foreach (Tag::all() as $key => $val) {
            if (preg_match('/^\[' . $val . ' \"(.*)\"\]$/', $tag)) {
                $isValid = true;
            }
        }
        if (!$isValid) {
            throw new UnknownNotationException("This tag is not valid: $tag.");
        }
        $exploded = explode(' "', $tag);
        $result = (object) [
            'name' => substr($exploded[0], 1),
            'value' => substr($exploded[1], 0, -2),
        ];

        return $result;
    }

    /**
     * Validates a PGN move.
     *
     * @param string $move
     * @return bool
     * @throws UnknownNotationException
     */
    public static function move(string $move): bool
    {
        switch (true) {
            case preg_match('/^' . Move::KING . '$/', $move):
                return true;
            case preg_match('/^' . Move::KING_CASTLING_SHORT . '$/', $move):
                return true;
            case preg_match('/^' . Move::KING_CASTLING_LONG . '$/', $move):
                return true;
            case preg_match('/^' . Move::KING_CASTLING_SHORT_FIDE . '$/', $move):
                return true;
            case preg_match('/^' . Move::KING_CASTLING_LONG_FIDE . '$/', $move):
                return true;
            case preg_match('/^' . Move::KING_CAPTURES . '$/', $move):
                return true;
            case preg_match('/^' . Move::PIECE . '$/', $move):
                return true;
            case preg_match('/^' . Move::PIECE_CAPTURES . '$/', $move):
                return true;
            case preg_match('/^' . Move::KNIGHT . '$/', $move):
                return true;
            case preg_match('/^' . Move::KNIGHT_CAPTURES . '$/', $move):
                return true;
            case preg_match('/^' . Move::PAWN . '$/', $move):
                return true;
            case preg_match('/^' . Move::PAWN_CAPTURES . '$/', $move):
                return true;
            case preg_match('/^' . Move::PAWN_PROMOTES . '$/', $move):
                return true;
            case preg_match('/^' . Move::PAWN_CAPTURES_AND_PROMOTES . '$/', $move):
                return true;
            default:
                throw new UnknownNotationException("Unknown PGN notation.");
        }
    }

    /**
     * Validates a PGN movetext.
     *
     * @param string $movetext
     * @return mixed bool|string true if the movetext is valid; otherwise the filtered movetext
     */
    public static function movetext(string $text)
    {
        return (new Movetext($text))->validate();
    }

    /**
     * Validates a set of tags.
     *
     * @param array $tags
     * @return bool true if the tags are valid; otherwise false
     */
    public static function tags(array $tags): bool
    {
        $keys = array_keys($tags);

        return !array_diff(Tag::mandatory(), $keys);
    }
}
