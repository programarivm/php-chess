<?php

namespace Chess\Variant;

use Chess\Variant\Classical\PGN\AN\Piece;

trait RandomStartPositionTrait
{
    protected array $default = [];

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
