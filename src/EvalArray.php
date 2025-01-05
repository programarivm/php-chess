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
     * Returns the mean of the elements in the array.
     *
     * @param \Chess\Function\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @return float
     */
    public static function mean(AbstractFunction $f, AbstractBoard $board): float
    {
        $normd = array_filter(self::normalization($f, $board));
        $sum = array_sum($normd);
        $count = count($normd);
        if ($count > 0) {
            return round($sum / $count, 5);
        }

        return 0.0;
    }

    /**
     * Returns the value in the middle of the array.
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
     * Returns the most common number in the array.
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
     * Returns a measure of how spread out the array is.
     *
     * @param \Chess\Function\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @return float
     */
    public static function var(AbstractFunction $f, AbstractBoard $board): float
    {
        $normd = array_filter(self::normalization($f, $board));
        $mean = self::mean($f, $board);
        $sum = 0;
        foreach ($normd as $val) {
            $diff = $val - $mean;
            $sum += $diff * $diff;
        }

        return round($sum / count($normd), 5);
    }

    /**
     * Returns a measure of how spread out the array is.
     *
     * @param \Chess\Function\AbstractFunction $f
     * @param \Chess\Variant\AbstractBoard $board
     * @return float
     */
    public static function sd(AbstractFunction $f, AbstractBoard $board): float
    {
        $var = self::var($f, $board);

        return round(sqrt($var), 5);
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
     * Normalizes the given array.
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
                $unnormd[$key] = round($unnormd[$key] * $newMax / $max, 5);
            } elseif ($val < 0) {
                $unnormd[$key] = round($unnormd[$key] * $newMin / $min, 5);
            } else {
                $unnormd[$key] = 0;
            }
        }

        return $unnormd;
    }
}
