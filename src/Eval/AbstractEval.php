<?php

namespace Chess\Eval;

use Chess\Variant\Capablanca\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\Board;

/**
 * Abstract evaluation.
 *
 * @author Jordi Bassagaña
 * @license MIT
 */
abstract class AbstractEval
{
    protected static $value = [
        Piece::A => 6.53,
        Piece::B => 3.33,
        Piece::C => 8.3,
        Piece::K => 4,
        Piece::N => 3.2,
        Piece::P => 1,
        Piece::Q => 8.8,
        Piece::R => 5.1,
    ];

    protected Board $board;

    protected array $result = [
        Color::W => 0,
        Color::B => 0,
    ];

    protected array $phrases = [];

    protected array $elaboration = [];

    public function getResult()
    {
        return $this->result;
    }

    public function getPhrases()
    {
        return $this->phrases;
    }

    public function getElaboration()
    {
        return $this->elaboration;
    }

    protected function diffPieces(array $array1, array $array2): array
    {
        $diff = [];

        $a = array_map(function($elem) {
            return $elem->getSq();
        }, $array1);

        $b = array_map(function($elem) {
            return $elem->getSq();
        }, $array2);

        foreach ($b as $sq) {
            if (!in_array($sq, $a)) {
                $diff[] = $this->board->getPieceBySq($sq);
            }
        }

        return $diff;
    }

    protected function sentence(array $result): ?string
    {
        $diff = $result[Color::W] - $result[Color::B];

        if ($diff > 0) {
            foreach ($this->phrase[Color::W] as $item) {
                if ($diff >= $item['diff']) {
                    return $item['meaning'];
                }
            }
        }

        if ($diff < 0) {
            foreach ($this->phrase[Color::B] as $item) {
                if ($diff <= $item['diff']) {
                    return $item['meaning'];
                }
            }
        }

        return null;
    }

    protected function shorten(array $result, string $singular, string $plural): void
    {
        $sqs = [...$result[Color::W], ...$result[Color::B]];

        if (count($sqs) > 1) {
            $str = '';
            $keys = array_keys($sqs);
            $lastKey = end($keys);
            foreach ($sqs as $key => $val) {
                if ($key === $lastKey) {
                    $str = substr($str, 0, -2);
                    $str .= " and $val are {$plural}.";
                } else {
                    $str .= "$val, ";
                }
            }
            $this->elaboration = [
                $str,
            ];
        } elseif (count($sqs) === 1) {
            $this->elaboration = [
                "$sqs[0] is {$singular}.",
            ];
        }
    }
}
