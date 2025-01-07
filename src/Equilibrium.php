<?php

namespace Chess;

use Chess\Eval\AbstractEval;
use Chess\Variant\AbstractBoard;

class Equilibrium
{
    public float $result = 0;

    public function __construct(AbstractBoard $board)
    {
        $phi = [];

        foreach ($board->square->all as $key => $val) {
            $phi[$val] = ($key + 1) / 100;
        }

        foreach ($phi as $sq => $val) {
            if ($piece = $board->pieceBySq($sq)) {
                $this->result += AbstractEval::$value[$piece->id] * $val;
            }
        }

        $this->result = round($this->result, 5);
    }
}
