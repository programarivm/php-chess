<?php

namespace Chess\Variant\Classical\FEN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\Classical\PGN\Color;

class PiecePlacement
{
    public function validate(string $value): string
    {
        $fields = explode('/', $value);

        if (
            $this->eightFields($fields) &&
            $this->kings($fields) &&
            $this->validChars($fields)
        ) {
            return $value;
        }

        throw new UnknownNotationException();
    }

    protected function eightFields(array $fields)
    {
        return count($fields) === 8;
    }

    protected function kings(array $fields)
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

    protected function validChars(array $fields)
    {
        foreach ($fields as $field) {
            if (!preg_match("#^[rnbqkpRNBQKP1-8]+$#", $field)) {
                return false;
            }
        }

        return true;
    }

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
