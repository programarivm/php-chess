<?php

namespace Chess;

use Chess\Eval\AbstractEval;
use Chess\Variant\AbstractBoard;

class Equilibrium
{
    public static function phi(AbstractBoard $board): float
    {
        $phi = 0;
        $weights = [];

        foreach ($board->square->all as $key => $val) {
            $weights[$val] = ($key + 1) / 100;
        }

        foreach ($weights as $sq => $val) {
            if ($piece = $board->pieceBySq($sq)) {
                $phi += AbstractEval::$value[$piece->id] * $val;
            }
        }

        return round($phi, 5);
    }
}
