<?php

namespace Chess\Variant\Chess960;

use Chess\PGN\AN\Piece;

class StartingPosition
{
    private $arr = [
        Piece::R,
        Piece::N,
        Piece::B,
        Piece::Q,
        Piece::K,
        Piece::B,
        Piece::N,
        Piece::R,
    ];

    public function create()
    {
        do {
            shuffle($this->arr);
        } while (!$this->bishops() || !$this->king());

        return $this->arr;
    }

    private function bishops()
    {
        $keys = [];
        foreach ($this->arr as $key => $val) {
            if ($val === Piece::B) {
                $keys[] = $key;
            }
        }
        $areEven = $keys[0] % 2 === 0 && $keys[1] % 2 === 0;
        $areOdd = $keys[0] % 2 !== 0 && $keys[1] % 2 !== 0;

        return !$areEven && !$areOdd;
    }

    private function king()
    {
        $str = implode('', $this->arr);
        $isBetweenRooks = preg_match('/^(.*)R(.*)K(.*)R(.*)$/', $str);

        return $isBetweenRooks;
    }
}
