<?php

namespace Chess;

use Chess\Eval\AbstractEval;
use Chess\Eval\InverseEvalInterface;
use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;

class EvalArray
{
    /**
     * Returns an array of normalized values.
     *
     * @param \Chess\Function\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @return array
     */
    public static function balance(AbstractFunction $f, AbstractBoard $board): array
    {
        $items = [];
        foreach ($f->names() as $val) {
            $item = self::add(EvalFactory::create($f, $val, $board));
            $items[] =  $item[Color::W] - $item[Color::B];
        }

        return self::normalize(-1, 1, $items);
    }

    /**
     * Returns the sum of the elements in the array of normalized values.
     *
     * @param \Chess\Function\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @return float
     */
    public static function sum(AbstractFunction $f, AbstractBoard $board): float
    {
        return round(array_sum(self::balance($f, $board)), 2);
    }

    /**
     * Add an item to the array.
     *
     * @param \Chess\Eval\AbstractEval $eval
     * @return array
     */
    public static function add(AbstractEval $eval): array
    {
        if (is_array($eval->result[Color::W])) {
            if ($eval instanceof InverseEvalInterface) {
                $item = [
                    Color::W => count($eval->result[Color::B]),
                    Color::B => count($eval->result[Color::W]),
                ];
            } else {
                $item = [
                    Color::W => count($eval->result[Color::W]),
                    Color::B => count($eval->result[Color::B]),
                ];
            }
        } else {
            if ($eval instanceof InverseEvalInterface) {
                $item = [
                    Color::W => $eval->result[Color::B],
                    Color::B => $eval->result[Color::W],
                ];
            } else {
                $item = $eval->result;
            }
        }

        return $item;
    }

    /**
     * Normalizes the given array of values.
     *
     * @param int $newMin
     * @param int $newMax
     * @param array $values
     * @return array
     */
    public static function normalize(int $newMin, int $newMax, array $values): array
    {
        $min = min($values);
        $max = max($values);

        foreach ($values as $key => $val) {
            if ($val > 0) {
                $values[$key] = round($values[$key] * $newMax / $max, 2);
            } elseif ($val < 0) {
                $values[$key] = round($values[$key] * $newMin / $min, 2);
            } else {
                $values[$key] = 0;
            }
        }

        return $values;
    }

    /**
     * Counts the number of evaluation features favoring the players.
     *
     * @param array $balance
     * @return int
     */
    public static function count(array $balance): int
    {
        $count = 0;

        foreach ($balance as $val) {
            if ($val > 0) {
                $count += 1;
            } elseif ($val < 0) {
                $count -= 1;
            }
        }

        return $count;
    }
}
