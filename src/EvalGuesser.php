<?php

namespace Chess;

use Chess\Eval\AbstractEval;
use Chess\Function\AbstractFunction;
use Chess\Variant\AbstractBoard;
use Chess\Variant\Classical\PGN\AN\Color;

class EvalGuesser
{
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
     * Calculates an item.
     *
     * @param \Chess\Eval\AbstractEval $eval
     * @return array
     */
    public static function item(AbstractEval $eval): array
    {
        $result = $eval->result;

        if (is_array($result[Color::W])) {
            if ($eval instanceof InverseEvalInterface) {
                $item = [
                    Color::W => count($result[Color::B]),
                    Color::B => count($result[Color::W]),
                ];
            } else {
                $item = [
                    Color::W => count($result[Color::W]),
                    Color::B => count($result[Color::B]),
                ];
            }
        } else {
            if ($eval instanceof InverseEvalInterface) {
                $item = [
                    Color::W => $result[Color::B],
                    Color::B => $result[Color::W],
                ];
            } else {
                $item = $result;
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
