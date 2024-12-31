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
     * As chess champion William Steinitz pointed out, a strong position can be
     * created by accumulating small advantages. The relative value of the
     * position without considering checkmate is obtained by counting the
     * advantages in the evaluation array.
     *
     * @param array $normd
     * @return int
     */
    public static function steinitz(array $normd): int
    {
        $count = 0;

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
        return round(array_sum(self::normalization($f, $board)), 2);
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
