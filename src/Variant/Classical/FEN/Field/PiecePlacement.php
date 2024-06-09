<?php

namespace Chess\Variant\Classical\FEN\Field;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\PGN\AN\Color;

/**
 * Piece placement.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
class PiecePlacement
{
    /**
     * String validation.
     *
     * @param string $value
     * @return string if the value is valid
     * @throws UnknownNotationException
     */
    public function validate(string $value): string
    {
        $fields = explode('/', $value);

        if (
            $this->eightFields($fields) &&
            $this->twoKings($fields) &&
            $this->validChars($fields)
        ) {
            return $value;
        }

        throw new UnknownNotationException();
    }

    /**
     * Checks out that there are exactly eight fields.
     *
     * @param array $fields
     * @return bool
     */
    protected function eightFields(array $fields)
    {
        return count($fields) === 8;
    }

    /**
     * Checks out that there are exactly two kings.
     *
     * @param array $fields
     * @return bool
     */
    protected function twoKings(array $fields)
    {
        $result = [
            Color::W => 0,
            Color::B => 0,
        ];

        foreach ($fields as $field) {
            $count = count_chars($field, 1);
            foreach ($count as $key => $val) {
                if (chr($key) === 'K') {
                    $result[Color::W] += $val;
                } elseif (chr($key) === 'k') {
                    $result[Color::B] += $val;
                }
            }
        }

        return $result[Color::W] === 1 && $result[Color::B] === 1;
    }

    /**
     * Checks out that the pieces are valid.
     *
     * @param array $fields
     * @return bool
     */
    protected function validChars(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match("#^[rnbqkpRNBQKP1-8]+$#", $field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the piece position in the given rank.
     *
     * @param string $rank
     * @param string $char
     * @return int
     */
    public function charPos(string $rank, string $char)
    {
        $str = '';
        $split = str_split($rank);
        foreach ($split as $key => $val) {
            if (is_numeric($val)) {
                $str .= str_repeat('.', $val);
            } else {
                $str .= $val;
            }
        }
        $arr = str_split($str);

        return array_search($char, $arr);
    }
}
