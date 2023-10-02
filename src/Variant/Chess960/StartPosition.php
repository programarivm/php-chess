<?php

namespace Chess\Variant\Chess960;

use Chess\Variant\Classical\PGN\AN\Piece;

class StartPosition
{
    private $default = [
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
            shuffle($this->default);
        } while (!$this->bishops() || !$this->king());

        return $this->default;
    }

    public function getDefault(): array {
        return $this->default;
    }

    private function bishops()
    {
        $keys = [];

        foreach ($this->default as $key => $val) {
            if ($val === Piece::B) {
                $keys[] = $key;
            }
        }

        $even = $keys[0] % 2 === 0 && $keys[1] % 2 === 0;
        $odd = $keys[0] % 2 !== 0 && $keys[1] % 2 !== 0;

        return !$even && !$odd;
    }

    private function king()
    {
        $str = implode('', $this->default);

        return preg_match('/^(.*)R(.*)K(.*)R(.*)$/', $str);
    }
}
