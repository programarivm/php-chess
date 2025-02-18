<?php

namespace Chess\Variant;

use Chess\Variant\Classical\PGN\Piece;

trait RandomShuffleTrait
{
    protected array $default = [];

    public function shuffle()
    {
        do {
            shuffle($this->default);
        } while (!$this->bishops() || !$this->king());

        return $this->default;
    }

    public function extract(string $fen): array
    {
        $fields = array_filter(explode(' ', $fen));
        $exploded = explode('/', $fields[0]);
        
        return str_split(mb_strtoupper($exploded[0]));
    }

    protected function bishops()
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

    protected function king()
    {
        $str = implode('', $this->default);

        return preg_match('/^(.*)R(.*)K(.*)R(.*)$/', $str);
    }
}
