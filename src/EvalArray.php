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
    public static function normalization(AbstractFunction $f, AbstractBoard $board): array
    {
        $items = [];
        foreach ($f->names() as $val) {
            $item = self::add(EvalFactory::create($f, $val, $board));
            $items[] =  $item[Color::W] - $item[Color::B];
        }

        return self::normalize(-1, 1, $items);
    }

    /**
     * Steinitz Evaluation
     *
     * A strong position can be created by accumulating small advantages. The
     * relative value of the position without considering checkmate is obtained
     * by counting the advantages in the evaluation array.
     *
     * @param array $normd
     * @return int
     */
    public static function steinitz(AbstractFunction $f, AbstractBoard $board): int
    {
        $count = 0;
        $normd = array_filter(self::normalization($f, $board));
        foreach ($normd as $val) {
            if ($val > 0) {
                $count += 1;
            } elseif ($val < 0) {
                $count -= 1;
            }
        }

        return $count;
    }

    /**
     * Mean Evaluation
     *
     * Returns the sum of the elements in the array of normalized values.
     *
     * @param \Chess\Function\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @return float
     */
    public static function mean(AbstractFunction $f, AbstractBoard $board): float
    {
        $normd = self::normalization($f, $board);
        $sum = array_sum($normd);
        $count = count($normd);

        return round($sum / $count, 4);
    }

    /**
     * Median Evaluation
     *
     * Returns the value in the middle of the array of normalized values.
     *
     * @param \Chess\Function\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @return float
     */
    public static function median(AbstractFunction $f, AbstractBoard $board): float
    {
        $normd = array_filter(self::normalization($f, $board));
        sort($normd);
        $size = sizeof($normd);
        if ($size % 2 == 0) {
            return ($normd[$size / 2] + $normd[$size / 2 - 1]) / 2;
        }

        return $normd[floor($size / 2)];
    }

    /**
     * Mode Evaluation
     *
     * Returns the most common number in the array of normalized values.
     *
     * @param \Chess\Function\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @return null|float
     */
    public static function mode(AbstractFunction $f, AbstractBoard $board): ?float
    {
        $normd = array_filter(self::normalization($f, $board));
        foreach ($normd as &$val) {
            $val = strval($val);
        }
        $values = array_count_values($normd);
        arsort($values);
        if (current($values) > 1) {
            return floatval(array_key_first($values));
        }

        return null;
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
     * @param array $unnormd
     * @return array
     */
    public static function normalize(int $newMin, int $newMax, array $unnormd): array
    {
        $min = min($unnormd);
        $max = max($unnormd);

        foreach ($unnormd as $key => $val) {
            if ($val > 0) {
                $unnormd[$key] = round($unnormd[$key] * $newMax / $max, 2);
            } elseif ($val < 0) {
                $unnormd[$key] = round($unnormd[$key] * $newMin / $min, 2);
            } else {
                $unnormd[$key] = 0;
            }
        }

        return $unnormd;
    }
}
