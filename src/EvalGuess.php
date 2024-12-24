<?php

namespace Chess;

use Chess\Eval\AbstractEval;
use Chess\Eval\InverseEvalInterface;
use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;

class EvalGuess
{
    /**
     * Makes a guess in the form of an array of normalized values.
     *
     * @param \Chess\Function\AbstractFunction $function
     * @param \Chess\Variant\AbstractBoard $board
     * @return array
     */
    public static function balance(AbstractFunction $function, AbstractBoard $board): array
    {
        $items = [];
        foreach ($function->names() as $val) {
            $item = self::item(EvalFactory::create(
                $function,
                $val,
                $board
            ));
            $items[] =  $item[Color::W] - $item[Color::B];
        }

        return self::normalize(-1, 1, $items);
    }

    /**
     * Makes a guess in the form of a float value.
     *
     * @param \Chess\Function\AbstractFunction $function
     * @param \Chess\Variant\AbstractBoard $board
     * @return float
     */
    public static function guess(AbstractFunction $function, AbstractBoard $board): float
    {
        return round(array_sum(self::balance($function, $board)), 2);
    }

    /**
     * Calculates an item.
     *
     * @param \Chess\Eval\AbstractEval $eval
     * @return array
     */
    public static function item(AbstractEval $eval): array
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
     * Normalizes an array of values.
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
}
