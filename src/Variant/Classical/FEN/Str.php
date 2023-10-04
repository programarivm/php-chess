<?php

namespace Chess\Variant\Classical\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\FEN\Field\EnPassantTargetSquare;
use Chess\Variant\Classical\FEN\Field\PiecePlacement;
use Chess\Variant\Classical\FEN\Field\SideToMove;

/**
 * FEN string.
 *
 * @author Jordi Bassagaña
 * @license GPL
 */
class Str
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

        if (
            !isset($fields[0]) ||
            !isset($fields[1]) ||
            !isset($fields[2]) ||
            !isset($fields[3])
        ) {
            throw new UnknownNotationException();
        }

        PiecePlacement::validate($fields[0]);
        SideToMove::validate($fields[1]);
        CastlingAbility::validate($fields[2]);
        EnPassantTargetSquare::validate($fields[3]);

        return $string;
    }

    /**
     * Returns an ASCII array.
     *
     * @param string $string
     * @return array
     */
    public function toAsciiArray(string $string): array
    {
        $array = [];
        $ranks = array_reverse(explode('/', $string));
        for ($i = count($ranks) - 1; $i >= 0; $i--) {
            $row = [];
            preg_match_all('!\d+!', $ranks[$i], $digits, PREG_OFFSET_CAPTURE);
            preg_match_all('/[a-zA-Z]{1}/', $ranks[$i], $letters, PREG_OFFSET_CAPTURE);
            $all = [...$digits[0], ...$letters[0]];
            usort($all, function ($a, $b) {
                return $a[1] <=> $b[1];
            });
            foreach ($all as $item) {
                !is_numeric($item[0])
                    ? $elem = [" {$item[0]} "]
                    : $elem = array_fill(0, $item[0], ' . ');
                $row = [...$row, ...$elem];
            }
            $array[$i] = $row;
        }

        return $array;
    }
}
